@extends('layouts.app')
@section('title', 'Find your next favourite thing')
@section('content')
<section class="home-hero">
    <div class="hero-copy">
        <div class="eyebrow"><span class="eyebrow-line"></span> THE GOOD STUFF IS OUT THERE</div>
        <h1>Your next favourite thing is <em>closer than you think.</em></h1>
        <p class="hero-intro">The gigs, the pop-ups, the new places and the plans you didn't know you needed. Find your next reason to get out there.</p>
        <form class="hero-search" action="{{ route('events.index') }}" method="GET">
            <span class="search-icon" aria-hidden="true">⌕</span>
            <input type="search" name="q" placeholder="Try ‘live music’ or ‘Cape Town’" aria-label="Search events">
            <button type="submit">Find events <span aria-hidden="true">↗</span></button>
        </form>
        <div class="hero-proof"><span class="proof-avatars"><i>J</i><i>M</i><i>K</i></span><span>Good plans are better <strong>together.</strong></span></div>
    </div>
    <div class="hero-visual" aria-label="A collection of experiences and events">
        <div class="hero-image hero-image-main"><img src="https://images.unsplash.com/photo-1506157786151-b8491531f063?auto=format&fit=crop&w=1200&q=88" alt="Crowd enjoying a live music performance"></div>
        <div class="hero-image hero-image-side"><img src="https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=700&q=85" alt="Friends sharing a meal"></div>
        <div class="hero-image hero-image-bottom"><img src="https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?auto=format&fit=crop&w=700&q=85" alt="People enjoying an outdoor event"></div>
        <div class="hero-note"><span class="note-star">✳</span><span>OUT THERE<br><strong>IS WHERE<br>IT HAPPENS.</strong></span></div>
        <div class="hero-location"><span class="location-pulse"></span> MADE FOR YOUR NEXT YES</div>
    </div>
</section>

<section class="quick-discover" aria-label="Browse event categories">
    <div class="quick-label"><span>START WITH A VIBE</span><small>Pick your kind of good</small></div>
    <a class="category-chip chip-music" href="{{ route('events.index', ['category' => 'Music']) }}"><span class="chip-icon">♫</span> Music <span class="chip-arrow">↗</span></a>
    <a class="category-chip chip-food" href="{{ route('events.index', ['category' => 'Food & Drink']) }}"><span class="chip-icon">✳</span> Food & drink <span class="chip-arrow">↗</span></a>
    <a class="category-chip chip-arts" href="{{ route('events.index', ['category' => 'Arts']) }}"><span class="chip-icon">◒</span> Arts & culture <span class="chip-arrow">↗</span></a>
    <a class="category-chip chip-tech" href="{{ route('events.index', ['category' => 'Technology']) }}"><span class="chip-icon">⌘</span> Tech & ideas <span class="chip-arrow">↗</span></a>
    <a class="category-chip chip-more" href="{{ route('events.index') }}">A bit of everything <span class="chip-arrow">↗</span></a>
</section>

<section class="content-section featured-section">
    <div class="section-heading">
        <div><div class="eyebrow"><span class="eyebrow-line"></span> HAND-PICKED FOR YOUR CALENDAR</div><h2>Worth making<br><em>time for.</em></h2></div>
        <a class="text-link" href="{{ route('events.index') }}">Explore all events <span>↗</span></a>
    </div>
    @if($featuredEvents->isNotEmpty())
        <div class="event-grid featured-grid">
            @foreach($featuredEvents as $event)<x-event-card :event="$event" />@endforeach
        </div>
    @else
        <div class="empty-inline"><p>We're finding the good stuff. Check out everything happening instead.</p><a class="text-link" href="{{ route('events.index') }}">Browse events ↗</a></div>
    @endif
</section>

<section class="manifesto-band">
    <div class="manifesto-symbol">✳</div>
    <div><span class="manifesto-kicker">A LITTLE REMINDER</span><h2>Life's better when<br>there's <em>something on.</em></h2></div>
    <p>Say yes to the random Tuesday gig. The Sunday market. The workshop that changes your perspective. Your next story starts somewhere.</p>
    <a href="{{ route('events.index') }}">Find your somewhere <span>↗</span></a>
</section>

<section class="content-section upcoming-section">
    <div class="section-heading compact-heading">
        <div><div class="eyebrow"><span class="eyebrow-line"></span> YOUR CALENDAR CALLED</div><h2>Coming up <em>next.</em></h2></div>
        <a class="text-link" href="{{ route('events.index') }}">See the full line-up <span>↗</span></a>
    </div>
    @if($upcomingEvents->isNotEmpty())
        <div class="event-grid upcoming-grid">
            @foreach($upcomingEvents->take(4) as $event)<x-event-card :event="$event" />@endforeach
        </div>
    @else
        <div class="empty-inline"><p>No events are listed just yet. Come back soon.</p></div>
    @endif
</section>

<section class="organiser-cta">
    <div class="organiser-cta-copy"><span class="organiser-kicker">FOR THE PEOPLE WHO MAKE IT HAPPEN</span><h2>Got a good thing<br>in the <em>works?</em></h2><p>Bring your people together. Share what you're creating and let the right crowd find you.</p><a href="{{ route('organisers') }}" class="button button-dark">Let's make it happen <span>↗</span></a></div>
    <div class="organiser-cta-art"><div class="cta-photo"><img src="https://images.unsplash.com/photo-1527529482837-4698179dc6ce?auto=format&fit=crop&w=1000&q=85" alt="Friends celebrating together"></div><div class="cta-stamp">GOOD<br>THINGS<br><b>GROW</b><span>TOGETHER</span></div><span class="cta-scribble">✳</span></div>
</section>
@endsection
