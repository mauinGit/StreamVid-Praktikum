@extends('layouts.app')
@section('content')
<div class="sv-mt-nav" style="padding:60px 48px;">
    <div style="text-align:center;margin-bottom:48px;">
        <h1 style="font-size:2.5rem;font-weight:900;margin-bottom:12px;">Pilih Paket Langganan</h1>
        <p style="color:var(--sv-text-secondary);font-size:1.1rem;">Nikmati streaming tanpa batas dengan paket yang sesuai kebutuhan Anda</p>
    </div>

    @if($activeSubscription)
        <div style="max-width:600px;margin:0 auto 40px;padding:20px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:12px;text-align:center;">
            <p style="color:#22c55e;font-weight:600;">✓ Anda sudah berlangganan paket <strong>{{ ucfirst($activeSubscription->package) }}</strong></p>
            <p style="color:var(--sv-text-muted);font-size:0.85rem;margin-top:6px;">Berlaku sampai {{ $activeSubscription->end_date->format('d M Y') }}</p>
        </div>
    @endif

    {{-- Pricing Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;max-width:1000px;margin:0 auto;">
        @foreach($packages as $pkg)
        <div style="
            background: var(--sv-bg-card);
            border: 1px solid {{ isset($pkg['popular']) ? 'var(--sv-accent)' : 'var(--sv-border)' }};
            border-radius: 16px;
            padding: 36px 28px;
            position: relative;
            transition: all 0.3s;
            {{ isset($pkg['popular']) ? 'transform:scale(1.05);box-shadow:0 0 30px var(--sv-accent-glow);' : '' }}
        " onmouseover="this.style.transform='scale(1.05)';this.style.boxShadow='0 16px 40px rgba(0,0,0,0.4)'" onmouseout="this.style.transform='{{ isset($pkg['popular']) ? 'scale(1.05)' : 'scale(1)' }}';this.style.boxShadow='{{ isset($pkg['popular']) ? '0 0 30px var(--sv-accent-glow)' : 'none' }}'">

            @if(isset($pkg['popular']))
                <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--sv-accent);color:white;padding:4px 20px;border-radius:20px;font-size:0.75rem;font-weight:700;">POPULER</div>
            @endif

            <h3 style="font-size:1.3rem;font-weight:700;margin-bottom:8px;">{{ $pkg['label'] }}</h3>
            <div style="margin-bottom:24px;">
                <span style="font-size:2.5rem;font-weight:900;">Rp {{ number_format($pkg['price'], 0, ',', '.') }}</span>
                <span style="color:var(--sv-text-muted);font-size:0.85rem;">/bulan</span>
            </div>

            <ul style="list-style:none;margin-bottom:32px;">
                @foreach($pkg['features'] as $feature)
                <li style="padding:8px 0;color:var(--sv-text-secondary);font-size:0.9rem;display:flex;align-items:center;gap:10px;">
                    <span style="color:#22c55e;">✓</span> {{ $feature }}
                </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('subscription.checkout') }}">
                @csrf
                <input type="hidden" name="package" value="{{ $pkg['name'] }}">
                <button type="submit" class="sv-btn {{ isset($pkg['popular']) ? 'sv-btn-primary' : 'sv-btn-outline' }}" style="width:100%;padding:14px;">
                    {{ $activeSubscription && $activeSubscription->package === $pkg['name'] ? 'Paket Aktif' : 'Pilih Paket' }}
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection
