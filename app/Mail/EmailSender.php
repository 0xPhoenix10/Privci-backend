<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Auth;

class EmailSender extends Mailable
{
    use Queueable, SerializesModels;

    private $emailParams;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->emailParams = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from($this->emailParams->senderEmail, $this->emailParams->senderName)
            ->subject($this->emailParams->subject)
            ->view('mail.template')
            ->with(['emailParams' => $this->emailParams]);
    }
}