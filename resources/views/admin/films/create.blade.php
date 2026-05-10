@extends('admin.layouts.app')
@section('admin-content')
<div class="admin-header">
    <h1>Tambah Film</h1>
    <a href="{{ route('admin.films.index') }}" class="sv-btn sv-btn-outline">← Kembali</a>
</div>

<div class="admin-card" style="max-width:700px;">
    @if($errors->any())
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:8px;padding:12px 16px;margin-bottom:20px;">
            @foreach($errors->all() as $error)
                <p style="color:#ef4444;font-size:0.85rem;">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.films.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.films._form')
        <button type="submit" class="sv-btn sv-btn-primary" style="margin-top:8px;">Simpan Film</button>
    </form>
</div>
@endsection
