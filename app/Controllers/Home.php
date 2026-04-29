<?php
namespace App\Controllers;

use App\Models\KamarModel;

class Home extends BaseController
{
    public function index()
    {
        $kamarModel = new KamarModel();
        $data['kamarPopuler'] = $kamarModel->where('status', 'tersedia')
                                           ->orderBy('tipe', 'DESC')
                                           ->limit(3)
                                           ->findAll();
        $data['title'] = 'KostRegar - Sewa Kost Nyaman';
        return view('landing', $data);
    }
}   