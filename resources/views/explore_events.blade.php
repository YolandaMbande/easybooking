@extends('layouts.app')

@section('content')

    <div class="relative overflow-hidden min-h-screen flex flex-col"
        style="background-image: url('{{ asset('images/left_side.png') }}'); background-size: cover; background-position: center">

        <!-- Background Image -->
        <div class="absolute inset-0">
            <img
                src="{{ asset('images/background.jpg') }}"
                alt="Background Image"
                class="object-cover w-full h-full filter blur-md"
            >
        </div>

        <!-- Dark Overlay + Content -->
        <div class="relative z-10 flex flex-col justify-center items-center min-h-screen bg-black bg-opacity-50 text-white">

            <h1 class="text-4xl font-bold">
                Find an event & buy tickets
            </h1>

            <!-- Search Form -->
            <main class="mt-10 w-full max-w-3xl">

                <form
                    method="GET"
                    action="{{ route('events.search') }}"
                    class="grid grid-cols-1 gap-6 md:grid-cols-3"
                >

                    <!-- Event Name -->
                    <div>
                        <label for="event_name" class="block text-white">
                            Search Event Name or Venue
                        </label>

                        <input
                            type="text"
                            id="event_name"
                            name="event_name"
                            class="mt-1 block w-full px-3 py-2 rounded-md hover:border-pink-500 focus:border-pink-500 text-black"
                            placeholder="Enter event name or venue"
                        >
                    </div>

                    <!-- Suburb -->
                    <div>
                        <label for="suburb" class="block text-white">
                            Suburb/Postcode
                        </label>

                        <input
                            type="text"
                            id="suburb"
                            name="suburb"
                            class="mt-1 block w-full px-3 py-2 rounded-md hover:border-pink-500 focus:border-pink-500 text-black"
                            placeholder="Enter suburb or postcode"
                        >
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-white">
                            Date
                        </label>

                        <input
                            type="date"
                            id="date"
                            name="date"
                            class="mt-1 block w-full px-3 py-2 rounded-md hover:border-pink-500 focus:border-pink-500 text-black"
                        >
                    </div>

                    <!-- Buttons -->
                    <div class="mt-6 flex justify-center gap-4 col-span-3">

                        <button
                            type="submit"
                            class="bg-pink-500 text-white px-4 py-2 rounded-md hover:bg-purple-500"
                        >
                            Find Events
                        </button>

                        <button
                            type="button"
                            id="clear-button"
                            class="bg-transparent border-2 border-white text-white px-4 py-2 rounded-md hover:bg-white hover:text-black"
                        >
                            Clear
                        </button>

                    </div>

                </form>

            </main>


            <!-- Display Events or No Events Found Message -->
            <div class="mt-10 w-full max-w-7xl px-6">

                @if(request()->query('event_name') || request()->query('suburb') || request()->query('date'))

                    @if($events->isEmpty())

                        <p class="text-center text-white">
                            No events found for the given criteria.
                        </p>

                    @else

                        <!-- Event Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                            @foreach ($events as $event)

                                <div class="bg-white shadow-lg p-4 border border-gray-200 rounded-lg flex flex-col justify-between relative aspect-square">

                                    <!-- Image + Date -->
                                    <div class="relative w-full">

                                        <img
                                            src="{{ Storage::url($event->image) }}"
                                            alt="{{ $event->title }}"
                                            class="object-cover w-full h-48 rounded-lg"
                                        >

                                        <!-- Date Bubble -->
                                        <span class="absolute top-2 right-2 inline-block w-fit text-xs font-medium text-gray-800 bg-white/90 px-3 py-1.5 rounded-lg shadow-sm">
                                            {{ \Carbon\Carbon::parse($event->date_time)->format('M j, Y') }}
                                        </span>

                                    </div>


                                    <!-- Event Name -->
                                    <a
                                        href="{{ route('events.show', $event->id) }}"
                                        class="block text-xl font-semibold text-gray-900 mb-1"
                                    >
                                        {{ $event->name }}
                                    </a>


                                    <!-- Location -->
                                    <p class="mt-2 text-gray-600 text-sm">
                                        {{ $event->location }}
                                    </p>


                                    <!-- Event Category -->
                                    <span class="inline-block text-sm font-medium text-red-500 bg-gray-100 px-3 py-1 rounded-lg shadow-sm mb-2 w-fit">
                                        {{ $event->category->name }}
                                    </span>


                                    <!-- Event Description -->
                                    <p class="mt-2 text-gray-600 text-sm">
                                        {{ $event->description }}
                                    </p>

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