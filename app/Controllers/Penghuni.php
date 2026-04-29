<?php
namespace App\Controllers;

use App\Models\PenghuniModel;
use App\Models\KamarModel;

class Penghuni extends BaseController
{
    protected $model;
    protected $kamarModel;
    protected $config;

    public function __construct()
    {
        $this->model      = new PenghuniModel();
        $this->kamarModel = new KamarModel();

        // Ambil daftar kamar untuk dropdown
        $kamarList = $this->kamarModel->findAll();
        $kamarOptions = [];
        foreach ($kamarList as $k) {
            $kamarOptions[$k['id_kamar']] = $k['nomor_kamar'] . ' - ' . ucfirst($k['tipe']);
        }

        $this->config = [
            'routePrefix' => 'penghuni',
            'primaryKey'  => 'id_penghuni',
            'title'       => 'Manajemen Penghuni',
            'fields'      => [
                [
                    'name'     => 'id_kamar',
                    'label'    => 'Kamar',
                    'type'     => 'select',
                    'options'  => $kamarOptions,
                    'required' => true,
                ],
                [
                    'name'     => 'nama_lengkap',
                    'label'    => 'Nama Lengkap',
                    'type'     => 'text',
                    'required' => true,
                ],
                [
                    'name'     => 'no_ktp',
                    'label'    => 'No KTP',
                    'type'     => 'text',
                    'required' => true,
                ],
                [
                    'name'     => 'no_hp',
                    'label'    => 'No HP',
                    'type'     => 'text',
                    'required' => true,
                ],
                [
                    'name'     => 'tgl_masuk',
                    'label'    => 'Tanggal Masuk',
                    'type'     => 'date',
                    'format'   => 'date',
                    'required' => true,
                ],
                [
                    'name'     => 'tgl_keluar',
                    'label'    => 'Tanggal Keluar',
                    'type'     => 'date',
                    'format'   => 'date',
                    'hideInList' => true, // tidak ditampilkan di tabel
                ],
                [
                    'name'    => 'status',
                    'label'   => 'Status',
                    'type'    => 'select',
                    'options' => ['aktif' => 'Aktif', 'keluar' => 'Keluar'],
                    'format'  => 'badge',
                    'badgeMap'=> ['aktif' => 'success', 'keluar' => 'secondary'],
                    'valueMap'=> ['aktif' => 'Aktif', 'keluar' => 'Keluar'],
                ],
            ],
        ];
    }

    // ─── Override store ───
    public function store()
    {
        $data = [];
        foreach ($this->config['fields'] as $f) {
            $data[$f['name']] = $this->request->getPost($f['name']);
        }
        // Jika status aktif, ubah status kamar menjadi terisi
        if ($data['status'] == 'aktif') {
            $this->kamarModel->update($data['id_kamar'], ['status' => 'terisi']);
        }
        $this->model->insert($data);
        return redirect()->to($this->config['routePrefix'])->with('success', 'Penghuni berhasil ditambahkan');
    }

    // ─── Override update ───
    public function update($id)
    {
        $old = $this->model->find($id);
        $data = [];
        foreach ($this->config['fields'] as $f) {
            $data[$f['name']] = $this->request->getPost($f['name']);
        }

        // Jika status berubah menjadi keluar, bebaskan kamar
        if ($data['status'] == 'keluar') {
            $this->kamarModel->update($data['id_kamar'], ['status' => 'tersedia']);
        }
        // Jika pindah kamar
        if ($old['id_kamar'] != $data['id_kamar']) {
            $this->kamarModel->update($old['id_kamar'], ['status' => 'tersedia']);
            if ($data['status'] == 'aktif') {
                $this->kamarModel->update($data['id_kamar'], ['status' => 'terisi']);
            }
        }
        // Jika status tetap aktif tapi kamar sama, pastikan kamar tetap terisi
        if ($data['status'] == 'aktif' && $old['id_kamar'] == $data['id_kamar']) {
            $this->kamarModel->update($data['id_kamar'], ['status' => 'terisi']);
        }

        $this->model->update($id, $data);
        return redirect()->to($this->config['routePrefix'])->with('success', 'Data penghuni diupdate');
    }

    // ─── Override delete ───
    public function delete($id)
    {
        $penghuni = $this->model->find($id);
        if ($penghuni['status'] == 'aktif') {
            $this->kamarModel->update($penghuni['id_kamar'], ['status' => 'tersedia']);
        }
        $this->model->delete($id);
        return redirect()->to($this->config['routePrefix'])->with('success', 'Penghuni dihapus');
    }

    // ─── Method standar lainnya ───
    public function index()
    {
        return view('crud', [
            'title'    => $this->config['title'],
            'fields'   => $this->config['fields'],
            'listData' => $this->model->getPenghuniWithKamar(), // method custom
            'config'   => $this->config,
        ]);
    }

    public function create()
    {
        return view('crud', [
            'title'    => 'Tambah ' . $this->config['title'],
            'fields'   => $this->config['fields'],
            'action'   => base_url($this->config['routePrefix'] . '/store'),
            'isCreate' => true,
            'config'   => $this->config,
        ]);
    }

    public function edit($id)
    {
        return view('crud', [
            'title'  => 'Edit ' . $this->config['title'],
            'fields' => $this->config['fields'],
            'data'   => $this->model->find($id),
            'action' => base_url($this->config['routePrefix'] . '/update/' . $id),
            'isEdit' => true,
            'config' => $this->config,
        ]);
    }
}