<?php
namespace App\Controllers;

use App\Models\TagihanModel;
use App\Models\PenghuniModel;

class Tagihan extends BaseController
{
    protected $model;
    protected $penghuniModel;
    protected $config;

    public function __construct()
    {
        $this->model          = new TagihanModel();
        $this->penghuniModel  = new PenghuniModel();

        $penghuniOptions = [];
        foreach ($this->penghuniModel->findAll() as $p) {
            $penghuniOptions[$p['id_penghuni']] = $p['nama_lengkap'];
        }

        $this->config = [
            'routePrefix' => 'tagihan',
            'primaryKey'  => 'id_tagihan',
            'title'       => 'Manajemen Tagihan',
            'fields'      => [
                [
                    'name'     => 'id_penghuni',
                    'label'    => 'Penghuni',
                    'type'     => 'select',
                    'options'  => $penghuniOptions,
                    'required' => true,
                ],
                [
                    'name'     => 'tahun',
                    'label'    => 'Tahun',
                    'type'     => 'number',
                    'required' => true,
                ],
                [
                    'name'     => 'total_bayar',
                    'label'    => 'Total Bayar',
                    'type'     => 'number',
                    'format'   => 'rupiah',
                    'required' => true,
                ],
                [
                    'name'    => 'status',
                    'label'   => 'Status',
                    'type'    => 'select',
                    'options' => ['belum_lunas' => 'Belum Lunas', 'lunas' => 'Lunas'],
                    'format'  => 'badge',
                    'badgeMap'=> ['belum_lunas' => 'danger', 'lunas' => 'success'],
                    'valueMap'=> ['belum_lunas' => 'Belum Lunas', 'lunas' => 'Lunas'],
                ],
                [
                    'name'      => 'tgl_bayar',
                    'label'     => 'Tanggal Bayar',
                    'type'      => 'date',
                    'format'    => 'date',
                    'hideInList'=> true,
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