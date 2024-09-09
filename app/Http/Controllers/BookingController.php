<?php
namespace App\Http\Controllers;

    use App\Models\Booking;
    use App\Models\Event;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

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

        // Check if the event has enough tickets
        if ($event->max_attendees && $event->bookings()->sum('num_tickets') + $request->num_tickets > $event->max_attendees) {
        return back()->with('error', 'Not enough tickets available for this event.');
    }

    // Calculate the total price
    $totalPrice = $event->ticket_price * $request->num_tickets;

    // Create the booking
    $booking = Booking::create([
    'user_id' => Auth::id(),
    'event_id' => $event->id,
    'booking_date' => now(),
    'num_tickets' => $request->num_tickets,
    'total_price' => $totalPrice,
    'payment_status' => 'Pending',
    'status' => 'Pending',
    ]);

    // Redirect to the payment page with the correct booking ID
    return redirect()->route('payment.show', ['booking' => $booking->id]);
    }
    }

