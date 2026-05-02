@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:80px 48px;text-align:center;">
    <div style="max-width:500px;margin:0 auto;">
        <div style="width:100px;height:100px;margin:0 auto 24px;border-radius:50%;background:rgba(34,197,94,0.15);display:flex;align-items:center;justify-content:center;">
            <span style="font-size:3rem;">✓</span>
        </div>
        <h1 style="font-size:2rem;font-weight:900;margin-bottom:12px;color:#22c55e;">Pembayaran Berhasil!</h1>
        <p style="color:var(--sv-text-secondary);margin-bottom:32px;">Subscription Anda telah aktif.</p>
        <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:28px;margin-bottom:32px;text-align:left;">
            <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
                <span style="color:var(--sv-text-muted);">Invoice ID</span>
                <span style="font-weight:700;font-family:monospace;color:var(--sv-accent);">{{ $payment->invoice_id }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
                <span style="color:var(--sv-text-muted);">Paket</span>
                <span style="font-weight:600;">{{ ucfirst($subscription->package) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
                <span style="color:var(--sv-text-muted);">Metode</span>
                <span style="font-weight:600;">{{ $payment->method_label }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:14px;">
                <span style="color:var(--sv-text-muted);">Total</span>
                <span style="font-weight:700;color:var(--sv-accent);">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
            </div>
            <div style="border-top:1px solid var(--sv-border);padding-top:14px;margin-top:8px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <span style="color:var(--sv-text-muted);">Mulai</span>
                    <span>{{ $subscription->start_date->format('d M Y') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--sv-text-muted);">Berakhir</span>
                    <span>{{ $subscription->end_date->format('d M Y') }}</span>
                </div>
            </div>
        </div>
        <a href="{{ route('home') }}" class="sv-btn sv-btn-primary sv-btn-lg">🎬 Mulai Nonton</a>
    </div>
</div>
@endsection
