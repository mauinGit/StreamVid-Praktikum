@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:40px 48px; max-width: 900px; margin: 0 auto;">
    <h1 class="sv-section-title" style="font-size:2rem;margin-bottom:32px;">
        <span class="sv-accent-bar"></span> Riwayat Pembayaran
    </h1>

    @if($payments->isEmpty())
        <div style="text-align:center;padding:80px 0;">
            <p style="font-size:1.2rem;color:var(--sv-text-muted);margin-bottom:24px;">Belum ada riwayat pembayaran</p>
            <a href="{{ route('subscription.index') }}" class="sv-btn sv-btn-primary">Mulai Berlangganan</a>
        </div>
    @else
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($payments as $payment)
            <a href="{{ route('payment.receipt', ['invoice' => $payment->invoice_id]) }}" style="text-decoration:none;color:inherit;display:flex;gap:20px;padding:20px;background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:12px;transition:all 0.2s;align-items:center;" onmouseover="this.style.borderColor='var(--sv-accent)'" onmouseout="this.style.borderColor='var(--sv-border)'">
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
                        <span style="font-weight:700;">{{ ucfirst($payment->subscription->package ?? '-') }}</span>
                        <span class="admin-badge admin-badge-{{ $payment->status_color }}" style="padding:3px 10px;border-radius:6px;font-size:0.7rem;font-weight:600;">{{ $payment->status_label }}</span>
                    </div>
                    <p style="font-size:0.85rem;color:var(--sv-text-muted);">{{ $payment->invoice_id }} &middot; {{ $payment->method_label }}</p>
                    <p style="font-size:0.8rem;color:var(--sv-text-muted);">{{ $payment->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    <span style="font-weight:800;font-size:1.1rem;color:var(--sv-accent);">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
            </a>
            @endforeach
        </div>

        @if($payments->hasPages())
            <div class="sv-pagination">
                {!! $payments->links('partials.pagination') !!}
            </div>
        @endif
    @endif
</div>
@endsection
