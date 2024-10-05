@extends('layouts.app')

@section('content')

<div class="bg-gray-800 min-h-screen flex flex-col items-center">
    <!-- Bubble-styled Title -->
    <h1 class="text-3xl font-bold mb-8 bg-gray-700 text-white px-6 py-3 shadow-md">
        Learn About Easy Booking!
    </h1>

    <div class="max-w-6xl mx-auto p-8 flex">
        <!-- Left section for the summary -->
        <div class="w-1/2 pr-8">
            <h2 class="text-4xl font-bold mb-6 bubble-text">About Us</h2>
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
            <p class="text-lg leading-relaxed text-gray-300">
                Easy Booking is your go-to platform for event ticketing, offering a seamless and efficient way for users to book events online. Whether you're hosting a small local gathering or a large-scale festival, we ensure that ticket sales are straightforward, fast, and secure. Our system caters to the needs of both event organizers and attendees, allowing them to engage effortlessly. Join us in experiencing the simplicity and convenience of modern event booking.
            </p>
        </div>

        <!-- Right section for the image -->
        <div class="w-1/2 flex justify-end">
            <img src="{{ asset('images/image.jpg') }}" alt="About Us Image" class="rounded-lg shadow-lg w-full h-auto">
        </div>
    </div>

    <!-- Why You Should Book With Us Section -->
    <div class="w-full bg-gray-700 py-12 mt-10">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-6 text-white">Why You Should Book With Us</h2>
            <div class="text-center text-lg text-gray-300 mb-10">
                <p>We offer a variety of reasons to book your events through Easy Booking, from exclusive deals to seamless user experience. Here's why you should choose us:</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8"> 
                <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                    <img src="{{asset ('images/secure_payment.jpeg')}}" alt="Secure Payments" class="w-full h-32 object-cover mb-4 rounded">
                    <h3 class="text-2xl font-bold text-white mb-4">Secure Payments</h3>
                    <p class="text-gray-300">We ensure that every transaction is encrypted and secure.</p>
                </div>
                <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                    <img src="{{ asset('images/easy_booking_process.png') }}" alt="Easy Booking Process" class="w-full h-32 object-cover mb-4 rounded">
                    <h3 class="text-2xl font-bold text-white mb-4">Easy Booking Process</h3>
                    <p class="text-gray-300">Our platform is designed to offer the fastest booking process available.</p>
                </div>
                <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                    <img src="{{asset('images/diverse_events.jpeg')}}" alt="Diverse Events" class="w-full h-32 object-cover mb-4 rounded">
                    <h3 class="text-2xl font-bold text-white mb-4">Diverse Events</h3>
                    <p class="text-gray-300">With a range of events from different industries, there's something for everyone.</p>
                </div>

                <div class="bg-gray-800 rounded-lg shadow-lg p-6">
                    <img src="{{asset('/images/create_your_own.jpeg')}}" alt="Create Your Own Event" class="w-full h-32 object-cover mb-4 rounded">
                    <h3 class="text-2xl font-bold text-white mb-4">Create Your Own Event!</h3>
                    <p class="text-gray-300">Register for FREE to create your own events and we will send you updates on people wanting to attend.</p>
                </div>  
            </div>
        </div>
    </div>

    <!-- Industries Available on the Platform Section -->
    <div class="w-full py-12 bg-gray-900">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-6 text-white">Industries Available on the Platform</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <div class="bg-gray-800 text-center text-white rounded-lg p-6 shadow-md">
                    <img src="{{ asset('images/industries/music_event.jpg') }}" alt="Music" class="w-full h-48 object-cover mb-4 rounded">
                    Music
                </div>
                <div class="bg-gray-800 text-center text-white rounded-lg p-6 shadow-md">
                    <img src="{{ asset('images/industries/sports_events.jpg') }}" alt="Sports" class="w-full h-48 object-cover mb-4 rounded">
                    Sports
                </div>
                <div class="bg-gray-800 text-center text-white rounded-lg p-6 shadow-md">
                    <img src="{{ asset('images/industries/tech_event.jpeg') }}" alt="Technology" class="w-full h-48 object-cover mb-4 rounded">
                    Technology
                </div>
                <div class="bg-gray-800 text-center text-white rounded-lg p-6 shadow-md">
                    <img src="{{ asset('images/industries/fashion_event.jpeg') }}" alt="Fashion" class="w-full h-48 object-cover mb-4 rounded">
                    Fashion
                </div>
                <div class="bg-gray-800 text-center text-white rounded-lg p-6 shadow-md">
                    <img src="{{ asset('images/industries/education_event.jpg') }}" alt="Education" class="w-full h-48 object-cover mb-4 rounded">
                    Education
                </div>
                <div class="bg-gray-800 text-center text-white rounded-lg p-6 shadow-md">
                    <img src="{{ asset('images/industries/arts_and_culture.jpeg') }}" alt="Arts & Culture" class="w-full h-48 object-cover mb-4 rounded">
                    Arts & Culture
                </div>
                <div class="bg-gray-800 text-center text-white rounded-lg p-6 shadow-md">
                    <img src="{{ asset('images/industries/travel_event.jpeg') }}" alt="Travel" class="w-full h-48 object-cover mb-4 rounded">
                    Travel
                </div>
                <div class="bg-gray-800 text-center text-white rounded-lg p-6 shadow-md">
                    <img src="{{ asset('images/industries/food_and_bev.jpeg') }}" alt="Food & Beverages" class="w-full h-48 object-cover mb-4 rounded">
                    Food & Beverages
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
