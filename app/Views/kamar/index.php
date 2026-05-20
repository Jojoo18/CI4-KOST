<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kamar</h3>
        <div class="card-tools">
            <a href="<?= base_url('kamar/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Kamar
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table id="tabelKamar" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor</th>
                    <th>Tipe</th>
                    <th>Kapasitas</th>
                    <th>Harga/Bulan</th>
                    <th>Status</th>
                    <th>Fasilitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($kamar as $k): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($k['nomor_kamar']) ?></td>
                    <td>
                        <span class="badge badge-<?= $k['tipe'] == 'vip' ? 'danger' : ($k['tipe'] == 'deluxe' ? 'warning' : 'secondary') ?>">
                            <?= ucfirst($k['tipe']) ?>
                        </span>
                    </td>
                    <td><?= $k['kapasitas'] ?? 1 ?> Orang</td>
                    <td>Rp <?= number_format($k['harga_per_bulan'], 0, ',', '.') ?></td>
                    <td>
                        <?php if ($k['status'] == 'tersedia'): ?>
                            <span class="badge badge-success">Tersedia</span>
                        <?php elseif ($k['status'] == 'terisi'): ?>
                            <span class="badge badge-warning">Terisi</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Perbaikan</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($k['fasilitas']) ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="<?= base_url('kamar/edit/' . $k['id_kamar']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= base_url('kamar/delete/' . $k['id_kamar']) ?>" class="btn btn-danger btn-sm btn-delete" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables -->
<script>
    $(document).ready(function () {
        $('#tabelKamar').DataTable({
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
<?= $this->endSection() ?>