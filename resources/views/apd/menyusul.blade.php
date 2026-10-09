@extends('layouts.app')

@section('title', $judul)

@section('content')
<div class="page-head">
    <div>
        <h4 class="page-title">{{ $judul }}</h4>
        <p class="text-muted mb-0">Halaman ini sedang disiapkan.</p>
    </div>
</div>

<div class="panel">
    <div class="text-center text-muted py-5">
        <i class="bi bi-tools fs-1 d-block mb-2"></i>
        Desain halaman {{ $judul }} belum dibuat.
    </div>
</div>
@endsection