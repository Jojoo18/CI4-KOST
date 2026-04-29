<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// Variabel wajib dari controller:
// $title      : judul halaman
// $fields     : array daftar field (nama, label, tipe input)
// $data       : data untuk ditampilkan (null jika create)
// $action     : URL tujuan form (store/update)
// $isEdit     : boolean mode edit
// $listData   : data untuk tabel (null jika create/edit)
// $config     : pengaturan tambahan (opsi select, dll)
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
            <!-- Tampilan Tabel Daftar -->
            <div class="table-responsive">
                <table class="table table-hover">
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
                                        // Format khusus
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
                                <a href="<?= base_url($config['routePrefix'].'/delete/'.$row[$config['primaryKey']]) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Form Create/Edit -->
            <form action="<?= $action ?>" method="post">
                <?= csrf_field() ?>
                <?php foreach ($fields as $f): ?>
                    <div class="form-group">
                        <label><?= $f['label'] ?></label>
                        <?php if ($f['type'] == 'select'): ?>
                            <select name="<?= $f['name'] ?>" class="form-control" <?= isset($f['required']) ? 'required' : '' ?>>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($f['options'] as $optValue => $optLabel): ?>
                                    <option value="<?= $optValue ?>" <?= (isset($data) && $data[$f['name']] == $optValue) ? 'selected' : '' ?>><?= $optLabel ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php elseif ($f['type'] == 'textarea'): ?>
                            <textarea name="<?= $f['name'] ?>" class="form-control" rows="2"><?= isset($data) ? esc($data[$f['name']]) : '' ?></textarea>
                        <?php elseif ($f['type'] == 'date'): ?>
                            <input type="date" name="<?= $f['name'] ?>" class="form-control" value="<?= isset($data) ? $data[$f['name']] : '' ?>" <?= isset($f['required']) ? 'required' : '' ?>>
                        <?php elseif ($f['type'] == 'number'): ?>
                            <input type="number" name="<?= $f['name'] ?>" class="form-control" value="<?= isset($data) ? $data[$f['name']] : (isset($f['default']) ? $f['default'] : '') ?>" <?= isset($f['required']) ? 'required' : '' ?>>
                        <?php else: ?>
                            <input type="<?= $f['type'] ?? 'text' ?>" name="<?= $f['name'] ?>" class="form-control" value="<?= isset($data) ? esc($data[$f['name']]) : '' ?>" <?= isset($f['required']) ? 'required' : '' ?>>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="<?= base_url($config['routePrefix']) ?>" class="btn btn-secondary">Batal</a>
            </form>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>