<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Find your next favourite experience with EasyBooking. Discover events, meet your people and make plans worth remembering.">
    <title>@yield('title', 'Find your people') · EasyBooking</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="announcement"><span class="announcement-dot"></span> Good plans start with a little discovery <a href="{{ route('events.index') }}">Find something happening <span aria-hidden="true">↗</span></a></div>
    <header class="site-header">
        <div class="nav-wrap">
            <a class="brand" href="{{ route('home') }}" aria-label="EasyBooking home"><span class="brand-mark">e</span><span>easybooking<span class="brand-period">.</span></span></a>
            <button class="menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" data-menu-toggle><span></span><span></span></button>
            <nav class="main-nav" data-main-nav>
                <a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'active' : '' }}">Explore events</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">How it works</a>
            </nav>
            <a class="nav-organiser" href="{{ route('organisers') }}">Host an event <span aria-hidden="true">↗</span></a>
        </div>
    </header>

    <main>@yield('content')</main>

    <footer class="site-footer">
        <div class="footer-main">
            <div class="footer-brand-block">
                <a class="brand brand-light" href="{{ route('home') }}"><span class="brand-mark">e</span><span>easybooking<span class="brand-period">.</span></span></a>
                <p>Less scrolling. More living.<br>Find the good stuff happening around you.</p>
            </div>
            <div class="footer-links"><span class="footer-label">DISCOVER</span><a href="{{ route('events.index') }}">All events</a><a href="{{ route('events.index', ['category' => 'Music']) }}">Music & nightlife</a><a href="{{ route('events.index', ['category' => 'Food & Drink']) }}">Food & drink</a></div>
            <div class="footer-links"><span class="footer-label">EASYBOOKING</span><a href="{{ route('about') }}">How it works</a><a href="{{ route('organisers') }}">For organisers</a></div>
            <div class="footer-note"><span class="footer-spark">✳</span><p>Make a plan.<br><strong>Make a memory.</strong></p></div>
        </div>
        <div class="footer-bottom"><span>© {{ date('Y') }} EasyBooking. Made for making memories.</span><span>Good things happen when we get together.</span></div>
    </footer>
</body>
</html>
