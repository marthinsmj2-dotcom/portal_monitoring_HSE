@extends('layouts.app')

@section('title', 'Kegiatan HSE')

@section('content')

{{-- Judul halaman --}}
<div class="page-head">
    <div>
        <div class="eyebrow"><span class="eyebrow-dot"></span>AUDIT &amp; PATROLI OPERASIONAL</div>
        <h4 class="page-title">Kegiatan HSE</h4>
        <p class="text-muted mb-0">Pencatatan inspeksi, observasi, patrol, dan audit keselamatan kerja secara komprehensif.</p>
    </div>
    <button type="button" class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#modalKegiatan">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kegiatan
    </button>
    @include('partials.modal-kegiatan', ['jenisAwal' => ''])
</div>

@if ($jenisAktif)
<div class="mb-3">
    <span class="count-pill">Jenis: {{ $jenisAktif }}</span>
    <a href="{{ route('kegiatan.index') }}" class="small ms-2 text-decoration-none">Tampilkan semua</a>
</div>
@endif

{{-- Kartu statistik --}}
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

{{-- Toolbar + tabel --}}
<div class="panel mb-3">
    <div class="toolbar">
        <div class="search-box search-wide">
            <i class="bi bi-search"></i>
            <input type="text" id="cariKegiatan" class="form-control" placeholder="Cari nomor, jenis, atau petugas...">
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

        <button type="button" class="btn btn-outline-light-border" id="btnReset">
            <i class="bi bi-funnel me-1"></i> Filter
        </button>

    </div>
</div>

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
                </tr>
            </thead>
            <tbody id="tabelKegiatan">
                @foreach ($kegiatan as $k)
                @php
                    $inisial = collect(explode(' ', $k['petugas']))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
                @endphp
                <tr data-jenis="{{ $k['jenis'] }}"
                    data-status="{{ $k['status'] }}"
                    data-cari="{{ strtolower($k['nomor'].' '.$k['jenis'].' '.$k['petugas'].' '.$k['lokasi']) }}">
                    <td><a href="#" class="nomor-link">{{ $k['nomor'] }}</a></td>
                    <td class="fw-medium">{{ $k['jenis'] }}</td>
                    <td class="text-nowrap text-secondary">
                        <i class="bi bi-calendar3 me-1 text-muted"></i>{{ \Carbon\Carbon::parse($k['tanggal'])->format('d M Y') }}
                    </td>
                    <td class="text-nowrap text-secondary">
                        <i class="bi bi-geo-alt-fill me-1 text-muted"></i>{{ $k['lokasi'] }}
                    </td>
                    <td class="text-nowrap">
                        <span class="mini-avatar tone-{{ $k['avatar'] }}">{{ $inisial }}</span>
                        <span class="fw-medium">{{ $k['petugas'] }}</span>
                    </td>
                    <td>
                        <span class="badge-dot tone-{{ $k['tone'] }}">{{ $k['status'] }}</span>
                    </td>
                </tr>
                @endforeach

                <tr id="barisKosong" class="d-none">
                    <td colspan="6" class="text-center text-muted py-4">Tidak ada kegiatan yang cocok.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-foot">
        <small class="text-muted"><i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i> Data tersinkronisasi otomatis dengan server DHJ</small>
        <small class="text-muted">Menampilkan <span id="jumlahTampil">{{ count($kegiatan) }}</span> kegiatan terbaru</small>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const cari    = document.getElementById('cariKegiatan');
    const jenis   = document.getElementById('filterJenis');
    const status  = document.getElementById('filterStatus');
    const baris   = document.querySelectorAll('#tabelKegiatan tr[data-jenis]');
    const kosong  = document.getElementById('barisKosong');
    const jumlah  = document.getElementById('jumlahTampil');

    function terapkanFilter() {
        const kata = cari.value.trim().toLowerCase();
        let tampil = 0;

        baris.forEach(tr => {
            const cocok =
                (!kata          || tr.dataset.cari.includes(kata)) &&
                (!jenis.value   || tr.dataset.jenis === jenis.value) &&
                (!status.value  || tr.dataset.status === status.value);

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