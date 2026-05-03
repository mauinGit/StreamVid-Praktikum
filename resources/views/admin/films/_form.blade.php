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
            <img src="{{ asset('storage/' . $film->thumbnail) }}" style="width:80px;height:120px;object-fit:cover;border-radius:8px;border:1px solid var(--sv-border);">
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
            <img src="{{ asset('storage/' . $film->cover) }}" style="width:160px;height:90px;object-fit:cover;border-radius:8px;border:1px solid var(--sv-border);">
            <span style="font-size:0.8rem;color:var(--sv-text-muted);">Cover saat ini</span>
        </div>
    @endif
    <input type="file" name="cover" class="sv-input" accept="image/jpeg,image/png,image/webp" style="padding:10px;">
</div>

{{-- Video URL --}}
<div class="sv-form-group">
    <label class="sv-label">🎬 Video URL</label>
    <p style="font-size:0.75rem;color:var(--sv-text-muted);margin-bottom:8px;">
        Masukkan URL video langsung (MP4/WebM). Contoh sumber gratis:
        <br>• <a href="https://sample-videos.com" target="_blank" style="color:var(--sv-accent);">sample-videos.com</a>
        • <a href="https://archive.org" target="_blank" style="color:var(--sv-accent);">archive.org</a>
        • Atau upload ke Google Drive → share link
    </p>
    <input type="text" name="video_url" class="sv-input" value="{{ old('video_url', $film->video_url ?? '') }}" required placeholder="https://example.com/video.mp4">
</div>

<div class="sv-form-group">
    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
        <input type="checkbox" name="is_featured" value="1"
            {{ old('is_featured', isset($film) && $film->is_featured ? '1' : '') ? 'checked' : '' }}
            style="accent-color:var(--sv-accent);width:18px;height:18px;">
        <span class="sv-label" style="margin-bottom:0;">⭐ Featured Film (tampil di Hero Section)</span>
    </label>
</div>
