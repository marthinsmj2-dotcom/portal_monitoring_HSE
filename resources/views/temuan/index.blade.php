@extends('layouts.app')

@section('title', $judul)

@section('content')

{{-- Judul halaman --}}
<div class="page-head">
    <div>
        <h4 class="page-title">{{ $judul }}</h4>
        <p class="text-muted mb-0">{{ $subjudul }}</p>
    </div>
    <button type="button" class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalTemuan">
        <i class="bi bi-plus-lg me-1"></i> Tambah Temuan
    </button>
</div>

{{-- Kartu angka: hanya di halaman akumulasi --}}
@if ($stats)
<div class="row g-3 mb-3">
    @foreach ($stats as $s)
    <div class="col-6 col-xl-3">
        <div class="panel kpi-card">
            <div>
                <div class="kpi-label">{{ $s['label'] }}</div>
                <div class="kpi-value {{ $s['warna'] }}">{{ $s['nilai'] }}</div>
            </div>
            <div class="kpi-icon tone-{{ $s['tone'] }}"><i class="bi {{ $s['ikon'] }}"></i></div>
        </div>
    </div>
    @endforeach
</div>
@endif

<div class="panel">
    {{-- Toolbar --}}
    <div class="toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="cariTemuan" class="form-control" placeholder="Cari nomor atau deskripsi temuan...">
        </div>

        <div class="toolbar-end">
            <select class="form-select toolbar-select" id="filterRisiko">
                <option value="">Semua risiko</option>
                @foreach ($daftarRisiko as $r)
                    <option value="{{ $r }}">{{ ucwords(strtolower($r)) }}</option>
                @endforeach
            </select>

            <select class="form-select toolbar-select" id="filterStatus">
                <option value="">Semua status</option>
                @foreach ($daftarStatus as $st)
                    <option value="{{ $st }}">{{ ucwords(strtolower($st)) }}</option>
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
                    <th>Nomor Temuan</th>
                    <th>Sumber Kegiatan</th>
                    <th>Deskripsi</th>
                    <th>Lokasi</th>
                    <th>Risiko</th>
                    <th>PIC</th>
                    <th>Status</th>
                    <th style="width: 40px"></th>
                </tr>
            </thead>
            <tbody id="tabelTemuan">
                @foreach ($temuan as $t)
                <tr data-risiko="{{ $t['risiko'] }}"
                    data-status="{{ $t['status'] }}"
                    data-cari="{{ strtolower($t['nomor'].' '.$t['deskripsi']) }}">
                    <td class="nomor-text">{{ $t['nomor'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $t['sumber'] }}</td>
                    <td class="col-deskripsi">{{ $t['deskripsi'] }}</td>
                    <td class="text-nowrap text-secondary">{{ $t['lokasi'] }}</td>
                    <td><span class="badge-dot no-dot tone-{{ $t['risiko_tone'] }}">{{ $t['risiko'] }}</span></td>
                    <td class="text-nowrap text-secondary">{{ $t['pic'] }}</td>
                    <td><span class="badge-dot no-dot tone-{{ $t['status_tone'] }}">{{ $t['status'] }}</span></td>
                    <td class="text-end">
                        <a href="#" class="row-chevron"><i class="bi bi-chevron-right"></i></a>
                    </td>
                </tr>
                @endforeach

                <tr id="barisKosong" class="{{ count($temuan) ? 'd-none' : '' }}">
                    <td colspan="8" class="text-center text-muted py-4">Tidak ada temuan yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-foot justify-content-end">
        <small class="text-muted">Menampilkan <span id="jumlahTampil">{{ count($temuan) }}</span> temuan terbaru</small>
    </div>
</div>
{{-- Modal Tambah Temuan --}}
@include('partials.modal-temuan')

@endsection

@push('scripts')
<script>
    const cari    = document.getElementById('cariTemuan');
    const risiko  = document.getElementById('filterRisiko');
    const status  = document.getElementById('filterStatus');
    const baris   = document.querySelectorAll('#tabelTemuan tr[data-risiko]');
    const kosong  = document.getElementById('barisKosong');
    const jumlah  = document.getElementById('jumlahTampil');

    function terapkanFilter() {
        const kata = cari.value.trim().toLowerCase();
        let tampil = 0;

        baris.forEach(tr => {
            const cocok =
                (!kata          || tr.dataset.cari.includes(kata)) &&
                (!risiko.value  || tr.dataset.risiko === risiko.value) &&
                (!status.value  || tr.dataset.status === status.value);

            tr.classList.toggle('d-none', !cocok);
            if (cocok) tampil++;
        });

        kosong.classList.toggle('d-none', tampil !== 0);
        jumlah.textContent = tampil;
    }

    [cari, risiko, status].forEach(el => el.addEventListener('input', terapkanFilter));

    // Tombol "Filter" mengembalikan semua filter ke awal
    document.getElementById('btnReset').addEventListener('click', () => {
        cari.value = ''; risiko.value = ''; status.value = '';
        terapkanFilter();
    });
</script>
@endpush