<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', '') }}</title>
        <link rel="icon" href="{{ asset('images/title.jpg') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="//unpkg.com/alpinejs" defer></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

        
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                @yield('content')
            </main>

            @include('layouts.footer')
        </div>
        <!-- Scroll to Top Button -->
        <button id="scrollToTopButton" class="fixed bottom-5 right-5 bg-navy text-white px-4 py-2 rounded-full shadow-md hidden">
            ↑ Top
        </button>
    </body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scrollToTopButton = document.getElementById('scrollToTopButton');

        // Show the button when the user scrolls down
        window.addEventListener('scroll', () => {
            if (window.scrollY > 200) { // Show button after 200px scroll
                scrollToTopButton.classList.remove('hidden');
            } else {
                scrollToTopButton.classList.add('hidden');
            }
        });

        // Smooth scroll to top when the button is clicked
        scrollToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>

