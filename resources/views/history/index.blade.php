@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:40px 48px;">
    <h1 class="sv-section-title" style="font-size:2rem;">
        <span class="sv-accent-bar"></span> Riwayat Tontonan
    </h1>
    @if($histories->isEmpty())
        <div style="text-align:center;padding:80px 0;">
            <p style="font-size:3rem;margin-bottom:16px;">🕐</p>
            <p style="font-size:1.2rem;color:var(--sv-text-muted);margin-bottom:8px;">Belum ada riwayat tontonan</p>
            <p style="color:var(--sv-text-muted);font-size:0.9rem;margin-bottom:24px;">Mulai menonton film sekarang</p>
            <a href="{{ route('films.index') }}" class="sv-btn sv-btn-primary">Jelajahi Film</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($histories as $history)
            <a href="{{ route('films.show', $history->film) }}" style="text-decoration:none;color:inherit;display:flex;gap:20px;padding:16px;background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:12px;transition:all 0.2s;align-items:center;" onmouseover="this.style.borderColor='var(--sv-accent)';this.style.background='var(--sv-bg-elevated)'" onmouseout="this.style.borderColor='var(--sv-border)';this.style.background='var(--sv-bg-card)'">
                <img src="{{ $history->film->thumbnail ? (str_starts_with($history->film->thumbnail, 'http') ? $history->film->thumbnail : asset('storage/' . $history->film->thumbnail)) : 'https://picsum.photos/seed/'.$history->film->id.'/80/120' }}" alt="{{ $history->film->title }}" style="width:80px;height:120px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                <div style="flex:1;min-width:0;">
                    <h3 style="font-size:1.05rem;font-weight:700;margin-bottom:6px;">{{ $history->film->title }}</h3>
                    <p style="font-size:0.85rem;color:var(--sv-text-muted);margin-bottom:4px;">{{ $history->film->genre_list }} • {{ $history->film->duration_formatted }}</p>
                    <p style="font-size:0.8rem;color:var(--sv-text-muted);">Ditonton {{ $history->watched_at->diffForHumans() }}</p>
                </div>
                <span style="color:var(--sv-accent);font-size:1.5rem;flex-shrink:0;">▶</span>
            </a>
            @endforeach
        </div>
        @if($histories->hasPages())
            <div class="sv-pagination">{!! $histories->links('partials.pagination') !!}</div>
        @endif
    @endif
</div>
@endsection
