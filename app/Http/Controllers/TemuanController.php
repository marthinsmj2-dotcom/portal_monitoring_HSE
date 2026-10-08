<?php

namespace App\Http\Controllers;

class TemuanController extends Controller
{
    // slug di URL => info halaman submenu
    // 'tahap' menentukan data mana yang tampil di submenu itu
    private array $halaman = [
        'tindakan-perbaikan' => [
            'judul'    => 'Tindakan Perbaikan',
            'subjudul' => 'Pantau temuan HSE dan progres tindakan perbaikannya.',
            'tahap'    => 'tindakan',
        ],
        'verifikasi' => [
            'judul'    => 'Verifikasi',
            'subjudul' => 'Pantau temuan HSE dan hasil verifikasi perbaikannya.',
            'tahap'    => 'verifikasi',
        ],
    ];

    private array $toneRisiko = ['TINGGI' => 'red',   'SEDANG' => 'amber', 'RENDAH' => 'green'];
    private array $toneStatus = ['OPEN'   => 'red',   'PROSES' => 'amber', 'CLOSE'  => 'green'];

    // Satu-satunya sumber data dummy. Tambah baris di sini untuk menambah data.
    private function dataTemuan(): array
    {
        return [
            ['nomor' => 'TM-2506-032', 'sumber' => 'Safety Patrol',          'deskripsi' => 'Pelindung mesin gerinda tidak terpasang sempurna', 'lokasi' => 'Workshop 2',     'risiko' => 'TINGGI', 'pic' => 'Ahmad Fauzi',  'status' => 'OPEN',   'tahap' => 'tindakan'],
            ['nomor' => 'TM-2506-031', 'sumber' => 'Audit 5R & Safety',      'deskripsi' => 'Material menghalangi jalur evakuasi',              'lokasi' => 'Gudang Utama',   'risiko' => 'SEDANG', 'pic' => 'Siti Rahma',   'status' => 'PROSES', 'tahap' => 'tindakan'],
            ['nomor' => 'TM-2506-030', 'sumber' => 'Inspeksi/Observasi HSE', 'deskripsi' => 'Pekerja tidak menggunakan pelindung wajah',       'lokasi' => 'Area Fabrikasi', 'risiko' => 'TINGGI', 'pic' => 'Budi Hartono', 'status' => 'PROSES', 'tahap' => 'tindakan'],
            ['nomor' => 'TM-2506-029', 'sumber' => 'Inspeksi APAR',          'deskripsi' => 'Segel pin APAR terlepas',                          'lokasi' => 'Gudang Utama',   'risiko' => 'SEDANG', 'pic' => 'Dian Lestari', 'status' => 'PROSES', 'tahap' => 'verifikasi'],
            ['nomor' => 'TM-2506-028', 'sumber' => 'Safety Patrol',          'deskripsi' => 'Kabel listrik panel terkelupas',                   'lokasi' => 'Plant Cikarang', 'risiko' => 'RENDAH', 'pic' => 'Raka Pratama', 'status' => 'CLOSE',  'tahap' => 'verifikasi'],
        ];
    }

    public function index(?string $jenis = null)
    {
        abort_if($jenis !== null && ! isset($this->halaman[$jenis]), 404);

        $semua = collect($this->dataTemuan())->sortByDesc('nomor')->values();

        if ($jenis) {
            // ----- Halaman submenu: hanya satu tahap -----
            $info     = $this->halaman[$jenis];
            $data     = $semua->where('tahap', $info['tahap'])->values();
            $judul    = $info['judul'];
            $subjudul = $info['subjudul'];
            $stats    = null;
        } else {
            // ----- Halaman Temuan: akumulasi kedua submenu -----
            $data     = $semua;
            $judul    = 'Temuan';
            $subjudul = 'Akumulasi temuan HSE dari tindakan perbaikan dan verifikasi.';
            $stats    = [
                ['label' => 'TOTAL TEMUAN',  'nilai' => $data->count(),                         'ikon' => 'bi-list-check',         'tone' => 'blue',  'warna' => ''],
                ['label' => 'TEMUAN OPEN',   'nilai' => $data->where('status', 'OPEN')->count(),   'ikon' => 'bi-exclamation-circle', 'tone' => 'red',   'warna' => 'val-red'],
                ['label' => 'TEMUAN PROSES', 'nilai' => $data->where('status', 'PROSES')->count(), 'ikon' => 'bi-pencil-square',      'tone' => 'amber', 'warna' => 'val-amber'],
                ['label' => 'TEMUAN CLOSE',  'nilai' => $data->where('status', 'CLOSE')->count(),  'ikon' => 'bi-check-lg',           'tone' => 'green', 'warna' => 'val-green'],
            ];
        }

        // Tambahkan warna badge risiko dan status
        $temuan = $data->map(fn ($t) => $t + [
            'risiko_tone' => $this->toneRisiko[$t['risiko']],
            'status_tone' => $this->toneStatus[$t['status']],
        ])->all();

        return view('temuan.index', [
            'judul'        => $judul,
            'subjudul'     => $subjudul,
            'stats'        => $stats,
            'temuan'       => $temuan,
            'daftarRisiko' => array_keys($this->toneRisiko),
            'daftarStatus' => array_keys($this->toneStatus),
        ]);
    }
}