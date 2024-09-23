@extends('layouts.app')

@section('content')
    <main class="relative overflow-hidden flex items-center justify-center min-h-screen">
        <!-- Background Image with Blur -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/background.jpeg') }}" alt="Background Image" class="object-cover w-full h-full filter blur-md">
        </div>

        <div class="relative z-10 max-w-4xl w-full bg-white p-20 rounded-lg shadow-md space-y-6"> <!-- Increased padding and width -->
            <h1 class="text-2xl font-bold text-center text-gray-900">Complete Your Payment</h1>
            <p class="text-center text-lg text-gray-700">Booking for: <span class="font-semibold">{{ $booking->event->name }}</span></p>
            <p class="text-center text-lg text-gray-700">Amount: R<span class="font-semibold">{{ $paymentData['amount'] }}</span></p>

            <form action="https://www.payfast.co.za/eng/process" method="post" class="space-y-4">
                @csrf

                <input type="hidden" name="merchant_id" value="{{ $paymentData['merchant_id'] }}">
                <input type="hidden" name="merchant_key" value="{{ $paymentData['merchant_key'] }}">
                <input type="hidden" name="amount" value="{{ $paymentData['amount'] }}">
                <input type="hidden" name="item_name" value="{{ $paymentData['item_name'] }}">
                <input type="hidden" name="return_url" value="{{ $paymentData['return_url'] }}">
                <input type="hidden" name="cancel_url" value="{{ $paymentData['cancel_url'] }}">
                <input type="hidden" name="notify_url" value="{{ $paymentData['notify_url'] }}">
                <input type="hidden" name="name_first" value="{{ $paymentData['name_first'] }}">
                <input type="hidden" name="name_last" value="{{ $paymentData['name_last'] }}">
                <input type="hidden" name="email_address" value="{{ $paymentData['email_address'] }}">

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out">
                        Pay Now
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection

