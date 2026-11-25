@extends('layouts.app')
@section('title', $event->title)
@section('content')
<section class="event-detail-page">
    <a class="back-link" href="{{ route('events.index') }}"><span>←</span> Back to all events</a>
    <div class="detail-cover-wrap"><img class="detail-cover" src="{{ $event->cover_image ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1600&q=90' }}" alt="{{ $event->title }}"><span class="detail-cover-label">{{ $event->category }} <span>·</span> THE GOOD STUFF</span></div>
    <div class="detail-layout">
        <article class="event-detail-content">
            <div class="eyebrow"><span class="eyebrow-line"></span> {{ $event->starts_at->format('l, j F Y') }}</div>
            <h1>{{ $event->title }}<span class="title-period">.</span></h1>
            <p class="detail-lead">{{ $event->summary }}</p>
            <div class="detail-facts"><div class="detail-fact"><span class="fact-icon">◷</span><div><small>WHEN</small><strong>{{ $event->starts_at->format('D, j M Y') }}</strong><span>{{ $event->starts_at->format('H:i') }}</span></div></div><div class="detail-fact"><span class="fact-icon">⌖</span><div><small>WHERE</small><strong>{{ $event->venue }}</strong><span>{{ $event->city }}</span></div></div></div>
            <div class="detail-description"><h2>Here's the <em>good bit.</em></h2><p>{{ $event->description }}</p><p>Bring your curiosity, bring a friend, or come as you are. We think you'll be glad you did.</p></div>
        </article>
        <aside class="ticket-panel"><div class="ticket-panel-top"><span class="ticket-kicker">YOUR PLANS START HERE</span><span class="ticket-spark">✳</span></div><h2>Save your<br><em>spot.</em></h2><p class="ticket-panel-intro">Choose your ticket and get ready for a good time.</p>
            @forelse($event->ticketTypes as $ticket)
                <div class="ticket-option"><div><strong>{{ $ticket->name }}</strong><small>{{ $ticket->description ?: 'Your entry to the event' }}</small></div><b>R{{ number_format((float) $ticket->price, 2) }}</b></div>
            @empty
                <div class="ticket-option"><div><strong>Tickets</strong><small>Ticket details coming soon</small></div><b>—</b></div>
            @endforelse
            <button class="button button-disabled" type="button" disabled>Booking coming soon <span>↗</span></button><small class="ticket-note">Online booking and secure checkout are not enabled yet.</small>
        </aside>
    </div>
</section>
@endsection
