@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>🎬 Kelola Film</h1>
    <a href="{{ route('admin.films.create') }}" class="sv-btn sv-btn-primary">+ Tambah Film</a>
</div>

<div class="admin-card">
    <form method="GET" style="margin-bottom:20px;">
        <input type="text" name="search" class="sv-input" placeholder="🔍 Cari film..." value="{{ request('search') }}" style="max-width:300px;">
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
                <th>Featured</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($films as $film)
            <tr>
                <td><img src="{{ $film->thumbnail }}" style="width:50px;height:75px;object-fit:cover;border-radius:6px;"></td>
                <td style="font-weight:600;color:white;">{{ $film->title }}</td>
                <td><span class="admin-badge admin-badge-info">{{ $film->genre_list }}</span></td>
                <td>{{ $film->release_year }}</td>
                <td>{{ $film->duration_formatted }}</td>
                <td>{{ number_format($film->views_count) }}</td>
                <td>{!! $film->is_featured ? '<span class="admin-badge admin-badge-success">Yes</span>' : '<span class="admin-badge admin-badge-warning">No</span>' !!}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.films.edit', $film) }}" class="sv-btn sv-btn-outline sv-btn-sm">✏️</a>
                        <form method="POST" action="{{ route('admin.films.destroy', $film) }}" onsubmit="return confirm('Hapus film ini?')">
                            @csrf @method('DELETE')
                            <button class="sv-btn sv-btn-danger sv-btn-sm">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--sv-text-muted);">Tidak ada film</td></tr>
        @endforelse
        </tbody>
    </table>

    @if($films->hasPages())
        <div class="sv-pagination">{!! $films->withQueryString()->links('partials.pagination') !!}</div>
    @endif
</div>
@endsection
