<?php

namespace App\Controllers;

use App\Models\KamarModel;
use App\Models\PenghuniModel;
use App\Models\CashflowModel;
use App\Models\PemesananModel;
use App\Models\TagihanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $kamarModel      = new KamarModel();
        $penghuniModel   = new PenghuniModel();
        $cashflowModel   = new CashflowModel();
        $pemesananModel  = new PemesananModel();
        $tagihanModel    = new TagihanModel();

        // Tagihan belum lunas
        $tagihan_belum_lunas = $tagihanModel
            ->select('tbl_tagihan.*, tbl_penghuni.nama_lengkap, tbl_kamar.nomor_kamar')
            ->join('tbl_penghuni', 'tbl_penghuni.id_penghuni = tbl_tagihan.id_penghuni')
            ->join('tbl_kamar', 'tbl_kamar.id_kamar = tbl_penghuni.id_kamar')
            ->where('tbl_tagihan.status', 'belum_lunas')
            ->orderBy('tbl_tagihan.tahun', 'ASC')
            ->limit(5)
            ->findAll();

        // Kamar terisi = jumlah kamar unik yang dihuni oleh penghuni aktif
        $kamar_terisi = $penghuniModel
            ->where('status', 'aktif')
            ->selectCount('id_kamar', 'total')
            ->groupBy('id_kamar')
            ->get()
            ->getNumRows();

        $data = [
            'title'                     => 'Dashboard',
            'total_kamar'               => $kamarModel->countAll(),
            'kamar_tersedia'            => $kamarModel->where('status', 'tersedia')->countAllResults(false),
            'kamar_terisi'              => $kamar_terisi,
            'total_penghuni'            => $penghuniModel->where('status', 'aktif')->countAllResults(false),
            'total_pemasukan'           => $cashflowModel->where('tipe', 'pemasukan')->selectSum('jumlah')->get()->getRow()->jumlah ?? 0,
            'total_pengeluaran'         => $cashflowModel->where('tipe', 'pengeluaran')->selectSum('jumlah')->get()->getRow()->jumlah ?? 0,
            'total_pemesanan_baru'      => $pemesananModel->where('status', 'menunggu_dp')->countAllResults(false),
            'tagihan_terlambat'         => $tagihan_belum_lunas,
            'jumlah_tagihan_belum_lunas'=> count($tagihan_belum_lunas),
        ];

        return view('dashboard/index', $data);
    }
}