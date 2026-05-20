<?php
namespace App\Controllers;

use App\Models\InventoriModel;
use App\Models\KamarModel;

class Inventori extends BaseController
{
    protected $model;
    protected $kamarModel;
    protected $config;

    public function __construct()
    {
        $this->model      = new InventoriModel();
        $this->kamarModel = new KamarModel();

        $kamarOptions = ['' => 'Area Umum'];
        foreach ($this->kamarModel->findAll() as $k) {
            $kamarOptions[$k['id_kamar']] = $k['nomor_kamar'] . ' - ' . ucfirst($k['tipe']);
        }

        $this->config = [
            'routePrefix' => 'inventori',
            'primaryKey'  => 'id_barang',
            'title'       => 'Manajemen Inventori',
            'fields'      => [
                [
                    'name'    => 'id_kamar',
                    'label'   => 'Kamar',
                    'type'    => 'select',
                    'options' => $kamarOptions,
                ],
                [
                    'name'     => 'nama_barang',
                    'label'    => 'Nama Barang',
                    'type'     => 'text',
                    'required' => true,
                ],
                [
                    'name'     => 'jumlah',
                    'label'    => 'Jumlah',
                    'type'     => 'number',
                    'required' => true,
                ],
                [
                    'name'     => 'harga_satuan',
                    'label'    => 'Harga Satuan (Rp)',
                    'type'     => 'number',
                    'format'   => 'rupiah',
                    'required' => true,
                ],
                [
                    'name'    => 'kondisi',
                    'label'   => 'Kondisi',
                    'type'    => 'select',
                    'options' => ['baik' => 'Baik', 'rusak' => 'Rusak', 'hilang' => 'Hilang'],
                    'format'  => 'badge',
                    'badgeMap'=> ['baik' => 'success', 'rusak' => 'danger', 'hilang' => 'warning'],
                    'valueMap'=> ['baik' => 'Baik', 'rusak' => 'Rusak', 'hilang' => 'Hilang'],
                ],
                [
                    'name'  => 'keterangan',
                    'label' => 'Keterangan',
                    'type'  => 'textarea',
                ],
            ],
        ];
    }

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