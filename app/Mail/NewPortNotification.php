<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPortNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $port;

    /**
     * Create a new message instance.
     *
     * @param $port
     */
    public function __construct($port)
    {
        $this->port = $port;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Port Detected on Your Host')
                    ->view('emails.new_port')
                    ->with([
                        'port' => $this->port->port_number,
                        'trigger' => $this->port->trigger,
                        'version' => $this->port->version,
                        'details' => $this->port->details,
                        'asset_group' => $this->port->asset_group,  // Pastikan ini ada dalam model $port
                    ]);
    }
}
