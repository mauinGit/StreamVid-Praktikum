@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding-top:40px;">
    <section class="sv-section">
        <h1 class="sv-section-title" style="font-size:2rem;">
            <span class="sv-accent-bar"></span> Semua Film
        </h1>

        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('films.index') }}" style="display:flex;gap:12px;margin-bottom:32px;flex-wrap:wrap;">
            <input type="text" name="search" class="sv-input" placeholder="🔍 Cari film..." value="{{ request('search') }}" style="max-width:300px;">
            <select name="genre" class="sv-input" style="max-width:180px;">
                <option value="">Semua Genre</option>
                @foreach($allGenres as $genre)
                    <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                @endforeach
            </select>
            <select name="year" class="sv-input" style="max-width:140px;">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            <button type="submit" class="sv-btn sv-btn-primary">Filter</button>
            @if(request()->hasAny(['search', 'genre', 'year']))
                <a href="{{ route('films.index') }}" class="sv-btn sv-btn-ghost">Reset</a>
            @endif
        </form>

        {{-- Film Grid --}}
        @if($films->isEmpty())
            <div style="text-align:center;padding:80px 0;">
                <p style="font-size:1.2rem;color:var(--sv-text-muted);">Tidak ada film ditemukan.</p>
            </div>
        @else
            <div class="sv-film-grid">
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
    </section>
</div>
@endsection
