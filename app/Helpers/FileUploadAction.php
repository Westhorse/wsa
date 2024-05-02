<?php


namespace App\Helpers;

use App\Mail\Event\EventCancelOneToOneMail;
use App\Mail\Event\EventCompanyStatusApprovedMail;
use App\Mail\Event\EventReceiverOneToOneMail;
use App\Mail\Event\EventResetPasswordMail;
use App\Mail\Event\EventSenderOneToOneMail;
use App\Mail\Event\InvoiceMail;
use App\Mail\Event\InvoiceMailAdmin;
use App\Models\Delegate;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FileUploadAction
{

    function str_random($length = 4)
    {
        return Str::random($length);
    }

    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }

    public function execute($model, $files, $destination)
    {
        foreach ($files as $images) {

            $img = "";
            $img = $this->str_random(4) . $images->getClientOriginalName();
            $originname = time() . '.' . $images->getClientOriginalName();
            $filename = $this->str_slug(pathinfo($originname, PATHINFO_FILENAME), "-");
            $filename = $images->hashName();
            $extention = pathinfo($originname, PATHINFO_EXTENSION);
            $img = $filename;
            $type = $images->extension();
            $size = $images->getSize();
            $images->move(public_path('storage'), $img);
            $model->images()->create(['image' => $img, 'type' => $type, 'size' => $size]);
        }
    }

    public function executeBase64($model, $files, $destination = null)
    {
        foreach ($files as $file) {
            if (preg_match('/^data:image\/(\w+);base64,/', $file)) {
                $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $replace = substr($file, 0, strpos($file, ',') + 1);
                $image = str_replace($replace, '', $file);
                $image = str_replace(' ', '+', $image);
                $imageName = Str::random(10) . '.' . $extension;
                Storage::disk('public')->put($imageName, base64_decode($image));
                $model->images()->create(['image' => $imageName, 'type' => $extension]);
            }
        }
    }

    public static function saveArrayOfBase64Images($imagesStrings)
    {
        $imagesPath = [];
        foreach ($imagesStrings as $imageString) {

            $path = '/' . Str::random(16) . '.jpg';
            $file = fopen('storage' . $path, 'wb');
            fwrite($file, base64_decode($imageString));
            fclose($file);
            $imagesPath[] = $path;
        }
        return $imagesPath;
    }



    public static function sendInvoiceEmail($order)
    {
        $delegatesData = [];
        $spousesData = [];
        $sponsorshipItemsData = [];
        $roomCount = null;
        $roomPrice = null;
        $amount = null;
        $total = null;

        // Delegates
        if ($order->delegates) {
            $delegates = $order->delegates;
            $pricePerDelegate = ($order->total_price_delegate && $delegates->count() != 0) ? ($order->total_price_delegate / $delegates->count()) : 0;
            if ($order->package) {
                $availableDelegatesCount = $order->package->delegate_count;
                foreach ($delegates as $key => $delegate) {
                    $priceMessage = ($availableDelegatesCount > $delegates->count()) ? $pricePerDelegate : 'free';
                    $delegatesData[] = [
                        'name' => $delegate->name,
                        'price' => $key < $availableDelegatesCount ? $priceMessage : $pricePerDelegate,
                    ];
                }
            } else {
                foreach ($delegates as $key => $delegate) {
                    $delegatesData[] = [
                        'name' => $delegate->name,
                        'price' => $pricePerDelegate,
                    ];
                }
            }
        }

        // Spouses
        if ($order->spouses) {
            $spouses = $order->spouses;
            $pricePerSpouse = ($order->total_price_spouse && $spouses->count() != 0) ? ($order->total_price_spouse / $spouses->count()) : 0;
            foreach ($spouses as $key => $spouse) {
                $spousesData[] = [
                    'name' => $spouse->name,
                    'price' => $pricePerSpouse,
                ];
            }
        }

        // SponsorshipItems
        if ($order->sponsorshipItems) {
            $sponsorshipItems = $order->sponsorshipItems;
            $pricePerSponsorshipItem = ($order->total_price_sponsorship_items && $sponsorshipItems->count() != 0) ? ($order->total_price_sponsorship_items / $sponsorshipItems->count()) : 0;
            foreach ($sponsorshipItems as $key => $sponsorshipItem) {
                $sponsorshipItemsData[] = [
                    'name' => $sponsorshipItem->name,
                    'price' => $pricePerSponsorshipItem,
                ];
            }
        }

        $roomCount = $order->rooms()->count();
        $roomPrice = $order->total_price_rooms;
        $amount = $order->amount;
        $total = $order->total;

        // Email
        $subject = DB::table('email_templates')->where('slug', 'conference_invoice_email_template')->value('subject');
        $emailCompany = DB::table('users')->where('id', $order->user_id)->pluck('email');

        Mail::to($emailCompany[0])->send(new InvoiceMail(
            $order,
            $roomPrice,
            $amount,
            $total,
            $roomCount,
            $subject,
            json_encode($delegatesData),
            json_encode($sponsorshipItemsData),
            json_encode($spousesData)
        ));

        $emails = DB::table('email_templates')->where('slug', 'conference_invoice_email_template')->select('bcc')->first();
        $emails_bcc = explode(',', $emails->bcc);
        foreach ($emails_bcc as $email) {
            Mail::to($email)->send(new InvoiceMail(
                $order,
                $roomPrice,
                $amount,
                $total,
                $roomCount,
                $subject,
                json_encode($delegatesData),
                json_encode($sponsorshipItemsData),
                json_encode($spousesData)
            ));
        }
    }



    public function sendConfirmationEmails($order)
    {
        $delegatesData = [];
        $spousesData = [];
        $sponsorshipItemsData = [];
        $roomCount = null;
        $roomPrice = null;
        $amount = null;
        $total = null;

        //delegates
        if ($order->delegates) {
            $delegates = $order->delegates;
            $pricePerDelegate = ($order->total_price_delegate) ? ($order->total_price_delegate / $delegates->count()) : 0;
            if ($order->package) {
                $availableDelegatesCount = $order->package->delegate_count;
                foreach ($delegates as $key => $delegate) {
                    if ($availableDelegatesCount > $delegates->count()) {
                        $priceMessage = $pricePerDelegate;
                    } else {
                        $priceMessage = 'free';
                    }
                    $delegatesData[] = [
                        'name' => $delegate->name,
                        'price' => $key < $availableDelegatesCount ? $priceMessage : $pricePerDelegate,
                    ];
                }
            } else {
                foreach ($delegates as $key => $delegate) {
                    $delegatesData[] = [
                        'name' => $delegate->name,
                        'price' => $pricePerDelegate,
                    ];
                }
            }
        }
        //spouses
        if ($order->spouses) {
            $spouses = $order->spouses;
            $pricePerspouse = ($order->total_price_spouse) ? ($order->total_price_spouse / $spouses->count()) : 0;

            foreach ($spouses as $key => $spouse) {
                $spousesData[] = [
                    'name' => $spouse->name,
                    'price' => $pricePerspouse,
                ];
            }
        }
        //sponsorshipItems
        if ($order->sponsorshipItems) {
            $sponsorshipItems = $order->sponsorshipItems;
            $pricePerSponsorshipItem = ($order->total_price_sponsorship_items) ? ($order->total_price_sponsorship_items / $sponsorshipItems->count()) : 0;

            foreach ($sponsorshipItems as $key => $sponsorshipItem) {
                $sponsorshipItemsData[] = [
                    'name' => $sponsorshipItem->name,
                    'price' => $pricePerSponsorshipItem,
                ];
            }
        }

        $roomCount = $order->rooms()->count();
        $roomPrice = $order->total_price_rooms;
        $amount = $order->amount;
        $total = $order->total;
        $emailCompany = DB::table('users')->where('id', $order->user_id)->pluck('email');
        //email for company
        $template = DB::table('email_templates')->where('slug', 'conference_payment_confirmation_email_template')->value('body');  // send email
        $subject = DB::table('email_templates')->where('slug', 'conference_payment_confirmation_email_template')->value('subject'); // send email

        $template = str_replace(
            [
                '{{company_name}}',
                '{{order_date}}',
                '{{company_address_1}}',
                '{{company_address_2}}',
            ],
            [
                $company_name = $order->user->name,
                $order_date = $order->created_at,
                $company_address_1 = $order->user->address_line1,
                $company_address_2 = $order->user->address_line2,
            ],
            $template
        );

        Mail::to($emailCompany[0])->send(new EventCompanyStatusApprovedMail(
            $template,
            $subject,
            $roomPrice,
            $amount,
            $total,
            $roomCount,
            json_encode($delegatesData),
            json_encode($sponsorshipItemsData),
            json_encode($spousesData),
        ));

        //email for delegate
        $subject = DB::table('email_templates')->where('slug', 'conference_confirmation_email_template_delegate')->value('subject'); // send email
        $template = DB::table('email_templates')->where('slug', 'conference_confirmation_email_template_delegate')->value('body');  // send email

        foreach ($delegates as $delegate) {
            $nameDelegate = $delegate->name;
            $emailDelegate = $delegate->email;
            $passwordDelegate = $delegate->unhashed_password;
            $template = str_replace(
                [
                    '{{website_button}}',
                    '{{delegate_name}}',
                    '{{email}}',
                    '{{password}}',
                ],
                [
                    $website_button = '<a href="wsa-events.com/login" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:18px;border-style:solid;border-color:#31CB4B;border-width:10px 20px 10px 20px;display:inline-block;background:#31CB4B;border-radius:5px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center">LOGIN HERE</a>',
                    $delegate_name = $nameDelegate,
                    $email = $emailDelegate,
                    $password = $passwordDelegate,
                ],
                $template
            );
            Mail::to($emailDelegate)->send(new EventResetPasswordMail($template, $subject));
        }
    }



    public function sendInvoiceEmails($order , $user)
    {
        $delegatesData = [];
        $spousesData = [];
        $sponsorshipItemsData = [];
        $roomCount = null;
        $roomPrice = null;
        $amount = null;
        $total = null;
        //delegates
        if ($order->delegates) {
            $delegates = $order->delegates;
            $pricePerDelegate = ($order->total_price_delegate) ? ($order->total_price_delegate / $delegates->count()) : 0;
            if ($order->package) {
                $availableDelegatesCount = $order->package->delegate_count;
                foreach ($delegates as $key => $delegate) {
                    if ($availableDelegatesCount > $delegates->count()) {
                        $priceMessage = $pricePerDelegate;
                    } else {
                        $priceMessage = 'free';
                    }
                    $delegatesData[] = [
                        'name' => $delegate->name,
                        'price' => $key < $availableDelegatesCount ? $priceMessage : $pricePerDelegate,
                    ];
                }
            } else {
                foreach ($delegates as $key => $delegate) {
                    $delegatesData[] = [
                        'name' => $delegate->name,
                        'price' => $pricePerDelegate,
                    ];
                }
            }
        }
        //spouses
        if ($order->spouses) {
            $spouses = $order->spouses;
            $pricePerspouse = ($order->total_price_spouse) ? ($order->total_price_spouse / $spouses->count()) : 0;

            foreach ($spouses as $key => $spouse) {
                $spousesData[] = [
                    'name' => $spouse->name,
                    'price' => $pricePerspouse,
                ];
            }
        }
        //sponsorshipItems
        if ($order->sponsorshipItems) {
            $sponsorshipItems = $order->sponsorshipItems;
            $pricePerSponsorshipItem = ($order->total_price_sponsorship_items) ? ($order->total_price_sponsorship_items / $sponsorshipItems->count()) : 0;
            foreach ($sponsorshipItems as $key => $sponsorshipItem) {
                $sponsorshipItemsData[] = [
                    'name' => $sponsorshipItem->name,
                    'price' => $pricePerSponsorshipItem,
                ];
            }
        }
        $roomCount = $order->rooms()->count();
        $roomPrice = $order->total_price_rooms;
        $amount = $order->amount;
        $total = $order->total;
        //email
        $subject = DB::table('email_templates')->where('slug', 'conference_invoice_email_template')->value('subject'); // send email
        $emails = DB::table('email_templates')->where('slug', 'conference_invoice_email_template')->select('bcc')->first();
        $emails_bcc = explode(',', $emails->bcc);
        foreach ($emails_bcc as $email) {
            Mail::to($email)->queue(new InvoiceMailAdmin(
                $order,
                $roomPrice,
                $amount,
                $total,
                $roomCount,
                $subject,
                json_encode($delegatesData),
                json_encode($sponsorshipItemsData),
                json_encode($spousesData),
            ));
        }
        Mail::to($user->email)->queue(new InvoiceMail($order,$roomPrice,$amount,$total,$roomCount,$subject,json_encode($delegatesData),json_encode($sponsorshipItemsData),json_encode($spousesData),));
    }

    /**
     * Send appointment emails to both the sender and receiver of a one-to-one meeting.
     *
     * @param mixed $loggedInDelegate The logged-in delegate instance.
     * @param int $delegateId The ID of the delegate who is the receiver of the appointment.
     * @param int $timeSlotId The ID of the time slot for the appointment.
     * @return void
     */
    public static function sendAppointmentEmails($loggedInDelegate, $delegateId, $timeSlotId)
    {
        //time
        $timeSlot = DB::table('time_slots')
            ->select(
                DB::raw("DATE_FORMAT(time_slots.time_from, '%h:%i %p') as time_from"),
                DB::raw("DATE_FORMAT(time_slots.time_to, '%h:%i %p') as time_to"),
                DB::raw("DATE_FORMAT(event_days.date, '%d %b %Y') as formatted_date")
            )
            ->join('event_days', 'time_slots.day_id', '=', 'event_days.id')
            ->where('time_slots.id', request('time_slot_id'))
            ->first();
        $time_from = $timeSlot->time_from;
        $time_to = $timeSlot->time_to;
        $day_date =  $timeSlot->formatted_date ?? null;
        //receiver
        $receiver = Delegate::with(['user.country'])
            ->where('id', request('delegate_id'))
            ->first();
        // Sender
        $sender = Delegate::with(['user.country'])
            ->where('id', $loggedInDelegate->id)
            ->first();
        if ($receiver && $sender) {
            // Receiver Details
            $receiver_name = $receiver->name;
            $receiver_title = ucfirst($receiver->title);
            $receiver_email = $receiver->email;
            $receiver_job_title = $receiver->job_title;
            $receiver_company_country = $receiver->user->country->name ?? null;
            $receiver_company_city = $receiver->user->city ?? null;
            $receiver_company_name = $receiver->user->name ?? null;
            // Sender Details
            $sender_name = $sender->name;
            $sender_title = ucfirst($sender->title);
            $sender_email = $sender->email;
            $sender_job_title = $sender->job_title;
            $sender_company_country = $sender->user->country->name ?? null;
            $sender_company_city = $sender->user->city ?? null;
            $sender_company_name = $sender->user->name ?? null;
        }
        // email
        $templates = EmailTemplate::whereIn('slug', [
            'one_to_one_receiver_request_email_template',
            'one_to_one_sender_request_email_template'
        ])->get()->keyBy('slug');
        $subjectReceiver = $templates['one_to_one_receiver_request_email_template']->subject ?? null;
        $templateReceiver = $templates['one_to_one_receiver_request_email_template']->body ?? null;
        $subjectSender = $templates['one_to_one_sender_request_email_template']->subject ?? null;
        $templateSender = $templates['one_to_one_sender_request_email_template']->body ?? null;
        $login_button = '<a href="https://wsa-events.com/login" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:18px;border-style:solid;border-color:#31CB4B;border-width:10px 20px 10px 20px;display:inline-block;background:#31CB4B;border-radius:5px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center">LOGIN HERE</a>';

        $templateReceiver = str_replace(
            [
                '{{time_from}}',
                '{{time_to}}',
                '{{day_date}}',

                '{{receiver_name}}',
                '{{receiver_title}}',

                '{{sender_name}}',
                '{{sender_title}}',
                '{{sender_job_title}}',

                '{{sender_company_name}}',
                '{{sender_company_city}}',
                '{{sender_company_country}}',
                '{{login_button}}'
            ],
            [
                $time_from,
                $time_to,
                $day_date,

                $receiver_name,
                $receiver_title,

                $sender_name,
                $sender_title,
                $sender_job_title,

                $sender_company_name,
                $sender_company_city,
                $sender_company_country,
                $login_button
            ],
            $templateReceiver
        );

        $templateSender = str_replace(
            [

                '{{sender_name}}',
                '{{sender_title}}',
                '{{time_from}}',
                '{{time_to}}',
                '{{day_date}}',

                '{{receiver_name}}',
                '{{receiver_title}}',
                '{{receiver_job_title}}',
                '{{receiver_company_name}}',
                '{{receiver_company_city}}',
                '{{receiver_company_country}}',

                '{{login_button}}'
            ],
            [
                $sender_name,
                $sender_title,

                $time_from,
                $time_to,
                $day_date,

                $receiver_name,
                $receiver_title,
                $receiver_job_title,
                $receiver_company_name,
                $receiver_company_city,
                $receiver_company_country,

                $login_button
            ],
            $templateSender
        );
        Mail::to($receiver_email)->queue(new EventReceiverOneToOneMail($templateReceiver, $subjectReceiver));
        Mail::to($sender_email)->queue(new EventSenderOneToOneMail($templateSender, $subjectSender));
    }
    /**
     * Send a cancellation email to the receiver of a canceled one-to-one meeting.
     *
     * @param mixed $loggedInDelegate The logged-in delegate instance.
     * @param int $delegateId The ID of the delegate who is the receiver of the canceled appointment.
     * @param int $timeSlotId The ID of the time slot for the canceled appointment.
     * @return void
     */
    public static function sendCancellationEmail($loggedInDelegate, $delegateId, $timeSlotId)
    {
        $delegate_name = DB::table('delegates')->where('id', $delegateId)->value('name');
        $receiver_email = DB::table('delegates')->where('id', $delegateId)->value('email');
        $sender_name = $loggedInDelegate->name;
        $sender_email = $loggedInDelegate->email;
        $time_from = DB::table('time_slots')
            ->where('id', $timeSlotId)
            ->value(DB::raw("DATE_FORMAT(time_from, '%h:%i %p')"));
        $time_to = DB::table('time_slots')
            ->where('id', $timeSlotId)
            ->value(DB::raw("DATE_FORMAT(time_to, '%h:%i %p')"));
        $day_date = DB::table('time_slots')
            ->join('event_days', 'time_slots.day_id', '=', 'event_days.id')
            ->where('time_slots.id', $timeSlotId)
            ->value(DB::raw("DATE_FORMAT(event_days.date, '%d %b %Y')"));
        $login_button = '<a href="https://wsa-events.com/login" class="es-button" target="_blank" style="mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;color:#FFFFFF;font-size:18px;border-style:solid;border-color:#31CB4B;border-width:10px 20px 10px 20px;display:inline-block;background:#31CB4B;border-radius:5px;font-family:arial, \'helvetica neue\', helvetica, sans-serif;font-weight:normal;font-style:normal;line-height:22px;width:auto;text-align:center">LOGIN HERE</a>';

        $subject = DB::table('email_templates')->where('slug', 'one_to_one_cancel_request_email_template')->value('subject');
        $template = DB::table('email_templates')->where('slug', 'one_to_one_cancel_request_email_template')->value('body');

        $template = str_replace(
            [
                '{{delegate_name}}',
                '{{sender_name}}',
                '{{sender_email}}',
                '{{time_from}}',
                '{{time_to}}',
                '{{day_date}}',
                '{{login_button}}'
            ],
            [
                $delegate_name,
                $sender_name,
                $sender_email,
                $time_from,
                $time_to,
                $day_date,
                $login_button
            ],
            $template
        );

        // Send the email
        Mail::to($receiver_email)->send(new EventCancelOneToOneMail($template, $subject));
    }
}
