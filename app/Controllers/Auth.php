<?php
namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function authenticate()
    {
        $db = \Config\Database::connect();
        $user = $db->table('tbl_users')
                   ->where('username', $this->request->getPost('username'))
                   ->get()
                   ->getRowArray();

        if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
            session()->set([
                'id_user'      => $user['id_user'],
                'username'     => $user['username'],
                'nama_lengkap' => $user['nama_lengkap'],
                'role'         => $user['role'],
                'isLoggedIn'   => true,
            ]);
            return redirect()->to('/dashboard')->with('success', 'Login berhasil');
        } else {
            return redirect()->back()->with('error', 'Username atau password salah');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}