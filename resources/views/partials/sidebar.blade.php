@php
    $di_kegiatan = request()->routeIs('kegiatan.*');
    $di_temuan   = request()->routeIs('temuan.*');
    $di_apd      = request()->routeIs('apd.*');
    $slugAktif   = request()->route('jenis');
    $halamanApd  = request()->route('halaman') ?? 'data-apd';
@endphp

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">DHJ</div>
        <div>
            <div class="brand-title">PT. DUTA HITA JAYA</div>
            <div class="brand-sub">HSE SYSTEM</div>
        </div>
    </div>

    <div class="menu-label">MODUL HSE</div>

    <ul class="menu list-unstyled">
        <li>
            <a href="{{ route('dashboard') }}"
               class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i><span>Dashboard</span>
            </a>
        </li>

        {{-- Kegiatan HSE --}}
        <li>
            <div class="menu-link menu-link-split {{ $di_kegiatan ? 'active' : '' }}">
                <a href="{{ route('kegiatan.index') }}" class="menu-main">
                    <i class="bi bi-clipboard-check"></i><span>Kegiatan HSE</span>
                </a>
                <button type="button" class="menu-toggle"
                        data-bs-toggle="collapse" data-bs-target="#menuKegiatan"
                        aria-expanded="{{ $di_kegiatan ? 'true' : 'false' }}"
                        aria-label="Buka submenu Kegiatan HSE">
                    <i class="bi bi-chevron-right chev"></i>
                </button>
            </div>

            <ul class="submenu collapse list-unstyled {{ $di_kegiatan ? 'show' : '' }}" id="menuKegiatan">
                <li>
                    <a href="{{ route('kegiatan.index', 'safety-patrol') }}"
                       class="{{ $di_kegiatan && $slugAktif === 'safety-patrol' ? 'active' : '' }}">Safety Patrol</a>
                </li>
                <li>
                    <a href="{{ route('kegiatan.index', 'inspeksi-apar') }}"
                       class="{{ $di_kegiatan && $slugAktif === 'inspeksi-apar' ? 'active' : '' }}">Inspeksi APAR</a>
                </li>
                <li>
                    <a href="{{ route('kegiatan.index', 'audit-5r-safety') }}"
                       class="{{ $di_kegiatan && $slugAktif === 'audit-5r-safety' ? 'active' : '' }}">Audit 5R &amp; Safety</a>
                </li>
            </ul>
        </li>

        {{-- Temuan --}}
        <li>
            <div class="menu-link menu-link-split {{ $di_temuan ? 'active' : '' }}">
                <a href="{{ route('temuan.index') }}" class="menu-main">
                    <i class="bi bi-exclamation-triangle"></i><span>Temuan</span>
                </a>
                <button type="button" class="menu-toggle"
                        data-bs-toggle="collapse" data-bs-target="#menuTemuan"
                        aria-expanded="{{ $di_temuan ? 'true' : 'false' }}"
                        aria-label="Buka submenu Temuan">
                    <i class="bi bi-chevron-right chev"></i>
                </button>
            </div>

            <ul class="submenu collapse list-unstyled {{ $di_temuan ? 'show' : '' }}" id="menuTemuan">
                <li>
                    <a href="{{ route('temuan.index', 'tindakan-perbaikan') }}"
                       class="{{ $di_temuan && $slugAktif === 'tindakan-perbaikan' ? 'active' : '' }}">Tindakan Perbaikan</a>
                </li>
                <li>
                    <a href="{{ route('temuan.index', 'verifikasi') }}"
                       class="{{ $di_temuan && $slugAktif === 'verifikasi' ? 'active' : '' }}">Verifikasi</a>
                </li>
            </ul>
        </li>

        {{-- APD: klik teks = Data APD, klik panah = buka/tutup dropdown --}}
        <li>
            <div class="menu-link menu-link-split {{ $di_apd ? 'active' : '' }}">
                <a href="{{ route('apd.index') }}" class="menu-main">
                    <i class="bi bi-shield-check"></i><span>APD</span>
                </a>
                <button type="button" class="menu-toggle"
                        data-bs-toggle="collapse" data-bs-target="#menuApd"
                        aria-expanded="{{ $di_apd ? 'true' : 'false' }}"
                        aria-label="Buka submenu APD">
                    <i class="bi bi-chevron-right chev"></i>
                </button>
            </div>

            <ul class="submenu collapse list-unstyled {{ $di_apd ? 'show' : '' }}" id="menuApd">
                <li>
                    <a href="{{ route('apd.index', 'data-apd') }}"
                       class="{{ $di_apd && $halamanApd === 'data-apd' ? 'active' : '' }}">Data APD</a>
                </li>
                <li>
                    <a href="{{ route('apd.index', 'transaksi-stok') }}"
                       class="{{ $di_apd && $halamanApd === 'transaksi-stok' ? 'active' : '' }}">Transaksi Stok</a>
                </li>
                <li>
                    <a href="{{ route('apd.index', 'monitoring-stok') }}"
                       class="{{ $di_apd && $halamanApd === 'monitoring-stok' ? 'active' : '' }}">Monitoring Stok</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#" class="menu-link">
                <i class="bi bi-file-earmark-text"></i><span>Laporan</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="safety-card">
            <i class="bi bi-check-circle"></i>
            <div>
                <div class="fw-semibold">Utamakan Keselamatan</div>
                <small>Safety starts with you.</small>
            </div>
        </div>
        <a href="#" class="back-link"><i class="bi bi-box-arrow-left me-1"></i> Kembali ke Portal</a>
    </div>
</aside>