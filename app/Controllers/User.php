<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    protected $model;
    protected $config;

    public function __construct()
    {
        $this->model = new UserModel();
        $this->config = [
            'routePrefix' => 'user',
            'primaryKey'  => 'id_user',
            'title'       => 'Manajemen User',
            'fields'      => [
                [
                    'name'     => 'username',
                    'label'    => 'Username',
                    'type'     => 'text',
                    'required' => true,
                ],
                [
                    'name'      => 'password',
                    'label'     => 'Password',
                    'type'      => 'password',
                    'hideInList'=> true, // tidak muncul di tabel
                ],
                [
                    'name'     => 'nama_lengkap',
                    'label'    => 'Nama Lengkap',
                    'type'     => 'text',
                    'required' => true,
                ],
                [
                    'name'     => 'role',
                    'label'    => 'Role',
                    'type'     => 'select',
                    'options'  => ['admin' => 'Admin', 'owner' => 'Owner'],
                    'format'   => 'badge',
                    'badgeMap' => ['admin' => 'info', 'owner' => 'primary'],
                    'valueMap' => ['admin' => 'Admin', 'owner' => 'Owner'],
                ],
            ],
        ];
    }

    // -----------------------------------------------------------------
    //  CRUD STANDAR
    // -----------------------------------------------------------------

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
            $val = $this->request->getPost($f['name']);
            if ($f['name'] == 'password') {
                $val = password_hash($val, PASSWORD_DEFAULT);
            }
            $data[$f['name']] = $val;
        }
        $this->model->insert($data);
        return redirect()->to($this->config['routePrefix'])->with('success', 'User berhasil ditambahkan.');
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
            $val = $this->request->getPost($f['name']);
            if ($f['name'] == 'password') {
                if (!empty($val)) {
                    $val = password_hash($val, PASSWORD_DEFAULT);
                } else {
                    continue; // jangan update password jika kosong
                }
            }
            $data[$f['name']] = $val;
        }
        $this->model->update($id, $data);
        return redirect()->to($this->config['routePrefix'])->with('success', 'User berhasil diupdate.');
    }

    public function delete($id)
    {
        // Cegah penghapusan akun sendiri
        if ($id == session()->get('id_user')) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }
        $this->model->delete($id);
        return redirect()->to($this->config['routePrefix'])->with('success', 'User berhasil dihapus.');
    }
}