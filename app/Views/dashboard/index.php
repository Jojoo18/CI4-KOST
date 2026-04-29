<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3><?= $total_kamar ?></h3>
                <p>Total Kamar</p>
            </div>
            <div class="icon"><i class="fas fa-bed"></i></div>
            <a href="<?= base_url('kamar') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $kamar_tersedia ?></h3>
                <p>Kamar Tersedia</p>
            </div>
            <div class="icon"><i class="fas fa-door-open"></i></div>
            <a href="<?= base_url('kamar') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $kamar_terisi ?></h3>
                <p>Kamar Terisi</p>
            </div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
            <a href="<?= base_url('penghuni') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $total_penghuni ?></h3>
                <p>Penghuni Aktif</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="<?= base_url('penghuni') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card bg-success">
            <div class="card-header">
                <h3 class="card-title text-white"><i class="fas fa-arrow-down mr-2"></i>Total Pemasukan</h3>
            </div>
            <div class="card-body text-center">
                <h2 class="text-white">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-danger">
            <div class="card-header">
                <h3 class="card-title text-white"><i class="fas fa-arrow-up mr-2"></i>Total Pengeluaran</h3>
            </div>
            <div class="card-body text-center">
                <h2 class="text-white">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>
</div>

<?php if (isset($total_pemesanan_baru) && $total_pemesanan_baru > 0): ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-shopping-cart mr-2"></i> Ada <strong><?= $total_pemesanan_baru ?></strong> pemesanan baru menunggu konfirmasi DP. 
            <a href="<?= base_url('pemesanan') ?>" class="btn btn-sm btn-primary ml-3">Lihat Pemesanan</a>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>