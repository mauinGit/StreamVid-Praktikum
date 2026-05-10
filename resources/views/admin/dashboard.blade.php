@extends('admin.layouts.app')
@section('admin-content')
<style>
    .dashboard-grid-stats { grid-template-columns: repeat(3, 1fr); }
    .dashboard-grid-charts { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 24px; }
    .dashboard-grid-tables { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    
    @media (max-width: 1024px) {
        .dashboard-grid-stats { grid-template-columns: 1fr !important; }
        .dashboard-grid-charts { grid-template-columns: 1fr !important; }
        .dashboard-grid-tables { grid-template-columns: 1fr !important; }
    }
</style>

<div class="admin-header">
    <h1>Dashboard</h1>
    <div class="admin-header-meta">
        <span>{{ now()->format('l, d M Y') }}</span>
        <span>Halo, {{ auth()->user()->name }}</span>
    </div>
</div>

{{-- Quick Stats --}}
<div class="admin-stat-grid dashboard-grid-stats">
    <div class="admin-stat">
        <div class="admin-stat-value">{{ number_format($activeSubscribers) }}</div>
        <div class="admin-stat-label">Subscriber Aktif</div>
    </div>
    <div class="admin-stat" style="{{ $pendingPayments > 0 ? 'border-color:rgba(234,179,8,0.5);' : '' }}">
        <div class="admin-stat-value" style="{{ $pendingPayments > 0 ? 'color:#eab308;' : '' }}">{{ $pendingPayments }}</div>
        <div class="admin-stat-label">Pembayaran Pending</div>
        @if($pendingPayments > 0)
            <a href="{{ route('admin.subscriptions.index', ['status' => 'pending']) }}" style="font-size:0.75rem;color:var(--sv-accent);text-decoration:none;margin-top:8px;display:inline-block;">Lihat &rarr;</a>
        @endif
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
        <div class="admin-stat-label">Pemasukan Bulan Ini</div>
    </div>
</div>

<div class="dashboard-grid-charts">
    {{-- Monthly User Growth Chart --}}
    <div class="admin-card">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;">Pertumbuhan User (12 Bulan)</h3>
        <canvas id="userGrowthChart" height="200"></canvas>
    </div>

    {{-- Top 3 Trending Films --}}
    <div class="admin-card">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;">Top 3 Trending Films</h3>
        @forelse($trendingFilms as $i => $film)
            <div style="display:flex;gap:12px;align-items:center;padding:12px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--sv-border);' : '' }}">
                <span style="font-size:1.5rem;font-weight:900;color:var(--sv-accent);min-width:30px;">{{ $i + 1 }}</span>
                @php
                    $thumb = $film->thumbnail
                        ? (str_starts_with($film->thumbnail, 'http') ? $film->thumbnail : asset('storage/' . $film->thumbnail))
                        : 'https://picsum.photos/seed/'.$film->id.'/50/75';
                @endphp
                <img src="{{ $thumb }}" style="width:40px;height:60px;object-fit:cover;border-radius:6px;">
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;color:white;font-size:0.85rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $film->title }}</div>
                    <div style="font-size:0.75rem;color:var(--sv-text-muted);">{{ number_format($film->views_count) }} views</div>
                </div>
            </div>
        @empty
            <p style="color:var(--sv-text-muted);text-align:center;padding:20px;">Belum ada film</p>
        @endforelse
    </div>
</div>

<div class="dashboard-grid-tables">
    {{-- Recent Payments --}}
    <div class="admin-card">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;">Transaksi Terbaru</h3>
        @if($recentPayments->isEmpty())
            <p style="color:var(--sv-text-muted);text-align:center;padding:20px;">Belum ada transaksi</p>
        @else
            <table class="admin-table">
                <thead><tr><th>User</th><th>Paket</th><th>Status</th><th>Amount</th></tr></thead>
                <tbody>
                @foreach($recentPayments as $p)
                    <tr>
                        <td>{{ $p->user->name }}</td>
                        <td><span class="admin-badge admin-badge-info">{{ ucfirst($p->subscription->package ?? '-') }}</span></td>
                        <td><span class="admin-badge admin-badge-{{ $p->status_color }}">{{ $p->status_label }}</span></td>
                        <td>Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Recent Users --}}
    <div class="admin-card">
        <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;">User Terbaru</h3>
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

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('userGrowthChart'), {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'User Baru',
            data: @json($chartData),
            backgroundColor: 'rgba(255, 92, 0, 0.6)',
            borderColor: '#FF5C00',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: '#737373', stepSize: 1 },
                grid: { color: 'rgba(255,255,255,0.05)' }
            },
            x: {
                ticks: { color: '#737373', font: { size: 10 } },
                grid: { display: false }
            }
        }
    }
});
</script>
@endsection
