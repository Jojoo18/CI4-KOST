<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pemesanan</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped table-datatable">
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
                            <a href="<?= base_url('download/bukti/' . $p['bukti_bayar']) ?>" class="btn btn-sm btn-info" target="_blank"><i class="fas fa-file-image"></i></a>
                        <?php else: ?>
                            - 
                        <?php endif; ?>
                    </td>
                    <td><?= $p['tgl_pesan'] ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="<?= base_url('pemesanan/struk/'.$p['id_pesanan']) ?>" class="btn btn-sm btn-info" target="_blank"><i class="fas fa-receipt"></i></a>
                            <?php if ($p['status'] == 'menunggu_dp'): ?>
                                <a href="<?= base_url('pemesanan/konfirmasi/'.$p['id_pesanan']) ?>" class="btn btn-sm btn-success">Konfirmasi DP</a>
                            <?php endif; ?>
                            <?php if ($p['status'] == 'dp_diterima'): ?>
                                <a href="<?= base_url('pemesanan/lunas/'.$p['id_pesanan']) ?>" class="btn btn-sm btn-primary">Lunas</a>
                            <?php endif; ?>
                            <?php if ($p['status'] != 'batal' && $p['status'] != 'lunas'): ?>
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
    $map = ['menunggu_dp' => 'warning', 'dp_diterima' => 'info', 'lunas' => 'success', 'batal' => 'danger'];
    $badge = $map[$status] ?? 'secondary';
    return '<span class="badge badge-'.$badge.'">'.str_replace('_', ' ', ucfirst($status)).'</span>';
}
?>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        $('.table-datatable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json" }
        });
    });
</script>
<?= $this->endSection() ?>