<?php

namespace App\Controllers;

use App\Models\PemesananModel;
use App\Models\KamarModel;

class PemesananController extends BaseController
{
    protected $model;
    protected $kamarModel;

    public function __construct()
    {
        $this->model = new PemesananModel();
        $this->kamarModel = new KamarModel();
    }

    // -----------------------------------------------------------------
    //  TAMPILAN DAFTAR
    // -----------------------------------------------------------------
    public function index()
    {
        $data['pemesanan'] = $this->model->getPemesananWithKamar();
        $data['title'] = 'Manajemen Pemesanan';
        return view('admin/pemesanan/index', $data);
    }

    // -----------------------------------------------------------------
    //  EDIT
    // -----------------------------------------------------------------
    public function edit($id)
    {
        $data['pemesanan'] = $this->model->find($id);
        $data['kamar'] = $this->kamarModel->findAll();
        $data['title'] = 'Edit Pemesanan';
        return view('admin/pemesanan/edit', $data);
    }

    // -----------------------------------------------------------------
    //  UPDATE
    // -----------------------------------------------------------------
    public function update($id)
    {
        $this->model->update($id, [
            'id_kamar'     => $this->request->getPost('id_kamar'),
            'nama_pemesan' => $this->request->getPost('nama_pemesan'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'no_ktp'       => $this->request->getPost('no_ktp'),
            'email'        => $this->request->getPost('email'),
            'tgl_mulai'    => $this->request->getPost('tgl_mulai'),
            'total_biaya'  => $this->request->getPost('total_biaya'),
            'dp_dibayar'   => $this->request->getPost('dp_dibayar'),
            'metode_bayar' => $this->request->getPost('metode_bayar'),
            'status'       => $this->request->getPost('status'),
        ]);
        return redirect()->to('/pemesanan')->with('success', 'Pemesanan diupdate.');
    }

    // -----------------------------------------------------------------
    //  KONFIRMASI DP
    // -----------------------------------------------------------------
    public function konfirmasiDp($id)
    {
        $this->model->update($id, [
            'status'       => 'dp_diterima',
            'tgl_bayar_dp' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/pemesanan')->with('success', 'DP dikonfirmasi.');
    }

    // -----------------------------------------------------------------
    //  LUNAS → JADI PENGHUNI
    // -----------------------------------------------------------------
    public function lunas($id)
{
    $db = \Config\Database::connect();
    $p = $db->table('tbl_pemesanan')->where('id_pesanan', $id)->get()->getRowArray();
    if (!$p) {
        return redirect()->to('/pemesanan')->with('error', 'Data tidak ditemukan');
    }

    // Update pemesanan jadi lunas
    $db->table('tbl_pemesanan')->update([
        'status'    => 'lunas',
        'tgl_lunas' => date('Y-m-d H:i:s'),
        'dp_dibayar'=> $p['total_biaya'],
    ], ['id_pesanan' => $id]);

    // Insert ke penghuni
    $db->table('tbl_penghuni')->insert([
        'id_kamar'     => $p['id_kamar'],
        'nama_lengkap' => $p['nama_pemesan'],
        'no_ktp'       => $p['no_ktp'] ?? '-',
        'no_hp'        => $p['no_hp'],
        'tgl_masuk'    => $p['tgl_mulai'],
        'status'       => 'aktif',
    ]);

    $id_penghuni = $db->insertID();

    // Update status kamar
    $db->table('tbl_kamar')->where('id_kamar', $p['id_kamar'])->update(['status' => 'terisi']);

    // Ambil harga kamar
    $kamar = $db->table('tbl_kamar')->where('id_kamar', $p['id_kamar'])->get()->getRowArray();

    // Buat tagihan tahun ini
    $db->table('tbl_tagihan')->insert([
        'id_penghuni' => $id_penghuni,
        'tahun'       => date('Y'),
        'total_bayar' => $kamar['harga_per_bulan'] * 12,
        'status'      => 'lunas', // langsung lunas
        'tgl_bayar'   => date('Y-m-d'),
    ]);

    // ✅ Catat pemasukan ke Cashflow
    $db->table('tbl_cashflow')->insert([
        'tipe'         => 'pemasukan',
        'jumlah'       => $kamar['harga_per_bulan'] * 12,
        'keterangan'   => 'Sewa kamar ' . $kamar['nomor_kamar'] . ' - ' . $p['nama_pemesan'],
        'tanggal'      => date('Y-m-d'),
        'metode_bayar' => $p['metode_bayar'],
    ]);

    return redirect()->to('/pemesanan')->with('success', 'Lunas, penghuni + tagihan + pemasukan tercatat.');
}

    // -----------------------------------------------------------------
    //  BATAL
    // -----------------------------------------------------------------
    public function batal($id)
    {
        $db = \Config\Database::connect();
        $p = $db->table('tbl_pemesanan')
                ->where('id_pesanan', $id)
                ->get()
                ->getRowArray();

        if ($p) {
            // Update status pemesanan jadi batal
            $db->table('tbl_pemesanan')->update([
                'status' => 'batal'
            ], ['id_pesanan' => $id]);

            // Kembalikan status kamar jadi tersedia (hanya jika belum lunas)
            if ($p['status'] != 'lunas') {
                $db->table('tbl_kamar')
                   ->where('id_kamar', $p['id_kamar'])
                   ->update(['status' => 'tersedia']);
            }
        }

        return redirect()->to('/pemesanan')->with('success', 'Pemesanan dibatalkan, kamar kembali tersedia.');
    }

    // -----------------------------------------------------------------
    //  CETAK STRUK
    // -----------------------------------------------------------------
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
            return redirect()->to('/pemesanan')->with('error', 'Data tidak ditemukan');
        }

        return view('public/struk', ['pemesanan' => $p]);
    }
}