<?php

namespace App\Http\Controllers;

class ApdController extends Controller
{
    // slug di URL => judul halaman
    private array $halaman = [
        'data-apd'        => 'Data APD',
        'transaksi-stok'  => 'Transaksi Stok',
        'monitoring-stok' => 'Monitoring Stok',
    ];

    // Data dummy APD. Ubah stok di sini, Data APD dan Monitoring Stok ikut berubah.
    private function dataApd(): array
    {
        return [
            ['kode' => 'APD-001', 'nama' => 'Helm Safety',       'kategori' => 'Pelindung Kepala',      'ukuran' => 'Adjustable', 'satuan' => 'Pcs',    'lokasi' => 'Gudang HSE',       'minimum' => 20, 'stok' => 48],
            ['kode' => 'APD-002', 'nama' => 'Kacamata Safety',   'kategori' => 'Pelindung Mata',        'ukuran' => 'All size',   'satuan' => 'Pcs',    'lokasi' => 'Gudang HSE',       'minimum' => 25, 'stok' => 25],
            ['kode' => 'APD-003', 'nama' => 'Sarung Tangan Las', 'kategori' => 'Pelindung Tangan',      'ukuran' => 'L',          'satuan' => 'Pasang', 'lokasi' => 'Gudang Workshop',  'minimum' => 15, 'stok' => 8],
            ['kode' => 'APD-004', 'nama' => 'Ear Plug',          'kategori' => 'Pelindung Pendengaran', 'ukuran' => 'All size',   'satuan' => 'Pasang', 'lokasi' => 'Gudang HSE',       'minimum' => 30, 'stok' => 0],
            ['kode' => 'APD-005', 'nama' => 'Safety Shoes',      'kategori' => 'Pelindung Kaki',        'ukuran' => '40–44',      'satuan' => 'Pasang', 'lokasi' => 'Gudang HSE',       'minimum' => 12, 'stok' => 19],
        ];
    }

    // Data dummy transaksi. 'jenis' berisi MASUK atau KELUAR. Penerima kosong (null) untuk stok masuk.
    private function dataTransaksi(): array
    {
        return [
            ['nomor' => 'TRX-2506-044', 'apd' => 'Helm Safety',       'jenis' => 'MASUK',  'jumlah' => 20, 'tanggal' => '2025-06-18', 'penerima' => null],
            ['nomor' => 'TRX-2506-043', 'apd' => 'Kacamata Safety',   'jenis' => 'KELUAR', 'jumlah' => 4,  'tanggal' => '2025-06-17', 'penerima' => 'Bayu Kurniawan'],
            ['nomor' => 'TRX-2506-042', 'apd' => 'Sarung Tangan Las', 'jenis' => 'KELUAR', 'jumlah' => 6,  'tanggal' => '2025-06-16', 'penerima' => 'Tim Welding A'],
        ];
    }

    public function index(?string $halaman = null)
    {
        $halaman ??= 'data-apd';
        abort_unless(isset($this->halaman[$halaman]), 404);

        $apd = $this->dataApd();

        // ----- Transaksi Stok -----
        if ($halaman === 'transaksi-stok') {
            // jenis => [label badge, warna badge]
            $peta = [
                'MASUK'       => ['STOK MASUK',  'green'],
                'KELUAR'      => ['STOK KELUAR', 'blue'],
                'PENYESUAIAN' => ['PENYESUAIAN', 'amber'],
            ];

            $transaksi = collect($this->dataTransaksi())
                ->sortByDesc('nomor')
                ->map(fn ($t) => $t + [
                    'label' => $peta[$t['jenis']][0],
                    'tone'  => $peta[$t['jenis']][1],
                ])
                ->values()
                ->all();

            // Daftar nama APD untuk dropdown di form Catat Transaksi
            $daftarApd = array_column($apd, 'nama');

            return view('apd.transaksi', compact('transaksi', 'daftarApd'));
        }

        // ----- Monitoring Stok: hanya yang pada/di bawah batas minimum -----
        if ($halaman === 'monitoring-stok') {
            $perhatian = collect($apd)
                ->filter(fn ($a) => $a['stok'] <= $a['minimum'])
                ->map(function ($a) {
                    if ($a['stok'] === 0) {
                        $status = 'HABIS';    $tone = 'red';
                    } elseif ($a['stok'] < $a['minimum']) {
                        $status = 'MINIMUM';  $tone = 'red';
                    } else {
                        $status = 'MINIMUM';  $tone = 'amber';
                    }

                    return $a + ['status' => $status, 'tone' => $tone];
                })
                ->values()
                ->all();

            return view('apd.monitoring', compact('perhatian'));
        }

        // ----- Data APD -----
        $daftarKategori = array_values(array_unique(array_column($apd, 'kategori')));

        return view('apd.index', compact('apd', 'daftarKategori'));
    }
}