<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pemesanan</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kamar</th>
                    <th>Total/Tahun</th>
                    <th>DP</th>
                    <th>Sisa</th>
                    <th>Status</th>
                    <th>Bukti Bayar</th>
                    <th>Tanggal Pesan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($pemesanan as $p): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $p['nama_pemesan'] ?></td>
                    <td><?= $p['nomor_kamar'] ?> - <?= ucfirst($p['tipe']) ?></td>
                    <td>Rp <?= number_format($p['total_biaya'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($p['dp_dibayar'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($p['sisa_bayar'], 0, ',', '.') ?></td>
                    <td><?= badgeStatus($p['status']) ?></td>
                    <td>
                        <?php if ($p['bukti_bayar']): ?>
                            <a href="<?= base_url('writable/uploads/bukti/' . $p['bukti_bayar']) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-file"></i> Lihat
                            </a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?= $p['tgl_pesan'] ?></td>
                    <td>
                        <div class="btn-group">
                            <?php if ($p['status'] == 'menunggu_dp'): ?>
                                <a href="<?= base_url('pemesanan/konfirmasi/'.$p['id_pesanan']) ?>" class="btn btn-sm btn-success">Konfirmasi DP</a>
                            <?php endif; ?>
                            <?php if ($p['status'] == 'dp_diterima'): ?>
                                <a href="<?= base_url('pemesanan/lunas/'.$p['id_pesanan']) ?>" class="btn btn-sm btn-primary">Lunas</a>
                            <?php endif; ?>
                            <?php if ($p['status'] != 'batal'): ?>
                                <a href="<?= base_url('pemesanan/batal/'.$p['id_pesanan']) ?>" class="btn btn-sm btn-danger">Batal</a>
                            <?php endif; ?>
                            <a href="<?= base_url('pemesanan/edit/'.$p['id_pesanan']) ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
function badgeStatus($status) {
    $map = [
        'menunggu_dp' => 'warning',
        'dp_diterima' => 'info',
        'lunas' => 'success',
        'batal' => 'danger',
    ];
    $badge = $map[$status] ?? 'secondary';
    return '<span class="badge badge-'.$badge.'">'.str_replace('_', ' ', ucfirst($status)).'</span>';
}
?>

<?= $this->endSection() ?>