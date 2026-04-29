<?php
namespace App\Controllers;

use App\Models\KamarModel;

class Kamar extends BaseController
{
    protected $model;
    protected $config;

    public function __construct()
    {
        $this->model = new KamarModel();
        $this->config = [
            'routePrefix' => 'kamar',
            'primaryKey'  => 'id_kamar',
            'title'       => 'Manajemen Kamar',
            'fields'      => [
                [
                    'name'     => 'nomor_kamar',
                    'label'    => 'Nomor Kamar',
                    'type'     => 'text',
                    'required' => true,
                ],
                [
                    'name'     => 'tipe',
                    'label'    => 'Tipe',
                    'type'     => 'select',
                    'options'  => ['standar' => 'Standar', 'deluxe' => 'Deluxe', 'vip' => 'VIP'],
                    'format'   => 'badge',
                    'badgeMap' => ['standar' => 'secondary', 'deluxe' => 'warning', 'vip' => 'danger'],
                    'valueMap' => ['standar' => 'Standar', 'deluxe' => 'Deluxe', 'vip' => 'VIP'],
                ],
                [
                    'name'     => 'harga_per_bulan',
                    'label'    => 'Harga/Bulan',
                    'type'     => 'number',
                    'format'   => 'rupiah',
                    'required' => true,
                ],
                [
                    'name'     => 'status',
                    'label'    => 'Status',
                    'type'     => 'select',
                    'options'  => ['tersedia' => 'Tersedia', 'terisi' => 'Terisi', 'perbaikan' => 'Perbaikan'],
                    'format'   => 'badge',
                    'badgeMap' => ['tersedia' => 'success', 'terisi' => 'warning', 'perbaikan' => 'danger'],
                    'valueMap' => ['tersedia' => 'Tersedia', 'terisi' => 'Terisi', 'perbaikan' => 'Perbaikan'],
                ],
                [
                    'name'  => 'fasilitas',
                    'label' => 'Fasilitas',
                    'type'  => 'textarea',
                ],
                [
                    'name'  => 'keterangan',
                    'label' => 'Keterangan',
                    'type'  => 'textarea',
                ],
            ],
        ];
    }

    // ─── CRUD standar ───
    public function index()
    {
        return view('crud', [
            'title'    => $this->config['title'],
            'fields'   => $this->config['fields'],
            'listData' => $this->model->findAll(),
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

    public function store()
    {
        $data = [];
        foreach ($this->config['fields'] as $f) {
            $data[$f['name']] = $this->request->getPost($f['name']);
        }
        $this->model->insert($data);
        return redirect()->to($this->config['routePrefix'])->with('success', 'Data berhasil ditambahkan');
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

    public function update($id)
    {
        $data = [];
        foreach ($this->config['fields'] as $f) {
            $data[$f['name']] = $this->request->getPost($f['name']);
        }
        $this->model->update($id, $data);
        return redirect()->to($this->config['routePrefix'])->with('success', 'Data berhasil diupdate');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to($this->config['routePrefix'])->with('success', 'Data berhasil dihapus');
    }
}