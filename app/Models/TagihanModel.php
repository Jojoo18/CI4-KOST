<?php
namespace App\Models;
use CodeIgniter\Model;

class TagihanModel extends Model
{
    protected $table      = 'tbl_tagihan';
    protected $primaryKey = 'id_tagihan';
    protected $allowedFields = ['id_penghuni', 'tahun', 'total_bayar', 'status', 'tgl_bayar'];
    protected $useTimestamps = false;

    public function getTagihanWithPenghuni()
    {
        return $this->select('tbl_tagihan.*, tbl_penghuni.nama_lengkap, tbl_kamar.nomor_kamar')
                    ->join('tbl_penghuni', 'tbl_penghuni.id_penghuni = tbl_tagihan.id_penghuni')
                    ->join('tbl_kamar', 'tbl_kamar.id_kamar = tbl_penghuni.id_kamar', 'left')
                    ->orderBy('tbl_tagihan.tahun', 'DESC')
                    ->findAll();
    }
}