<header class="topbar">
    <button class="btn btn-light d-lg-none me-2" id="btnSidebar" type="button">
        <i class="bi bi-list fs-5"></i>
    </button>

    <div class="search-box">
        <i class="bi bi-search"></i>
        <input type="text" class="form-control" placeholder="Cari di modul HSE...">
    </div>

    <div class="ms-auto d-flex align-items-center gap-3">
        {{-- Lonceng + panel notifikasi --}}
        @include('partials.notifikasi')

        {{-- Profil + logout --}}
        <div class="dropdown">
            <a href="#" class="user-box text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar">CA</span>
                <span class="d-none d-sm-block lh-sm">
                    <span class="d-block fw-semibold text-dark">Contoh aja</span>
                    <small class="text-muted">Admin HSE</small>
                </span>
                <i class="bi bi-chevron-down small text-muted d-none d-sm-block"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-end profile-menu">
                <div class="profile-head">
                    <span class="avatar">CA</span>
                    <div class="lh-sm">
                        <div class="fw-semibold">Contoh aja</div>
                        <small class="text-muted">Admin HSE</small>
                    </div>
                </div>

                <div class="dropdown-divider my-1"></div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item profile-logout">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>