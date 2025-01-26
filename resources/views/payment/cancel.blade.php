@extends('layouts.app')

@section('content')
   
<main class="relative min-h-screen bg-gray-100">
    <div class="absolute inset-0">
        <img src="{{ asset('images/background.jpg') }}" alt="Background Image" class="object-cover w-full h-full filter blur-md">
    </div>
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen">
        <h1 class="text-4xl font-bold text-pink-500 mb-4">Payment Canceled</h1>
        <p class="text-lg text-gray-700 text-center mb-4">
            Your payment for the event <strong>{{ $booking->event->name }}</strong> was canceled.
        </p>
        <p class="text-lg text-gray-700 text-center mb-6">
            You can try making the payment again by visiting your dashboard and selecting the event booking.
        </p>
        <a href="{{ route('dashboard') }}" class="inline-block bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold py-2 px-4 rounded-full shadow-lg transition duration-300 ease-in-out">
            Go to your Dashboard
        </a>
    </div>
</main>
@endsection

