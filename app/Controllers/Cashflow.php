<?php
namespace App\Controllers;

use App\Models\CashflowModel;

class Cashflow extends BaseController
{
    protected $model;
    protected $config;

    public function __construct()
    {
        $this->model = new CashflowModel();
        $this->config = [
            'routePrefix' => 'cashflow',
            'primaryKey'  => 'id_cashflow',
            'title'       => 'Manajemen Keuangan',
            'fields'      => [
                [
                    'name'     => 'tipe',
                    'label'    => 'Tipe',
                    'type'     => 'select',
                    'options'  => ['pemasukan' => 'Pemasukan', 'pengeluaran' => 'Pengeluaran'],
                    'format'   => 'badge',
                    'badgeMap' => ['pemasukan' => 'success', 'pengeluaran' => 'danger'],
                    'valueMap' => ['pemasukan' => 'Pemasukan', 'pengeluaran' => 'Pengeluaran'],
                ],
                [
                    'name'     => 'jumlah',
                    'label'    => 'Jumlah',
                    'type'     => 'number',
                    'format'   => 'rupiah',
                    'required' => true,
                ],
                [
                    'name'  => 'keterangan',
                    'label' => 'Keterangan',
                    'type'  => 'text',
                ],
                [
                    'name'     => 'tanggal',
                    'label'    => 'Tanggal',
                    'type'     => 'date',
                    'format'   => 'date',
                    'required' => true,
                ],
                [
                    'name'    => 'metode_bayar',
                    'label'   => 'Metode Bayar',
                    'type'    => 'select',
                    'options' => ['tunai' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS'],
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