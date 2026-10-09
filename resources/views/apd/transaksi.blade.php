@extends('layouts.app')

@section('title', 'Transaksi Stok')

@section('content')

{{-- Judul halaman --}}
<div class="page-head">
    <div>
        <h4 class="page-title">Alat Pelindung Diri</h4>
        <p class="text-muted mb-0">Kelola data dan pergerakan stok APD secara sederhana.</p>
    </div>
    <button type="button" class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalTransaksi">
        <i class="bi bi-plus-lg me-1"></i> Catat Transaksi
    </button>
</div>

<div class="panel">
    {{-- Toolbar --}}
    <div class="toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="cariTransaksi" class="form-control" placeholder="Cari transaksi atau APD...">
        </div>

        <div class="toolbar-end">
            <select class="form-select toolbar-select" id="filterJenis">
                <option value="">Semua transaksi</option>
                <option value="MASUK">Stok masuk</option>
                <option value="KELUAR">Stok keluar</option>
                <option value="PENYESUAIAN">Penyesuaian</option>
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
                    <th>Nomor Transaksi</th>
                    <th>APD</th>
                    <th>Jenis Transaksi</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Penerima</th>
                    <th style="width: 40px"></th>
                </tr>
            </thead>
            <tbody id="tabelTransaksi">
                @foreach ($transaksi as $t)
                <tr data-jenis="{{ $t['jenis'] }}"
                    data-cari="{{ strtolower($t['nomor'].' '.$t['apd'].' '.($t['penerima'] ?? '')) }}">
                    <td class="nomor-text">{{ $t['nomor'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $t['apd'] }}</td>
                    <td><span class="badge-dot no-dot tone-{{ $t['tone'] }}">{{ $t['label'] }}</span></td>
                    <td class="text-secondary">{{ $t['jumlah'] }}</td>
                    <td class="text-nowrap text-secondary">{{ \Carbon\Carbon::parse($t['tanggal'])->format('d M Y') }}</td>
                    <td class="text-nowrap text-secondary">{{ $t['penerima'] ?? '—' }}</td>
                    <td class="text-end">
                        <a href="#" class="row-chevron"><i class="bi bi-chevron-right"></i></a>
                    </td>
                </tr>
                @endforeach

                <tr id="barisKosong" class="{{ count($transaksi) ? 'd-none' : '' }}">
                    <td colspan="7" class="text-center text-muted py-4">Tidak ada transaksi yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
{{-- Modal Catat Transaksi --}}
@include('partials.modal-transaksi', ['daftarApd' => $daftarApd])
@endsection

@push('scripts')
<script>
    const cari   = document.getElementById('cariTransaksi');
    const jenis  = document.getElementById('filterJenis');
    const baris  = document.querySelectorAll('#tabelTransaksi tr[data-jenis]');
    const kosong = document.getElementById('barisKosong');

    function terapkanFilter() {
        const kata = cari.value.trim().toLowerCase();
        let tampil = 0;

        baris.forEach(tr => {
            const cocok =
                (!kata        || tr.dataset.cari.includes(kata)) &&
                (!jenis.value || tr.dataset.jenis === jenis.value);

            tr.classList.toggle('d-none', !cocok);
            if (cocok) tampil++;
        });

        kosong.classList.toggle('d-none', tampil !== 0);
    }

    [cari, jenis].forEach(el => el.addEventListener('input', terapkanFilter));

    // Tombol "Filter" mengembalikan semua filter ke awal
    document.getElementById('btnReset').addEventListener('click', () => {
        cari.value = ''; jenis.value = '';
        terapkanFilter();
    });
</script>
@endpush