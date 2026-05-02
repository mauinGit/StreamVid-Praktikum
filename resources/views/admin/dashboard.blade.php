@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>Dashboard</h1>
    <div class="admin-header-meta">
        <span>{{ now()->format('l, d M Y') }}</span>
        <span>👋 Halo, {{ auth()->user()->name }}</span>
    </div>
</div>

{{-- Stats --}}
<div class="admin-stat-grid">
    <div class="admin-stat">
        <div class="admin-stat-icon">👥</div>
        <div class="admin-stat-value">{{ number_format($totalUsers) }}</div>
        <div class="admin-stat-label">Total Users</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-icon">💳</div>
        <div class="admin-stat-value">{{ number_format($totalSubscribers) }}</div>
        <div class="admin-stat-label">Active Subscribers</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-icon">🎬</div>
        <div class="admin-stat-value">{{ number_format($totalFilms) }}</div>
        <div class="admin-stat-label">Total Films</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-icon">💰</div>
        <div class="admin-stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="admin-stat-label">Total Revenue</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    {{-- Recent Payments --}}
    <div class="admin-card">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;">💳 Pembayaran Terbaru</h3>
        @if($recentPayments->isEmpty())
            <p style="color:var(--sv-text-muted);text-align:center;padding:20px;">Belum ada pembayaran</p>
        @else
            <table class="admin-table">
                <thead><tr><th>User</th><th>Paket</th><th>Amount</th></tr></thead>
                <tbody>
                @foreach($recentPayments as $p)
                    <tr>
                        <td>{{ $p->user->name }}</td>
                        <td><span class="admin-badge admin-badge-info">{{ ucfirst($p->subscription->package) }}</span></td>
                        <td>Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Recent Users --}}
    <div class="admin-card">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;">👥 User Terbaru</h3>
        @if($recentUsers->isEmpty())
            <p style="color:var(--sv-text-muted);text-align:center;padding:20px;">Belum ada user</p>
        @else
            <table class="admin-table">
                <thead><tr><th>Nama</th><th>Email</th><th>Joined</th></tr></thead>
                <tbody>
                @foreach($recentUsers as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td style="font-size:0.8rem;">{{ $u->email }}</td>
                        <td style="font-size:0.8rem;">{{ $u->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
