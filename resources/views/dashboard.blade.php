@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden">
        <!-- Background Image with Blur -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/background.jpg') }}" alt="Background Image" class="object-cover w-full h-full filter blur-md">
        </div>

        <div class="relative z-10 text-center mb-6">
            <h2 class="font-semibold text-3xl text-black dark:text-gray-400 leading-tight mb-2 bubble-text">
                {{ __('Your Events') }}
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

        <main class="relative z-10">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-pink-100 dark:bg-pink-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-bold mb-6 text-center">{{ __('Your Upcoming Events') }}</h3>

                            <!-- Adjusted grid layout with pink background -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach ($events as $event)
                                    <div class="bg-gray-800 dark:bg-gray-900 rounded-lg shadow-lg p-4 border border-gray-600 dark:border-gray-700 flex flex-col justify-between aspect-square">
                                        <a href="{{ route('events.show', $event->id) }}" class="block text-xl font-semibold text-white mb-2">
                                            {{ $event->name }}
                                        </a>
                                        <span class="block text-gray-300 dark:text-gray-400 text-sm">
                                            {{ \Carbon\Carbon::parse($event->date_time)->format('F j, Y, g:i a') }}
                                        </span>

                                        <!-- Delete Button -->
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="mt-4">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Are you sure?')">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
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
