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

<div class="sv-form-group">
    <label class="sv-label">Thumbnail URL</label>
    <input type="url" name="thumbnail" class="sv-input" value="{{ old('thumbnail', $film->thumbnail ?? '') }}" placeholder="https://...">
</div>

<div class="sv-form-group">
    <label class="sv-label">Video URL (Embed)</label>
    <input type="url" name="video_url" class="sv-input" value="{{ old('video_url', $film->video_url ?? '') }}" required placeholder="https://www.youtube.com/embed/...">
</div>

<div class="sv-form-group">
    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
        <input type="checkbox" name="is_featured" value="1"
            {{ old('is_featured', isset($film) && $film->is_featured ? '1' : '') ? 'checked' : '' }}
            style="accent-color:var(--sv-accent);width:18px;height:18px;">
        <span class="sv-label" style="margin-bottom:0;">Featured Film</span>
    </label>
</div>
