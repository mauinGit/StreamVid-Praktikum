@extends('layouts.app')
@section('content')



<style>
    @media (max-width: 1024px) {
        .film-search-form { width: 100%; margin-bottom: 24px !important; }
        .film-search-input { max-width: 100% !important; width: 100% !important; }
        .film-search-btn { display: none !important; }
        .mobile-scroll-grid {
            display: flex !important;
            gap: 12px !important;
            overflow-x: auto !important;
            scrollbar-width: none !important;
            padding-bottom: 10px !important;
        }
        .mobile-scroll-grid::-webkit-scrollbar { display: none !important; }
        .mobile-scroll-grid > * {
            flex: 0 0 calc((100% - 24px) / 3) !important;
            max-width: calc((100% - 24px) / 3) !important;
        }
    }
</style>

<div class="sv-mt-nav" style="padding:40px 48px;">
    {{-- Search & Filter --}}
    <form class="film-search-form" method="GET" action="{{ route('films.index') }}" style="display:flex;gap:12px;margin-bottom:32px;flex-wrap:wrap;justify-content: flex-start;">
        <input type="text" name="search" class="sv-input film-search-input" placeholder="🔍 Cari film..." value="{{ request('search') }}" style="flex:1;">
        <button type="submit" class="sv-btn sv-btn-primary film-search-btn">Cari Film</button>
        @if(request()->has('search') && request('search') != '')
            <a href="{{ route('films.index') }}" class="sv-btn sv-btn-ghost">Reset</a>
        @endif
    </form>

    @if(request()->has('search') && request('search') != '')
        <h1 class="sv-section-title" style="font-size:2rem;margin-bottom:20px;">
            <span class="sv-accent-bar"></span>
            Hasil Pencarian
        </h1>

        {{-- Film Grid: 6 columns --}}
        @if($films->isEmpty())
            <div style="text-align:center;padding:80px 0;">
                <p style="font-size:1.2rem;color:var(--sv-text-muted);">Tidak ada film ditemukan.</p>
            </div>
        @else
            <div class="sv-all-films-grid mobile-scroll-grid" style="display:grid;grid-template-columns:repeat(6,1fr);gap:16px;">
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
    @endif
</div>

{{-- Random Genre Sections --}}
@foreach($genreSections as $section)
    @if($section['films']->isNotEmpty())
    <section class="sv-section">
        <h2 class="sv-section-title">
            <span class="sv-accent-bar"></span> {{ $section['genre'] }}
        </h2>
        <div class="sv-scroll-row">
            @foreach($section['films'] as $film)
                @include('partials.film-card', ['film' => $film])
            @endforeach
        </div>
    </section>
    @endif
@endforeach

@endsection
