@extends('layouts.app')

@section('content')

    <main>
    <div class="flex justify-center mt-10">
        <div class="event-details bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 max-w-lg w-full">
            <h2 class="text-3xl font-bold text-pink-500 mb-4 text-center">{{ $event->name }}</h2>

            <div class="space-y-4">
                <div class="flex items-center">
                    <label class="w-1/3 font-semibold text-lg text-gray-700 dark:text-gray-300">Description:</label>
                    <p class="w-2/3 text-lg">{{ $event->description }}</p>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 font-semibold text-lg text-gray-700 dark:text-gray-300">Location:</label>
                    <p class="w-2/3 text-lg">{{ $event->location }}</p>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 font-semibold text-lg text-gray-700 dark:text-gray-300">Date and Time:</label>
                    <p class="w-2/3 text-lg">{{ $event->date_time }}</p>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 font-semibold text-lg text-gray-700 dark:text-gray-300">Category:</label>
                    <p class="w-2/3 text-lg">{{ $event->category->name }}</p>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 font-semibold text-lg text-gray-700 dark:text-gray-300">Max Attendees:</label>
                    <p class="w-2/3 text-lg">{{ $event->max_attendees }}</p>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 font-semibold text-lg text-gray-700 dark:text-gray-300">Ticket Price:</label>
                    <p class="w-2/3 text-lg">${{ $event->ticket_price }}</p>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 font-semibold text-lg text-gray-700 dark:text-gray-300">Status:</label>
                    <p class="w-2/3 text-lg">{{ $event->status }}</p>
                </div>
            </div>

            @auth
                <a href="{{ route('bookings.create', $event->id) }}" class="block mt-8 bg-pink-500 hover:bg-pink-600 text-white font-semibold py-2 px-6 rounded-full shadow-md transition duration-300 ease-in-out text-center">
                    Book Now
                </a>
            @else
                <p class="text-lg text-gray-500 mt-6">Please <a href="{{ route('login') }}" class="text-pink-500 hover:underline">login</a> to book this event.</p>
            @endauth
        </div>
    </div>
    </main>
@endsection
