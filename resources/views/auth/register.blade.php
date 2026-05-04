@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="min-height:calc(100vh - 68px);display:flex;">

    {{-- Left: Image + Tagline --}}
    <div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:60px 48px;background:linear-gradient(135deg, rgba(255,92,0,0.08) 0%, rgba(10,10,10,1) 100%);position:relative;overflow:hidden;">
        {{-- Decorative circles --}}
        <div style="position:absolute;top:-80px;left:-80px;width:300px;height:300px;border-radius:50%;background:rgba(255,92,0,0.06);"></div>
        <div style="position:absolute;bottom:-60px;right:-60px;width:250px;height:250px;border-radius:50%;background:rgba(255,92,0,0.04);"></div>

        <img src="{{ asset('img/welcome.png') }}" alt="Welcome" style="max-width:380px;width:100%;margin-bottom:40px;border-radius:16px;position:relative;z-index:1;">

        <h2 style="font-size:2rem;font-weight:900;text-align:center;margin-bottom:12px;position:relative;z-index:1;">
            Bergabung dan Nikmati <span style="color:var(--sv-accent);">Semua Konten Film Premium</span>
        </h2>
        <p style="color:var(--sv-text-muted);text-align:center;font-size:0.95rem;max-width:360px;line-height:1.6;position:relative;z-index:1;">
            Daftar sekarang dan akses ribuan film berkualitas tinggi tanpa batas.
        </p>
    </div>

    {{-- Right: Register Form --}}
    <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:60px 48px;">
        <div style="width:100%;max-width:420px;">
            <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:40px 36px;">
                <h1 style="font-size:1.8rem;font-weight:900;margin-bottom:8px;">Daftar Akun</h1>
                <p style="color:var(--sv-text-muted);font-size:0.9rem;margin-bottom:32px;">Buat akun StreamVid baru</p>

                @if($errors->any())
                    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:12px 16px;margin-bottom:20px;">
                        @foreach($errors->all() as $error)
                            <p style="color:#ef4444;font-size:0.85rem;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="sv-form-group">
                        <label class="sv-label">Nama</label>
                        <input type="text" name="name" class="sv-input" value="{{ old('name') }}" required autofocus placeholder="Nama lengkap">
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-label">Email</label>
                        <input type="email" name="email" class="sv-input" value="{{ old('email') }}" required placeholder="nama@email.com">
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-label">Password</label>
                        <input type="password" name="password" class="sv-input" required placeholder="Minimal 8 karakter">
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="sv-input" required placeholder="Ulangi password">
                    </div>

                    {{-- Terms & Privacy Checkbox --}}
                    <div class="sv-form-group">
                        <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;color:var(--sv-text-muted);font-size:0.85rem;line-height:1.5;">
                            <input type="checkbox" name="terms" required style="accent-color:var(--sv-accent);margin-top:3px;flex-shrink:0;">
                            <span>Saya menyetujui <a href="#" style="color:var(--sv-accent);text-decoration:none;">Syarat & Ketentuan</a> dan <a href="#" style="color:var(--sv-accent);text-decoration:none;">Kebijakan Privasi</a> StreamVid</span>
                        </label>
                        @error('terms')
                            <p style="color:#ef4444;font-size:0.8rem;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="sv-btn sv-btn-primary" style="width:100%;padding:14px;font-size:1rem;">Daftar</button>
                </form>

                <p style="text-align:center;margin-top:24px;color:var(--sv-text-muted);font-size:0.9rem;">
                    Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--sv-accent);text-decoration:none;font-weight:600;">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .sv-mt-nav { flex-direction: column !important; }
    }
</style>
@endsection
