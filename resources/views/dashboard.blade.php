@extends('layouts.app')

@section('title', 'Dashboard HSE')

@section('content')
<div class="mb-4">
    <h4 class="page-title">Dashboard HSE</h4>
    <p class="text-muted mb-0">Ringkasan kondisi kesehatan, keselamatan, dan lingkungan kerja.</p>
</div>

{{-- Kartu statistik --}}
<div class="row g-3 mb-3">
    @foreach ($stats as $s)
    <div class="col-6 col-xl-3">
        <div class="panel stat-card">
            <div class="stat-icon tone-{{ $s['tone'] }}"><i class="bi {{ $s['ikon'] }}"></i></div>
            <div>
                <div class="stat-label">{{ $s['label'] }}</div>
                <div class="stat-value">{{ $s['nilai'] }}</div>
                <div class="stat-note">{{ $s['catatan'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Banner peringatan --}}
<div class="row g-3 mb-3">
    @foreach ($peringatan as $p)
    <div class="col-12 col-lg-6">
        <div class="panel alert-card">
            <div class="alert-icon tone-{{ $p['tone'] }}"><i class="bi {{ $p['ikon'] }}"></i></div>
            <div class="flex-grow-1">
                <div class="fw-semibold">{{ $p['judul'] }}</div>
                <div class="text-muted small">{{ $p['teks'] }}</div>
            </div>
            @if ($p['tombol'])
                <a href="#" class="btn btn-sm btn-outline-secondary text-nowrap">
                    {{ $p['tombol'] }} <i class="bi bi-chevron-right"></i>
                </a>
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- Grafik --}}
<div class="row g-3 mb-3">
    {{-- Grafik batang --}}
    <div class="col-12 col-xl-8">
        <div class="panel h-100">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Kegiatan HSE per bulan</div>
                    <small class="text-muted">Jumlah kegiatan selama tahun {{ now()->year }}</small>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="count-pill">{{ $totalKegiatan }} KEGIATAN</span>
                    <div class="seg">
                        <button type="button" class="active" data-range="bulanan">Bulanan</button>
                        <button type="button" data-range="triwulan">Triwulan</button>
                    </div>
                </div>
            </div>
            <div class="chart-wrap">
                <canvas id="chartKegiatan"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik pie --}}
    <div class="col-12 col-xl-4">
        <div class="panel h-100 d-flex flex-column">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Status Temuan HSE</div>
                    <small class="text-muted">Total {{ $totalTemuan }} temuan teridentifikasi</small>
                </div>
                <button class="btn btn-sm btn-light" type="button"><i class="bi bi-three-dots-vertical"></i></button>
            </div>

            <div class="row g-3 align-items-center px-3 pb-3 flex-grow-1">
                <div class="col-12 col-sm-6 col-xl-12 col-xxl-6">
                    <div class="pie-wrap">
                        <canvas id="chartStatus"></canvas>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-12 col-xxl-6">
                    @foreach ($statusTemuan as $s)
                    <div class="legend-row">
                        <span class="legend-dot" style="background: {{ $s['warna'] }}"></span>
                        <div class="lh-sm">
                            <div class="small fw-medium">{{ $s['label'] }}</div>
                            <small class="text-muted">{{ $s['jumlah'] }}</small>
                            <span class="pct-pill tone-{{ $s['tone'] }}">{{ $s['persen'] }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="panel-foot">
                <small class="text-muted">Tingkat Efektivitas <strong class="text-dark">71%</strong></small>
                <a href="#" class="link-primary small fw-medium text-decoration-none">
                    Rincian Temuan <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Aktivitas terbaru --}}
<div class="panel">
    <div class="panel-head">
        <div>
            <div class="panel-title">Aktivitas terbaru</div>
            <small class="text-muted">Pembaruan operasional HSE dalam 7 hari terakhir</small>
        </div>
        <a href="#" class="text-dark small fw-medium text-decoration-none">
            Lihat semua <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-activity align-middle mb-0">
            <thead>
                <tr>
                    <th>Aktivitas</th>
                    <th>Lokasi</th>
                    <th>Penanggung Jawab</th>
                    <th>Waktu</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($aktivitas as $a)
                <tr>
                    <td class="text-nowrap">
                        <div class="fw-semibold">{{ $a['judul'] }}</div>
                        <small class="text-muted">{{ $a['sub'] }}</small>
                    </td>
                    <td class="text-nowrap">{{ $a['lokasi'] }}</td>
                    <td class="text-nowrap">{{ $a['pic'] }}</td>
                    <td class="text-nowrap">{{ $a['waktu'] }}</td>
                    <td class="text-center">
                        <span class="status-badge tone-{{ $a['tone'] }}">{{ $a['status'] }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const kegiatan     = @json($kegiatan);
    const statusTemuan = @json($statusTemuan);

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.responsive = true;            // ikut ukuran wadah
    Chart.defaults.maintainAspectRatio = false;  // tinggi diatur lewat CSS

    // Jumlahkan per 3 bulan untuk tampilan triwulan
    const perTriwulan = arr => [0, 3, 6, 9].map(i => arr.slice(i, i + 3).reduce((a, b) => a + b, 0));

    // ===== Grafik batang =====
    const barChart = new Chart(document.getElementById('chartKegiatan'), {
        type: 'bar',
        data: {
            labels: kegiatan.bulan,
            datasets: [
                { label: 'Rutin',    data: kegiatan.rutin,    backgroundColor: '#4c5fe3', borderRadius: 3, categoryPercentage: .8, barPercentage: .85 },
                { label: 'Inspeksi', data: kegiatan.inspeksi, backgroundColor: '#22d3ee', borderRadius: 3, categoryPercentage: .8, barPercentage: .85 },
            ],
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { autoSkip: true, maxRotation: 0 } },
                y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9' } },
            },
        },
    });

    // Tombol Bulanan / Triwulan
    document.querySelectorAll('[data-range]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-range]').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const triwulan = btn.dataset.range === 'triwulan';
            barChart.data.labels = triwulan ? ['Q1', 'Q2', 'Q3', 'Q4'] : kegiatan.bulan;
            barChart.data.datasets[0].data = triwulan ? perTriwulan(kegiatan.rutin)    : kegiatan.rutin;
            barChart.data.datasets[1].data = triwulan ? perTriwulan(kegiatan.inspeksi) : kegiatan.inspeksi;
            barChart.update();
        });
    });

    // ===== Grafik pie =====
    new Chart(document.getElementById('chartStatus'), {
        type: 'pie',
        data: {
            labels: statusTemuan.map(s => s.label),
            datasets: [{
                data: statusTemuan.map(s => s.jumlah),
                backgroundColor: statusTemuan.map(s => s.warna),
                borderColor: '#fff',
                borderWidth: 2,
            }],
        },
        options: { plugins: { legend: { display: false } } },
    });
</script>
@endpush