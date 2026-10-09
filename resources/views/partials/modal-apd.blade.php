@php
    // ===== Pilihan dropdown: kalo mo ubah di sini =====
    $opsiKategori = ['Pelindung Kepala', 'Pelindung Mata', 'Pelindung Tangan', 'Pelindung Pendengaran', 'Pelindung Kaki', 'Pelindung Pernapasan', 'Pelindung Tubuh'];
    $opsiSatuan   = ['Pcs', 'Pasang', 'Set', 'Box'];
    $opsiLokasi   = ['Gudang HSE', 'Gudang Workshop', 'Gudang Utama'];
@endphp

{{-- ===== Modal Tambah Data APD ===== --}}
<div class="modal fade modal-kegiatan modal-apd" id="modalApd" tabindex="-1" aria-labelledby="apJudul" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content" id="formApd" novalidate>

            <div class="modal-header">
                <div>
                    <div class="mk-eyebrow">DATA INVENTARIS</div>
                    <h5 class="modal-title" id="apJudul">Tambah Data APD</h5>
                </div>
                <button type="button" class="mk-close" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label" for="apKode">Kode APD</label>
                        <input type="text" class="form-control" id="apKode" required placeholder="Contoh: APD-006">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="apNama">Nama APD</label>
                        <input type="text" class="form-control" id="apNama" required placeholder="Masukkan nama APD">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="apKategori">Kategori</label>
                        <select class="form-select" id="apKategori" required>
                            <option value="">Pilih kategori</option>
                            @foreach ($opsiKategori as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="apUkuran">Ukuran</label>
                        <input type="text" class="form-control" id="apUkuran" required placeholder="Contoh: All size">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="apSatuan">Satuan</label>
                        <select class="form-select" id="apSatuan" required>
                            <option value="">Pilih satuan</option>
                            @foreach ($opsiSatuan as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="apLokasi">Lokasi penyimpanan</label>
                        <select class="form-select" id="apLokasi" required>
                            <option value="">Pilih lokasi</option>
                            @foreach ($opsiLokasi as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="apMinimum">Stok minimum</label>
                        <input type="number" class="form-control" id="apMinimum" required min="0" step="1" inputmode="numeric">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="apAwal">Stok awal</label>
                        <input type="number" class="form-control" id="apAwal" required min="0" step="1" inputmode="numeric">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <div class="text-danger small me-auto d-none" id="apError">
                    <i class="bi bi-exclamation-circle me-1"></i>Lengkapi kolom yang bertanda merah.
                </div>
                <button type="button" class="btn btn-outline-light-border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-brand">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Notifikasi setelah Simpan --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="apToast" class="toast align-items-center text-bg-success border-0" role="status" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="apToastPesan"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const modalEl = document.getElementById('modalApd');
    const form    = document.getElementById('formApd');
    const errorEl = document.getElementById('apError');

    // Pindahkan modal ke <body> supaya tidak tertutup elemen lain
    document.body.appendChild(modalEl);

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            errorEl.classList.remove('d-none');
            return;
        }

        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        document.getElementById('apToastPesan').textContent =
            'Form berhasil divalidasi. Penyimpanan ke server menunggu backend tersambung.';
        bootstrap.Toast.getOrCreateInstance(document.getElementById('apToast')).show();
    });

    // Kosongkan form setiap modal ditutup
    modalEl.addEventListener('hidden.bs.modal', function () {
        form.reset();
        form.classList.remove('was-validated');
        errorEl.classList.add('d-none');
    });
})();
</script>
@endpush