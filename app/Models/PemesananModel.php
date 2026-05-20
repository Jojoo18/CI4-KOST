<?php
namespace App\Models;

use CodeIgniter\Model;

class PemesananModel extends Model
{
    protected $table      = 'tbl_pemesanan';
    protected $primaryKey = 'id_pesanan';
    protected $allowedFields = [
        'id_kamar', 'nama_pemesan', 'no_hp', 'no_ktp', 'email',
        'tgl_mulai', 'total_biaya', 'dp_dibayar',
        'metode_bayar', 'status', 'bukti_bayar',
        'tgl_bayar_dp', 'tgl_lunas'
    ];
    protected $useTimestamps = false;

    public function getPemesananWithKamar()
    {
        return $this->select('tbl_pemesanan.*, tbl_kamar.nomor_kamar, tbl_kamar.tipe')
                    ->join('tbl_kamar', 'tbl_kamar.id_kamar = tbl_pemesanan.id_kamar')
                    ->orderBy('tbl_pemesanan.id_pesanan', 'DESC')
                    ->findAll();
    }
}