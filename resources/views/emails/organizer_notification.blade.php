<h1>New Booking for Your Event!</h1>

<p>Hi {{ $event->organizer->name }},</p>

<p>An attendee has just booked tickets for your event "{{ $event->name }}".</p>

<p><strong>Attendee:</strong> {{ $booking->user->name }}</p>
<p><strong>Email:</strong> {{ $booking->user->email }}</p>
<p><strong>Number of Tickets:</strong> {{ $booking->num_tickets }}</p>
<p><strong>Total Price:</strong> ${{ number_format($booking->total_price, 2) }}</p>

<p>Event Date: {{ $event->date_time->format('F j, Y, g:i a') }}</p>

<p>Thanks,<br>{{ config('app.name') }}</p>
