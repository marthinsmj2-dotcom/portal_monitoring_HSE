@php
    $jenisAwal = $jenisAwal ?? '';

    $daftarJenisForm = ['Safety Patrol', 'Inspeksi APAR', 'Audit 5R & Safety'];
    $daftarLokasi    = ['Workshop 2', 'Gudang Utama', 'Plant Cikarang', 'Area Fabrikasi', 'Yard Material'];
    $daftarKaryawan  = ['Marthin', 'Ricky', 'mbak tania', 'mbak puti', 'mbak nabila'];

    // Label, pilihan, dan item checklist untuk tiap jenis kegiatan
    $konfigForm = [
        '' => [
            'identitas' => ['label' => 'Nomor/Identitas',  'placeholder' => 'Contoh: HSE-2506-019'],
            'tanggal'   => 'Tanggal kegiatan',
            'lokasi'    => ['label' => 'Lokasi',           'placeholder' => 'Pilih lokasi/unit'],
            'kondisi'   => ['label' => 'Kondisi',          'placeholder' => 'Pilih kondisi', 'opsi' => ['Baik', 'Perlu tindak lanjut', 'Berbahaya']],
            'hasil'     => 'Hasil kegiatan',
            'checklist' => [],
        ],
        'Safety Patrol' => [
            'identitas' => ['label' => 'Nomor/Rute Patrol', 'placeholder' => 'Contoh: PTR-WS2-01'],
            'tanggal'   => 'Tanggal patrol',
            'lokasi'    => ['label' => 'Area patrol',       'placeholder' => 'Pilih lokasi/unit'],
            'kondisi'   => ['label' => 'Kondisi area',      'placeholder' => 'Pilih kondisi', 'opsi' => ['Aman', 'Perlu perbaikan', 'Berbahaya']],
            'hasil'     => 'Hasil patrol',
            'checklist' => [
                'Akses dan jalur kerja dalam kondisi aman',
                'APD digunakan sesuai jenis pekerjaan',
                'Peralatan kerja memiliki inspeksi aktif',
                'Area bebas dari material yang menghalangi',
                'Rambu keselamatan tersedia dan terbaca',
            ],
        ],
        'Inspeksi APAR' => [
            'identitas' => ['label' => 'Nomor/Identitas APAR', 'placeholder' => 'Contoh: APAR-WS2-014'],
            'tanggal'   => 'Tanggal pemeriksaan',
            'lokasi'    => ['label' => 'Lokasi APAR',          'placeholder' => 'Pilih lokasi/unit'],
            'kondisi'   => ['label' => 'Kondisi APAR',         'placeholder' => 'Pilih kondisi', 'opsi' => ['Baik', 'Perlu isi ulang', 'Rusak', 'Kadaluarsa']],
            'hasil'     => 'Hasil pemeriksaan',
            'checklist' => [
                'Pin pengaman dan segel dalam kondisi baik',
                'Tekanan pada indikator berada di area hijau',
                'Selang dan nozzle tidak rusak',
                'Tabung tidak berkarat atau penyok',
                'Label dan masa berlaku dapat terbaca',
            ],
        ],
        'Audit 5R & Safety' => [
            'identitas' => ['label' => 'Nomor/Area Audit', 'placeholder' => 'Contoh: AUD-5R-CKR-03'],
            'tanggal'   => 'Tanggal audit',
            'lokasi'    => ['label' => 'Area audit',       'placeholder' => 'Pilih lokasi/unit'],
            'kondisi'   => ['label' => 'Kategori hasil',   'placeholder' => 'Pilih kategori', 'opsi' => ['Baik', 'Cukup', 'Kurang']],
            'hasil'     => 'Hasil audit',
            'checklist' => [
                'R1- Ringkas: barang yang tidak diperlukan dipisahkan',
                'R2- Rapi: peralatan tersusun dan memiliki identitas',
                'R3- Resik: area kerja bersih dari kotoran',
                'R4- Rawat: standar 5R dijalankan secara konsisten',
                'R5- Rajin: disiplin dan aspek safety dipatuhi',
            ],
        ],
    ];
@endphp

{{-- ===== Modal Tambah Kegiatan ===== --}}
<div class="modal fade modal-kegiatan" id="modalKegiatan" tabindex="-1" aria-labelledby="mkJudul" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content" id="formKegiatan" novalidate>

            <div class="modal-header">
                <div>
                    <div class="mk-eyebrow">KEGIATAN BARU</div>
                    <h5 class="modal-title" id="mkJudul">Tambah Kegiatan</h5>
                </div>
                <button type="button" class="mk-close" data-bs-dismiss="modal" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label" for="mkJenis">Jenis kegiatan</label>
                        <select class="form-select" id="mkJenis" required>
                            <option value="">Pilih jenis kegiatan</option>
                            @foreach ($daftarJenisForm as $j)
                                <option value="{{ $j }}" @selected($j === $jenisAwal)>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="mkIdentitas" id="lblIdentitas">Nomor/Identitas</label>
                        <input type="text" class="form-control" id="mkIdentitas" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="mkTanggal" id="lblTanggal">Tanggal kegiatan</label>
                        <input type="date" class="form-control" id="mkTanggal" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="mkLokasi" id="lblLokasi">Lokasi</label>
                        <select class="form-select" id="mkLokasi" required>
                            <option value="" id="phLokasi">Pilih lokasi/unit</option>
                            @foreach ($daftarLokasi as $l)
                                <option value="{{ $l }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="mkPetugas">Petugas</label>
                        <select class="form-select" id="mkPetugas" required>
                            <option value="">Pilih dari data karyawan</option>
                            @foreach ($daftarKaryawan as $n)
                                <option value="{{ $n }}">{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="mkKondisi" id="lblKondisi">Kondisi</label>
                        <select class="form-select" id="mkKondisi" required></select>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="mkHasil" id="lblHasil">Hasil kegiatan</label>
                        <textarea class="form-control" id="mkHasil" rows="2" required
                                  placeholder="Tuliskan ringkasan hasil kegiatan..."></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="mkTemuan">Temuan</label>
                        <textarea class="form-control" id="mkTemuan" rows="2"
                                  placeholder="Catat kondisi berbahaya atau temuan jika ada..."></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="mkKeterangan">Keterangan</label>
                        <textarea class="form-control" id="mkKeterangan" rows="2"
                                  placeholder="Tuliskan tujuan atau catatan kegiatan..."></textarea>
                    </div>

                    {{-- Ringkasan checklist (isi lengkap diatur di panel overlay) --}}
                    <div class="col-12">
                        <div class="checklist-box">
                            <div class="checklist-head">
                                <div>
                                    <div class="fw-medium small">Checklist pemeriksaan</div>
                                    <small class="text-muted" id="mkChecklistRingkas">Pilih jenis kegiatan terlebih dahulu</small>
                                </div>
                                <button type="button" class="btn btn-outline-light-border" id="mkBtnChecklist">
                                    <i class="bi bi-plus-lg me-1"></i> Atur Checklist
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <div class="text-danger small me-auto d-none" id="mkError">
                    <i class="bi bi-exclamation-circle me-1"></i>Lengkapi kolom yang bertanda merah.
                </div>
                <button type="button" class="btn btn-outline-light-border" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-brand">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== Panel overlay Checklist Pemeriksaan ===== --}}
<div class="modal fade modal-kegiatan modal-checklist" id="modalChecklist" tabindex="-1" aria-labelledby="ckJudul" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <div>
                    <div class="mk-eyebrow" id="ckJenis">CHECKLIST</div>
                    <h5 class="modal-title" id="ckJudul">Checklist Pemeriksaan</h5>
                </div>
                <button type="button" class="mk-close" data-bs-dismiss="modal" aria-label="Kembali">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="ck-table" id="ckDaftar"></div>

                <label class="form-label mt-3" for="ckCatatan">Catatan checklist</label>
                <textarea class="form-control" id="ckCatatan" rows="3"
                          placeholder="Tambahkan catatan hasil pemeriksaan..."></textarea>
            </div>

            <div class="modal-footer ck-footer">
                <button type="button" class="btn btn-outline-light-border" data-bs-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-brand" id="ckSimpan">Simpan Checklist</button>
            </div>
        </div>
    </div>
</div>

{{-- Notifikasi setelah Simpan --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="mkToast" class="toast align-items-center text-bg-success border-0" role="status" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="mkToastPesan"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const config = @json($konfigForm);
    const mainEl = document.getElementById('modalKegiatan');
    const ckEl   = document.getElementById('modalChecklist');
    const form   = document.getElementById('formKegiatan');
    const $      = id => document.getElementById(id);

    // Pindahkan modal ke <body> supaya tidak tertutup elemen lain
    document.body.append(mainEl, ckEl);

    const modalUtama     = () => bootstrap.Modal.getOrCreateInstance(mainEl);
    const modalChecklist = () => bootstrap.Modal.getOrCreateInstance(ckEl);

    // ----- Data checklist (disimpan di memori) -----
    let itemChecklist    = [];      // [{ teks, nilai }]  nilai: 'sesuai' | 'tidak' | 'na'
    let catatanChecklist = '';
    let keChecklist      = false;   // penanda: modal utama ditutup hanya untuk membuka panel checklist

    function ringkasChecklist() {
        const total = itemChecklist.length;
        $('mkBtnChecklist').disabled = total === 0;

        if (!total) {
            $('mkChecklistRingkas').textContent = 'Pilih jenis kegiatan terlebih dahulu';
            return;
        }

        const sesuai = itemChecklist.filter(i => i.nilai === 'sesuai').length;
        const tidak  = itemChecklist.filter(i => i.nilai === 'tidak').length;
        let teks = (sesuai + tidak) + ' dari ' + total + ' item diperiksa';
        if (tidak > 0) teks += ' · ' + tidak + ' tidak sesuai';
        $('mkChecklistRingkas').textContent = teks;
    }

    // ----- Sesuaikan isi form dengan jenis kegiatan -----
    function terapkanJenis() {
        const jenis = $('mkJenis').value;
        const c = config[jenis] || config[''];

        $('mkJudul').textContent      = jenis ? 'Tambah ' + jenis : 'Tambah Kegiatan';
        $('lblIdentitas').textContent = c.identitas.label;
        $('mkIdentitas').placeholder  = c.identitas.placeholder;
        $('lblTanggal').textContent   = c.tanggal;
        $('lblLokasi').textContent    = c.lokasi.label;
        $('phLokasi').textContent     = c.lokasi.placeholder;
        $('lblKondisi').textContent   = c.kondisi.label;
        $('lblHasil').textContent     = c.hasil;
        $('mkHasil').placeholder      = 'Tuliskan ringkasan ' + c.hasil.toLowerCase() + '...';

        const kondisi = $('mkKondisi');
        kondisi.innerHTML = '';
        kondisi.add(new Option(c.kondisi.placeholder, ''));
        c.kondisi.opsi.forEach(o => kondisi.add(new Option(o, o)));

        // Checklist dimulai ulang: semua item berstatus N/A
        itemChecklist    = c.checklist.map(teks => ({ teks, nilai: 'na' }));
        catatanChecklist = '';
        ringkasChecklist();
    }

    $('mkJenis').addEventListener('change', terapkanJenis);
    terapkanJenis();

    // ----- Buka panel checklist -----
    $('mkBtnChecklist').addEventListener('click', function () {
        const daftar = $('ckDaftar');
        daftar.innerHTML = '';

        $('ckJenis').textContent = $('mkJenis').value.toUpperCase();

        itemChecklist.forEach((item, i) => {
            const baris = document.createElement('div');
            baris.className = 'ck-row';

            const no = document.createElement('span');
            no.className = 'ck-no';
            no.textContent = String(i + 1).padStart(2, '0');

            const teks = document.createElement('span');
            teks.className = 'ck-text';
            teks.textContent = item.teks;

            const opsi = document.createElement('div');
            opsi.className = 'ck-options';

            [['sesuai', 'Sesuai'], ['tidak', 'Tidak sesuai'], ['na', 'N/A']].forEach(([nilai, label]) => {
                const id = 'ckr' + i + '_' + nilai;

                const wrap = document.createElement('div');
                wrap.className = 'form-check';

                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.className = 'form-check-input';
                radio.name = 'ckr' + i;
                radio.id = id;
                radio.value = nilai;
                radio.checked = item.nilai === nilai;

                const lbl = document.createElement('label');
                lbl.className = 'form-check-label';
                lbl.htmlFor = id;
                lbl.textContent = label;

                wrap.append(radio, lbl);
                opsi.appendChild(wrap);
            });

            baris.append(no, teks, opsi);
            daftar.appendChild(baris);
        });

        $('ckCatatan').value = catatanChecklist;

        // Tutup modal utama dulu, panel checklist dibuka setelah modal utama selesai tertutup
        keChecklist = true;
        modalUtama().hide();
    });

    // ----- Simpan checklist -----
    $('ckSimpan').addEventListener('click', function () {
        itemChecklist.forEach((item, i) => {
            const dipilih = ckEl.querySelector('input[name="ckr' + i + '"]:checked');
            item.nilai = dipilih ? dipilih.value : 'na';
        });
        catatanChecklist = $('ckCatatan').value.trim();
        ringkasChecklist();
        modalChecklist().hide();
    });

    // Panel checklist tertutup (Kembali / Simpan / tombol X) -> kembali ke form utama
    ckEl.addEventListener('hidden.bs.modal', function () {
        modalUtama().show();
    });

    // ----- Simpan form -----
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            $('mkError').classList.remove('d-none');
            return;
        }

        modalUtama().hide();
        $('mkToastPesan').textContent = 'Form berhasil divalidasi. Penyimpanan ke server menunggu backend tersambung.';
        bootstrap.Toast.getOrCreateInstance($('mkToast')).show();
    });

    // Modal utama tertutup
    mainEl.addEventListener('hidden.bs.modal', function () {
        // Hanya pindah ke panel checklist: jangan kosongkan form
        if (keChecklist) {
            keChecklist = false;
            modalChecklist().show();
            return;
        }

        form.reset();
        form.classList.remove('was-validated');
        $('mkError').classList.add('d-none');
        terapkanJenis();
    });
})();
</script>
@endpush