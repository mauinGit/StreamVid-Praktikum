@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:60px 48px;">
    <div style="max-width:550px;margin:0 auto;">
        <h1 style="font-size:2rem;font-weight:800;margin-bottom:32px;text-align:center;">Konfirmasi Pembayaran</h1>

        {{-- Order Summary --}}
        <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:28px;margin-bottom:24px;">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;color:var(--sv-text-secondary);">Ringkasan Pesanan</h3>
            <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                <span style="color:var(--sv-text-muted);">Nama</span>
                <span style="font-weight:600;">{{ $user->name }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                <span style="color:var(--sv-text-muted);">Email</span>
                <span style="font-weight:600;">{{ $user->email }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                <span style="color:var(--sv-text-muted);">Paket</span>
                <span style="font-weight:600;color:var(--sv-accent);">{{ ucfirst($package) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                <span style="color:var(--sv-text-muted);">Durasi</span>
                <span style="font-weight:600;">30 Hari</span>
            </div>
            <div style="border-top:1px solid var(--sv-border);margin-top:16px;padding-top:16px;display:flex;justify-content:space-between;">
                <span style="font-weight:700;font-size:1.1rem;">Total</span>
                <span style="font-weight:900;font-size:1.3rem;color:var(--sv-accent);">Rp {{ number_format($price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Payment Form --}}
        <form method="POST" action="{{ route('payment.process') }}" enctype="multipart/form-data" id="payment-form">
            @csrf
            <input type="hidden" name="package" value="{{ $package }}">

            {{-- Payment Method --}}
            <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:28px;margin-bottom:24px;">
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;color:var(--sv-text-secondary);">Metode Pembayaran</h3>

                <label style="display:flex;align-items:center;gap:14px;padding:14px;border:1px solid var(--sv-border);border-radius:10px;cursor:pointer;margin-bottom:10px;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--sv-accent)'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--sv-border)'">
                    <input type="radio" name="method" value="transfer_bank" required style="accent-color:var(--sv-accent);width:18px;height:18px;">
                    <div>
                        <div style="font-weight:600;">Transfer Bank</div>
                        <div style="font-size:0.8rem;color:var(--sv-text-muted);">BCA, Mandiri, BNI, BRI</div>
                    </div>
                </label>

                <label style="display:flex;align-items:center;gap:14px;padding:14px;border:1px solid var(--sv-border);border-radius:10px;cursor:pointer;margin-bottom:10px;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--sv-accent)'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--sv-border)'">
                    <input type="radio" name="method" value="e_wallet" style="accent-color:var(--sv-accent);width:18px;height:18px;">
                    <div>
                        <div style="font-weight:600;">E-Wallet</div>
                        <div style="font-size:0.8rem;color:var(--sv-text-muted);">GoPay, OVO, DANA, ShopeePay</div>
                    </div>
                </label>

                <label style="display:flex;align-items:center;gap:14px;padding:14px;border:1px solid var(--sv-border);border-radius:10px;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.borderColor='var(--sv-accent)'" onmouseout="if(!this.querySelector('input').checked)this.style.borderColor='var(--sv-border)'">
                    <input type="radio" name="method" value="qris" style="accent-color:var(--sv-accent);width:18px;height:18px;">
                    <div>
                        <div style="font-weight:600;">QRIS</div>
                        <div style="font-size:0.8rem;color:var(--sv-text-muted);">Scan & Pay</div>
                    </div>
                </label>
            </div>

            {{-- Upload Payment Proof --}}
            <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:28px;margin-bottom:24px;">
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:8px;color:var(--sv-text-secondary);">Upload Bukti Pembayaran</h3>
                <p style="font-size:0.8rem;color:var(--sv-text-muted);margin-bottom:16px;">Upload screenshot/foto bukti transfer Anda. Format: JPG, PNG, WebP. Maks 4MB.</p>

                <div id="proof-preview" style="display:none;margin-bottom:16px;text-align:center;">
                    <img id="proof-img" style="max-width:100%;max-height:300px;border-radius:10px;border:1px solid var(--sv-border);">
                </div>

                <input type="file" name="payment_proof" id="proofInput" accept="image/jpeg,image/png,image/webp" required style="display:none;" onchange="previewProof(this)">
                <button type="button" onclick="document.getElementById('proofInput').click()" class="sv-btn sv-btn-outline" style="width:100%;padding:14px;border:2px dashed var(--sv-border);color:var(--sv-text-muted);" id="upload-btn">
                    + Pilih File Bukti Pembayaran
                </button>
                @error('payment_proof')
                    <p style="color:#ef4444;font-size:0.8rem;margin-top:8px;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="sv-btn sv-btn-primary" style="width:100%;padding:16px;font-size:1.05rem;" id="pay-btn">
                Kirim Pembayaran - Rp {{ number_format($price, 0, ',', '.') }}
            </button>
            <p style="text-align:center;margin-top:12px;font-size:0.8rem;color:var(--sv-text-muted);">
                Pembayaran akan diverifikasi oleh admin dalam 1x24 jam
            </p>
        </form>
    </div>
</div>

<script>
function previewProof(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('proof-img').src = e.target.result;
            document.getElementById('proof-preview').style.display = 'block';
            document.getElementById('upload-btn').textContent = 'Ganti File Bukti';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
