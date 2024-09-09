@extends('layouts.app')

@section('content')

    <main>
        <div class="flex justify-center mt-10">
            <div class="event-details bg-gray-100 dark:bg-gray-700 shadow-lg rounded-lg p-8 max-w-lg w-full"> <!-- Changed background color -->

                <h2 class="text-4xl font-bold text-pink-500 mb-6 text-center">{{ $event->name }}</h2>

                <div class="space-y-6">
                    <div class="flex flex-col">
                        <label class="font-semibold text-lg text-gray-700 dark:text-gray-300">Description:</label>
                        <p class="text-gray-600 dark:text-gray-400">{{ $event->description }}</p>
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold text-lg text-gray-700 dark:text-gray-300">Location:</label>
                        <p class="text-gray-600 dark:text-gray-400">{{ $event->location }}</p>
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold text-lg text-gray-700 dark:text-gray-300">Date and Time:</label>
                        <p class="text-gray-600 dark:text-gray-400">{{ $event->date_time }}</p>
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold text-lg text-gray-700 dark:text-gray-300">Category:</label>
                        <p class="text-gray-600 dark:text-gray-400">{{ $event->category->name }}</p>
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold text-lg text-gray-700 dark:text-gray-300">Max Attendees:</label>
                        <p class="text-gray-600 dark:text-gray-400">{{ $event->max_attendees }}</p>
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold text-lg text-gray-700 dark:text-gray-300">Ticket Price:</label>
                        <p class="text-gray-600 dark:text-gray-400">R{{ $event->ticket_price }}</p>
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold text-lg text-gray-700 dark:text-gray-300">Status:</label>
                        <p class="text-gray-600 dark:text-gray-400">{{ $event->status }}</p>
                    </div>
                </div>

                @auth
                    <div class="mt-8 text-center">
                        <a href="{{ route('bookings.create', $event->id) }}" class="inline-block bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold py-5 px-20 rounded-full shadow-lg transition duration-300 ease-in-out"> <!-- Changed button style -->
                            Book Now
                        </a>
                    </div>
                @else
                    <p class="text-lg text-gray-500 mt-6 text-center">Please <a href="{{ route('login') }}" class="text-pink-500 hover:underline">login</a> to book this event.</p>
                @endauth
            </div>
        </div>
    </main>
@endsection
