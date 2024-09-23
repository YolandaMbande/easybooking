<!DOCTYPE html>
<html>
<head>
    <title>Payment Success</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> 
</head>
<body class="relative min-h-screen bg-gray-100">
    <div class="absolute inset-0">
        <img src="{{ asset('images/background.jpeg') }}" alt="Background Image" class="object-cover w-full h-full filter blur-md">
    </div>
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen">
        <h1 class="text-4xl font-bold text-pink-500 mb-4">Payment Successful!</h1>
        <p class="text-lg text-gray-700 text-center mb-4">
            Thank you for your payment. Your booking for the event <strong>{{ $booking->event->name }}</strong> was successful.
        </p>
        <p class="text-lg text-gray-700 text-center mb-4">
            We have received your payment of <strong>R{{ $booking->total_price }}</strong>.
        </p>
        <p class="text-lg text-gray-700 text-center mb-4">
            Your booking ID is <strong>{{ $booking->id }}</strong>.
        </p>
        <p class="text-lg text-gray-700 text-center mb-6">
            We look forward to seeing you at the event!
        </p>
        <a href="{{ route('dashboard') }}" class="inline-block bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out">
            Go to your Dashboard
        </a>
    </div>
</body>
</html>
