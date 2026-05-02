@extends('layouts.app')
@section('content')

{{-- Hero Section --}}
@if($heroFilm)
<section class="sv-hero">
    <div class="sv-hero-bg">
        <img src="{{ $heroFilm->thumbnail }}" alt="{{ $heroFilm->title }}">
    </div>
    <div class="sv-hero-content">
        <h1 class="sv-hero-title">{{ $heroFilm->title }}</h1>
        <div class="sv-hero-meta">
            <span class="sv-badge">{{ $heroFilm->release_year }}</span>
            <span>{{ $heroFilm->duration_formatted }}</span>
            <span>{{ $heroFilm->genre_list }}</span>
        </div>
        <p class="sv-hero-description">{{ $heroFilm->description }}</p>
        <div class="sv-hero-actions">
            @auth
                @if(auth()->user()->hasActiveSubscription())
                    <a href="{{ route('films.watch', $heroFilm) }}" class="sv-btn sv-btn-primary sv-btn-lg">
                        ▶ Tonton Sekarang
                    </a>
                @else
                    <a href="{{ route('subscription.index') }}" class="sv-btn sv-btn-primary sv-btn-lg">
                        ▶ Subscribe untuk Menonton
                    </a>
                @endif
                <a href="{{ route('films.show', $heroFilm) }}" class="sv-btn sv-btn-outline sv-btn-lg">
                    ℹ Detail Film
                </a>
            @else
                <a href="{{ route('login') }}" class="sv-btn sv-btn-primary sv-btn-lg">
                    ▶ Tonton Sekarang
                </a>
                <a href="{{ route('login') }}" class="sv-btn sv-btn-outline sv-btn-lg">
                    ℹ Detail Film
                </a>
            @endauth
        </div>
    </div>
</section>
@endif

{{-- Featured Films --}}
@if($featuredFilms->isNotEmpty())
<section class="sv-section" style="margin-top: 40px;">
    <h2 class="sv-section-title">
        <span class="sv-accent-bar"></span> Featured Films
    </h2>
    <div class="sv-scroll-row">
        @foreach($featuredFilms as $film)
            @include('partials.film-card', ['film' => $film])
        @endforeach
    </div>
</section>
@endif

{{-- Trending Films --}}
@if($trendingFilms->isNotEmpty())
<section class="sv-section">
    <h2 class="sv-section-title">
        <span class="sv-accent-bar"></span> 🔥 Trending Now
    </h2>
    <div class="sv-scroll-row">
        @foreach($trendingFilms as $film)
            @include('partials.film-card', ['film' => $film])
        @endforeach
    </div>
</section>
@endif

{{-- Films by Genre --}}
@foreach($filmsByGenre as $genre => $films)
    @if($films->isNotEmpty())
    <section class="sv-section">
        <h2 class="sv-section-title">
            <span class="sv-accent-bar"></span> {{ $genre }}
        </h2>
        <div class="sv-scroll-row">
            @foreach($films as $film)
                @include('partials.film-card', ['film' => $film])
            @endforeach
        </div>
    </section>
    @endif
@endforeach

@endsection
