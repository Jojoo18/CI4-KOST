<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-cart-plus mr-2"></i>Form Pemesanan Kamar <?= $kamar['nomor_kamar'] ?>
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Detail Kamar -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr><th width="120">Tipe</th><td><?= ucfirst($kamar['tipe']) ?></td></tr>
                                <tr><th>Fasilitas</th><td><?= $kamar['fasilitas'] ?></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded">
                                <small class="text-muted">Harga per Tahun</small>
                                <h2 class="text-success mb-0">Rp <?= number_format($kamar['harga_per_tahun'], 0, ',', '.') ?></h2>
                                <small class="text-danger">* DP minimal 50%</small>
                            </div>
                        </div>
                    </div>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

                    <form action="<?= base_url('pesan/store') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id_kamar" value="<?= $kamar['id_kamar'] ?>">

                        <div class="form-row">
    <div class="form-group col-md-6">
        <label>Nama Lengkap *</label>
        <input type="text" name="nama_pemesan" class="form-control" value="<?= old('nama_pemesan') ?>" required>
    </div>
    <div class="form-group col-md-6">
        <label>No HP *</label>
        <input type="text" name="no_hp" class="form-control" value="<?= old('no_hp') ?>" required>
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label>No KTP *</label>
        <input type="text" name="no_ktp" class="form-control" value="<?= old('no_ktp') ?>" required>
    </div>
    <div class="form-group col-md-6">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= old('email') ?>">
    </div>
</div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Tanggal Mulai Sewa *</label>
                                <input type="date" name="tgl_mulai" class="form-control" value="<?= old('tgl_mulai') ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Metode Bayar *</label>
                                <select name="metode_bayar" id="metodeBayar" class="form-control" required>
                                    <option value="tunai" <?= old('metode_bayar') == 'tunai' ? 'selected' : '' ?>>Tunai</option>
                                    <option value="qris" <?= old('metode_bayar') == 'qris' ? 'selected' : '' ?>>QRIS</option>
                                </select>
                            </div>
                        </div>

                        <!-- QRIS Box -->
                        <div class="form-group" id="qrisBox" style="display: <?= old('metode_bayar') == 'qris' ? 'block' : 'none' ?>;">
                            <label>Scan QRIS untuk Pembayaran</label>
                            <div class="text-center border rounded p-3 bg-light">
                                <div id="qrcode" style="display: inline-block; min-width: 200px; min-height: 200px;"></div>
                                <p class="text-muted mt-2 mb-0">Buka aplikasi e-wallet / mobile banking, scan kode QRIS.</p>
                                <p class="text-muted">Setelah transfer, upload bukti bayar di bawah.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Jumlah DP (minimum 50%)</label>
                            <input type="number" name="dp_dibayar" id="dp" class="form-control" min="<?= ceil(0.5 * $kamar['harga_per_tahun']) ?>" value="<?= old('dp_dibayar', ceil(0.5 * $kamar['harga_per_tahun'])) ?>" required>
                            <small id="sisaText" class="text-muted">Sisa: Rp 0</small>
                        </div>

                        <!-- Upload Bukti Bayar -->
                        <div class="form-group">
                            <label>Upload Bukti Bayar (opsional, JPG/PNG/PDF)</label>
                            <div class="custom-file">
                                <input type="file" name="bukti_bayar" class="custom-file-input" id="buktiBayar">
                                <label class="custom-file-label" for="buktiBayar">Pilih file...</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg btn-block"><i class="fas fa-check mr-1"></i> Konfirmasi Pemesanan</button>
                        <a href="<?= base_url('katalog') ?>" class="btn btn-outline-secondary btn-block mt-2">Kembali ke Katalog</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Cek apakah library QRCode sudah terload
    if (typeof QRCode === 'undefined') {
        console.error('Library QRCode tidak ter-load. Periksa koneksi internet atau CDN.');
        document.getElementById('qrcode').innerHTML = '<p class="text-danger">QR Code gagal dimuat. Silakan refresh halaman.</p>';
    }

    const total = <?= $kamar['harga_per_tahun'] ?>;
    const dpInput = document.getElementById('dp');
    const sisaText = document.getElementById('sisaText');
    const metodeBayar = document.getElementById('metodeBayar');
    const qrisBox = document.getElementById('qrisBox');
    const qrcodeDiv = document.getElementById('qrcode');

    function updateQR() {
        // Hapus QR sebelumnya
        qrcodeDiv.innerHTML = '';
        const dp = parseInt(dpInput.value) || 0;
        if (dp > 0 && metodeBayar.value === 'qris' && typeof QRCode !== 'undefined') {
            const qrText = 'KOSTREGAR - DP Rp' + dp.toLocaleString('id-ID') + ' - REK 1234567890';
            try {
                new QRCode(qrcodeDiv, {
                    text: qrText,
                    width: 220,
                    height: 220,
                    colorDark : "#000000",
                    colorLight : "#ffffff",
                });
            } catch (e) {
                console.error(e);
                qrcodeDiv.innerHTML = '<p class="text-danger">Gagal membuat QR code.</p>';
            }
        }
    }

    function updateSisa() {
        let dp = parseInt(dpInput.value) || 0;
        if (dp > total) dp = total;
        dpInput.value = dp; // koreksi jika melebihi total
        let sisa = total - dp;
        sisaText.textContent = 'Sisa: Rp ' + sisa.toLocaleString('id-ID');
    }

    dpInput.addEventListener('input', function() {
        updateSisa();
        updateQR();
    });

    metodeBayar.addEventListener('change', function() {
        if (this.value === 'qris') {
            qrisBox.style.display = 'block';
            updateQR();
        } else {
            qrisBox.style.display = 'none';
        }
    });

    // Inisialisasi saat halaman dimuat
    updateSisa();
    if (metodeBayar.value === 'qris') {
        qrisBox.style.display = 'block';
        updateQR();
    } else {
        qrisBox.style.display = 'none';
    }
</script>

<?= $this->endSection() ?>