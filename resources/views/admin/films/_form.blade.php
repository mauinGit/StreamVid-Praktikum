@php $allGenres = ['Action','Drama','Comedy','Horror','Sci-Fi','Romance','Thriller','Adventure']; @endphp

<div class="sv-form-group">
    <label class="sv-label">Judul Film</label>
    <input type="text" name="title" class="sv-input" value="{{ old('title', $film->title ?? '') }}" required>
</div>

<div class="sv-form-group">
    <label class="sv-label">Deskripsi</label>
    <textarea name="description" class="sv-input" rows="4" required style="resize:vertical;">{{ old('description', $film->description ?? '') }}</textarea>
</div>

<div class="sv-form-group">
    <label class="sv-label">Genre (pilih satu atau lebih)</label>
    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:6px;">
        @foreach($allGenres as $g)
            <label style="display:flex;align-items:center;gap:6px;padding:6px 14px;background:var(--sv-bg-primary);border:1px solid var(--sv-border);border-radius:8px;cursor:pointer;font-size:0.85rem;color:var(--sv-text-secondary);">
                <input type="checkbox" name="genre[]" value="{{ $g }}"
                    {{ in_array($g, old('genre', isset($film) ? $film->genre : [])) ? 'checked' : '' }}
                    style="accent-color:var(--sv-accent);">
                {{ $g }}
            </label>
        @endforeach
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <div class="sv-form-group">
        <label class="sv-label">Durasi (menit)</label>
        <input type="number" name="duration" class="sv-input" value="{{ old('duration', $film->duration ?? '') }}" required min="1">
    </div>
    <div class="sv-form-group">
        <label class="sv-label">Tahun Rilis</label>
        <input type="number" name="release_year" class="sv-input" value="{{ old('release_year', $film->release_year ?? date('Y')) }}" required min="1900" max="{{ date('Y') + 1 }}">
    </div>
</div>

{{-- Thumbnail Upload --}}
<div class="sv-form-group">
    <label class="sv-label">📷 Thumbnail (Poster Film)</label>
    <p style="font-size:0.75rem;color:var(--sv-text-muted);margin-bottom:8px;">Ukuran rekomendasi: 400x600px (rasio 2:3). Format: JPG, PNG, WebP. Maks 2MB.</p>
    @if(isset($film) && $film->thumbnail)
        <div style="margin-bottom:10px;display:flex;align-items:center;gap:12px;">
            <img src="{{ str_starts_with($film->thumbnail, 'http') ? $film->thumbnail : asset('storage/' . $film->thumbnail) }}" style="width:80px;height:120px;object-fit:cover;border-radius:8px;border:1px solid var(--sv-border);">
            <span style="font-size:0.8rem;color:var(--sv-text-muted);">Thumbnail saat ini</span>
        </div>
    @endif
    <input type="file" name="thumbnail" class="sv-input" accept="image/jpeg,image/png,image/webp" style="padding:10px;" {{ !isset($film) ? 'required' : '' }}>
</div>

{{-- Cover Upload --}}
<div class="sv-form-group">
    <label class="sv-label">🖼️ Cover (Hero Background)</label>
    <p style="font-size:0.75rem;color:var(--sv-text-muted);margin-bottom:8px;">Ukuran rekomendasi: 1920x1080px (rasio 16:9). Format: JPG, PNG, WebP. Maks 4MB.</p>
    @if(isset($film) && $film->cover)
        <div style="margin-bottom:10px;display:flex;align-items:center;gap:12px;">
            <img src="{{ str_starts_with($film->cover, 'http') ? $film->cover : asset('storage/' . $film->cover) }}" style="width:160px;height:90px;object-fit:cover;border-radius:8px;border:1px solid var(--sv-border);">
            <span style="font-size:0.8rem;color:var(--sv-text-muted);">Cover saat ini</span>
        </div>
    @endif
    <input type="file" name="cover" class="sv-input" accept="image/jpeg,image/png,image/webp" style="padding:10px;">
</div>

{{-- Video Input --}}
<div class="sv-form-group">
    <label class="sv-label">🎬 Video Film</label>
    <p style="font-size:0.75rem;color:var(--sv-text-muted);margin-bottom:12px;">
        Pilih salah satu: upload file video langsung <strong>(direkomendasikan)</strong> atau masukkan URL video.
    </p>

    {{-- Tab Selector --}}
    <div style="display:flex;gap:0;margin-bottom:16px;">
        <button type="button" onclick="switchVideoTab('upload')" id="tab-upload" style="flex:1;padding:10px;border:1px solid var(--sv-border);border-radius:8px 0 0 8px;background:var(--sv-accent);color:white;font-weight:600;font-size:0.85rem;cursor:pointer;font-family:inherit;">📁 Upload File</button>
        <button type="button" onclick="switchVideoTab('url')" id="tab-url" style="flex:1;padding:10px;border:1px solid var(--sv-border);border-radius:0 8px 8px 0;background:var(--sv-bg-primary);color:var(--sv-text-secondary);font-weight:600;font-size:0.85rem;cursor:pointer;font-family:inherit;">🔗 URL Eksternal</button>
    </div>

    {{-- Upload Tab --}}
    <div id="video-upload-tab">
        @if(isset($film) && $film->video_url && !str_starts_with($film->video_url, 'http'))
            <div style="margin-bottom:10px;padding:10px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);border-radius:8px;">
                <span style="color:#22c55e;font-size:0.85rem;">✓ Video sudah diupload: {{ basename($film->video_url) }}</span>
            </div>
        @endif
        <input type="file" name="video_file" class="sv-input" accept="video/mp4,video/webm,video/ogg" style="padding:10px;">
        <p style="font-size:0.7rem;color:var(--sv-text-muted);margin-top:6px;">Format: MP4, WebM, OGG. Maks 100MB.</p>
    </div>

    {{-- URL Tab --}}
    <div id="video-url-tab" style="display:none;">
        <input type="text" name="video_url" class="sv-input" value="{{ old('video_url', isset($film) && str_starts_with($film->video_url ?? '', 'http') ? $film->video_url : '') }}" placeholder="https://example.com/video.mp4">
        <p style="font-size:0.7rem;color:var(--sv-text-muted);margin-top:6px;">
            Masukkan URL langsung ke file video (MP4/WebM). ⚠️ Google Drive & YouTube tidak bisa diputar langsung.
        </p>
    </div>
</div>

<div class="sv-form-group">
    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
        <input type="checkbox" name="is_featured" value="1"
            {{ old('is_featured', isset($film) && $film->is_featured ? '1' : '') ? 'checked' : '' }}
            style="accent-color:var(--sv-accent);width:18px;height:18px;">
        <span class="sv-label" style="margin-bottom:0;">⭐ Featured Film (tampil di Hero Section)</span>
    </label>
</div>

<script>
function switchVideoTab(tab) {
    const uploadTab = document.getElementById('video-upload-tab');
    const urlTab = document.getElementById('video-url-tab');
    const btnUpload = document.getElementById('tab-upload');
    const btnUrl = document.getElementById('tab-url');

    if (tab === 'upload') {
        uploadTab.style.display = 'block';
        urlTab.style.display = 'none';
        btnUpload.style.background = 'var(--sv-accent)';
        btnUpload.style.color = 'white';
        btnUrl.style.background = 'var(--sv-bg-primary)';
        btnUrl.style.color = 'var(--sv-text-secondary)';
        // Clear URL input when switching to upload
        document.querySelector('input[name="video_url"]').value = '';
    } else {
        uploadTab.style.display = 'none';
        urlTab.style.display = 'block';
        btnUrl.style.background = 'var(--sv-accent)';
        btnUrl.style.color = 'white';
        btnUpload.style.background = 'var(--sv-bg-primary)';
        btnUpload.style.color = 'var(--sv-text-secondary)';
        // Clear file input when switching to URL
        document.querySelector('input[name="video_file"]').value = '';
    }
}
</script>
