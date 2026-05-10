@extends('layouts.app')
@section('content')

{{-- Hero Section (changes based on genre filter) --}}
@if($heroFilm)
@php
    $heroCover = $heroFilm->cover
        ? (str_starts_with($heroFilm->cover, 'http') ? $heroFilm->cover : asset('storage/' . $heroFilm->cover))
        : ($heroFilm->thumbnail
            ? (str_starts_with($heroFilm->thumbnail, 'http') ? $heroFilm->thumbnail : asset('storage/' . $heroFilm->thumbnail))
            : 'https://picsum.photos/seed/' . $heroFilm->id . '/1920/1080');
@endphp
<section class="sv-hero" style="height:50vh;min-height:380px;">
    <div class="sv-hero-bg">
        <img src="{{ $heroCover }}" alt="{{ $heroFilm->title }}">
    </div>
    <div class="sv-hero-content">
        <h1 class="sv-hero-title" style="font-size:2.5rem;">{{ $heroFilm->title }}</h1>
        <div class="sv-hero-meta">
            <span class="sv-badge">{{ $heroFilm->release_year }}</span>
            <span>{{ $heroFilm->duration_formatted }}</span>
            <span>{{ $heroFilm->genre_list }}</span>
        </div>
        <p class="sv-hero-description" style="-webkit-line-clamp:2;">{{ $heroFilm->description }}</p>
        <div class="sv-hero-actions">
            @auth
                <a href="{{ route('films.show', $heroFilm) }}" class="sv-btn sv-btn-primary sv-btn-lg">Detail Film</a>
            @else
                <a href="{{ route('login') }}" class="sv-btn sv-btn-primary sv-btn-lg">Login untuk Detail</a>
            @endauth
        </div>
    </div>
</section>
@endif

<div style="padding:40px 48px;">
    <h1 class="sv-section-title" style="font-size:2rem;">
        <span class="sv-accent-bar"></span>
        {{ request('genre') ? request('genre') . ' Films' : 'Semua Film' }}
    </h1>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('films.index') }}" style="display:flex;gap:12px;margin-bottom:32px;flex-wrap:wrap;justify-content: flex-start;">
        <input type="text" name="search" class="sv-input" placeholder="🔍 Cari film..." value="{{ request('search') }}" style="max-width:300px;">
        <select name="genre" class="sv-input" style="max-width:180px;">
            <option value="">Semua Genre</option>
            @foreach($allGenres as $genre)
                <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
            @endforeach
        </select>
        <button type="submit" class="sv-btn sv-btn-primary">Filter</button>
        @if(request()->hasAny(['search', 'genre']))
            <a href="{{ route('films.index') }}" class="sv-btn sv-btn-ghost">Reset</a>
        @endif
    </form>

    {{-- Film Grid: 6 columns --}}
    @if($films->isEmpty())
        <div style="text-align:center;padding:80px 0;">
            <p style="font-size:1.2rem;color:var(--sv-text-muted);">Tidak ada film ditemukan.</p>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:16px;">
            @foreach($films as $film)
                @include('partials.film-card', ['film' => $film])
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($films->hasPages())
            <div class="sv-pagination">
                {!! $films->withQueryString()->links('partials.pagination') !!}
            </div>
        @endif
    @endif
</div>
@endsection
