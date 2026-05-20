<?php

namespace App\Controllers;

class DownloadController extends BaseController
{
    public function buktiBayar($filename)
    {
        // Cek login & role admin
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $path = WRITEPATH . 'uploads/bukti/' . $filename;

        if (!file_exists($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan');
        }

        return $this->response->download($path, null);
    }
}