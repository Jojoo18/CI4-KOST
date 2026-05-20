<?php
namespace App\Models;
use CodeIgniter\Model;

class PenghuniModel extends Model
{
    protected $table      = 'tbl_penghuni';
    protected $primaryKey = 'id_penghuni';
    protected $allowedFields = [
    'id_kamar', 'nama_lengkap', 'no_ktp', 'no_hp',
    'tgl_masuk', 'tgl_keluar', 'status'
];
    protected $useTimestamps = false;

    public function getPenghuniWithKamar()
    {
        return $this->select('tbl_penghuni.*, tbl_kamar.nomor_kamar')
                    ->join('tbl_kamar', 'tbl_kamar.id_kamar = tbl_penghuni.id_kamar')
                    ->orderBy('tbl_penghuni.id_penghuni', 'DESC')
                    ->findAll();
    }
}