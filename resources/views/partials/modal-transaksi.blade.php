@php
    // ===== Pilihan dropdown: ubah di sini =====
    $daftarApd = $daftarApd ?? [];
    $opsiJenis = ['MASUK' => 'Stok masuk', 'KELUAR' => 'Stok keluar', 'PENYESUAIAN' => 'Penyesuaian'];
    $opsiPenerima = ['Bayu Kurniawan', 'Tim Welding A', 'Raka Pratama', 'Dian Lestari', 'Nadia Putri', 'Ahmad Fauzi'];
@endphp

{{-- ===== Modal Catat Transaksi Stok ===== --}}
<div class="modal fade modal-kegiatan modal-apd" id="modalTransaksi" tabindex="-1" aria-labelledby="trJudul" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content" id="formTransaksi" novalidate>

            <div class="modal-header">
                <div>
                    <div class="mk-eyebrow">TRANSAKSI BARU</div>
                    <h5 class="modal-title" id="trJudul">Catat Transaksi Stok</h5>
                </div>
                <button type="button" class="mk-close" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label" for="trApd">APD</label>
                        <select class="form-select" id="trApd" required>
                            <option value="">Pilih APD</option>
                            @foreach ($daftarApd as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="trJenis">Jenis transaksi</label>
                        <select class="form-select" id="trJenis" required>
                            <option value="">Pilih jenis transaksi</option>
                            @foreach ($opsiJenis as $nilai => $label)
                                <option value="{{ $nilai }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="trJumlah">Jumlah</label>
                        <input type="number" class="form-control" id="trJumlah" required min="1" step="1" inputmode="numeric">
                        <div class="form-text d-none" id="trHintJumlah">
                            Penyesuaian: gunakan angka negatif untuk mengurangi stok.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="trTanggal">Tanggal</label>
                        <input type="date" class="form-control" id="trTanggal" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="trPenerima">Penerima</label>
                        <select class="form-select" id="trPenerima" disabled>
                            <option value="">Pilih dari data karyawan (untuk stok keluar)</option>
                            @foreach ($opsiPenerima as $o)
                                <option value="{{ $o }}">{{ $o }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="trKet">Keterangan</label>
                        <textarea class="form-control" id="trKet" rows="3"
                                  placeholder="Tambahkan keterangan transaksi..."></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <div class="text-danger small me-auto d-none" id="trError">
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
    <div id="trToast" class="toast align-items-center text-bg-success border-0" role="status" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="trToastPesan"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const modalEl = document.getElementById('modalTransaksi');
    const form    = document.getElementById('formTransaksi');
    const $       = id => document.getElementById(id);

    // Pindahkan modal ke <body> supaya tidak tertutup elemen lain
    document.body.appendChild(modalEl);

    // Jumlah 0 tidak diterima. Penyesuaian boleh negatif.
    function cekJumlah() {
        const v = $('trJumlah').value;
        $('trJumlah').setCustomValidity(v !== '' && Number(v) === 0 ? 'Jumlah tidak boleh 0' : '');
    }

    // Sesuaikan form dengan jenis transaksi
    function aturForm() {
        const jenis  = $('trJenis').value;
        const keluar = jenis === 'KELUAR';

        // Penerima hanya untuk stok keluar
        $('trPenerima').disabled = !keluar;
        $('trPenerima').required = keluar;
        if (!keluar) $('trPenerima').value = '';

        // Penyesuaian: jumlah boleh negatif dan keterangan wajib diisi
        const sesuai = jenis === 'PENYESUAIAN';
        if (sesuai) { $('trJumlah').removeAttribute('min'); } else { $('trJumlah').min = 1; }
        $('trHintJumlah').classList.toggle('d-none', !sesuai);
        $('trKet').required = sesuai;

        cekJumlah();
    }

    $('trJenis').addEventListener('change', aturForm);
    $('trJumlah').addEventListener('input', cekJumlah);

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            $('trError').classList.remove('d-none');
            return;
        }

        bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        $('trToastPesan').textContent =
            'Form berhasil divalidasi. Penyimpanan ke server menunggu backend tersambung.';
        bootstrap.Toast.getOrCreateInstance($('trToast')).show();
    });

    // Kosongkan form setiap modal ditutup
    modalEl.addEventListener('hidden.bs.modal', function () {
        form.reset();
        form.classList.remove('was-validated');
        $('trError').classList.add('d-none');
        aturForm();
    });

    aturForm();
})();
</script>
@endpush