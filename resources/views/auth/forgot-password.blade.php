@extends('layouts.app')
@section('content')
<div class="sv-auth-container" style="min-height:100vh; display:flex; align-items:center; justify-content:center; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; background: url('{{ asset('img/welcome.png') }}') no-repeat center center; background-size: cover; padding: 20px;">
    
    {{-- Black Overlay --}}
    <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.65);"></div>

    {{-- Forgot Password Form Card --}}
    <div style="position: relative; z-index: 2; background: rgba(20,20,20,0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 48px 40px; width: 100%; max-width: 420px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.7);">
        
        {{-- Center Logo --}}
        <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 32px;">
            <img src="{{ asset('img/Logo Tegak.png') }}" alt="StreamVid" style="width: 180px; height: auto;">
        </div>

        <div style="margin-bottom: 32px; text-align: center;">
            <h2 style="font-size: 1.6rem; font-weight: 800; margin: 0 0 8px; color: #fff;">Lupa Password</h2>
            <p style="color: var(--sv-text-muted); font-size: 0.9rem; line-height: 1.5;">Tidak masalah. Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password.</p>
        </div>

        <!-- Session Status -->
        @if(session('status'))
            <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
                <p style="color: #22c55e; font-size: 0.85rem; margin: 0;">{{ session('status') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 12px 16px; margin-bottom: 20px;">
                @foreach($errors->all() as $error)
                    <p style="color: #ef4444; font-size: 0.85rem; margin: 0;">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="sv-form-group" style="margin-bottom: 24px;">
                <label class="sv-label">Email</label>
                <input id="email" class="sv-input" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1);">
            </div>

            <button type="submit" class="sv-btn sv-btn-primary" style="width: 100%; padding: 14px; font-size: 1rem; font-weight: 600;">
                Kirim Tautan Reset
            </button>
        </form>

        <p style="text-align: center; margin-top: 24px; color: var(--sv-text-muted); font-size: 0.9rem;">
            Kembali ke <a href="{{ route('login') }}" style="color: var(--sv-accent); text-decoration: none; font-weight: 600;">Login</a>
        </p>
    </div>
</div>

<style>
    /* Hide regular navbar and footer on this page */
    #navbar, .sv-footer { display: none !important; }
    body { overflow: hidden; background: #1C1B1B; }
    
    @media (max-width: 640px) {
        .sv-auth-container { padding: 16px !important; }
        .sv-auth-container > div:last-child { padding: 32px 24px !important; }
    }
</style>
@endsection
