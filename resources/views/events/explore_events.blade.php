@extends('layouts.app')

@section('content')
    <div class="bg-blue-900 text-white py-20">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold">Find an event & buy tickets</h1>

            <form method="GET" action="{{ route('events.index') }}" class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                <div>
                    <label for="event_name" class="block text-white">Search Event Name or Venue</label>
                    <input type="text" id="event_name" name="event_name" class="mt-1 block w-full px-3 py-2 rounded-md" placeholder="Enter event name or venue">
                </div>

                <div>
                    <label for="suburb" class="block text-white">Suburb/Postcode</label>
                    <input type="text" id="suburb" name="suburb" class="mt-1 block w-full px-3 py-2 rounded-md" placeholder="Enter suburb or postcode">
                </div>

                <div>
                    <label for="date" class="block text-white">Date</label>
                    <input type="date" id="date" name="date" class="mt-1 block w-full px-3 py-2 rounded-md">
                </div>
            </form>

            <div class="mt-6 flex justify-center gap-4">
                <button class="bg-yellow-500 text-black px-4 py-2 rounded-md">Find Events</button>
                <button class="bg-transparent border-2 border-white text-white px-4 py-2 rounded-md">Clear</button>
            </div>
        </div>
    </div>
@endsection
