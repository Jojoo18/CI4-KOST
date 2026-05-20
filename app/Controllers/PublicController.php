<?php

namespace App\Controllers;

use App\Models\KamarModel;
use App\Models\PemesananModel;

class PublicController extends BaseController
{
    public function katalog()
    {
        $kamarModel = new KamarModel();
        $data['kamar'] = $kamarModel->where('status', 'tersedia')->findAll();
        return view('public/katalog', $data);
    }

    public function pesan($id_kamar)
    {
        $kamarModel = new KamarModel();
        $kamar = $kamarModel->find($id_kamar);
        if (!$kamar || $kamar['status'] != 'tersedia') {
            return redirect()->to('/katalog')->with('error', 'Kamar tidak tersedia');
        }
        $kamar['harga_per_tahun'] = $kamar['harga_per_bulan'] * 12;
        return view('public/pesan', ['kamar' => $kamar]);
    }

    public function store()
{
    
    $pemesananModel = new PemesananModel();
    $kamarModel = new KamarModel();

    $id_kamar = $this->request->getPost('id_kamar');
    $kamar = $kamarModel->find($id_kamar);
    $total_biaya = $kamar['harga_per_bulan'] * 12;
    $dp_dibayar = (int) $this->request->getPost('dp_dibayar');
    $min_dp = ceil(0.5 * $total_biaya);

    if ($dp_dibayar < $min_dp) {
        return redirect()->back()
            ->with('error', 'DP minimal 50% dari total (Rp ' . number_format($min_dp, 0, ',', '.') . ')')
            ->withInput();
    }

    // Upload bukti bayar
    $file = $this->request->getFile('bukti_bayar');
    $namaFile = null;
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/bukti', $newName);
        $namaFile = $newName;
    }

    $data = [
        'id_kamar'     => $id_kamar,
        'nama_pemesan' => $this->request->getPost('nama_pemesan'),
        'no_hp'        => $this->request->getPost('no_hp'),
        'no_ktp'       => $this->request->getPost('no_ktp'),   // ← HARUS ADA
        'email'        => $this->request->getPost('email'),
        'tgl_mulai'    => $this->request->getPost('tgl_mulai'),
        'total_biaya'  => $total_biaya,
        'dp_dibayar'   => $dp_dibayar,
        'metode_bayar' => $this->request->getPost('metode_bayar'),
        'bukti_bayar'  => $namaFile,
        'status'       => 'menunggu_dp',
    ];
    $pemesananModel->insert($data);
    $id_pesanan = $pemesananModel->getInsertID();

    return redirect()->to('/struk/' . $id_pesanan);
}

    public function struk($id)
    {
        $db = \Config\Database::connect();
        $p = $db->table('tbl_pemesanan')
                ->select('tbl_pemesanan.*, tbl_kamar.nomor_kamar, tbl_kamar.tipe')
                ->join('tbl_kamar', 'tbl_kamar.id_kamar = tbl_pemesanan.id_kamar')
                ->where('id_pesanan', $id)
                ->get()
                ->getRowArray();

        if (!$p) {
            return redirect()->to('/katalog')->with('error', 'Pemesanan tidak ditemukan');
        }

        return view('public/struk', ['pemesanan' => $p]);
    }
}