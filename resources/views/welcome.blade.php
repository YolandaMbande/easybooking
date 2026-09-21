@extends('layouts.app')

@section('content')

    <!-- HERO SECTION -->
    <div class="relative w-full h-screen overflow-hidden bg-white" style="background-image: url('{{ asset('images/left_side.png') }}'); background-size: cover; background-position: center">

        <!-- Hero Content -->
        <div class="relative z-10 flex flex-col items-left justify-center h-full text-left px-6 -translate-y-10">   
        <h2 class="font-bold text-8xl text-gray-900 leading-tight bubble-text">
                Find Amazing Events Happening 
                <img src="{{ asset('images/test1.jpg') }}" alt=""
                    class="inline-block w-60 h-20 object-cover rounded-3xl mx-2">
                <br/>
                 in Your City.
            </h2>
            <p class="text-lg text-gray-600">
                Looking for something exciting to do? Explore events happening in your city,
                discover new experiences, and <br/> find something worth adding to your calendar.
                From live entertainment and social gatherings to workshops <br/> and special occasions,
                Easy Booking makes it simple to find your next event.
            </p>
            <a href="{{ route('explore_events') }}"
                class="self-start mt-10 px-6 py-3 bg-black text-white text-sm font-semibold rounded-xl shadow-md hover:bg-gray-800 transition duration-200">
                Explore Events Available!
            </a>
        </div>

        <style>
            .bubble-text {
                text-shadow:
                    0 2px 3px rgba(0, 0, 0, 0.1),
                    0 4px 6px rgba(0, 0, 0, 0.1),
                    0 6px 12px rgba(0, 0, 0, 0.1);
                font-family: 'Baloo';
                color: #151414;
                padding: 10px;
            }
        </style>

    </div>

        <!-- Exciting Speech Section -->
<!-- Events Information Section -->
<div
    class="relative overflow-hidden"
    style="background-image: url('{{ asset('images/separator.jpg') }}'); background-size: cover; background-position: center;"
>

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/70"></div>

    <!-- Content -->
    <div class="relative z-10">

        <!-- Exciting Speech Section -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center">

                <div class="lg:w-1/2 mb-6 lg:mb-0">
                    <img
                        src="{{ asset('images/dance-event.jpg') }}"
                        alt="Exciting Event"
                        class="object-cover w-full h-64 rounded-lg shadow-lg"
                    >
                </div>

                <div class="lg:w-1/2 lg:pl-8">
                    <h3 class="text-2xl font-bold mb-4 text-white">
                        Get Ready for an Exciting Journey!
                    </h3>

                    <p class="text-gray-200">
                        Our events are designed to inspire and engage. Experience live performances, interactive workshops, and networking sessions that connect you with industry leaders. Don’t miss out on the opportunity to learn and grow in a vibrant community. Join us and be part of something extraordinary!
                    </p>
                </div>

            </div>
        </div>


        <!-- Create Your Own Events Section -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center">

                <div class="lg:w-1/2 lg:pr-8">
                    <h3 class="text-2xl font-bold mb-4 text-white">
                        Create Your Own Events for Free!
                    </h3>

                    <p class="text-gray-200 mb-4">
                        Have an idea for an event? You can create your own events for free! It’s simple and hassle-free. Share your passion and bring people together for an unforgettable experience. Start your journey today and make a difference in your community!
                    </p>
                </div>

                <div class="lg:w-1/2 mb-6 lg:mb-0">
                    <img
                        src="{{ asset('images/create-event.webp') }}"
                        alt="Create Event"
                        class="object-cover w-full h-64 rounded-lg shadow-lg"
                    >
                </div>

            </div>
        </div>

    </div>
</div>

        <main class="relative z-10">
    <div class="py-12">
        <div class="w-full px-6 lg:px-12">

            <div class="bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900">

                    <!-- Upcoming Events Section -->

                    <h3 class="text-6xl font-bold mb-4 text-center text-black bubble-text">
                        {{ __('Explore Exciting Events & Experiences') }}
                    </h3>

                    <style>
                        .bubble-text {
                            text-shadow:
                                0 2px 3px rgba(0, 0, 0, 0.1),
                                0 4px 6px rgba(0, 0, 0, 0.1),
                                0 6px 12px rgba(0, 0, 0, 0.1);
                            font-family: 'Baloo';
                            color: #151414;
                            padding: 20px;
                        }
                    </style>
                    <p class="text-xl text-black mb-[180px] text-center">
                        Variety of Events to suit any taste
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                        @forelse ($upcomingEvents as $event)

                            @if (\Carbon\Carbon::parse($event->date_time)->isFuture())

                            <!-- Event Card -->
                        <div class="bg-white shadow-lg p-4 border border-gray-200 rounded-lg flex flex-col justify-between relative aspect-square">
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

                        <a
                            href="{{ route('events.show', $event->id) }}"
                            class="block text-xl font-semibold text-gray-900 mb-1"
                        >
                            {{ $event->name }}
                        </a>

                        <p class="mt-2 text-gray-600 text-sm">
                            {{ $event->location }}
                        </p>

                        <!-- Event Category -->
                        <span class="inline-block text-sm font-medium text-red-500 bg-gray-100 px-3 py-1 rounded-lg shadow-sm mb-2 w-fit">
                            {{ $event->category->name }}
                        </span>

                        <!-- Event description -->
                        <p class="mt-2 text-gray-600 text-sm">
                            {{ $event->description }}
                        </p>

                    </div>

                        @endif

                        @empty

                            <p class="text-center text-gray-600">
                                No upcoming events found.
                            </p>

                        @endforelse

                    </div>


                    <!-- Ongoing Events Section -->

                    <h3 class="text-4xl font-bold mb-12 text-center mt-24 text-black">
                        {{ __('What\'s Happening This Month') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                        @foreach ($ongoingEvents as $event)

                            <!-- Event Card -->
                            <div class="bg-white shadow-lg p-4 border border-gray-200 rounded-lg flex flex-col justify-between relative aspect-square">

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

                                <a
                                    href="{{ route('events.show', $event->id) }}"
                                    class="block text-xl font-semibold text-gray-900 mb-1"
                                >
                                    {{ $event->name }}
                                </a>

                                <span class="block text-gray-500 text-sm">
                                    {{ \Carbon\Carbon::parse($event->date_time)->format('F j, Y, g:i a') }}
                                </span>

                                <!-- Event description -->
                                <p class="mt-2 text-gray-600 text-sm">
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
