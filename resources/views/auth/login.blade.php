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
            Ribuan Film, <span style="color:var(--sv-accent);">Satu Langganan</span>
        </h2>
        <p style="color:var(--sv-text-muted);text-align:center;font-size:0.95rem;max-width:360px;line-height:1.6;position:relative;z-index:1;">
            Nikmati koleksi film terlengkap dengan kualitas terbaik. Streaming kapan saja, di mana saja.
        </p>
    </div>

    {{-- Right: Login Form --}}
    <div style="flex:1;display:flex;align-items:center;justify-content:center;padding:60px 48px;">
        <div style="width:100%;max-width:420px;">
            <div style="background:var(--sv-bg-card);border:1px solid var(--sv-border);border-radius:16px;padding:40px 36px;">
                <h1 style="font-size:1.8rem;font-weight:900;margin-bottom:8px;">Login</h1>
                <p style="color:var(--sv-text-muted);font-size:0.9rem;margin-bottom:32px;">Masuk ke akun StreamVid Anda</p>

                @if($errors->any())
                    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:12px 16px;margin-bottom:20px;">
                        @foreach($errors->all() as $error)
                            <p style="color:#ef4444;font-size:0.85rem;">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="sv-form-group">
                        <label class="sv-label">Email</label>
                        <input type="email" name="email" class="sv-input" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-label">Password</label>
                        <input type="password" name="password" class="sv-input" required placeholder="••••••••">
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
                        <label style="display:flex;align-items:center;gap:8px;color:var(--sv-text-muted);font-size:0.85rem;cursor:pointer;">
                            <input type="checkbox" name="remember" style="accent-color:var(--sv-accent);">
                            Ingat saya
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="color:var(--sv-accent);font-size:0.85rem;text-decoration:none;">Lupa password?</a>
                        @endif
                    </div>
                    <button type="submit" class="sv-btn sv-btn-primary" style="width:100%;padding:14px;font-size:1rem;">Login</button>
                </form>

                <p style="text-align:center;margin-top:24px;color:var(--sv-text-muted);font-size:0.9rem;">
                    Belum punya akun? <a href="{{ route('register') }}" style="color:var(--sv-accent);text-decoration:none;font-weight:600;">Daftar Sekarang</a>
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
