<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero d-flex align-items-center text-center text-white">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h1 class="display-4 font-weight-bold">Temukan Kost Impianmu</h1>
        <p class="lead">Harga terjangkau, fasilitas lengkap, lokasi strategis. Booking sekarang!</p>
        <a href="<?= base_url('katalog') ?>" class="btn btn-primary btn-lg mt-3">
            <i class="fas fa-search mr-2"></i>Lihat Katalog Kamar
        </a>
    </div>
</section>

<!-- Kenapa Memilih KostRegar -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="mb-5">Mengapa KostRegar?</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card p-4 shadow-sm h-100">
                    <i class="fas fa-wifi fa-3x text-primary mb-3"></i>
                    <h5>WiFi Cepat</h5>
                    <p class="text-muted">Internet kencang untuk kerja dan belajar.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4 shadow-sm h-100">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h5>Keamanan 24 Jam</h5>
                    <p class="text-muted">CCTV dan satpam menjaga kenyamanan Anda.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4 shadow-sm h-100">
                    <i class="fas fa-broom fa-3x text-primary mb-3"></i>
                    <h5>Kebersihan Terjaga</h5>
                    <p class="text-muted">Petugas kebersihan rutin membersihkan area kost.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kamar Populer -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Kamar Pilihan</h2>
        <div class="row">
            <?php foreach ($kamarPopuler as $k): ?>
            <div class="col-md-4 mb-4">
                <div class="card card-kamar h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Kamar <?= $k['nomor_kamar'] ?></h5>
                        <span class="badge badge-<?= $k['tipe'] == 'vip' ? 'danger' : ($k['tipe'] == 'deluxe' ? 'warning' : 'secondary') ?> mb-2">
                            <?= ucfirst($k['tipe']) ?>
                        </span>
                        <p class="card-text small"><?= $k['fasilitas'] ?? 'Fasilitas standar' ?></p>
                        <h4 class="text-success">Rp <?= number_format($k['harga_per_bulan'] * 12, 0, ',', '.') ?> <small>/tahun</small></h4>
                        <a href="<?= base_url('pesan/'.$k['id_kamar']) ?>" class="btn btn-outline-primary">Pesan</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= base_url('katalog') ?>" class="btn btn-primary btn-lg">Lihat Semua Kamar</a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>