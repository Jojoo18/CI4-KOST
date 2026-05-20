<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Barang</h3>
        <div class="card-tools">
            <a href="<?= base_url('inventori/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Barang
            </a>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Kamar</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total Harga</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($inventori as $i): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $i['nama_barang'] ?></td>
                    <td><?= $i['nomor_kamar'] ?? 'Area Umum' ?></td>
                    <td><?= $i['jumlah'] ?></td>
                    <td>Rp <?= number_format($i['harga_satuan'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($i['harga_satuan'] * $i['jumlah'], 0, ',', '.') ?></td>
                    <td>
                        <?php
                        $badge = 'secondary';
                        if ($i['kondisi'] == 'baik') $badge = 'success';
                        elseif ($i['kondisi'] == 'rusak') $badge = 'danger';
                        elseif ($i['kondisi'] == 'hilang') $badge = 'warning';
                        ?>
                        <span class="badge badge-<?= $badge ?>"><?= ucfirst($i['kondisi']) ?></span>
                    </td>
                    <td>
                        <a href="<?= base_url('inventori/edit/'.$i['id_barang']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="<?= base_url('inventori/delete/'.$i['id_barang']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus barang?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>