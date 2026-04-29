<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'KostRegar' ?> | Sewa Kost Nyaman</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">
    <!-- CSS Custom -->
    <link rel="stylesheet" href="<?= base_url('css/custom.css') ?>">
</head>
<body class="layout-top-nav">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-light navbar-public fixed-top">
        <div class="container">
            <a href="<?= base_url() ?>" class="navbar-brand font-weight-bold">
                <i class="fas fa-home text-primary"></i> KostRegar
            </a>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a href="<?= base_url('katalog') ?>" class="nav-link">Katalog</a></li>
                <li class="nav-item"><a href="<?= base_url('login') ?>" class="btn btn-outline-primary btn-sm ml-3">Login Admin</a></li>
            </ul>
        </div>
    </nav>
    <div class="content-wrapper" style="margin-top: 56px;">
        <?= $this->renderSection('content') ?>
    </div>
    <footer class="main-footer text-center">
        <strong>KostRegar &copy; <?= date('Y') ?></strong> - Solusi Kost Modern
    </footer>
</div>
<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js') ?>"></script>
<!-- bs-custom-file-input -->
<script src="<?= base_url('assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') ?>"></script>
<!-- qrcode.js -->
<!-- qrcodejs (David Shim) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
</script>
</body>
</html>