<?php

namespace App\Http\Controllers;

class KegiatanController extends Controller
{
    // slug di URL => info halaman submenu
    private array $halaman = [
        'safety-patrol' => [
            'jenis'    => 'Safety Patrol',
            'subjudul' => 'Daftar kegiatan safety patrol terbaru.',
            'area'     => 'Area Plant & Workshop',
            'ringkas'  => false,
        ],
        'inspeksi-apar' => [
            'jenis'    => 'Inspeksi APAR',
            'subjudul' => 'Daftar kegiatan inspeksi apar terbaru.',
            'area'     => null,      // tanpa label kecil di atas judul
            'ringkas'  => true,      // baris tabel polos, tanpa ikon/avatar
        ],
        'audit-5r-safety' => [
            'jenis'    => 'Audit 5R & Safety',
            'subjudul' => 'Daftar audit 5R dan safety terbaru.',
            'area'     => 'Area Plant & Office',
            'ringkas'  => false,
        ],
    ];

    // Satu-satunya sumber data dummy. Tambah baris di sini untuk menambah data.
    private function dataKegiatan(): array
    {
        return [
            ['nomor' => 'HSE-2506-018', 'jenis' => 'Safety Patrol',     'tanggal' => '2025-06-18', 'lokasi' => 'Workshop 2',     'petugas' => 'Ricky',  'avatar' => 'gray',  'status' => 'SELESAI',        'tone' => 'green'],
            ['nomor' => 'HSE-2506-017', 'jenis' => 'Inspeksi APAR',     'tanggal' => '2025-06-17', 'lokasi' => 'Gudang Utama',   'petugas' => 'Marthin',  'avatar' => 'amber', 'status' => 'TINDAK LANJUT',  'tone' => 'amber'],
            ['nomor' => 'HSE-2506-016', 'jenis' => 'Audit 5R & Safety', 'tanggal' => '2025-06-16', 'lokasi' => 'Plant Cikarang', 'petugas' => 'Simanjuntak', 'avatar' => 'gray',  'status' => 'SELESAI',        'tone' => 'green'],
        ];
    }

    public function index(?string $jenis = null)
    {
        abort_if($jenis !== null && ! isset($this->halaman[$jenis]), 404);

        $semua        = $this->dataKegiatan();
        $daftarJenis  = array_column($this->halaman, 'jenis');
        $daftarStatus = ['SELESAI', 'TINDAK LANJUT', 'TERJADWAL'];

        // ----- Halaman submenu: hanya satu jenis -----
        if ($jenis) {
            $info     = $this->halaman[$jenis];
            $kegiatan = array_values(array_filter($semua, fn ($k) => $k['jenis'] === $info['jenis']));

                       // Inspeksi APAR punya file view sendiri, yang lain memakai view umum
            $view = $jenis === 'inspeksi-apar' ? 'kegiatan.inspeksi-apar' : 'kegiatan.jenis';

            return view($view, compact('info', 'kegiatan', 'daftarJenis', 'daftarStatus'));
        }

        // ----- Halaman Semua Kegiatan: akumulasi ketiga jenis -----
        $data = collect($semua)->sortByDesc('tanggal')->values();

        $total     = $data->count();
        $bulanAcuan = substr($data->max('tanggal'), 0, 7);   // bulan dari data terbaru
        $bulanIni  = $data->filter(fn ($k) => substr($k['tanggal'], 0, 7) === $bulanAcuan)->count();
        $tindak    = $data->where('status', 'TINDAK LANJUT')->count();
        $selesai   = $data->where('status', 'SELESAI')->count();
        $kepatuhan = $total ? round($selesai / $total * 100, 1) : 0;

        $stats = [
            ['label' => 'TOTAL KEGIATAN',      'nilai' => $total,           'ikon' => 'bi-list-check',      'tone' => 'blue',   'warna' => ''],
            ['label' => 'BULAN INI',           'nilai' => $bulanIni,        'ikon' => 'bi-calendar2-check', 'tone' => 'green',  'warna' => 'val-green'],
            ['label' => 'PERLU TINDAK LANJUT', 'nilai' => $tindak,          'ikon' => 'bi-clock-history',   'tone' => 'amber',  'warna' => 'val-amber'],
            ['label' => 'KEPATUHAN TARGET',    'nilai' => $kepatuhan . '%', 'ikon' => 'bi-pie-chart-fill',  'tone' => 'purple', 'warna' => ''],
        ];

        return view('kegiatan.index', [
            'stats'        => $stats,
            'kegiatan'     => $data->all(),
            'jenisAktif'   => null,
            'daftarJenis'  => $daftarJenis,
            'daftarStatus' => $daftarStatus,
        ]);
    }
}