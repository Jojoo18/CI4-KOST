<?php
namespace App\Controllers;

class ReviewController extends BaseController
{
    public function store()
    {
        $db = \Config\Database::connect();
        $db->table('tbl_review')->insert([
            'id_kamar'      => $this->request->getPost('id_kamar'),
            'nama_reviewer' => $this->request->getPost('nama_reviewer'),
            'rating'        => $this->request->getPost('rating'),
            'komentar'      => $this->request->getPost('komentar'),
        ]);
        return redirect()->back()->with('success', 'Review terkirim!');
    }
}