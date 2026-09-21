@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden">

        <!-- Display Session Messages -->
        @if(session('success'))
            <div class="bg-green-500 text-white text-center py-2 px-4 rounded mb-4 max-w-7xl mx-auto">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="bg-red-500 text-white text-center py-2 px-4 rounded mb-4 max-w-7xl mx-auto">
                {{ session('error') }}
            </div>
        @endif

        <!-- Background Image with Blur -->
        <div class="absolute inset-0">
            <img
                src="{{ asset('images/background.jpg') }}"
                alt="Background Image"
                class="object-cover w-full h-full filter blur-md"
            >
        </div>

        <!-- Page Heading -->
        <div class="relative z-10 text-center mb-6">
            <h2 class="text-6xl font-bold mb-4 text-center text-black bubble-text">
                {{ __('Your Events') }}
            </h2>

            <p class="text-center text-gray-500 text-sm mb-8">
                Keep track of the events you’ve created and what’s coming up next.
            </p>
        </div>

        <style>
            .bubble-text {
                text-shadow:
                    0 2px 3px rgba(0, 0, 0, 0.1),
                    0 4px 6px rgba(0, 0, 0, 0.1),
                    0 6px 12px rgba(0, 0, 0, 0.1);
                font-family: 'Baloo';
                color: #232121;
                padding: 10px;
            }
        </style>

        <main class="relative z-10">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                    <!-- Events Section -->
                    <div class="relative overflow-hidden shadow-sm sm:rounded-lg">

                        <!-- Background Video -->
                        <div class="absolute inset-0">
                            <video
                                autoplay
                                muted
                                loop
                                playsinline
                                class="w-full h-full object-cover"
                            >
                                <source
                                    src="{{ asset('images/dashboard_vid.mp4') }}"
                                    type="video/mp4"
                                >
                            </video>
                        </div>

                        <!-- Light Overlay -->
                        <div class="absolute inset-0 bg-white/60"></div>

                        <!-- Content -->
                        <div class="relative z-10 p-6 text-gray-900">

                            <h3 class="text-lg font-bold mb-6 text-center">
                                {{ __('Your Upcoming Events') }}
                            </h3>

                            <!-- Event Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                                @foreach ($events as $event)

                                    <div class="bg-gray-800 dark:bg-gray-900 rounded-lg shadow-lg p-4 border border-gray-600 dark:border-gray-700 flex flex-col justify-between aspect-square">

                                        <!-- Event Image -->
                                        <img
                                            src="{{ Storage::url($event->image) }}"
                                            alt="{{ $event->title }}"
                                            class="object-cover w-full h-32 rounded-lg mb-3"
                                        >

                                        <!-- Event Name -->
                                        <a
                                            href="{{ route('events.show', $event->id) }}"
                                            class="block text-xl font-semibold text-white mb-2"
                                        >
                                            {{ $event->name }}
                                        </a>

                                        <!-- Event Date -->
                                        <span class="block text-gray-300 dark:text-gray-400 text-sm">
                                            {{ \Carbon\Carbon::parse($event->date_time)->format('F j, Y, g:i a') }}
                                        </span>

                                        <!-- Event Description -->
                                        <p class="mt-2 text-gray-300 dark:text-gray-400 text-sm">
                                            {{ $event->description }}
                                        </p>

                                        <!-- Delete Button -->
                                        <form
                                            action="{{ route('events.destroy', $event->id) }}"
                                            method="POST"
                                            class="mt-4"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="bg-red-500 text-white px-4 py-2 rounded"
                                                onclick="return confirm('Are you sure?')"
                                            >
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