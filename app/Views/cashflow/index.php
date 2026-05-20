<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Filter & Ringkasan seperti sebelumnya -->
<div class="row mb-3">
    <div class="col-md-6">
        <form action="<?= base_url('cashflow') ?>" method="get" class="form-inline">
            <div class="input-group">
                <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                <div class="input-group-append"><span class="input-group-text">s/d</span></div>
                <input type="date" name="tgl_akhir" class="form-control" value="<?= $tgl_akhir ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-filter"></i> Filter</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?= base_url('cashflow/create') ?>" class="btn btn-success"><i class="fas fa-plus"></i> Tambah Transaksi</a>
    </div>
</div>

<!-- Ringkasan -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>Rp <?= number_format($total_masuk, 0, ',', '.') ?></h3><p>Total Pemasukan</p></div>
            <div class="icon"><i class="fas fa-arrow-down"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>Rp <?= number_format($total_keluar, 0, ',', '.') ?></h3><p>Total Pengeluaran</p></div>
            <div class="icon"><i class="fas fa-arrow-up"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>Rp <?= number_format($saldo, 0, ',', '.') ?></h3><p>Saldo</p></div>
            <div class="icon"><i class="fas fa-wallet"></i></div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Pemasukan vs Pengeluaran (<?= date('Y') ?>)</h3></div>
            <div class="card-body"><canvas id="barChart" style="height: 300px;"></canvas></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Metode Pembayaran</h3></div>
            <div class="card-body"><canvas id="pieChart" style="height: 250px;"></canvas></div>
        </div>
    </div>
</div>

<!-- Tabel -->
<div class="card">
    <div class="card-header"><h3 class="card-title">Riwayat Transaksi</h3></div>
    <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-striped table-datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Metode Bayar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($cashflow as $c): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $c['tanggal'] ?></td>
                    <td>
                        <?php if ($c['tipe'] == 'pemasukan'): ?>
                            <span class="badge badge-success">Pemasukan</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Pengeluaran</span>
                        <?php endif; ?>
                    </td>
                    <td>Rp <?= number_format($c['jumlah'], 0, ',', '.') ?></td>
                    <td><?= $c['keterangan'] ?></td>
                    <td><?= ucfirst($c['metode_bayar']) ?></td>
                    <td>
                        <a href="<?= base_url('cashflow/edit/'.$c['id_cashflow']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                        <a href="<?= base_url('cashflow/delete/'.$c['id_cashflow']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus transaksi?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxBar = document.getElementById('barChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: <?= $grafik_bulan ?>,
            datasets: [
                { label: 'Pemasukan', data: <?= $grafik_pemasukan ?>, backgroundColor: 'rgba(40, 167, 69, 0.7)', borderColor: 'rgba(40, 167, 69, 1)', borderWidth: 1 },
                { label: 'Pengeluaran', data: <?= $grafik_pengeluaran ?>, backgroundColor: 'rgba(220, 53, 69, 0.7)', borderColor: 'rgba(220, 53, 69, 1)', borderWidth: 1 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
    });

    const ctxPie = document.getElementById('pieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: <?= $grafik_label_metode ?>,
            datasets: [{ data: <?= $grafik_data_metode ?>, backgroundColor: ['#007bff','#28a745','#ffc107'] }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>

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