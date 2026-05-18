@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>Laporan</h1>
</div>

{{-- Date & Status Filter --}}
<div class="admin-card" style="margin-bottom:24px;">
    <form method="GET" action="{{ route('admin.reports.index') }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
        <div>
            <label class="sv-label">Tanggal Mulai</label>
            <input type="date" name="start_date" class="sv-input" value="{{ $startDate }}" style="width:180px;">
        </div>
        <div>
            <label class="sv-label">Tanggal Akhir</label>
            <input type="date" name="end_date" class="sv-input" value="{{ $endDate }}" style="width:180px;">
        </div>
        <div>
            <label class="sv-label">Status Transaksi</label>
            <select name="status" class="sv-input" style="width:180px;">
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua</option>
                <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Sukses / Approve</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>
        <button type="submit" class="sv-btn sv-btn-primary">Filter</button>
        @if($startDate || $endDate || (request('status') && request('status') !== 'all'))
            <a href="{{ route('admin.reports.index') }}" class="sv-btn sv-btn-outline">Reset</a>
        @endif
    </form>
    @if($startDate && $endDate)
        <p style="margin-top:12px;font-size:0.8rem;color:var(--sv-text-muted);">Menampilkan data dari {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
    @endif
</div>

{{-- Summary Widgets --}}
<div class="admin-stat-grid" style="grid-template-columns:repeat(5,1fr);">
    <div class="admin-stat">
        <div class="admin-stat-value">{{ number_format($totalUsers) }}</div>
        <div class="admin-stat-label">Total Users</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value">{{ number_format($totalFilms) }}</div>
        <div class="admin-stat-label">Total Film</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value">{{ number_format($totalSubscribers) }}</div>
        <div class="admin-stat-label">Subscriber Aktif</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="admin-stat-label">Total Pemasukan</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value">{{ number_format($totalTransactions) }}</div>
        <div class="admin-stat-label">Total Transaksi</div>
    </div>
</div>

{{-- Export Buttons --}}
<div class="admin-card" style="margin-bottom:24px;">
    <h3 style="font-size:1rem;font-weight:700;margin-bottom:16px;">Export Laporan PDF</h3>
    <div style="display:flex;gap:12px;flex-wrap:wrap;">
        <a href="{{ route('admin.reports.export', ['type' => 'summary', 'start_date' => $startDate, 'end_date' => $endDate, 'status' => request('status')]) }}" class="sv-btn sv-btn-primary">Laporan Summary</a>
        <a href="{{ route('admin.reports.export', ['type' => 'users', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="sv-btn sv-btn-outline">Laporan Users</a>
        <a href="{{ route('admin.reports.export', ['type' => 'films', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="sv-btn sv-btn-outline">Laporan Film</a>
        <a href="{{ route('admin.reports.export', ['type' => 'subscriptions', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="sv-btn sv-btn-outline">Laporan Langganan</a>
    </div>
</div>

{{-- Transaction Table --}}
<div class="admin-card">
    <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;">Riwayat Transaksi</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>User</th>
                <th>Paket</th>
                <th>Metode</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
        @forelse($transactions as $t)
            <tr>
                <td style="font-family:monospace;font-size:0.75rem;">{{ $t->invoice_id }}</td>
                <td>{{ $t->user->name }}</td>
                <td><span class="admin-badge admin-badge-info">{{ ucfirst($t->subscription->package ?? '-') }}</span></td>
                <td>{{ $t->method_label }}</td>
                <td style="font-weight:600;">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                <td><span class="admin-badge admin-badge-{{ $t->status_color }}">{{ $t->status_label }}</span></td>
                <td style="font-size:0.8rem;">{{ $t->created_at->format('d M Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--sv-text-muted);">Tidak ada transaksi</td></tr>
        @endforelse
        </tbody>
    </table>

    @if($transactions->hasPages())
        <div class="sv-pagination">{!! $transactions->withQueryString()->links('partials.pagination') !!}</div>
    @endif
</div>
@endsection
