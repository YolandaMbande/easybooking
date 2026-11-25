@extends('layouts.app')
@section('title', 'Explore events')
@section('content')
<section class="explore-hero">
    <div class="explore-hero-inner"><div class="eyebrow eyebrow-light"><span class="eyebrow-line"></span> THE WORLD IS YOUR WHAT-TO-DO LIST</div><h1>Find your <em>next.</em></h1><p>From big nights out to small moments worth showing up for. It's all happening here.</p>
        <form class="explore-search" method="GET" action="{{ route('events.index') }}"><span class="search-icon">⌕</span><input type="search" name="q" value="{{ request('q') }}" placeholder="Search events, places or categories" aria-label="Search events"><button type="submit">Search <span>↗</span></button></form>
    </div><div class="explore-orbit">✳<span>MAKE<br>A PLAN</span></div>
</section>
<section class="content-section listing-section">
    <div class="listing-toolbar"><div><span class="listing-count">{{ $events->total() }}</span> <span>good things to get into</span></div><span class="listing-city">CURRENTLY EXPLORING <strong>CAPE TOWN & BEYOND</strong></span></div>
    <div class="filter-row"><a class="filter-chip {{ !request('category') ? 'selected' : '' }}" href="{{ route('events.index', request()->only('q')) }}">Everything</a>@foreach($categories as $category)<a class="filter-chip {{ request('category') === $category ? 'selected' : '' }}" href="{{ route('events.index', array_merge(request()->only('q'), ['category' => $category])) }}">{{ $category }}</a>@endforeach</div>
    @if($events->isNotEmpty())
        <div class="event-grid listing-grid">@foreach($events as $event)<x-event-card :event="$event" />@endforeach</div>
        <div class="pagination-wrap">{{ $events->links() }}</div>
    @else
        <div class="no-results"><span class="no-results-icon">⌕</span><h2>Nothing on that page. <em>Yet.</em></h2><p>Try another search or clear your filters. Your next good plan is still out there.</p><a class="button button-dark" href="{{ route('events.index') }}">Show me everything <span>↗</span></a></div>
    @endif
</section>
@endsection
