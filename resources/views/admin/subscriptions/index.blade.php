@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>💳 Manage Subscriptions</h1>
    <form method="GET" style="display:flex;gap:8px;">
        <select name="status" class="sv-input" style="width:auto;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </form>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>User</th><th>Paket</th><th>Mulai</th><th>Berakhir</th><th>Status</th><th>Pembayaran</th></tr></thead>
        <tbody>
        @forelse($subscriptions as $sub)
            <tr>
                <td style="font-weight:600;color:white;">{{ $sub->user->name }}</td>
                <td><span class="admin-badge admin-badge-info">{{ ucfirst($sub->package) }}</span></td>
                <td>{{ $sub->start_date->format('d M Y') }}</td>
                <td>{{ $sub->end_date->format('d M Y') }}</td>
                <td>
                    @if($sub->isActive())
                        <span class="admin-badge admin-badge-success">Active</span>
                    @else
                        <span class="admin-badge admin-badge-danger">{{ ucfirst($sub->status) }}</span>
                    @endif
                </td>
                <td>{{ $sub->payment ? 'Rp ' . number_format($sub->payment->amount, 0, ',', '.') : '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--sv-text-muted);">Tidak ada subscription</td></tr>
        @endforelse
        </tbody>
    </table>

    @if($subscriptions->hasPages())
        <div class="sv-pagination">{!! $subscriptions->withQueryString()->links('partials.pagination') !!}</div>
    @endif
</div>
@endsection
