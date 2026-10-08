<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        // ===== Data dummy (front end saja) =====

        $stats = [
            ['label' => 'Kegiatan bulan ini', 'nilai' => 24, 'catatan' => '5 kegiatan terbaru',    'ikon' => 'bi-file-earmark-text',   'tone' => 'blue'],
            ['label' => 'Temuan OPEN',        'nilai' => 8,  'catatan' => 'Perlu ditindaklanjuti', 'ikon' => 'bi-exclamation-circle',  'tone' => 'red'],
            ['label' => 'Temuan PROSES',      'nilai' => 12, 'catatan' => 'Dalam pemantauan',      'ikon' => 'bi-pencil-square',       'tone' => 'amber'],
            ['label' => 'Temuan CLOSE',       'nilai' => 37, 'catatan' => 'Selesai diverifikasi',  'ikon' => 'bi-check-lg',            'tone' => 'green'],
        ];

        $peringatan = [
            [
                'judul'  => '7 tindakan perbaikan sedang berjalan',
                'teks'   => '3 tindakan melewati target penyelesaian minggu ini.',
                'ikon'   => 'bi-pencil-square',
                'tone'   => 'amber',
                'tombol' => 'Lihat tindakan',
            ],
            [
                'judul'  => '3 jenis APD perlu perhatian',
                'teks'   => '1 stok minimum dan 2 stok di bawah batas aman.',
                'ikon'   => 'bi-box-seam',
                'tone'   => 'red',
                'tombol' => null,
            ],
        ];

        $kegiatan = [
            'bulan'    => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'rutin'    => [4, 5, 5, 6, 6, 7, 7, 8, 8, 9, 9, 10],
            'inspeksi' => [2, 2, 3, 3, 3, 3, 4, 4, 4, 4, 4, 4],
        ];
        $totalKegiatan = array_sum($kegiatan['rutin']) + array_sum($kegiatan['inspeksi']);

        $statusTemuan = [
            ['label' => 'Selesai',          'jumlah' => 52, 'warna' => '#22c55e', 'tone' => 'green'],
            ['label' => 'Dalam Proses',     'jumlah' => 38, 'warna' => '#3b82f6', 'tone' => 'blue'],
            ['label' => 'Terbuka (Open)',   'jumlah' => 24, 'warna' => '#f59e0b', 'tone' => 'amber'],
            ['label' => 'Ditutup (Closed)', 'jumlah' => 14, 'warna' => '#64748b', 'tone' => 'gray'],
        ];
        $totalTemuan  = array_sum(array_column($statusTemuan, 'jumlah'));
        $statusTemuan = array_map(
            fn ($s) => $s + ['persen' => round($s['jumlah'] / $totalTemuan * 100)],
            $statusTemuan
        );

        $aktivitas = [
            ['judul' => 'Safety Patrol HSE-2506-018', 'sub' => 'Pemeriksaan area kerja rutin',    'lokasi' => 'Workshop 2', 'pic' => 'Raka Pratama', 'waktu' => 'Hari ini, 09:30', 'status' => 'SELESAI',  'tone' => 'green'],
            ['judul' => 'Temuan TM-2506-032 dibuat',  'sub' => 'Pekerjaan mesin tidak terproteksi', 'lokasi' => 'Workshop 2', 'pic' => 'Ahmad Fauzi',  'waktu' => 'Hari ini, 10:15', 'status' => 'OPEN',     'tone' => 'red'],
            ['judul' => 'Stok Helm Safety diterima',  'sub' => 'Transaksi stok masuk 20 pcs',       'lokasi' => 'Gudang HSE', 'pic' => 'Dian Lestari',  'waktu' => 'Kemarin, 14:20',  'status' => 'TERCATAT', 'tone' => 'blue'],
        ];

        return view('dashboard', compact(
            'stats', 'peringatan', 'kegiatan', 'totalKegiatan',
            'statusTemuan', 'totalTemuan', 'aktivitas'
        ));
    }
}