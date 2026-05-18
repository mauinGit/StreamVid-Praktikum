@extends('layouts.app')
@section('content')

    {{-- Hero Carousel --}}
    @if($heroFilms->isNotEmpty())
        <section class="sv-hero" style="position:relative;overflow:hidden;">

            {{-- Slides --}}
            @foreach($heroFilms as $i => $film)
                @php
                    $cover = $film->cover
                        ? (str_starts_with($film->cover, 'http') ? $film->cover : asset('storage/' . $film->cover))
                        : ($film->thumbnail
                            ? (str_starts_with($film->thumbnail, 'http') ? $film->thumbnail : asset('storage/' . $film->thumbnail))
                            : 'https://picsum.photos/seed/' . $film->id . '/1920/1080');
                @endphp
                <div class="sv-hero-slide" data-index="{{ $i }}" style="position:absolute;inset:0;
                           display:flex;align-items:center;padding:0 48px;
                           opacity:{{ $i === 0 ? 1 : 0 }};
                           transition:opacity 0.8s ease;
                           pointer-events:{{ $i === 0 ? 'auto' : 'none' }};">
                    <div class="sv-hero-bg">
                        <img src="{{ $cover }}" alt="{{ $film->title }}" style="width:100%;height:100%;object-fit:cover;">
                    </div>
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
                                    <a href="{{ route('films.watch', $film) }}" class="sv-btn sv-btn-primary sv-btn-lg">▶ Tonton
                                        Sekarang</a>
                                @else
                                    <a href="{{ route('subscription.index') }}" class="sv-btn sv-btn-primary sv-btn-lg">▶ Subscribe untuk
                                        Menonton</a>
                                @endif
                                <a href="{{ route('films.show', $film) }}" class="sv-btn sv-btn-outline sv-btn-lg">Detail Film</a>
                            @else
                                <a href="{{ route('login') }}" class="sv-btn sv-btn-primary sv-btn-lg">▶ Tonton Sekarang</a>
                                <a href="{{ route('login') }}" class="sv-btn sv-btn-outline sv-btn-lg">Detail Film</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Dot Indicators --}}
            <div style="position:absolute;bottom:24px;left:50%;transform:translateX(-50%);display:flex;gap:10px;z-index:10;">
                @foreach($heroFilms as $i => $film)
                    <button class="sv-hero-dot" data-target="{{ $i }}" onclick="goToSlide({{ $i }})" style="width:{{ $i === 0 ? '28px' : '10px' }};height:10px;border-radius:5px;border:none;
                               cursor:pointer;padding:0;transition:all 0.3s;
                               background:{{ $i === 0 ? 'white' : 'rgba(255,255,255,0.4)' }};">
                    </button>
                @endforeach
            </div>

            {{-- Arrow Prev --}}
            <button onclick="prevSlide()" style="position:absolute;left:20px;top:50%;transform:translateY(-50%);z-index:10;
                       background:rgba(0,0,0,0.4);border:none;border-radius:50%;width:44px;height:44px;
                       cursor:pointer;display:flex;align-items:center;justify-content:center;
                       backdrop-filter:blur(4px);transition:background 0.2s;"
                onmouseover="this.style.background='rgba(0,0,0,0.7)'" onmouseout="this.style.background='rgba(0,0,0,0.4)'">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                    <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6z" />
                </svg>
            </button>

            {{-- Arrow Next --}}
            <button onclick="nextSlide()" style="position:absolute;right:20px;top:50%;transform:translateY(-50%);z-index:10;
                       background:rgba(0,0,0,0.4);border:none;border-radius:50%;width:44px;height:44px;
                       cursor:pointer;display:flex;align-items:center;justify-content:center;
                       backdrop-filter:blur(4px);transition:background 0.2s;"
                onmouseover="this.style.background='rgba(0,0,0,0.7)'" onmouseout="this.style.background='rgba(0,0,0,0.4)'">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
                    <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z" />
                </svg>
            </button>

        </section>
    @endif


{{-- Movie Terbaru --}}
@if($newestFilms->isNotEmpty())
<section class="sv-section" style="margin-top: 40px;">
    <h2 class="sv-section-title">
        <span class="sv-accent-bar"></span> MOVIE TERBARU
    </h2>
    <div class="sv-scroll-row">
        @foreach($newestFilms as $film)
            @include('partials.film-card', ['film' => $film])
        @endforeach
    </div>
</section>
@endif

{{-- Trending Saat Ini --}}
@if($trendingFilms->isNotEmpty())
<section class="sv-section">
    <h2 class="sv-section-title">
        <span class="sv-accent-bar"></span> 🔥 TRENDING SAAT INI
    </h2>
    <div class="sv-scroll-row">
        @foreach($trendingFilms as $film)
            @include('partials.film-card', ['film' => $film])
        @endforeach
    </div>
</section>
@endif

{{-- Semua Film --}}
<section class="sv-section">
    <h2 class="sv-section-title">
        <span class="sv-accent-bar"></span> SEMUA FILM
    </h2>
    @if($allFilms->isNotEmpty())
        <div class="sv-scroll-row">
            @foreach($allFilms as $film)
                @include('partials.film-card', ['film' => $film])
            @endforeach
        </div>
    @else
        <p style="color:var(--sv-text-muted);text-align:center;padding:40px;">Belum ada film.</p>
    @endif
</section>

<script>
    let currentSlide = 0;
    const totalSlides = {{ $heroFilms->count() }};
    let autoplayTimer = null;

    function goToSlide(index) {
        document.querySelectorAll('.sv-hero-slide').forEach((slide, i) => {
            slide.style.opacity = i === index ? '1' : '0';
            slide.style.pointerEvents = i === index ? 'auto' : 'none';
        });
        document.querySelectorAll('.sv-hero-dot').forEach((dot, i) => {
            dot.style.width = i === index ? '28px' : '10px';
            dot.style.background = i === index ? 'white' : 'rgba(255,255,255,0.4)';
        });
        currentSlide = index;
        resetAutoplay();
    }

    function nextSlide() {
        goToSlide((currentSlide + 1) % totalSlides);
    }

    function prevSlide() {
        goToSlide((currentSlide - 1 + totalSlides) % totalSlides);
    }

    function resetAutoplay() {
        clearInterval(autoplayTimer);
        autoplayTimer = setInterval(nextSlide, 6000);
    }

    resetAutoplay();
</script>

@endsection