@props(['event'])
<a href="{{ route('events.show', $event->slug) }}" class="event-card">
    <div class="event-card-media">
        <img src="{{ $event->cover_image ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=900&q=85' }}" alt="{{ $event->title }}" loading="lazy">
        <span class="event-date"><strong>{{ $event->starts_at->format('d') }}</strong><small>{{ $event->starts_at->format('M') }}</small></span>
        @if($event->featured)<span class="featured-tag"><span>✳</span> PICKED FOR YOU</span>@endif
        <span class="card-image-arrow" aria-hidden="true">↗</span>
    </div>
    <div class="event-card-body">
        <div class="event-card-topline"><span class="event-category">{{ $event->category }}</span><span class="event-time">{{ $event->starts_at->format('D · H:i') }}</span></div>
        <h3>{{ $event->title }}</h3>
        <p class="event-card-summary">{{ $event->summary }}</p>
        <div class="event-card-bottom"><span class="event-venue"><span aria-hidden="true">⌖</span> {{ $event->venue }}, {{ $event->city }}</span><span class="event-price">From <strong>R{{ number_format((float) ($event->ticketTypes->min('price') ?? 0), 0) }}</strong></span></div>
    </div>
</a>
