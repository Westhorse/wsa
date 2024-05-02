<?php

namespace App\Mail;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mailer\Transport\Dsn;

class MailchimpJob extends Mailable
{

    protected User $client;

    public function __construct(User $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * @param SentMessage $message
     * @return void
     */

    protected function samp(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $this->client->messages->send(['message' => [
            'from_email' => $email->getFrom(),
            'to' => collect($email->getTo())->map(function ($email) {
                return ['email' => $email->getAddress(), 'type' => 'to'];
            })->all(),
            'subject' => $email->getSubject(),
            'text' => $email->getTextBody(),
        ]]);
    }

    public function job(): void
    {
        Mail::extend('sendinblue', function () {

            new Dsn(
                'sendinblue+api',
                'default',
                config('services.sendinblue.key')
            );
        });
    }

    /**
     * Get the string .
     *
     * @return string
     */

    public function __toString(): string
    {
        return 'mailchimp';
    }

    public function content(): Content
    {
        return new Content(
            with: [
                'mailchimp' => "aa2087333@gmail.com",
            ],
        );
    }

    public function session_decode(): Content
    {
        return new Content(
            with: [
                'orderName' => $this->client->name,
                'orderPrice' => $this->client->price,
            ],
        );
    }
}
