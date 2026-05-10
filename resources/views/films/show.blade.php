@extends('layouts.app')
@section('content')

{{-- Film Detail Hero --}}
@php
    $detailCover = $film->cover
        ? (str_starts_with($film->cover, 'http') ? $film->cover : asset('storage/' . $film->cover))
        : ($film->thumbnail
            ? (str_starts_with($film->thumbnail, 'http') ? $film->thumbnail : asset('storage/' . $film->thumbnail))
            : 'https://picsum.photos/seed/' . $film->id . '/1920/1080');
@endphp
<style>
    .hero-detail { height: 70vh; min-height: 500px; }
    @media (max-width: 1024px) {
        .hero-detail { height: 40vh !important; min-height: 250px !important; margin-top: 68px !important; }
    }
</style>
<section class="sv-hero hero-detail">
    <div class="sv-hero-bg">
        <img src="{{ $detailCover }}" alt="{{ $film->title }}">    </div>
    <div class="sv-hero-content">
        <h1 class="sv-hero-title">{{ $film->title }}</h1>
        <div class="sv-hero-meta">
            <span class="sv-badge">{{ $film->release_year }}</span>
            <span>{{ $film->duration_formatted }}</span>
            <span>{{ $film->genre_list }}</span>
        </div>
        <p class="sv-hero-description">{{ $film->description }}</p>
        <div class="sv-hero-actions">
            @auth
                @if(auth()->user()->hasActiveSubscription())
                    <a href="{{ route('films.watch', $film) }}" class="sv-btn sv-btn-primary sv-btn-lg">▶ Tonton Sekarang</a>
                @else
                    <a href="{{ route('subscription.index') }}" class="sv-btn sv-btn-primary sv-btn-lg" title="Berlangganan untuk menonton">🔒 Subscribe untuk Menonton</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="sv-btn sv-btn-primary sv-btn-lg">▶ Login untuk Menonton</a>
            @endauth

            {{-- My List Toggle --}}
            @auth
                <button class="sv-btn sv-btn-outline sv-btn-lg" id="mylist-btn" onclick="toggleMyList({{ $film->id }})">
                    {{ $inMyList ? '✓ Di My List' : '+ My List' }}
                </button>
            @endauth
        </div>
    </div>
</section>

{{-- Recommendations --}}
@if($recommendations->isNotEmpty())
<section class="sv-section" style="margin-top:40px;">
    <h2 class="sv-section-title">
        <span class="sv-accent-bar"></span> Film Serupa
    </h2>
    <div class="sv-scroll-row">
        @foreach($recommendations as $rec)
            @include('partials.film-card', ['film' => $rec])
        @endforeach
    </div>
</section>
@endif

@push('scripts')
<script>
function toggleMyList(filmId) {
    const btn = document.getElementById('mylist-btn');
    fetch(`/my-list/toggle/${filmId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        btn.innerHTML = data.added ? '✓ Di My List' : '+ My List';
    });
}
</script>
@endpush

@endsection
