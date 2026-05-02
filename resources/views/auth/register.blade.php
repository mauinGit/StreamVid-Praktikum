@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="min-height:calc(100vh - 68px);display:flex;align-items:center;justify-content:center;padding:40px 20px;">
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
                <button type="submit" class="sv-btn sv-btn-primary" style="width:100%;padding:14px;font-size:1rem;margin-top:8px;">Daftar</button>
            </form>

            <p style="text-align:center;margin-top:24px;color:var(--sv-text-muted);font-size:0.9rem;">
                Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--sv-accent);text-decoration:none;font-weight:600;">Login</a>
            </p>
        </div>
    </div>
</div>
@endsection
