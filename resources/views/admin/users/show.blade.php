@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>👤 Detail User: {{ $user->name }}</h1>
    <a href="{{ route('admin.users.index') }}" class="sv-btn sv-btn-outline">← Kembali</a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    <div class="admin-card">
        <h3 style="margin-bottom:16px;font-weight:700;">Info User</h3>
        <p style="margin-bottom:8px;"><strong>Nama:</strong> {{ $user->name }}</p>
        <p style="margin-bottom:8px;"><strong>Email:</strong> {{ $user->email }}</p>
        <p style="margin-bottom:8px;"><strong>Joined:</strong> {{ $user->created_at->format('d M Y H:i') }}</p>
    </div>

    <div class="admin-card">
        <h3 style="margin-bottom:16px;font-weight:700;">Subscriptions</h3>
        @forelse($user->subscriptions as $sub)
            <div style="padding:10px;border:1px solid var(--sv-border);border-radius:8px;margin-bottom:8px;">
                <div style="display:flex;justify-content:space-between;">
                    <span class="admin-badge {{ $sub->isActive() ? 'admin-badge-success' : 'admin-badge-danger' }}">{{ ucfirst($sub->status) }}</span>
                    <span style="font-weight:600;">{{ ucfirst($sub->package) }}</span>
                </div>
                <p style="font-size:0.8rem;color:var(--sv-text-muted);margin-top:6px;">{{ $sub->start_date->format('d M Y') }} - {{ $sub->end_date->format('d M Y') }}</p>
            </div>
        @empty
            <p style="color:var(--sv-text-muted);">Belum ada subscription</p>
        @endforelse
    </div>
</div>

<div class="admin-card" style="margin-top:24px;">
    <h3 style="margin-bottom:16px;font-weight:700;">Riwayat Tontonan</h3>
    @forelse($user->watchHistories->take(10) as $wh)
        <div style="display:flex;gap:12px;align-items:center;padding:8px 0;border-bottom:1px solid var(--sv-border);">
            <span style="font-weight:600;">{{ $wh->film->title }}</span>
            <span style="color:var(--sv-text-muted);font-size:0.8rem;margin-left:auto;">{{ $wh->watched_at->diffForHumans() }}</span>
        </div>
    @empty
        <p style="color:var(--sv-text-muted);">Belum ada riwayat</p>
    @endforelse
</div>
@endsection
