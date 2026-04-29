<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'KostRegar' ?> | Sistem Manajemen Kost</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user"></i> <?= session()->get('nama_lengkap') ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="<?= base_url('logout') ?>" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?= base_url('dashboard') ?>" class="brand-link">
            <span class="brand-text font-weight-light">KostRegar</span>
        </a>
        <li class="nav-item">
    <a href="<?= base_url('pemesanan') ?>" class="nav-link">
        <i class="nav-icon fas fa-receipt"></i>
        <p>Pemesanan</p>
    </a>
</li>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="<?= base_url('dashboard') ?>" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('kamar') ?>" class="nav-link">
                            <i class="nav-icon fas fa-bed"></i><p>Kamar</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('penghuni') ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i><p>Penghuni</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('cashflow') ?>" class="nav-link">
                            <i class="nav-icon fas fa-money-bill-wave"></i><p>Keuangan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('inventori') ?>" class="nav-link">
                            <i class="nav-icon fas fa-boxes"></i><p>Inventori</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('tagihan') ?>" class="nav-link">
                            <i class="nav-icon fas fa-file-invoice"></i><p>Tagihan</p>
                        </a>
                    </li>
                    <?php if (session()->get('role') == 'admin'): ?>
                    <li class="nav-item">
                        <a href="<?= base_url('user') ?>" class="nav-link">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>User</p>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1><?= $title ?? 'Halaman' ?></h1></div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?= $this->renderSection('content') ?>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>KostRegar &copy; <?= date('Y') ?></strong>
    </footer>
</div>
<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.btn-delete').on('click', function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        Swal.fire({
            title: 'Yakin?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = href;
<script>
$(document).ready(function() {
    // Konfirmasi semua tombol dengan class .btn-delete
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        Swal.fire({
            title: 'Yakin?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
});
</script>
</body>
</html>