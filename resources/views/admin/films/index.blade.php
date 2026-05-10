@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>Kelola Film</h1>
    <a href="{{ route('admin.films.create') }}" class="sv-btn sv-btn-primary">+ Tambah Film</a>
</div>

{{-- Hero Carousel Section --}}
<div class="admin-card" style="margin-bottom:24px;">
    <h3 style="font-size:1rem;font-weight:700;margin-bottom:16px;">Hero Carousel (Maks 3 Film)</h3>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
        @for($i = 0; $i < 3; $i++)
            @if(isset($featuredFilms[$i]))
                @php $f = $featuredFilms[$i]; @endphp
                <div style="position:relative;border:1px solid var(--sv-accent);border-radius:12px;overflow:hidden;aspect-ratio:16/9;">
                    @php
                        $thumb = $f->cover
                            ? (str_starts_with($f->cover, 'http') ? $f->cover : asset('storage/' . $f->cover))
                            : ($f->thumbnail
                                ? (str_starts_with($f->thumbnail, 'http') ? $f->thumbnail : asset('storage/' . $f->thumbnail))
                                : 'https://picsum.photos/seed/'.$f->id.'/400/225');
                    @endphp
                    <img src="{{ $thumb }}" style="width:100%;height:100%;object-fit:cover;">
                    <div style="position:absolute;inset:0;background:linear-gradient(transparent 50%,rgba(0,0,0,0.8) 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:16px;">
                        <span style="font-weight:700;font-size:0.9rem;">{{ $f->title }}</span>
                        <span style="font-size:0.75rem;color:var(--sv-text-muted);">{{ $f->release_year }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.films.toggle-featured', $f) }}" style="position:absolute;top:8px;right:8px;">
                        @csrf
                        <button type="submit" class="sv-btn sv-btn-sm sv-btn-danger" onclick="return confirm('Hapus dari Hero Carousel?')" style="padding:4px 10px;font-size:0.7rem;">X</button>
                    </form>
                </div>
            @else
                <div onclick="openFeaturedModal()" style="border:2px dashed var(--sv-border);border-radius:12px;aspect-ratio:16/9;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;flex-direction:column;gap:8px;" onmouseover="this.style.borderColor='var(--sv-accent)';this.style.background='rgba(255,92,0,0.05)'" onmouseout="this.style.borderColor='var(--sv-border)';this.style.background='transparent'">
                    <span style="font-size:2rem;color:var(--sv-text-muted);">+</span>
                    <span style="font-size:0.8rem;color:var(--sv-text-muted);">Tambah Film</span>
                </div>
            @endif
        @endfor
    </div>
</div>

{{-- Film List --}}
<div class="admin-card">
    <form method="GET" style="margin-bottom:20px;">
        <input type="text" name="search" class="sv-input" placeholder="Cari film..." value="{{ request('search') }}" style="max-width:300px;">
    </form>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Thumbnail</th>
                <th>Judul</th>
                <th>Genre</th>
                <th>Tahun</th>
                <th>Durasi</th>
                <th>Views</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($films as $film)
            <tr>
                <td><img src="{{ $film->thumbnail ? (str_starts_with($film->thumbnail, 'http') ? $film->thumbnail : asset('storage/' . $film->thumbnail)) : 'https://picsum.photos/seed/'.$film->id.'/50/75' }}" style="width:50px;height:75px;object-fit:cover;border-radius:6px;"></td>
                <td style="font-weight:600;color:white;">{{ $film->title }}</td>
                <td><span class="admin-badge admin-badge-info">{{ $film->genre_list }}</span></td>
                <td>{{ $film->release_year }}</td>
                <td>{{ $film->duration_formatted }}</td>
                <td>{{ number_format($film->views_count) }}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.films.edit', $film) }}" class="sv-btn sv-btn-outline sv-btn-sm">Edit</a>
                        <form method="POST" action="{{ route('admin.films.destroy', $film) }}" onsubmit="return confirm('Hapus film ini?')">
                            @csrf @method('DELETE')
                            <button class="sv-btn sv-btn-danger sv-btn-sm">Hapus</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--sv-text-muted);">Tidak ada film</td></tr>
        @endforelse
        </tbody>
    </table>

    @if($films->hasPages())
        <div class="sv-pagination">{!! $films->withQueryString()->links('partials.pagination') !!}</div>
    @endif
</div>

{{-- Featured Film Modal --}}
<div id="featured-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:28px;width:500px;max-height:80vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:1.1rem;font-weight:700;">Pilih Film untuk Hero</h3>
            <button onclick="closeFeaturedModal()" style="background:none;border:none;color:var(--sv-text-muted);font-size:1.5rem;cursor:pointer;">X</button>
        </div>
        <input type="text" id="featured-search" class="sv-input" placeholder="Cari film..." style="margin-bottom:16px;" oninput="searchFeaturedFilms(this.value)">
        <div id="featured-results" style="display:flex;flex-direction:column;gap:8px;">
            <p style="color:var(--sv-text-muted);text-align:center;padding:20px;">Ketik untuk mencari film...</p>
        </div>
    </div>
</div>

<script>
let searchTimeout;

function openFeaturedModal() {
    document.getElementById('featured-modal').style.display = 'flex';
    document.getElementById('featured-search').value = '';
    document.getElementById('featured-search').focus();
}

function closeFeaturedModal() {
    document.getElementById('featured-modal').style.display = 'none';
}

function searchFeaturedFilms(query) {
    clearTimeout(searchTimeout);
    if (query.length < 1) {
        document.getElementById('featured-results').innerHTML = '<p style="color:var(--sv-text-muted);text-align:center;padding:20px;">Ketik untuk mencari film...</p>';
        return;
    }
    searchTimeout = setTimeout(() => {
        fetch(`{{ route('admin.films.search-json') }}?q=${encodeURIComponent(query)}`)
            .then(r => r.json())
            .then(films => {
                if (films.length === 0) {
                    document.getElementById('featured-results').innerHTML = '<p style="color:var(--sv-text-muted);text-align:center;padding:20px;">Tidak ditemukan</p>';
                    return;
                }
                let html = '';
                films.forEach(film => {
                    html += `
                    <form method="POST" action="/admin/films/${film.id}/toggle-featured" style="margin:0;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" style="width:100%;display:flex;gap:12px;align-items:center;padding:12px;background:var(--sv-bg-primary);border:1px solid var(--sv-border);border-radius:8px;cursor:pointer;text-align:left;color:white;font-family:inherit;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--sv-accent)'" onmouseout="this.style.borderColor='var(--sv-border)'">
                            <img src="${film.thumbnail_url}" style="width:40px;height:60px;object-fit:cover;border-radius:4px;">
                            <div style="flex:1;">
                                <div style="font-weight:600;font-size:0.9rem;">${film.title}</div>
                                <div style="font-size:0.75rem;color:var(--sv-text-muted);">${film.release_year}</div>
                            </div>
                            <span style="font-size:0.75rem;color:var(--sv-accent);">Pilih</span>
                        </button>
                    </form>`;
                });
                document.getElementById('featured-results').innerHTML = html;
            });
    }, 300);
}

// Close modal on outside click
document.getElementById('featured-modal').addEventListener('click', function(e) {
    if (e.target === this) closeFeaturedModal();
});
</script>
@endsection
