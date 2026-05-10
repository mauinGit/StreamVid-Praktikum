@extends('layouts.app')
@section('content')
<style>
    @media (max-width: 1024px) {
        .watch-nav-padding { padding: 0 !important; }
        .watch-wrapper { border-radius: 0 !important; box-shadow: none !important; background: var(--sv-bg-primary) !important; }
        .watch-info { padding: 16px !important; background: var(--sv-bg-primary) !important; }
        .watch-title { font-size: 1.2rem !important; margin-bottom: 6px !important; }
        .watch-desc { -webkit-line-clamp: unset !important; display: block !important; font-size: 13px !important; margin-top: 10px !important; }
        .watch-meta-badge { font-size: 11px !important; padding: 2px 6px !important; }
        .watch-meta-text { font-size: 13px !important; }
        .mobile-zoom-btn { display: flex !important; }
        .desktop-fs-btn { display: none !important; }
    }
    .mobile-zoom-btn { display: none; }
</style>

<div class="sv-mt-nav watch-nav-padding" style="padding:20px 48px;">
    @php
        $isUploadedVideo = $film->video_url && !str_starts_with($film->video_url, 'http');
        $videoSrc = $isUploadedVideo
            ? asset('storage/' . $film->video_url)
            : $film->video_url;
    @endphp

    {{-- Player Wrapper --}}
    <div class="watch-wrapper" style="max-width:1200px;margin:0 auto;background:#0d0d0d;border-radius:16px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,0.6);">

        {{-- Video --}}
        <div id="videoWrapper" style="position:relative;width:100%;aspect-ratio:16/9;background:#000;">

            <video id="mainVideo" style="width:100%;height:100%;object-fit:contain;" controlsList="nodownload">
                <source src="{{ $videoSrc }}" type="video/mp4">
                <source src="{{ $videoSrc }}" type="video/webm">
                Browser Anda tidak mendukung video player.
            </video>

            {{-- Click overlay --}}
            <div onclick="togglePlay()" style="position:absolute;inset:0;cursor:pointer;z-index:2;"></div>

            {{-- Big play icon --}}
            <div id="bigPlayIcon" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,0.12);border:2px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;pointer-events:none;transition:opacity 0.25s;z-index:3;opacity:1;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="white"><polygon points="6,3 20,12 6,21"/></svg>
            </div>

            {{-- Controls --}}
            <div id="controlBar" style="position:absolute;bottom:0;left:0;right:0;padding:0 20px 16px;background:linear-gradient(transparent,rgba(0,0,0,0.9));z-index:4;transition:opacity 0.3s;">

                {{-- Progress bar --}}
                <div id="progressContainer" onclick="seekVideo(event,this)" style="position:relative;height:20px;display:flex;align-items:center;cursor:pointer;margin-bottom:10px;" onmouseover="this.querySelector('.track').style.height='5px'" onmouseout="this.querySelector('.track').style.height='3px'">
                    <div class="track" style="position:absolute;left:0;right:0;height:3px;background:rgba(255,255,255,0.2);border-radius:3px;transition:height 0.15s;">
                        <div id="progressFill" style="height:100%;width:0%;background:#e50914;border-radius:3px;position:relative;">
                            <span style="position:absolute;right:-6px;top:50%;transform:translateY(-50%) scale(0);width:12px;height:12px;background:#fff;border-radius:50%;display:block;transition:transform 0.15s;" id="progressThumb"></span>
                        </div>
                    </div>
                </div>

                {{-- Buttons row --}}
                <div style="display:flex;align-items:center;gap:6px;">

                    {{-- Play/Pause --}}
                    <button onclick="togglePlay()" style="background:none;border:none;cursor:pointer;padding:6px;color:white;display:flex;align-items:center;border-radius:6px;" onmouseover="this.style.background='rgba(255,255,255,0.12)'" onmouseout="this.style.background='none'" title="Play/Pause (Space)">
                        <svg id="iconPlay" width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><polygon points="6,3 20,12 6,21"/></svg>
                        <svg id="iconPause" width="22" height="22" viewBox="0 0 24 24" fill="currentColor" style="display:none"><rect x="6" y="4" width="4" height="16" rx="1"/><rect x="14" y="4" width="4" height="16" rx="1"/></svg>
                    </button>

                    {{-- Skip -10s --}}
                    <button onclick="skipVideo(-10)" style="background:none;border:none;cursor:pointer;padding:6px;color:white;display:flex;align-items:center;border-radius:6px;font-size:11px;font-weight:500;gap:2px;" onmouseover="this.style.background='rgba(255,255,255,0.12)'" onmouseout="this.style.background='none'" title="Mundur 10 detik (←)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/></svg>
                    </button>

                    {{-- Skip +10s --}}
                    <button onclick="skipVideo(10)" style="background:none;border:none;cursor:pointer;padding:6px;color:white;display:flex;align-items:center;border-radius:6px;" onmouseover="this.style.background='rgba(255,255,255,0.12)'" onmouseout="this.style.background='none'" title="Maju 10 detik (→)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 5V1l5 5-5 5V7c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6h2c0 4.42-3.58 8-8 8s-8-3.58-8-8 3.58-8 8-8z"/></svg>
                    </button>

                    {{-- Volume --}}
                    <div style="display:flex;align-items:center;gap:6px;" id="volWrap" onmouseover="document.getElementById('volSlider').style.width='64px'" onmouseout="document.getElementById('volSlider').style.width='0'">
                        <button onclick="toggleMute()" style="background:none;border:none;cursor:pointer;padding:6px;color:white;display:flex;align-items:center;border-radius:6px;" onmouseover="this.style.background='rgba(255,255,255,0.12)'" onmouseout="this.style.background='none'" title="Mute (M)">
                            <svg id="iconVol" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></svg>
                            <svg id="iconMute" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="display:none"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                        </button>
                        <div id="volSlider" style="width:0;overflow:hidden;transition:width 0.2s;">
                            <input type="range" min="0" max="100" value="100" oninput="setVolume(this.value)" style="-webkit-appearance:none;appearance:none;width:64px;height:3px;border-radius:3px;background:rgba(255,255,255,0.3);outline:none;cursor:pointer;">
                        </div>
                    </div>

                    {{-- Time --}}
                    <span id="timeDisplay" style="color:rgba(255,255,255,0.75);font-size:12px;font-family:monospace;white-space:nowrap;margin:0 4px;">0:00 / 0:00</span>

                    <div style="flex:1;"></div>

                    {{-- Landscape Zoom (Mobile Only) --}}
                    <button class="mobile-zoom-btn" onclick="toggleLandscape()" style="background:none;border:none;cursor:pointer;padding:6px;color:white;align-items:center;border-radius:6px;" onmouseover="this.style.background='rgba(255,255,255,0.12)'" onmouseout="this.style.background='none'" title="Zoom / Landscape">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M15 3l2.3 2.3-2.89 2.87 1.42 1.42L18.7 6.7 21 9V3zM3 9l2.3-2.3 2.87 2.89 1.42-1.42L6.7 5.3 9 3H3zm6 12l-2.3-2.3 2.89-2.87-1.42-1.42L5.3 17.3 3 15v6zm12-6l-2.3 2.3-2.87-2.89-1.42 1.42 2.89 2.87L15 21h6z"/></svg>
                    </button>

                    {{-- Playback speed --}}
                    <button id="speedBtn" onclick="changeSpeed()" style="background:none;border:none;cursor:pointer;padding:6px 8px;color:rgba(255,255,255,0.8);font-size:12px;font-weight:500;border-radius:6px;min-width:36px;" onmouseover="this.style.background='rgba(255,255,255,0.12)'" onmouseout="this.style.background='none'" title="Kecepatan">1×</button>

                    {{-- Fullscreen --}}
                    <button class="desktop-fs-btn" onclick="toggleFullscreen()" style="background:none;border:none;cursor:pointer;padding:6px;color:white;display:flex;align-items:center;border-radius:6px;" onmouseover="this.style.background='rgba(255,255,255,0.12)'" onmouseout="this.style.background='none'" title="Layar penuh (F)">
                        <svg id="iconFsEnter" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
                        <svg id="iconFsExit" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="display:none"><path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/></svg>
                    </button>

                </div>
            </div>
        </div>

        {{-- Film Info --}}
        <div class="watch-info" style="padding:20px 24px 24px;background:#111;">
            <div style="display:flex;gap:12px;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;">
                <div>
                    <h1 class="watch-title" style="margin:0 0 10px;font-size:26px;font-weight:600;color:#fff;letter-spacing:-0.3px;">{{ $film->title }}</h1>
                    <div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap;">
                        <span class="watch-meta-badge" style="background:#e50914;color:#fff;font-size:13px;font-weight:600;padding:3px 10px;border-radius:4px;">{{ $film->release_year }}</span>
                        <span class="watch-meta-text" style="color:rgba(255,255,255,0.7);font-size:15px;">{{ $film->duration_formatted }}</span>
                        <span class="watch-meta-text" style="color:rgba(255,255,255,0.4);font-size:15px;">•</span>
                        <span class="watch-meta-text" style="color:rgba(255,255,255,0.7);font-size:15px;">{{ $film->genre_list }}</span>
                    </div>
                </div>
            </div>
            <p class="watch-desc" style="margin:16px 0 0;color:rgba(255,255,255,0.8);font-size:16px;line-height:1.7;">{{ $film->description }}</p>
        </div>

    </div>
</div>

<script>
    const video    = document.getElementById('mainVideo');
    const iconPlay = document.getElementById('iconPlay');
    const iconPause= document.getElementById('iconPause');
    const bigPlayIcon = document.getElementById('bigPlayIcon');
    const progressFill= document.getElementById('progressFill');
    const progressThumb=document.getElementById('progressThumb');
    const timeDisplay = document.getElementById('timeDisplay');
    const iconVol  = document.getElementById('iconVol');
    const iconMute = document.getElementById('iconMute');
    const iconFsEnter=document.getElementById('iconFsEnter');
    const iconFsExit= document.getElementById('iconFsExit');
    const wrapper  = document.getElementById('videoWrapper');
    const controlBar=document.getElementById('controlBar');
    const speedBtn = document.getElementById('speedBtn');
    const speeds   = [0.5, 0.75, 1, 1.25, 1.5, 2];
    let speedIdx   = 2, hideTimer;

    wrapper.addEventListener('mousemove', () => {
        controlBar.style.opacity = '1';
        bigPlayIcon.style.opacity = video.paused ? '1' : '0';
        clearTimeout(hideTimer);
        hideTimer = setTimeout(() => { if (!video.paused) controlBar.style.opacity = '0'; }, 3000);
    });
    wrapper.addEventListener('mouseleave', () => { if (!video.paused) controlBar.style.opacity = '0'; });

    // Show thumb on progress hover
    document.getElementById('progressContainer').addEventListener('mouseover', () => {
        progressThumb.style.transform = 'translateY(-50%) scale(1)';
    });
    document.getElementById('progressContainer').addEventListener('mouseout', () => {
        progressThumb.style.transform = 'translateY(-50%) scale(0)';
    });

    function togglePlay() { video.paused ? video.play() : video.pause(); }
    function skipVideo(s) { video.currentTime = Math.max(0, Math.min(video.duration||0, video.currentTime+s)); }
    function seekVideo(e, el) { const r=el.getBoundingClientRect(); video.currentTime=(e.clientX-r.left)/r.width*(video.duration||0); }
    function toggleMute() { video.muted=!video.muted; iconVol.style.display=video.muted?'none':'block'; iconMute.style.display=video.muted?'block':'none'; }
    function setVolume(v) { video.volume=v/100; video.muted=v==0; iconVol.style.display=v>0?'block':'none'; iconMute.style.display=v==0?'block':'none'; }
    function changeSpeed() { speedIdx=(speedIdx+1)%speeds.length; video.playbackRate=speeds[speedIdx]; speedBtn.textContent=speeds[speedIdx]+'×'; }
    function toggleFullscreen() { document.fullscreenElement ? document.exitFullscreen() : wrapper.requestFullscreen(); }
    function toggleLandscape() {
        if (!document.fullscreenElement) {
            wrapper.requestFullscreen().then(() => {
                if (screen.orientation && screen.orientation.lock) {
                    screen.orientation.lock('landscape').catch(e => console.log(e));
                }
            });
        } else {
            document.exitFullscreen();
        }
    }
    function fmt(s) { if(isNaN(s)) return '0:00'; const m=Math.floor(s/60),sec=Math.floor(s%60); return m+':'+(sec<10?'0':'')+sec; }

    video.addEventListener('play',  () => { iconPlay.style.display='none'; iconPause.style.display='block'; bigPlayIcon.style.opacity='0'; });
    video.addEventListener('pause', () => { iconPlay.style.display='block'; iconPause.style.display='none'; bigPlayIcon.style.opacity='1'; controlBar.style.opacity='1'; });
    video.addEventListener('timeupdate', () => {
        const p = video.duration ? (video.currentTime/video.duration)*100 : 0;
        progressFill.style.width = p+'%';
        timeDisplay.textContent = fmt(video.currentTime)+' / '+fmt(video.duration);
    });
    document.addEventListener('fullscreenchange', () => {
        const f=!!document.fullscreenElement;
        iconFsEnter.style.display=f?'none':'block';
        iconFsExit.style.display=f?'block':'none';
    });
    document.addEventListener('keydown', e => {
        if (['Space','KeyF','KeyM','ArrowLeft','ArrowRight'].includes(e.code)) {
            e.preventDefault();
            if (e.code==='Space') togglePlay();
            if (e.code==='KeyF') toggleFullscreen();
            if (e.code==='KeyM') toggleMute();
            if (e.code==='ArrowLeft') skipVideo(-10);
            if (e.code==='ArrowRight') skipVideo(10);
        }
    });
</script>
@endsection