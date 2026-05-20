<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <h1 class="text-center mb-4">Katalog Kamar Tersedia</h1>
    <p class="text-center text-muted mb-5">Klik gambar untuk detail dan pesan sekarang</p>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (empty($kamar)): ?>
        <div class="text-center py-5">
            <i class="fas fa-bed fa-4x text-muted mb-3"></i>
            <h4>Maaf, semua kamar sedang tidak tersedia</h4>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($kamar as $k): ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card card-kamar h-100 shadow-sm border-0">
                    <!-- Gambar Kamar -->
                    <div class="card-img-top" style="height: 220px; overflow: hidden; border-radius: 12px 12px 0 0;">
                        <?php if (!empty($k['gambar']) && file_exists(FCPATH . 'images/kamar/' . $k['gambar'])): ?>
                            <img src="<?= base_url('images/kamar/' . $k['gambar']) ?>" 
                                 class="img-fluid w-100 h-100" style="object-fit: cover;" alt="Kamar <?= $k['nomor_kamar'] ?>">
                        <?php else: ?>
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                <i class="fas fa-bed fa-4x text-secondary"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body text-center">
                        <!-- Tipe & Kapasitas -->
                        <span class="badge badge-<?= $k['tipe'] == 'vip' ? 'danger' : ($k['tipe'] == 'deluxe' ? 'warning' : 'secondary') ?> mb-2">
                            <?= ucfirst($k['tipe']) ?> • <?= $k['kapasitas'] ?? 1 ?> Orang
                        </span>
                        
                        <h5 class="card-title">Kamar <?= $k['nomor_kamar'] ?></h5>
                        <p class="card-text small text-muted"><?= $k['fasilitas'] ?? 'Fasilitas standar' ?></p>
                        
                        <h4 class="text-success mb-2">
                            Rp <?= number_format($k['harga_per_bulan'] * 12, 0, ',', '.') ?> <small>/tahun</small>
                        </h4>
                        
                        <!-- Rating -->
                        <?php
                        $db = \Config\Database::connect();
                        $avg = $db->table('tbl_review')->where('id_kamar', $k['id_kamar'])->selectAvg('rating')->get()->getRow()->rating ?? 0;
                        $count = $db->table('tbl_review')->where('id_kamar', $k['id_kamar'])->countAllResults();
                        ?>
                        <p class="mb-2">⭐ <?= round($avg, 1) ?>/5 (<?= $count ?> ulasan)</p>
                        
                        <a href="<?= base_url('pesan/' . $k['id_kamar']) ?>" class="btn btn-primary btn-block mb-3">
                            <i class="fas fa-cart-plus mr-1"></i> Pesan Sekarang
                        </a>
                        
                        <!-- Form Review Singkat -->
                        <hr>
                        <form action="<?= base_url('review/store') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_kamar" value="<?= $k['id_kamar'] ?>">
                            <input type="text" name="nama_reviewer" class="form-control form-control-sm mb-1" placeholder="Nama Anda" required>
                            <select name="rating" class="form-control form-control-sm mb-1">
                                <option value="5">⭐⭐⭐⭐⭐</option>
                                <option value="4">⭐⭐⭐⭐</option>
                                <option value="3">⭐⭐⭐</option>
                                <option value="2">⭐⭐</option>
                                <option value="1">⭐</option>
                            </select>
                            <textarea name="komentar" class="form-control form-control-sm mb-1" placeholder="Komentar singkat..."></textarea>
                            <button type="submit" class="btn btn-sm btn-outline-primary btn-block">Kirim Review</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>