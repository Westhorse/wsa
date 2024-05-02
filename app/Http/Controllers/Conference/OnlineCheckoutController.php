<?php

namespace App\Http\Controllers\Conference;

use App\Helpers\FileUploadAction;
use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TimeSlot;
use Exception;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class OnlineCheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        try {
            $stripe = new StripeClient(config('stripe.stripe.secret_key'));
            $amountInCents = ceil($request->amount * 100 * 1.044);
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
            return response()->json(['clientSecret' => $paymentIntent->client_secret]);
        } catch (ApiErrorException $e) {
            http_response_code(500);
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * @throws ApiErrorException
     */
    public function validatePaymentIntent(Order $order, Request $request)
    {
        Stripe::setApiKey(config('stripe.stripe.secret_key'));
        $intent = PaymentIntent::retrieve($request->stripeId);
        $latest_charge = $intent->latest_charge;
        $status = $intent->status;
        if ($status === 'succeeded') {
            $order->update([
                'status' => 'approved_online_payment',
                'stripe_pi' => $request->stripeId,
                'stripe_ch' => $latest_charge,
                // 'online_free' => ceil($request->amount * 0.044)
            ]);

            try {
                $fileUploadAction = new FileUploadAction();
                $fileUploadAction->sendConfirmationEmails($order);
            } catch (Exception $e) {
                \Log::error('Error in PaymentIntent Email: ' . $e->getMessage(), ['context' => $e]);
                return JsonResponse::respondError('Failed to send confirmation email');
            }

            $user = $order->user;
            $delegates = $user->delegates;
            foreach ($delegates as $delegate) {
                $timeSlots = TimeSlot::all();
                $delegate->timeSlots()->sync($timeSlots);
            }
            return JsonResponse::respondSuccess('Your Payment received successfully');
        }
        return JsonResponse::respondError('Your Payment is not confirmed');
    }
}
