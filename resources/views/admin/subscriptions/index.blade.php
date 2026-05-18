@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>Manajemen Transaksi</h1>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.subscriptions.index') }}" class="sv-btn sv-btn-sm {{ !request('status') ? 'sv-btn-primary' : 'sv-btn-outline' }}">Semua</a>
        <a href="{{ route('admin.subscriptions.index', ['status' => 'pending']) }}" class="sv-btn sv-btn-sm {{ request('status') === 'pending' ? 'sv-btn-primary' : 'sv-btn-outline' }}">Pending</a>
        <a href="{{ route('admin.subscriptions.index', ['status' => 'success']) }}" class="sv-btn sv-btn-sm {{ request('status') === 'success' ? 'sv-btn-primary' : 'sv-btn-outline' }}">Approved</a>
        <a href="{{ route('admin.subscriptions.index', ['status' => 'failed']) }}" class="sv-btn sv-btn-sm {{ request('status') === 'failed' ? 'sv-btn-primary' : 'sv-btn-outline' }}">Rejected</a>
    </div>
</div>

<div class="admin-card">
    <div style="overflow-x: auto; width: 100%;">
        <table class="admin-table" style="white-space: nowrap; min-width: 100%;">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>User</th>
                    <th>Paket</th>
                    <th>Metode</th>
                    <th>Amount</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td style="font-family:monospace;font-size:0.75rem;">{{ $payment->invoice_id }}</td>
                    <td>
                        <div style="font-weight:600;color:white;">{{ $payment->user->name }}</div>
                        <div style="font-size:0.75rem;color:var(--sv-text-muted);">{{ $payment->user->email }}</div>
                    </td>
                    <td><span class="admin-badge admin-badge-info">{{ ucfirst($payment->subscription->package ?? '-') }}</span></td>
                    <td>{{ $payment->method_label }}</td>
                    <td style="font-weight:600;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>
                        @if($payment->payment_proof)
                            <img src="{{ asset('storage/' . $payment->payment_proof) }}"
                                 style="width:40px;height:40px;object-fit:cover;border-radius:6px;cursor:pointer;border:1px solid var(--sv-border);"
                                 onclick="showProof('{{ asset('storage/' . $payment->payment_proof) }}')"
                                 title="Klik untuk memperbesar">
                        @else
                            <span style="color:var(--sv-text-muted);font-size:0.8rem;">-</span>
                        @endif
                    </td>
                    <td><span class="admin-badge admin-badge-{{ $payment->status_color }}">{{ $payment->status_label }}</span></td>
                    <td style="font-size:0.8rem;">{{ $payment->created_at->format('d M Y H:i') }}</td>
                    <td>
                        @if($payment->status === 'pending')
                            <div style="display:flex;gap:6px;">
                                <form method="POST" action="{{ route('admin.subscriptions.approve', $payment) }}">
                                    @csrf
                                    <button type="submit" class="sv-btn sv-btn-sm" style="background:rgba(34,197,94,0.15);color:#22c55e;border:1px solid rgba(34,197,94,0.3);" onclick="return confirm('Approve pembayaran ini?')">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.subscriptions.reject', $payment) }}" onsubmit="return confirmReject(event)">
                                    @csrf
                                    <input type="hidden" name="reason" id="reject-reason-{{ $payment->id }}">
                                    <button type="submit" class="sv-btn sv-btn-sm sv-btn-danger">Reject</button>
                                </form>
                            </div>
                        @else
                            <span style="font-size:0.75rem;color:var(--sv-text-muted);">{{ $payment->admin_notes ?? '-' }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" style="text-align:center;padding:40px;color:var(--sv-text-muted);">Tidak ada transaksi</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($payments->hasPages())
        <div class="sv-pagination">{!! $payments->withQueryString()->links('partials.pagination') !!}</div>
    @endif
</div>

{{-- Proof Modal --}}
<div id="proof-modal" onclick="this.style.display='none'" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:9999;display:none;align-items:center;justify-content:center;cursor:pointer;">
    <img id="proof-modal-img" style="max-width:90%;max-height:90%;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
</div>

<script>
function showProof(url) {
    document.getElementById('proof-modal-img').src = url;
    document.getElementById('proof-modal').style.display = 'flex';
}

function confirmReject(e) {
    const reason = prompt('Alasan penolakan (opsional):');
    if (reason === null) { e.preventDefault(); return false; }
    const form = e.target;
    form.querySelector('input[name="reason"]').value = reason || 'Pembayaran ditolak oleh admin.';
    return true;
}
</script>
@endsection
