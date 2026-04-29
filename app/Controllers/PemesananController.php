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

    public function index()
    {
        $data['pemesanan'] = $this->model->getPemesananWithKamar();
        $data['title'] = 'Manajemen Pemesanan';
        return view('admin/pemesanan/index', $data);
    }

    public function edit($id)
    {
        $data['pemesanan'] = $this->model->find($id);
        $data['kamar'] = $this->kamarModel->findAll();
        $data['title'] = 'Edit Pemesanan';
        return view('admin/pemesanan/edit', $data);
    }

    public function update($id)
    {
        $this->model->update($id, [
            'id_kamar'    => $this->request->getPost('id_kamar'),
            'nama_pemesan'=> $this->request->getPost('nama_pemesan'),
            'no_hp'       => $this->request->getPost('no_hp'),
            'email'       => $this->request->getPost('email'),
            'tgl_mulai'   => $this->request->getPost('tgl_mulai'),
            'total_biaya' => $this->request->getPost('total_biaya'),
            'dp_dibayar'  => $this->request->getPost('dp_dibayar'),
            'metode_bayar'=> $this->request->getPost('metode_bayar'),
            'status'      => $this->request->getPost('status'),
        ]);
        return redirect()->to('/pemesanan')->with('success', 'Pemesanan diupdate.');
    }

    public function konfirmasiDp($id)
    {
        $this->model->update($id, [
            'status' => 'dp_diterima',
            'tgl_bayar_dp' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/pemesanan')->with('success', 'DP dikonfirmasi.');
    }

    public function lunas($id)
    {
        $pesanan = $this->model->find($id);
        $this->model->update($id, [
            'status' => 'lunas',
            'tgl_lunas' => date('Y-m-d H:i:s'),
            'dp_dibayar' => $pesanan['total_biaya'], // lunas = dp jadi full
        ]);
        // Ubah status kamar menjadi terisi
        $this->kamarModel->update($pesanan['id_kamar'], ['status' => 'terisi']);
        return redirect()->to('/pemesanan')->with('success', 'Pembayaran lunas, kamar berhasil ditempati.');
    }

    public function batal($id)
    {
        $this->model->update($id, ['status' => 'batal']);
        return redirect()->to('/pemesanan')->with('success', 'Pemesanan dibatalkan.');
    }
}