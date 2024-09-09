@extends('layouts.app')

@section('content')
    <main class="flex justify-center mt-10">
        <div class="bg-gray-100 dark:bg-gray-700 shadow-lg rounded-lg p-8 max-w-lg w-full"> <!-- Changed background color -->
            <h2 class="text-3xl font-bold text-pink-500 mb-6 text-center">Book Event: {{ $event->name }}</h2>

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="space-y-4">
                    <div class="flex items-center">
                        <label for="num_tickets" class="w-1/3 font-semibold text-lg text-gray-700 dark:text-gray-300">Number of Tickets:</label>
                        <input type="number" name="num_tickets" class="w-2/3 border-gray-300 rounded-lg p-2 text-lg" id="num_tickets" required>
                    </div>
                </div>

                <button type="submit" class="block mt-6 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold py-2 px-6 rounded-full shadow-md transition duration-300 ease-in-out w-full text-center">
                    Book Now
                </button>
            </form>
        </div>
    </main>
@endsection
