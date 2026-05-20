<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// Variabel wajib dari controller:
// $title, $fields, $data, $action, $isEdit, $listData, $config
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= $title ?></h3>
        <?php if (!isset($isEdit) && !isset($isCreate)): ?>
        <div class="card-tools">
            <a href="<?= base_url($config['routePrefix'].'/create') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (isset($listData)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-datatable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <?php foreach ($fields as $f): ?>
                                <?php if (!isset($f['hideInList']) || $f['hideInList'] === false): ?>
                                    <th><?= $f['label'] ?></th>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($listData as $row): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <?php foreach ($fields as $f): ?>
                                <?php if (!isset($f['hideInList']) || $f['hideInList'] === false): ?>
                                    <td>
                                        <?php
                                        $value = $row[$f['name']] ?? '';
                                        if (isset($f['format']) && $f['format'] == 'rupiah') {
                                            echo 'Rp ' . number_format($value, 0, ',', '.');
                                        } elseif (isset($f['format']) && $f['format'] == 'badge') {
                                            $badgeClass = $f['badgeMap'][$value] ?? 'secondary';
                                            $label = $f['valueMap'][$value] ?? $value;
                                            echo '<span class="badge badge-'.$badgeClass.'">'.$label.'</span>';
                                        } elseif (isset($f['format']) && $f['format'] == 'date') {
                                            echo $value ? date('d/m/Y', strtotime($value)) : '-';
                                        } else {
                                            echo esc($value);
                                        }
                                        ?>
                                    </td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td>
                                <a href="<?= base_url($config['routePrefix'].'/edit/'.$row[$config['primaryKey']]) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <a href="<?= base_url($config['routePrefix'].'/delete/'.$row[$config['primaryKey']]) ?>" class="btn btn-danger btn-sm btn-delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Form Create/Edit tetap sama -->
        <?php endif; ?>
    </div>
</div>

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