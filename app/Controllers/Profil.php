<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profil extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $user = $model->find(session()->get('id_user'));
        return view('profil/index', ['user' => $user, 'title' => 'Profil Saya']);
    }

    public function update()
    {
        $model = new UserModel();
        $id = session()->get('id_user');

        $rules = [
            'nama_lengkap' => 'required',
            'password_lama' => 'required',
            'password_baru' => 'permit_empty|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $model->find($id);
        $password_lama = $this->request->getPost('password_lama');

        // Verifikasi password lama
        if (!password_verify($password_lama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai.');
        }

        $data = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        ];

        // Jika password baru diisi, update password
        $password_baru = $this->request->getPost('password_baru');
        if (!empty($password_baru)) {
            $data['password'] = password_hash($password_baru, PASSWORD_DEFAULT);
        }

        $model->update($id, $data);
        session()->set('nama_lengkap', $data['nama_lengkap']);

        return redirect()->to('/profil')->with('success', 'Profil berhasil diperbarui.');
    }
}