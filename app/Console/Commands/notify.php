<?php

namespace App\Console\Commands;

use App\Models\ContactPerson;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\BirthDateMail;

class notify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WSA birth Date';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $emails = ContactPerson::select('id','email')->
        whereRaw('DATE_FORMAT(birth_date, \'%m-%d\') = \'' . Carbon::now()->format('m-d') . '\'')->get();
        foreach ($emails as  $email) {
            $contact = ContactPerson::where('id' , $email->id)->first();
            Mail::to($email->email)->send(new BirthDateMail($contact));
        }
    }
}
