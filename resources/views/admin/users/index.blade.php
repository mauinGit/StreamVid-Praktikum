@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>👥 Kelola Users</h1>
</div>

<div class="admin-card">
    <form method="GET" style="margin-bottom:20px;">
        <input type="text" name="search" class="sv-input" placeholder="🔍 Cari user..." value="{{ request('search') }}" style="max-width:300px;">
    </form>

    <table class="admin-table">
        <thead><tr><th>Nama</th><th>Email</th><th>Status</th><th>Paket</th><th>Joined</th><th>Aksi</th></tr></thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td style="font-weight:600;color:white;">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->activeSubscription)
                        <span class="admin-badge admin-badge-success">Active</span>
                    @else
                        <span class="admin-badge admin-badge-warning">Free</span>
                    @endif
                </td>
                <td>{{ $user->activeSubscription ? ucfirst($user->activeSubscription->package) : '-' }}</td>
                <td style="font-size:0.8rem;">{{ $user->created_at->format('d M Y') }}</td>
                <td><a href="{{ route('admin.users.show', $user) }}" class="sv-btn sv-btn-outline sv-btn-sm">Detail</a></td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--sv-text-muted);">Tidak ada user</td></tr>
        @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
        <div class="sv-pagination">{!! $users->withQueryString()->links('partials.pagination') !!}</div>
    @endif
</div>
@endsection
