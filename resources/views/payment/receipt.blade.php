@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:60px 48px;">
    <div style="max-width:550px;margin:0 auto;">
        {{-- Status Banner --}}
        @if($payment->status === 'pending')
            <div style="text-align:center;padding:24px;background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.3);border-radius:12px;margin-bottom:32px;">
                <div style="font-size:2.5rem;margin-bottom:8px;">⏳</div>
                <h2 style="font-size:1.3rem;font-weight:700;color:#eab308;margin-bottom:6px;">Menunggu Verifikasi</h2>
                <p style="color:var(--sv-text-muted);font-size:0.9rem;">Pembayaran Anda sedang diproses oleh admin</p>
            </div>
        @elseif($payment->status === 'success')
            <div style="text-align:center;padding:24px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:12px;margin-bottom:32px;">
                <div style="font-size:2.5rem;margin-bottom:8px;">✓</div>
                <h2 style="font-size:1.3rem;font-weight:700;color:#22c55e;margin-bottom:6px;">Pembayaran Berhasil</h2>
                <p style="color:var(--sv-text-muted);font-size:0.9rem;">Langganan Anda sudah aktif. Selamat menonton!</p>
            </div>
        @else
            <div style="text-align:center;padding:24px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:12px;margin-bottom:32px;">
                <div style="font-size:2.5rem;margin-bottom:8px;">✕</div>
                <h2 style="font-size:1.3rem;font-weight:700;color:#ef4444;margin-bottom:6px;">Pembayaran Ditolak</h2>
                <p style="color:var(--sv-text-muted);font-size:0.9rem;">{{ $payment->admin_notes ?? 'Silakan hubungi admin untuk informasi lebih lanjut.' }}</p>
            </div>
        @endif

        {{-- Receipt Card --}}
        <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:32px;margin-bottom:24px;">
            <div style="text-align:center;margin-bottom:24px;padding-bottom:20px;border-bottom:1px dashed var(--sv-border);">
                <img src="{{ asset('img/logo.png') }}" alt="StreamVid" style="width:160px;height:auto;margin-bottom:12px;">
                <h3 style="font-size:1.1rem;font-weight:700;">Struk Pembayaran</h3>
            </div>

            <div style="display:flex;flex-direction:column;gap:14px;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--sv-text-muted);">Invoice ID</span>
                    <span style="font-weight:600;font-family:monospace;font-size:0.85rem;">{{ $payment->invoice_id }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--sv-text-muted);">Tanggal</span>
                    <span style="font-weight:600;">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--sv-text-muted);">Nama</span>
                    <span style="font-weight:600;">{{ $payment->user->name }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--sv-text-muted);">Email</span>
                    <span style="font-weight:600;">{{ $payment->user->email }}</span>
                </div>

                <div style="border-top:1px solid var(--sv-border);padding-top:14px;"></div>

                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--sv-text-muted);">Paket</span>
                    <span style="font-weight:600;color:var(--sv-accent);">{{ ucfirst($payment->subscription->package) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--sv-text-muted);">Durasi</span>
                    <span style="font-weight:600;">30 Hari</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--sv-text-muted);">Metode</span>
                    <span style="font-weight:600;">{{ $payment->method_label }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--sv-text-muted);">Status</span>
                    <span class="admin-badge admin-badge-{{ $payment->status_color }}" style="padding:4px 12px;border-radius:6px;font-size:0.75rem;font-weight:600;">{{ $payment->status_label }}</span>
                </div>

                <div style="border-top:1px solid var(--sv-border);padding-top:14px;display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;font-size:1.1rem;">Total Bayar</span>
                    <span style="font-weight:900;font-size:1.4rem;color:var(--sv-accent);">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Proof Image --}}
        @if($payment->payment_proof)
        <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:24px;margin-bottom:24px;">
            <h3 style="font-size:0.95rem;font-weight:700;margin-bottom:16px;color:var(--sv-text-secondary);">Bukti Pembayaran</h3>
            <img src="{{ asset('storage/' . $payment->payment_proof) }}" style="width:100%;border-radius:10px;border:1px solid var(--sv-border);">
        </div>
        @endif

        {{-- Actions --}}
        <div style="display:flex;gap:12px;">
            <a href="{{ route('home') }}" class="sv-btn sv-btn-primary" style="flex:1;justify-content:center;padding:14px;">Kembali ke Beranda</a>
            <a href="{{ route('payment.history') }}" class="sv-btn sv-btn-outline" style="flex:1;justify-content:center;padding:14px;">Riwayat Pembayaran</a>
        </div>
    </div>
</div>
@endsection
