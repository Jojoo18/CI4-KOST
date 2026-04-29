<?php

namespace App\Controllers;

use App\Models\KamarModel;
use App\Models\PenghuniModel;
use App\Models\CashflowModel;
use App\Models\PemesananModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $kamarModel     = new KamarModel();
        $penghuniModel  = new PenghuniModel();
        $cashflowModel  = new CashflowModel();
        $pemesananModel = new PemesananModel();

        $data = [
            'title'                => 'Dashboard',
            'total_kamar'          => $kamarModel->countAll(),
            'kamar_tersedia'       => $kamarModel->where('status', 'tersedia')->countAllResults(false),
            'kamar_terisi'         => $kamarModel->where('status', 'terisi')->countAllResults(false),
            'total_penghuni'       => $penghuniModel->where('status', 'aktif')->countAllResults(false),
            'total_pemasukan'      => $cashflowModel->where('tipe', 'pemasukan')->selectSum('jumlah')->get()->getRow()->jumlah ?? 0,
            'total_pengeluaran'    => $cashflowModel->where('tipe', 'pengeluaran')->selectSum('jumlah')->get()->getRow()->jumlah ?? 0,
            'total_pemesanan_baru' => $pemesananModel->where('status', 'menunggu_dp')->countAllResults(false),
        ];

        return view('dashboard/index', $data);
    }
}