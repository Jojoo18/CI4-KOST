<?php
namespace App\Models;
use CodeIgniter\Model;

class CashflowModel extends Model
{
    protected $table      = 'tbl_cashflow';
    protected $primaryKey = 'id_cashflow';
    protected $allowedFields = ['tipe', 'jumlah', 'keterangan', 'tanggal', 'metode_bayar'];
    protected $useTimestamps = false;
}