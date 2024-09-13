<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
</head>
<body>
<h1>Payment Successful!</h1>
<p>Thank you for your payment. Your booking for the event <strong>{{ $booking->event->name }}</strong> was successful.</p>
<p>We have received your payment of <strong>R{{ $booking->total_price }}</strong>.</p>
<p>Your booking ID is <strong>{{ $booking->id }}</strong>.</p>
<p>We look forward to seeing you at the event!</p>

<a href="{{ route('dashboard') }}">Go to your Dashboard</a>
</body>
</html>
