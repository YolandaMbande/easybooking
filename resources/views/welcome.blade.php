@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden">
        <!-- Background Image with Blur -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/background.jpg') }}" alt="Background Image" class="object-cover w-full h-full filter blur-md">
        </div>
    
        <div class="relative z-10 text-center mb-6">
            <h2 class="font-semibold text-5xl text-white leading-tight mb-2 bubble-text">
                {{ __('Welcome To Easy Booking!') }}
            </h2>
        </div>

        <style>
            .bubble-text {
                text-shadow: 0 2px 3px rgba(0, 0, 0, 0.1),
                            0 4px 6px rgba(0, 0, 0, 0.1),
                            0 6px 12px rgba(0, 0, 0, 0.1);
                font-family: 'Baloo'; 
                color: #e5e5e5;
                padding: 10px;
            }
        </style>

        <!-- Introductory Section -->
        <div class="relative z-10 text-center py-12 bg-gray-900 bg-opacity-50">
            <h1 class="text-4xl font-bold text-white mb-4">Discover Amazing Events!</h1>
            <p class="text-lg text-white mb-4">
                Join us for an unforgettable experience. Browse through our upcoming events and find the perfect one for you!
            </p>
            <p class="text-lg text-white">
                Whether you're looking for fun, learning, or networking opportunities, we have something for everyone.
            </p>
        </div>

        <!-- Exciting Speech Section -->
        <div class="relative z-10 py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 mb-6 lg:mb-0">
                    <img src="{{ asset('images/dance-event.jpg') }}" alt="Exciting Event" class="object-cover w-full h-64 rounded-lg shadow-lg">
                </div>
                <div class="lg:w-1/2 lg:pl-8">
                    <h3 class="text-2xl font-bold mb-4">Get Ready for an Exciting Journey!</h3>
                    <p class="text-gray-700">
                        Our events are designed to inspire and engage. Experience live performances, interactive workshops, and networking sessions that connect you with industry leaders. Don’t miss out on the opportunity to learn and grow in a vibrant community. Join us and be part of something extraordinary!
                    </p>
                </div>
            </div>
        </div>

        <!-- Create Your Own Events Section -->
        <div class="relative z-10 py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center">
                <div class="lg:w-1/2 lg:pr-8">
                    <h3 class="text-2xl font-bold mb-4">Create Your Own Events for Free!</h3>
                    <p class="text-gray-700 mb-4">
                        Have an idea for an event? You can create your own events for free! It’s simple and hassle-free. Share your passion and bring people together for an unforgettable experience. Start your journey today and make a difference in your community!
                    </p>
                </div>
                <div class="lg:w-1/2 mb-6 lg:mb-0">
                    <img src="{{ asset('images/create-event.webp') }}" alt="Create Event" class="object-cover w-full h-64 rounded-lg shadow-lg">
                </div>
            </div>
        </div>

        <main class="relative z-10">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-grey dark:bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <!-- Upcoming Events Section -->
                            <h3 class="text-5xl font-bold mb-6 text-center text-white">{{ __('Coming Events') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @forelse ($upcomingEvents as $event)
                                    @if (\Carbon\Carbon::parse($event->date_time)->isFuture())
                                        <div class="bg-gray-800 dark:bg-gray-900 shadow-lg p-4 border border-gray-600 dark:border-gray-700 flex flex-col justify-between relative aspect-square">
                                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="object-cover w-full h-32 rounded-lg mb-2">
                                            
                                            <a href="{{ route('events.show', $event->id) }}" class="block text-xl font-semibold text-white mb-2">
                                                {{ $event->name }}
                                            </a>
                                            <span class="block text-gray-300 dark:text-gray-400 text-sm">
                                                {{ \Carbon\Carbon::parse($event->date_time)->format('F j, Y, g:i a') }}
                                            </span>
                                            <!-- Event description -->
                                            <p class="mt-2 text-gray-300 dark:text-gray-400 text-sm">
                                                {{ $event->description }}
                                            </p>
                                        </div>
                                    @endif
                                @empty
                                    <p class="text-center text-white">No upcoming events found.</p>
                                @endforelse
                            </div>

                            <!-- Ongoing Events Section -->
                            <h3 class="text-5xl font-bold mb-6 text-center mt-10 text-white">{{ __('What\'s Happening Today') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach ($ongoingEvents as $event)
                            
                                <div class="bg-gray-800 dark:bg-gray-900 shadow-lg p-4 border border-gray-600 dark:border-gray-700 flex flex-col justify-between relative aspect-square">
                                    <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}" class="object-cover w-full h-32 rounded-lg mb-2 border-4 border-white"> <!-- Add your image here -->
                                    <a href="{{ route('events.show', $event->id) }}" class="block text-xl font-semibold text-white mb-2">
                                        {{ $event->name }}
                                    </a>
                                    <span class="block text-gray-300 dark:text-gray-400 text-sm">
                                        {{ \Carbon\Carbon::parse($event->date_time)->format('F j, Y, g:i a') }}
                                    </span>
                                    <!-- Event description -->
                                    <p class="mt-2 text-gray-300 dark:text-gray-400 text-sm">
                                        {{ $event->description }}
                                    </p>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
