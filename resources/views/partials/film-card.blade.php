@php
    // Helper to get image URL - supports both storage paths and full URLs
    $thumbnailUrl = $film->thumbnail
        ? (str_starts_with($film->thumbnail, 'http') ? $film->thumbnail : asset('storage/' . $film->thumbnail))
        : 'https://picsum.photos/seed/' . $film->id . '/400/600';
@endphp
<a href="{{ auth()->check() ? route('films.show', $film) : route('login') }}" class="sv-film-card" style="text-decoration:none;color:inherit;">
    <img src="{{ $thumbnailUrl }}" alt="{{ $film->title }}" loading="lazy">
    <div class="sv-film-card-overlay">
        <div class="sv-film-card-title">{{ $film->title }}</div>
        <div class="sv-film-card-meta">{{ $film->release_year }} • {{ $film->duration_formatted }}</div>
        <span class="sv-film-card-genre">{{ Str::limit($film->genre_list, 25) }}</span>
    </div>
</a>
