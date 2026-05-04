@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:40px 48px;">
    <h1 class="sv-section-title" style="font-size:2rem;margin-bottom:40px;">
        <span class="sv-accent-bar"></span> Profil Saya
    </h1>

    <div style="display:grid;grid-template-columns:300px 1fr;gap:32px;max-width:1000px;">
        {{-- Left: Profile Photo --}}
        <div>
            <div class="admin-card" style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:28px;text-align:center;">
                @php
                    $photoUrl = $user->profile_photo
                        ? asset('storage/' . $user->profile_photo)
                        : null;
                @endphp
                <div style="width:140px;height:140px;margin:0 auto 16px;border-radius:50%;overflow:hidden;border:3px solid var(--sv-accent);display:flex;align-items:center;justify-content:center;background:var(--sv-bg-elevated);">
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <span style="font-size:3.5rem;font-weight:900;color:var(--sv-accent);">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    @endif
                </div>
                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:4px;">{{ $user->name }}</h3>
                <p style="color:var(--sv-text-muted);font-size:0.85rem;margin-bottom:16px;">{{ $user->email }}</p>

                <form method="POST" action="{{ route('profile.photo') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_photo" id="photoInput" accept="image/*" style="display:none;" onchange="this.form.submit()">
                    <button type="button" onclick="document.getElementById('photoInput').click()" class="sv-btn sv-btn-outline" style="width:100%;padding:10px;font-size:0.85rem;border:1px solid var(--sv-border);color:var(--sv-text-secondary);">
                        📷 {{ $photoUrl ? 'Ganti Foto' : 'Upload Foto' }}
                    </button>
                </form>
                @error('profile_photo')
                    <p style="color:#ef4444;font-size:0.8rem;margin-top:6px;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Right: Forms --}}
        <div>
            {{-- Update Info --}}
            <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:28px;margin-bottom:24px;">
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;">📝 Informasi Akun</h3>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf @method('PATCH')
                    <div class="sv-form-group">
                        <label class="sv-label">Nama</label>
                        <input type="text" name="name" class="sv-input" value="{{ old('name', $user->name) }}" required>
                        @error('name') <p class="sv-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-label">Email</label>
                        <input type="email" name="email" class="sv-input" value="{{ old('email', $user->email) }}" required>
                        @error('email') <p class="sv-error">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="sv-btn sv-btn-primary">Simpan Perubahan</button>
                </form>
            </div>

            {{-- Subscription Status --}}
            <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:28px;margin-bottom:24px;">
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:20px;">💳 Status Langganan</h3>
                @if($activeSubscription)
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                        <span style="color:var(--sv-text-muted);">Paket</span>
                        <span style="font-weight:700;color:var(--sv-accent);">{{ ucfirst($activeSubscription->package) }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                        <span style="color:var(--sv-text-muted);">Status</span>
                        <span class="admin-badge" style="background:rgba(34,197,94,0.15);color:#22c55e;padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;">Active</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                        <span style="color:var(--sv-text-muted);">Mulai</span>
                        <span>{{ $activeSubscription->start_date->format('d M Y') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                        <span style="color:var(--sv-text-muted);">Berakhir</span>
                        <span>{{ $activeSubscription->end_date->format('d M Y') }}</span>
                    </div>
                    <form method="POST" action="{{ route('profile.cancel-subscription') }}" onsubmit="return confirm('Yakin ingin mencabut langganan? Akses menonton film akan dihentikan.')">
                        @csrf
                        <button type="submit" class="sv-btn" style="width:100%;padding:12px;background:rgba(239,68,68,0.1);color:#ef4444;border:1px solid rgba(239,68,68,0.3);font-weight:600;">
                            🚫 Cabut Langganan
                        </button>
                    </form>
                @else
                    <div style="text-align:center;padding:20px 0;">
                        <p style="color:var(--sv-text-muted);margin-bottom:16px;">Anda belum berlangganan</p>
                        <a href="{{ route('subscription.index') }}" class="sv-btn sv-btn-primary">Mulai Berlangganan</a>
                    </div>
                @endif
            </div>

            {{-- Delete Account --}}
            <div style="background:var(--sv-bg-card);border:1px solid rgba(239,68,68,0.2);border-radius:16px;padding:28px;">
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:8px;color:#ef4444;">⚠️ Hapus Akun</h3>
                <p style="color:var(--sv-text-muted);font-size:0.85rem;margin-bottom:16px;">Setelah akun dihapus, semua data akan hilang secara permanen.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('PERINGATAN: Akun akan dihapus secara permanen. Lanjutkan?')">
                    @csrf @method('DELETE')
                    <div class="sv-form-group">
                        <label class="sv-label">Konfirmasi Password</label>
                        <input type="password" name="password" class="sv-input" required placeholder="Masukkan password Anda">
                    </div>
                    <button type="submit" class="sv-btn" style="background:rgba(239,68,68,0.15);color:#ef4444;border:1px solid rgba(239,68,68,0.3);">Hapus Akun</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
