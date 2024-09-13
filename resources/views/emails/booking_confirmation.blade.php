<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
 <h1>Thank you for booking {{ $numTickets }} tickets for {{ $event->name }}!</h1>
<p>Event Date: {{ $event->date_time->format('F j, Y, g:i a') }}</p>
<p>Your booking details:</p>
<ul>
    <li>Number of Tickets: {{ $numTickets }}</li>
    <li>Total Price: R{{ $event->ticket_price * $numTickets }}</li>
</ul>
<p>We look forward to seeing you at the event, dzoll!</p>
</body>
</html>
