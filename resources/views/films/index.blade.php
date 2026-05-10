@extends('layouts.app')
@section('content')



<div class="sv-mt-nav" style="padding:40px 48px;">
    <h1 class="sv-section-title" style="font-size:2rem;">
        <span class="sv-accent-bar"></span>
        Film
    </h1>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('films.index') }}" style="display:flex;gap:12px;margin-bottom:32px;flex-wrap:wrap;justify-content: flex-start;">
        <input type="text" name="search" class="sv-input" placeholder="🔍 Cari film..." value="{{ request('search') }}" style="max-width:300px;flex:1;">
        <button type="submit" class="sv-btn sv-btn-primary">Cari Film</button>
        @if(request()->has('search') && request('search') != '')
            <a href="{{ route('films.index') }}" class="sv-btn sv-btn-ghost">Reset</a>
        @endif
    </form>

    {{-- Film Grid: 6 columns --}}
    @if($films->isEmpty())
        <div style="text-align:center;padding:80px 0;">
            <p style="font-size:1.2rem;color:var(--sv-text-muted);">Tidak ada film ditemukan.</p>
        </div>
    @else
        <div class="sv-all-films-grid" style="display:grid;grid-template-columns:repeat(6,1fr);gap:16px;">
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
