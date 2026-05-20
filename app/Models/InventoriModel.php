<?php
namespace App\Models;
use CodeIgniter\Model;

class InventoriModel extends Model
{
    protected $table      = 'tbl_inventori';
    protected $primaryKey = 'id_barang';
    protected $allowedFields = [
    'id_kamar', 'nama_barang', 'jumlah', 'harga_satuan', 'kondisi', 'keterangan'];
    protected $useTimestamps = false;

    public function getInventoriWithKamar()
    {
        return $this->select('tbl_inventori.*, tbl_kamar.nomor_kamar')
                    ->join('tbl_kamar', 'tbl_kamar.id_kamar = tbl_inventori.id_kamar', 'left')
                    ->findAll();
    }
}