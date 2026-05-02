@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>📄 Export Reports</h1>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
    <div class="admin-card" style="text-align:center;">
        <div style="font-size:3rem;margin-bottom:16px;">👥</div>
        <h3 style="margin-bottom:8px;font-weight:700;">Laporan Users</h3>
        <p style="color:var(--sv-text-muted);font-size:0.85rem;margin-bottom:20px;">Export daftar semua user dan status subscription</p>
        <a href="{{ route('admin.reports.export', 'users') }}" class="sv-btn sv-btn-primary">📥 Download PDF</a>
    </div>
    <div class="admin-card" style="text-align:center;">
        <div style="font-size:3rem;margin-bottom:16px;">🎬</div>
        <h3 style="margin-bottom:8px;font-weight:700;">Laporan Film</h3>
        <p style="color:var(--sv-text-muted);font-size:0.85rem;margin-bottom:20px;">Export daftar semua film dan statistik views</p>
        <a href="{{ route('admin.reports.export', 'films') }}" class="sv-btn sv-btn-primary">📥 Download PDF</a>
    </div>
    <div class="admin-card" style="text-align:center;">
        <div style="font-size:3rem;margin-bottom:16px;">💳</div>
        <h3 style="margin-bottom:8px;font-weight:700;">Laporan Subscriptions</h3>
        <p style="color:var(--sv-text-muted);font-size:0.85rem;margin-bottom:20px;">Export riwayat subscription dan pembayaran</p>
        <a href="{{ route('admin.reports.export', 'subscriptions') }}" class="sv-btn sv-btn-primary">📥 Download PDF</a>
    </div>
</div>
@endsection
