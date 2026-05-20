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

<?php if ($jumlah_tagihan_belum_lunas > 0): ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Perhatian!</strong> Terdapat <strong><?= $jumlah_tagihan_belum_lunas ?></strong> tagihan yang belum lunas.
            <a href="<?= base_url('tagihan') ?>" class="btn btn-sm btn-outline-dark ml-3">Lihat Tagihan</a>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 🔔 Pengingat Tagihan Belum Lunas -->
<?php if (!empty($tagihan_terlambat)): ?>
<div class="row">
    <div class="col-12">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Tagihan Belum Lunas
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('tagihan') ?>" class="btn btn-tool btn-sm">
                        <i class="fas fa-arrow-right"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Penghuni</th>
                            <th>Kamar</th>
                            <th>Tahun</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tagihan_terlambat as $t): ?>
                        <tr>
                            <td><?= esc($t['nama_lengkap']) ?></td>
                            <td><?= esc($t['nomor_kamar']) ?></td>
                            <td><?= $t['tahun'] ?></td>
                            <td>Rp <?= number_format($t['total_bayar'], 0, ',', '.') ?></td>
                            <td>
                                <a href="<?= base_url('tagihan/edit/' . $t['id_tagihan']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Bayar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>