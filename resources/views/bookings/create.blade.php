@extends('layouts.app')

@section('content')
    <main class="relative overflow-hidden flex items-center justify-center min-h-screen">
        <!-- Background Image with Blur -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/background.jpeg') }}" alt="Background Image" class="object-cover w-full h-full filter blur-md">
        </div>

        <!-- Floating Caption -->
        <div class="absolute top-10 left-1/2 transform -translate-x-1/2 z-20 text-center">
            <p class="text-4xl font-semibold text-gray-700 dark:text-gray-300">
                Thank you for booking with us, please state the number of tickets you want to book:
            </p>
        </div>

        <div class="relative z-10 bg-gray-100 dark:bg-gray-700 shadow-lg rounded-lg p-8 max-w-lg w-full mt-20"> <!-- Margin-top to separate from caption -->
            <!-- Event Title -->
            <h2 class="text-3xl font-bold text-pink-500 mb-6 text-center">Book Event: {{ $event->name }}</h2>

            <!-- Form -->
            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="space-y-4">
                    <div class="flex items-center">
                        <label for="num_tickets" class="w-1/3 font-semibold text-lg text-gray-700 dark:text-gray-300">Number of Tickets:</label>
                        <input type="number" name="num_tickets" class="w-2/3 border-gray-300 rounded-lg p-2 text-lg" id="num_tickets" required>
                    </div>
                </div>

                <!-- Submit Button with Hover and Active Effects -->
                <button type="submit" class="block mt-6 bg-white border border-gray-300 hover:bg-pink-500 hover:text-white text-gray-700 font-semibold py-2 px-6 rounded-full shadow-md transition duration-300 ease-in-out w-full text-center
                        focus:ring-4 focus:ring-pink-300 active:bg-pink-600 active:text-white active:border-pink-600">
                    Book Now
                </button>
            </form>
        </div>
    </main>
@endsection
