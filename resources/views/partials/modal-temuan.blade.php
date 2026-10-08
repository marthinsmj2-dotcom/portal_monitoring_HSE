@php
    // ===== Pilihan dropdown: ubah di sini =====
    $opsiSumber = ['Safety Patrol', 'Inspeksi APAR', 'Audit 5R & Safety', 'Inspeksi/Observasi HSE'];
    $opsiKategori = ['Kondisi tidak aman', 'Tindakan tidak aman', 'APD tidak sesuai', 'Kebersihan dan kerapihan', 'Peralatan dan mesin', 'Lingkungan kerja'];
    $opsiLokasi = ['Workshop 2', 'Gudang Utama', 'Plant Cikarang', 'Area Fabrikasi', 'Yard Material'];
    $opsiRisiko = ['Tinggi', 'Sedang', 'Rendah'];
    $opsiPic    = ['Ahmad Fauzi', 'Siti Rahma', 'Budi Hartono', 'Dian Lestari', 'Raka Pratama', 'Nadia Putri'];
    $opsiStatus = ['OPEN', 'PROSES', 'CLOSE'];
@endphp

{{-- ===== Modal Tambah Temuan ===== --}}
<div class="modal fade modal-kegiatan" id="modalTemuan" tabindex="-1" aria-labelledby="tmJudul" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content" id="formTemuan" novalidate>

            <div class="modal-header">
                <div>
                    <div class="mk-eyebrow">TEMUAN BARU</div>
                    <h5 class="modal-title" id="tmJudul">Tambah Temuan</h5>
                </div>
                <button type="button" class="mk-close" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label" for="tmSumber">Sumber kegiatan</label>
                        <select class="form-select" id="tmSumber" required>
                            <option value="">Pilih sumber kegiatan</option>
                            @foreach ($opsiSumber as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tmKategori">Kategori</label>
                        <select class="form-select" id="tmKategori" required>
                            <option value="">Pilih kategori</option>
                            @foreach ($opsiKategori as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="tmDeskripsi">Deskripsi temuan</label>
                        <textarea class="form-control" id="tmDeskripsi" rows="3" required
                                  placeholder="Jelaskan kondisi atau tindakan yang ditemukan..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tmLokasi">Lokasi</label>
                        <select class="form-select" id="tmLokasi" required>
                            <option value="">Pilih lokasi/unit</option>
                            @foreach ($opsiLokasi as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tmRisiko">Tingkat risiko</label>
                        <select class="form-select" id="tmRisiko" required>
                            <option value="">Pilih tingkat risiko</option>
                            @foreach ($opsiRisiko as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tmPic">PIC</label>
                        <select class="form-select" id="tmPic" required>
                            <option value="">Pilih dari data karyawan</option>
                            @foreach ($opsiPic as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tmTanggal">Tanggal temuan</label>
                        <input type="date" class="form-control" id="tmTanggal" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="tmStatus">Status</label>
                        <select class="form-select" id="tmStatus" required>
                            @foreach ($opsiStatus as $o)
                                <option value="{{ $o }}" @selected($o === 'OPEN')>{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <div class="text-danger small me-auto d-none" id="tmError">
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
    <div id="tmToast" class="toast align-items-center text-bg-success border-0" role="status" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="tmToastPesan"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const modalEl = document.getElementById('modalTemuan');
    const form    = document.getElementById('formTemuan');
    const errorEl = document.getElementById('tmError');

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
        document.getElementById('tmToastPesan').textContent =
            'Form berhasil divalidasi. Penyimpanan ke server menunggu backend tersambung.';
        bootstrap.Toast.getOrCreateInstance(document.getElementById('tmToast')).show();
    });

    // Kosongkan form setiap modal ditutup (status kembali ke OPEN)
    modalEl.addEventListener('hidden.bs.modal', function () {
        form.reset();
        form.classList.remove('was-validated');
        errorEl.classList.add('d-none');
    });
})();
</script>
@endpush