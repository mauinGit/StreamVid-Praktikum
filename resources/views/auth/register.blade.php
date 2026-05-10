@extends('layouts.app')
@section('content')
<div class="sv-auth-container" style="min-height:100vh; display:flex; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; background: #1C1B1B;">

    {{-- Left: Full Background Image (65%) --}}
    <div style="flex: 0 0 65%; background: url('{{ asset('img/welcome.png') }}') no-repeat center center; background-size: cover; position: relative; display: flex; flex-direction: column; justify-content: space-between; padding: 60px;">
        {{-- Black Overlay (35% opacity) --}}
        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.35);"></div>
        {{-- Gradient for color transition --}}
        <div style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(28,27,27,0.8) 0%, rgba(28,27,27,0) 100%);"></div>
        
        {{-- Top Left Logo --}}
        <div style="position: relative; z-index: 2; width: 100%;">
            <img src="{{ asset('img/logo.png') }}" alt="StreamVid" style="width: 240px; height: auto;">
        </div>

        {{-- Bottom Left Tagline --}}
        <div style="position: relative; z-index: 2; max-width: 600px;">
            <h2 style="font-size: 3rem; font-weight: 900; line-height: 1.1; margin: 0; color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                Bergabung dan Nikmati<br><span style="color: var(--sv-accent);">Semua Konten Film Premium</span>
            </h2>
        </div>
    </div>

    {{-- Right: Register Form (35%) --}}
    <div style="flex: 0 0 35%; background: #1C1B1B; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; overflow-y: auto;">
        <div style="width: 100%; max-width: 360px;">
            {{-- Center Logo (Tegak) --}}
            <div style="text-align: center; margin-bottom: 40px;">
                <img src="{{ asset('img/Logo Tegak.png') }}" alt="StreamVid" style="width: 280px; height: auto;">
            </div>

            <div style="margin-bottom: 32px;">
                <span style="color: var(--sv-accent); font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Register</span>
                <h2 style="font-size: 1.8rem; font-weight: 900; margin: 8px 0;">Buat Akun Baru</h2>
                <p style="color: var(--sv-text-muted); font-size: 0.9rem; line-height: 1.5;">Mulai petualangan sinematik Anda hari ini.</p>
            </div>

            @if($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
                    @foreach($errors->all() as $error)
                        <p style="color: #ef4444; font-size: 0.85rem;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="sv-form-group">
                    <label class="sv-label">Nama</label>
                    <input type="text" name="name" class="sv-input" value="{{ old('name') }}" required autofocus placeholder="Nama lengkap" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                </div>
                <div class="sv-form-group">
                    <label class="sv-label">Email</label>
                    <input type="email" name="email" class="sv-input" value="{{ old('email') }}" required placeholder="nama@email.com" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                </div>
                <div class="sv-form-group">
                    <label class="sv-label">Password</label>
                    <input type="password" name="password" class="sv-input" required placeholder="Minimal 8 karakter" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                </div>
                <div class="sv-form-group">
                    <label class="sv-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="sv-input" required placeholder="Ulangi password" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
                </div>

                {{-- Terms & Privacy Checkbox --}}
                <div class="sv-form-group">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; color: var(--sv-text-muted); font-size: 0.8rem; line-height: 1.4;">
                        <input type="checkbox" name="terms" required style="accent-color: var(--sv-accent); margin-top: 3px; flex-shrink: 0;">
                        <span>Saya menyetujui <a href="#" style="color: var(--sv-accent); text-decoration: none;">Syarat & Ketentuan</a> dan <a href="#" style="color: var(--sv-accent); text-decoration: none;">Kebijakan Privasi</a> StreamVid</span>
                    </label>
                    @error('terms')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="sv-btn sv-btn-primary" style="width: 100%; padding: 14px; font-size: 1rem;">Daftar</button>
            </form>

            <p style="text-align: center; margin-top: 24px; color: var(--sv-text-muted); font-size: 0.9rem;">
                Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--sv-accent); text-decoration: none; font-weight: 600;">Login</a>
            </p>
        </div>
    </div>
</div>

<style>
    /* Hide regular navbar and footer on this page */
    #navbar, .sv-footer { display: none !important; }
    body { overflow: hidden; }
    
    @media (max-width: 1024px) {
        div[style*="flex: 0 0 65%"] { display: none; }
        div[style*="flex: 0 0 35%"] { flex: 0 0 100% !important; }
        body { overflow: auto; }
    }
</style>
@endsection
