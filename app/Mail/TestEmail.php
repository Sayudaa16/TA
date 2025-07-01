<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Message
     */
    public function build()
    {
        return $this->subject('Test Email')
                    ->view('emails.test'); // pastikan Anda memiliki tampilan ini
    }
}

