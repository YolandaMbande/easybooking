<!DOCTYPE html>
<html>
<head>
    <title>Payment Canceled</title>
</head>
<body>
    <h1>Payment Canceled</h1>
    <p>Your payment for the event <strong>{{ $booking->event->name }}</strong> was canceled.</p>
    <p>You can try making the payment again by visiting your dashboard and selecting the event booking.</p>

    <a href="{{ route('dashboard') }}">Go to your Dashboard</a>
</body>
</html>
