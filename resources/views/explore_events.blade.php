@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden min-h-screen flex flex-col">
        <div class="absolute inset-0">
            <img src="{{ asset('images/background.jpg') }}" alt="Background Image" class="object-cover w-full h-full filter blur-md">
        </div>

        <div class="relative z-10 flex flex-col justify-center items-center min-h-screen bg-black bg-opacity-50 text-white">
            <h1 class="text-4xl font-bold">Find an event & buy tickets</h1>
            <main class="mt-10 w-full max-w-3xl">
                <form method="GET" action="{{ route('events.search') }}" class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label for="event_name" class="block text-white">Search Event Name or Venue</label>
                        <input type="text" id="event_name" name="event_name" class="mt-1 block w-full px-3 py-2 rounded-md hover:border-pink-500 focus:border-pink-500" placeholder="Enter event name or venue">
                    </div>

                    <div>
                        <label for="suburb" class="block text-white">Suburb/Postcode</label>
                        <input type="text" id="suburb" name="suburb" class="mt-1 block w-full px-3 py-2 rounded-md hover:border-pink-500 focus:border-pink-500" placeholder="Enter suburb or postcode">
                    </div>

                    <div>
                        <label for="date" class="block text-white">Date</label>
                        <input type="date" id="date" name="date" class="mt-1 block w-full px-3 py-2 rounded-md hover:border-pink-500 focus:border-pink-500">
                    </div>

                    <div class="mt-6 flex justify-center gap-4 col-span-3">
                        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-purple-500">
                            Find Events
                        </button>
                        <button type="button" id="clear-button" class="bg-transparent border-2 border-white text-white px-4 py-2 rounded-md hover:bg-white hover:text-black">
                            Clear
                        </button>
                    </div>
                </form>
            </main>
 
            <!-- Display Events or No Events Found Message -->
            <div class="mt-10 w-full max-w-3xl">
                @if(request()->query('event_name') || request()->query('suburb') || request()->query('date'))
                    @if($events->isEmpty())
                        <p class="text-center text-white">No events found for the given criteria.</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach ($events as $event)
                                <div class="bg-gray-800 rounded-lg shadow-lg p-4">
                                    <a href="{{ route('events.show', $event->id) }}" class="block text-xl font-semibold text-white mb-2">
                                        {{ $event->name }}
                                    </a>
                                    <span class="block text-gray-300 text-sm">
                                        @php
                                            $date = \Carbon\Carbon::parse($event->date_time);
                                        @endphp
                                        {{ $date->format('F j, Y, g:i a') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Script for Clearing the Form -->
    <script>
        document.getElementById('clear-button').addEventListener('click', function() {
            document.getElementById('event_name').value = '';
            document.getElementById('suburb').value = '';
            document.getElementById('date').value = '';
        });
    </script>
@endsection
