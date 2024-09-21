@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden">
        <!-- Background Image with Blur -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/background.jpeg') }}" alt="Background Image" class="object-cover w-full h-full filter blur-md">
        </div>
    
        <div class="relative z-10 text-center mb-6">
            <h2 class="font-semibold text-3xl text-black dark:text-gray-400 leading-tight mb-2 bubble-text">
                {{ __('Welcome To Easy Booking!') }}
            </h2>
        </div>

        <style>
            .bubble-text {
                text-shadow: 0 2px 3px rgba(0, 0, 0, 0.1),
                            0 4px 6px rgba(0, 0, 0, 0.1),
                            0 6px 12px rgba(0, 0, 0, 0.1);
                font-family: 'Baloo', cursive; 
                color: #c2185b; 
                padding: 10px;
            }
        </style>


        <main class="relative z-10">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-pink-100 dark:bg-pink-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <!-- Upcoming Events Section -->
                            <h3 class="text-lg font-bold mb-6 text-center">{{ __('Upcoming Events') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach ($upcomingEvents as $event)
                                    <div class="bg-pink-200 dark:bg-pink-700 rounded-lg shadow-lg p-4 border border-pink-300 dark:border-pink-600 flex flex-col justify-between relative aspect-square">
                                        <a href="{{ route('events.show', $event->id) }}" class="block text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                            {{ $event->title }}
                                        </a>
                                        <span class="block text-gray-600 dark:text-gray-400 text-sm">
                                            {{ \Carbon\Carbon::parse($event->date_time)->format('F j, Y, g:i a') }}
                                        </span>
                                        <!-- Event description -->
                                        <p class="mt-2 text-gray-700 dark:text-gray-300 text-sm">
                                            {{ $event->description }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Complete Events Section -->
                            <h3 class="text-lg font-bold mb-6 text-center mt-10">{{ __('Complete Events') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach ($completeEvents as $event)
                                    <div class="bg-pink-200 dark:bg-pink-700 rounded-lg shadow-lg p-4 border border-pink-300 dark:border-pink-600 flex flex-col justify-between relative aspect-square">
                                        <a href="{{ route('events.show', $event->id) }}" class="block text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                            {{ $event->title }}
                                        </a>
                                        <span class="block text-gray-600 dark:text-gray-400 text-sm">
                                            {{ \Carbon\Carbon::parse($event->date_time)->format('F j, Y, g:i a') }}
                                        </span>
                                        <!-- Event description -->
                                        <p class="mt-2 text-gray-700 dark:text-gray-300 text-sm">
                                            {{ $event->description }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Ongoing Events Section -->
                            <h3 class="text-lg font-bold mb-6 text-center mt-10">{{ __('Ongoing Events') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach ($ongoingEvents as $event)
                                    <div class="bg-pink-200 dark:bg-pink-700 rounded-lg shadow-lg p-4 border border-pink-300 dark:border-pink-600 flex flex-col justify-between relative aspect-square">
                                        <a href="{{ route('events.show', $event->id) }}" class="block text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                                            {{ $event->title }}
                                        </a>
                                        <span class="block text-gray-600 dark:text-gray-400 text-sm">
                                            {{ \Carbon\Carbon::parse($event->date_time)->format('F j, Y, g:i a') }}
                                        </span>
                                        <!-- Event description -->
                                        <p class="mt-2 text-gray-700 dark:text-gray-300 text-sm">
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
