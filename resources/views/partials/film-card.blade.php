<a href="{{ auth()->check() ? route('films.show', $film) : route('login') }}" class="sv-film-card" style="text-decoration:none;color:inherit;">
    <img src="{{ $film->thumbnail }}" alt="{{ $film->title }}" loading="lazy">
    <div class="sv-film-card-overlay">
        <div class="sv-film-card-title">{{ $film->title }}</div>
        <div class="sv-film-card-meta">{{ $film->release_year }} • {{ $film->duration_formatted }}</div>
        <span class="sv-film-card-genre">{{ Str::limit($film->genre_list, 25) }}</span>
    </div>
</a>
