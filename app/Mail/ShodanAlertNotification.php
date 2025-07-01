<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShodanAlertNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $alert;

    public function __construct($alert)
    {
        $this->alert = $alert;
    }

    public function build()
    {
        return $this->subject('Shodan Alert Notification')
                    ->view('emails.shodan_alert')
                    ->with([
                        'alert' => $this->alert,
                    ]);
    }
}
