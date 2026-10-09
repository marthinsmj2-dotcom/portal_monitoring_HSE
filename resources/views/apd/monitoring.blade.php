@extends('layouts.app')

@section('title', 'Monitoring Stok')

@section('content')

{{-- Judul halaman --}}
<div class="page-head">
    <div>
        <h4 class="page-title">Alat Pelindung Diri</h4>
        <p class="text-muted mb-0">Kelola data dan pergerakan stok APD secara sederhana.</p>
    </div>
    <button type="button" class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalApd">
        <i class="bi bi-plus-lg me-1"></i> Tambah Data APD
    </button>
</div>

<div class="panel">
    <div class="panel-head align-items-center">
        <div>
            <div class="panel-title">APD memerlukan perhatian</div>
            <small class="text-muted">Hanya menampilkan stok pada atau di bawah batas minimum.</small>
        </div>
        <span class="count-pill pill-amber">{{ count($perhatian) }} ITEM</span>
    </div>

    <div class="table-responsive">
        <table class="table table-kegiatan table-temuan align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode APD</th>
                    <th>Nama APD</th>
                    <th>Lokasi</th>
                    <th>Stok Minimum</th>
                    <th>Stok Saat Ini</th>
                    <th>Status</th>
                    <th style="width: 40px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perhatian as $a)
                <tr>
                    <td class="nomor-text">{{ $a['kode'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $a['nama'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $a['lokasi'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $a['minimum'] }} {{ $a['satuan'] }}</td>
                    <td class="text-nowrap stok-aman">{{ $a['stok'] }} {{ $a['satuan'] }}</td>
                    <td><span class="badge-dot no-dot tone-{{ $a['tone'] }}">{{ $a['status'] }}</span></td>
                    <td class="text-end">
                        <a href="#" class="row-chevron"><i class="bi bi-chevron-right"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Semua stok APD masih dalam batas aman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{-- Modal Tambah Data APD --}}
@include('partials.modal-apd')
@endsection