<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirmation;
use App\Mail\OrganizerNotification;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('bookings.create', compact('event'));
    }

    public function store(Request $request)
    {
        $event = Event::findOrFail($request->event_id);

        $request->validate([
            'num_tickets' => 'required|integer|min:1',
        ]);

        if ($event->max_attendees && $event->bookings()->sum('num_tickets') + $request->num_tickets > $event->max_attendees) {
            return back()->with('error', 'Not enough tickets available for this event.');
        }

        $totalPrice = $event->ticket_price * $request->num_tickets;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'booking_date' => now(),
            'num_tickets' => $request->num_tickets,
            'total_price' => $totalPrice,
            'payment_status' => 'Pending',
            'status' => 'Pending',
        ]);

        // Send email to the attendee
        $numTickets = $request->num_tickets;  
        Mail::to(Auth::user()->email)->send(new BookingConfirmation($event, Auth::user(), $numTickets));

        
        $organizer = $event->organizer;
        if ($organizer) {
            Mail::to($organizer->email)->send(new OrganizerNotification($event, Auth::user(), $numTickets));
        }

        
        return redirect()->route('payment.show', ['booking' => $booking->id]);
    }
}
