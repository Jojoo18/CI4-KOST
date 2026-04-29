<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Pemesanan #<?= $pemesanan['id_pesanan'] ?></h3>
    </div>
    <form action="<?= base_url('pemesanan/update/' . $pemesanan['id_pesanan']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kamar</label>
                        <select name="id_kamar" class="form-control" required>
                            <?php foreach ($kamar as $k): ?>
                            <option value="<?= $k['id_kamar'] ?>" <?= $k['id_kamar'] == $pemesanan['id_kamar'] ? 'selected' : '' ?>>
                                <?= $k['nomor_kamar'] ?> - <?= ucfirst($k['tipe']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nama Pemesan</label>
                        <input type="text" name="nama_pemesan" class="form-control" value="<?= esc($pemesanan['nama_pemesan']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= esc($pemesanan['no_hp']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= esc($pemesanan['email']) ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Tanggal Mulai Sewa</label>
                        <input type="date" name="tgl_mulai" class="form-control" value="<?= $pemesanan['tgl_mulai'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Total Biaya (Rp/tahun)</label>
                        <input type="number" name="total_biaya" class="form-control" value="<?= $pemesanan['total_biaya'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>DP Dibayar</label>
                        <input type="number" name="dp_dibayar" class="form-control" value="<?= $pemesanan['dp_dibayar'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Metode Bayar</label>
                        <select name="metode_bayar" class="form-control" required>
                            <option value="tunai" <?= $pemesanan['metode_bayar'] == 'tunai' ? 'selected' : '' ?>>Tunai</option>
                            <option value="qris" <?= $pemesanan['metode_bayar'] == 'qris' ? 'selected' : '' ?>>QRIS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="menunggu_dp" <?= $pemesanan['status'] == 'menunggu_dp' ? 'selected' : '' ?>>Menunggu DP</option>
                            <option value="dp_diterima" <?= $pemesanan['status'] == 'dp_diterima' ? 'selected' : '' ?>>DP Diterima</option>
                            <option value="lunas" <?= $pemesanan['status'] == 'lunas' ? 'selected' : '' ?>>Lunas</option>
                            <option value="batal" <?= $pemesanan['status'] == 'batal' ? 'selected' : '' ?>>Batal</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
            <a href="<?= base_url('pemesanan') ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>