@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:40px 48px;">
    <h1 class="sv-section-title" style="font-size:2rem;">
        <span class="sv-accent-bar"></span> My List
    </h1>
    @if($myList->isEmpty())
        <div style="text-align:center;padding:80px 0;">
            <p style="font-size:3rem;margin-bottom:16px;">📌</p>
            <p style="font-size:1.2rem;color:var(--sv-text-muted);margin-bottom:8px;">My List masih kosong</p>
            <p style="color:var(--sv-text-muted);font-size:0.9rem;margin-bottom:24px;">Tambahkan film favorit Anda ke My List</p>
            <a href="{{ route('films.index') }}" class="sv-btn sv-btn-primary">Jelajahi Film</a>
        </div>
    @else
        <div class="sv-film-grid">
            @foreach($myList as $item)
                <div style="position:relative;">
                    @include('partials.film-card', ['film' => $item->film])
                    <form method="POST" action="{{ route('mylist.toggle', $item->film) }}" style="position:absolute;top:8px;right:8px;z-index:20;">
                        @csrf
                        <button type="submit" style="background:rgba(0,0,0,0.7);border:none;color:#ef4444;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(4px);" title="Hapus dari My List">✕</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
