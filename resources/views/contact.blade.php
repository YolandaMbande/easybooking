@extends('layouts.app') 

@section('content') 
    <div class="relative overflow-hidden min-h-screen flex flex-col"> 
        <!-- Background Image with Blur -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/background.jpg') }}" alt="Background Image" class="object-cover w-full h-full filter blur-md">
        </div>

        <main class="relative z-10 flex-grow">
            <div class="max-w-6xl mx-auto p-6">
                <h1 class="text-4xl font-bold mb-4 text-center text-white">Contact Us</h1>
                <p class="mb-4 text-center text-gray-300">We would love to hear from you! Please fill out the form below:</p>
                
                <form action="{{ route('contact.store') }}" method="POST" class="bg-gray-100 dark:bg-gray-700 shadow-lg rounded-lg p-6">
                    @csrf <!-- CSRF token for security -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" id="name" name="name" required class="mt-1 block w-full p-2 border border-gray-600 rounded-md bg-gray-700 text-white">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" id="email" name="email" required class="mt-1 block w-full p-2 border border-gray-600 rounded-md bg-gray-700 text-white">
                    </div>
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                        <textarea id="message" name="message" rows="4" required class="mt-1 block w-full p-2 border border-gray-600 rounded-md bg-gray-700 text-white"></textarea>
                    </div>
                    <button type="submit" class="inline-block bg-white border border-gray-300 hover:bg-pink-500 hover:text-white text-gray-700 font-semibold py-5 px-20 rounded-full shadow-lg transition duration-300 ease-in-out 
                    focus:ring-4 focus:ring-pink-300 active:bg-pink-600 active:text-white active:border-pink-600">
                        Send Message
                    </button>
                </form>
            </div>
        </main>
    </div>
@endsection

