@extends('layouts.app')

@section('title', 'Inspeksi APAR')

@section('content')

{{-- Judul halaman --}}
<div class="page-head">
    <div>
        <h4 class="page-title">Inspeksi APAR</h4>
        <p class="text-muted mb-0">Daftar kegiatan inspeksi apar terbaru.</p>
    </div>
    <button type="button" class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalKegiatan">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kegiatan
    </button>
</div>

{{-- Toolbar pencarian dan filter --}}
<div class="panel mb-3">
    <div class="toolbar">
        <div class="search-box search-wide">
            <i class="bi bi-search"></i>
            <input type="text" id="cariKegiatan" class="form-control" placeholder="Cari nomor, jenis, petugas...">
        </div>

        <select class="form-select toolbar-select" id="filterJenis">
            <option value="">Semua jenis</option>
            @foreach ($daftarJenis as $j)
                <option value="{{ $j }}">{{ $j }}</option>
            @endforeach
        </select>

        <select class="form-select toolbar-select" id="filterStatus">
            <option value="">Semua status</option>
            @foreach ($daftarStatus as $st)
                <option value="{{ $st }}">{{ ucwords(strtolower($st)) }}</option>
            @endforeach
        </select>

    </div>
</div>

{{-- Tabel inspeksi APAR (tampilan polos: tanpa ikon dan avatar) --}}
<div class="panel">
    <div class="table-responsive">
        <table class="table table-kegiatan align-middle mb-0">
            <thead>
                <tr>
                    <th>Nomor Kegiatan</th>
                    <th>Jenis Kegiatan</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th style="width: 40px"></th>
                </tr>
            </thead>
            <tbody id="tabelKegiatan">
                @foreach ($kegiatan as $k)
                <tr data-jenis="{{ $k['jenis'] }}"
                    data-status="{{ $k['status'] }}"
                    data-cari="{{ strtolower($k['nomor'].' '.$k['jenis'].' '.$k['petugas'].' '.$k['lokasi']) }}">
                    <td class="text-nowrap"><a href="#" class="nomor-link">{{ $k['nomor'] }}</a></td>
                    <td class="text-nowrap">{{ $k['jenis'] }}</td>
                    <td class="text-nowrap text-secondary">{{ \Carbon\Carbon::parse($k['tanggal'])->format('d M Y') }}</td>
                    <td class="text-nowrap text-secondary">{{ $k['lokasi'] }}</td>
                    <td class="text-nowrap">{{ $k['petugas'] }}</td>
                    <td>
                        <span class="badge-dot no-dot tone-{{ $k['tone'] }}">{{ $k['status'] }}</span>
                    </td>
                    <td class="text-end">
                        <a href="#" class="row-chevron"><i class="bi bi-chevron-right"></i></a>
                    </td>
                </tr>
                @endforeach

                <tr id="barisKosong" class="{{ count($kegiatan) ? 'd-none' : '' }}">
                    <td colspan="7" class="text-center text-muted py-4">Tidak ada kegiatan yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-foot justify-content-end">
        <small class="text-muted">Menampilkan <span id="jumlahTampil">{{ count($kegiatan) }}</span> kegiatan terbaru</small>
    </div>
</div>

{{-- Modal Tambah Kegiatan (jenis otomatis Inspeksi APAR) --}}
@include('partials.modal-kegiatan', ['jenisAwal' => $info['jenis']])
@endsection

@push('scripts')
<script>
    const cari   = document.getElementById('cariKegiatan');
    const jenis  = document.getElementById('filterJenis');
    const status = document.getElementById('filterStatus');
    const baris  = document.querySelectorAll('#tabelKegiatan tr[data-jenis]');
    const kosong = document.getElementById('barisKosong');
    const jumlah = document.getElementById('jumlahTampil');

    function terapkanFilter() {
        const kata = cari.value.trim().toLowerCase();
        let tampil = 0;

        baris.forEach(tr => {
            const cocok =
                (!kata         || tr.dataset.cari.includes(kata)) &&
                (!jenis.value  || tr.dataset.jenis === jenis.value) &&
                (!status.value || tr.dataset.status === status.value);

            tr.classList.toggle('d-none', !cocok);
            if (cocok) tampil++;
        });

        kosong.classList.toggle('d-none', tampil !== 0);
        jumlah.textContent = tampil;
    }

    [cari, jenis, status].forEach(el => el.addEventListener('input', terapkanFilter));

    // Tombol "Filter" mengembalikan semua filter ke awal
    document.getElementById('btnReset').addEventListener('click', () => {
        cari.value = ''; jenis.value = ''; status.value = '';
        terapkanFilter();
    });
</script>
@endpush