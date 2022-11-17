<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $pwd_store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pwd_store)
    {
        $this->pwd_store = $pwd_store;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $this->details['subject']
        return $this->subject('Reset your password')
            ->view('email.reset_password');
    }
}
