@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:20px 48px;">
    {{-- Video Player --}}
    <div style="position:relative;width:100%;max-width:1200px;margin:0 auto;aspect-ratio:16/9;background:#000;border-radius:12px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
        @if(str_contains($film->video_url, 'youtube.com/embed') || str_contains($film->video_url, 'youtu.be'))
            {{-- YouTube Embed (may be blocked on localhost) --}}
            <iframe
                src="{{ $film->video_url }}"
                style="width:100%;height:100%;border:none;"
                allowfullscreen
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            ></iframe>
        @else
            {{-- HTML5 Video Player (direct URLs) --}}
            <video
                controls
                autoplay
                style="width:100%;height:100%;object-fit:contain;"
                controlsList="nodownload"
            >
                <source src="{{ $film->video_url }}" type="video/mp4">
                <source src="{{ $film->video_url }}" type="video/webm">
                Browser Anda tidak mendukung video player.
            </video>
        @endif
    </div>

    {{-- Film Info --}}
    <div style="max-width:1200px;margin:32px auto 0;">
        <h1 style="font-size:1.8rem;font-weight:800;margin-bottom:12px;">{{ $film->title }}</h1>
        <div style="display:flex;gap:16px;align-items:center;margin-bottom:16px;color:var(--sv-text-secondary);font-size:0.9rem;">
            <span>{{ $film->release_year }}</span>
            <span>•</span>
            <span>{{ $film->duration_formatted }}</span>
            <span>•</span>
            <span>{{ $film->genre_list }}</span>
        </div>
        <p style="color:var(--sv-text-secondary);line-height:1.7;max-width:800px;">{{ $film->description }}</p>
    </div>
</div>
@endsection
