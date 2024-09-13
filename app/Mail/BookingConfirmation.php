<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $attendee;
    public $numTickets;

    public function __construct($event, $user, $numTickets)
    {
        $this->event = $event;
        $this->user = $user;
        $this->numTickets = $numTickets;
    }

    public function build()
    {
        return $this->subject('Event Booking Confirmation')
            ->view('emails.booking_confirmation')
            ->with([
                'event' => $this->event,
                'attendee' => $this->attendee,
            ]);
    }
}

