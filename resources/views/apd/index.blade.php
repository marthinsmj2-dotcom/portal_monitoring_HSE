@extends('layouts.app')

@section('title', 'Alat Pelindung Diri')

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
    {{-- Toolbar --}}
    <div class="toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="cariApd" class="form-control" placeholder="Cari kode atau nama APD...">
        </div>

        <div class="toolbar-end">
            <select class="form-select toolbar-select" id="filterKategori">
                <option value="">Semua kategori</option>
                @foreach ($daftarKategori as $k)
                    <option value="{{ $k }}">{{ $k }}</option>
                @endforeach
            </select>

            <button type="button" class="btn btn-outline-light-border" id="btnReset">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="table-responsive">
        <table class="table table-kegiatan table-temuan align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode APD</th>
                    <th>Nama APD</th>
                    <th>Kategori</th>
                    <th>Ukuran</th>
                    <th>Satuan</th>
                    <th>Lokasi Penyimpanan</th>
                    <th>Stok Minimum</th>
                    <th>Stok Saat Ini</th>
                    <th style="width: 40px"></th>
                </tr>
            </thead>
            <tbody id="tabelApd">
                @foreach ($apd as $a)
                <tr data-kategori="{{ $a['kategori'] }}"
                    data-cari="{{ strtolower($a['kode'].' '.$a['nama']) }}">
                    <td class="nomor-text">{{ $a['kode'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $a['nama'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $a['kategori'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $a['ukuran'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $a['satuan'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $a['lokasi'] }}</td>
                    <td class="text-secondary">{{ $a['minimum'] }}</td>
                    <td class="{{ $a['stok'] <= $a['minimum'] ? 'stok-rendah' : 'stok-aman' }}">{{ $a['stok'] }}</td>
                    <td class="text-end">
                        <a href="#" class="row-chevron"><i class="bi bi-chevron-right"></i></a>
                    </td>
                </tr>
                @endforeach

                <tr id="barisKosong" class="{{ count($apd) ? 'd-none' : '' }}">
                    <td colspan="9" class="text-center text-muted py-4">Tidak ada data APD yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
{{-- Modal Tambah Data APD --}}
@include('partials.modal-apd')

@push('scripts')
<script>
    const cari     = document.getElementById('cariApd');
    const kategori = document.getElementById('filterKategori');
    const baris    = document.querySelectorAll('#tabelApd tr[data-kategori]');
    const kosong   = document.getElementById('barisKosong');

    function terapkanFilter() {
        const kata = cari.value.trim().toLowerCase();
        let tampil = 0;

        baris.forEach(tr => {
            const cocok =
                (!kata            || tr.dataset.cari.includes(kata)) &&
                (!kategori.value  || tr.dataset.kategori === kategori.value);

            tr.classList.toggle('d-none', !cocok);
            if (cocok) tampil++;
        });

        kosong.classList.toggle('d-none', tampil !== 0);
    }

    [cari, kategori].forEach(el => el.addEventListener('input', terapkanFilter));

    // Tombol "Filter" mengembalikan semua filter ke awal
    document.getElementById('btnReset').addEventListener('click', () => {
        cari.value = ''; kategori.value = '';
        terapkanFilter();
    });
</script>
@endpush