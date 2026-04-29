<?php
namespace App\Models;
use CodeIgniter\Model;

class KamarModel extends Model
{
    protected $table      = 'tbl_kamar';
    protected $primaryKey = 'id_kamar';
    protected $allowedFields = ['nomor_kamar', 'tipe', 'harga_per_bulan', 'status', 'fasilitas', 'keterangan'];
    protected $useTimestamps = false;
}