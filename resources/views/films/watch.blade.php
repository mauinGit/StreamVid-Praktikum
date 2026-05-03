@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:20px 48px;">
    @php
        // Determine video source: uploaded file or external URL
        $isUploadedVideo = $film->video_url && !str_starts_with($film->video_url, 'http');
        $videoSrc = $isUploadedVideo
            ? asset('storage/' . $film->video_url)
            : $film->video_url;
    @endphp

    {{-- Video Player --}}
    <div style="position:relative;width:100%;max-width:1200px;margin:0 auto;aspect-ratio:16/9;background:#000;border-radius:12px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
        <video
            controls
            autoplay
            style="width:100%;height:100%;object-fit:contain;"
            controlsList="nodownload"
        >
            <source src="{{ $videoSrc }}" type="video/mp4">
            <source src="{{ $videoSrc }}" type="video/webm">
            Browser Anda tidak mendukung video player.
        </video>
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
