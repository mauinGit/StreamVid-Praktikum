@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:40px 48px;">
    <h1 class="sv-section-title" style="font-size:2rem;margin-bottom:40px;">
        <span class="sv-accent-bar"></span> Koleksi Saya
    </h1>
</div>

{{-- Lanjutkan Tontonan --}}
<section class="sv-section">
    <h2 class="sv-section-title">
        <span class="sv-accent-bar"></span> Lanjutkan Tontonan
    </h2>
    @if($histories->isEmpty())
        <p style="color:var(--sv-text-muted);font-size:0.9rem;padding-left:10px;">Belum ada riwayat tontonan.</p>
    @else
        <div class="sv-scroll-row">
            @foreach($histories as $item)
                @include('partials.film-card', ['film' => $item->film])
            @endforeach
        </div>
    @endif
</section>

{{-- Film Favorit --}}
<section class="sv-section">
    <h2 class="sv-section-title">
        <span class="sv-accent-bar"></span> Film Favorit
    </h2>
    @if($myList->isEmpty())
        <div style="text-align:center;padding:40px 0;">
            <p style="color:var(--sv-text-muted);font-size:0.9rem;margin-bottom:16px;">Belum ada film favorit.</p>
            <a href="{{ route('films.index') }}" class="sv-btn sv-btn-primary">Jelajahi Film</a>
        </div>
    @else
        <div class="sv-scroll-row">
            @foreach($myList as $item)
                <div style="position:relative;">
                    @include('partials.film-card', ['film' => $item->film])
                    <form method="POST" action="{{ route('mylist.toggle', $item->film) }}" style="position:absolute;top:8px;right:8px;z-index:20;">
                        @csrf
                        <button type="submit" style="background:rgba(0,0,0,0.7);border:none;color:#ef4444;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);" title="Hapus dari Favorit">✕</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</section>

@endsection
