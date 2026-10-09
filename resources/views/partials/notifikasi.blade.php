@php
    // ===== Data notifikasi dummy: ubah di sini =====
    // 'baru' => true  : tampil belum dibaca (titik biru) sampai diklik
    // 'tone'          : red, amber, green, blue, purple
    $notifikasi = [
        [
            'id' => 'n1', 'baru' => true,
            'judul'  => 'Ear Plug habis, stok 0 Pasang',
            'sumber' => 'APD', 'waktu' => '2 menit lalu',
            'ikon'   => 'bi-box-seam', 'tone' => 'red',
            'url'    => route('apd.index', 'monitoring-stok'),
        ],
        [
            'id' => 'n2', 'baru' => true,
            'judul'  => 'Sarung Tangan Las tersisa 8 Pasang',
            'sumber' => 'APD', 'waktu' => '10 menit lalu',
            'ikon'   => 'bi-box-seam', 'tone' => 'amber',
            'url'    => route('apd.index', 'monitoring-stok'),
        ],
        [
            'id' => 'n3', 'baru' => true,
            'judul'  => 'Temuan TM-2506-032 berisiko tinggi',
            'sumber' => 'Temuan', 'waktu' => '25 menit lalu',
            'ikon'   => 'bi-exclamation-triangle', 'tone' => 'red',
            'url'    => route('temuan.index', 'tindakan-perbaikan'),
        ],
        [
            'id' => 'n4', 'baru' => false,
            'judul'  => 'Stok masuk: Helm Safety +20 Pcs',
            'sumber' => 'APD', 'waktu' => '1 jam lalu',
            'ikon'   => 'bi-box-arrow-in-down', 'tone' => 'green',
            'url'    => route('apd.index', 'transaksi-stok'),
        ],
        [
            'id' => 'n5', 'baru' => false,
            'judul'  => 'Stok keluar: Kacamata Safety -4 Pcs',
            'sumber' => 'APD', 'waktu' => '2 jam lalu',
            'ikon'   => 'bi-box-arrow-up', 'tone' => 'blue',
            'url'    => route('apd.index', 'transaksi-stok'),
        ],
        [
            'id' => 'n6', 'baru' => false,
            'judul'  => 'TM-2506-029 menunggu verifikasi',
            'sumber' => 'Temuan', 'waktu' => '3 jam lalu',
            'ikon'   => 'bi-patch-check', 'tone' => 'purple',
            'url'    => route('temuan.index', 'verifikasi'),
        ],
        [
            'id' => 'n7', 'baru' => false,
            'judul'  => 'Inspeksi APAR HSE-2506-017 perlu tindak lanjut',
            'sumber' => 'Kegiatan', 'waktu' => 'Kemarin',
            'ikon'   => 'bi-clipboard-check', 'tone' => 'amber',
            'url'    => route('kegiatan.index', 'inspeksi-apar'),
        ],
    ];
@endphp

<div class="dropdown notif">
    {{-- Tombol lonceng --}}
    <button type="button" class="notif-bell" id="btnNotif"
            data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static"
            aria-expanded="false" aria-label="Buka notifikasi">
        <i class="bi bi-bell fs-5"></i>
        <span class="bell-dot" id="bellDot"></span>
    </button>

    {{-- Panel notifikasi --}}
    <div class="dropdown-menu dropdown-menu-end notif-panel">

        <div class="notif-head">
            <div>
                <div class="notif-title">Notifikasi</div>
                <div class="notif-sub" id="notifSub">0 notifikasi belum dibaca</div>
            </div>
            <button type="button" class="mk-close" id="notifTutup" aria-label="Tutup notifikasi">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="notif-sec">
            <span class="notif-sec-label">TERBARU</span>
            <button type="button" class="notif-markall" id="notifSemua">Tandai semua sudah dibaca</button>
        </div>

        <div class="notif-list">
            @foreach ($notifikasi as $n)
            <a href="{{ $n['url'] }}" class="notif-item"
               data-id="{{ $n['id'] }}" data-baru="{{ $n['baru'] ? '1' : '0' }}">
                <span class="notif-icon tone-{{ $n['tone'] }}"><i class="bi {{ $n['ikon'] }}"></i></span>
                <span class="notif-body">
                    <span class="notif-item-title">{{ $n['judul'] }}</span>
                    <span class="notif-meta">{{ $n['sumber'] }} · {{ $n['waktu'] }}</span>
                </span>
                <span class="notif-dot"></span>
            </a>
            @endforeach
        </div>

        <a href="#" class="notif-foot" id="notifLihat">
            Lihat semua notifikasi <i class="bi bi-chevron-right ms-1"></i>
        </a>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const KUNCI   = 'hse_notif_dibaca';
    const items   = Array.from(document.querySelectorAll('.notif-item'));
    const subEl   = document.getElementById('notifSub');
    const dotEl   = document.getElementById('bellDot');
    const semuaEl = document.getElementById('notifSemua');

    // Status dibaca disimpan di browser
    function dibaca() {
        try { return JSON.parse(localStorage.getItem(KUNCI)) || []; } catch (e) { return []; }
    }
    function simpan(ids) {
        try { localStorage.setItem(KUNCI, JSON.stringify(ids)); } catch (e) {}
    }

    // Perbarui titik biru, tulisan "belum dibaca", dan titik merah di lonceng
    function tampilkan() {
        const sudah = dibaca();
        let belum = 0;

        items.forEach(el => {
            const unread = el.dataset.baru === '1' && !sudah.includes(el.dataset.id);
            el.classList.toggle('unread', unread);
            if (unread) belum++;
        });

        subEl.textContent = belum ? belum + ' notifikasi belum dibaca' : 'Semua notifikasi sudah dibaca';
        dotEl.classList.toggle('d-none', belum === 0);
        semuaEl.disabled = belum === 0;
    }

    // Klik notifikasi: tandai dibaca, lalu pindah ke halaman tujuan
    items.forEach(el => el.addEventListener('click', () => {
        const ids = dibaca();
        if (!ids.includes(el.dataset.id)) { ids.push(el.dataset.id); simpan(ids); }
    }));

    // Tandai semua sudah dibaca
    semuaEl.addEventListener('click', () => {
        simpan(items.map(el => el.dataset.id));
        tampilkan();
    });

    // Tombol X menutup panel
    document.getElementById('notifTutup').addEventListener('click', () => {
        bootstrap.Dropdown.getOrCreateInstance(document.getElementById('btnNotif')).hide();
    });

    // Halaman "semua notifikasi" belum dibuat
    document.getElementById('notifLihat').addEventListener('click', e => e.preventDefault());

    tampilkan();
})();
</script>
@endpush