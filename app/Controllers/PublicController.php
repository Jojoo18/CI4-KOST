<?php
namespace App\Controllers;

use App\Models\KamarModel;
use App\Models\PemesananModel;

class PublicController extends BaseController
{
    public function katalog()
    {
        $kamarModel = new KamarModel();
        $data['kamar'] = $kamarModel->where('status', 'tersedia')->findAll();
        return view('public/katalog', $data);
    }

    public function pesan($id_kamar)
    {
        $kamarModel = new KamarModel();
        $kamar = $kamarModel->find($id_kamar);
        if (!$kamar || $kamar['status'] != 'tersedia') {
            return redirect()->to('/katalog')->with('error', 'Kamar tidak tersedia');
        }
        $kamar['harga_per_tahun'] = $kamar['harga_per_bulan'] * 12;
        return view('public/pesan', ['kamar' => $kamar]);
    }

    public function store()
    {
        $pemesananModel = new PemesananModel();
        $kamarModel = new KamarModel();
        $id_kamar = $this->request->getPost('id_kamar');
        $kamar = $kamarModel->find($id_kamar);
        $total_biaya = $kamar['harga_per_bulan'] * 12;
        $dp_dibayar = (int) $this->request->getPost('dp_dibayar');
        $min_dp = ceil(0.5 * $total_biaya);

        if ($dp_dibayar < $min_dp) {
            return redirect()->back()
                ->with('error', 'DP minimal 50% dari total (Rp ' . number_format($min_dp, 0, ',', '.') . ')')
                ->withInput();
        }

        // Upload bukti bayar
        $file = $this->request->getFile('bukti_bayar');
        $namaFile = null;
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/bukti', $newName);
            $namaFile = $newName;
        }

        $data = [
            'id_kamar'     => $id_kamar,
            'nama_pemesan' => $this->request->getPost('nama_pemesan'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'email'        => $this->request->getPost('email'),
            'tgl_mulai'    => $this->request->getPost('tgl_mulai'),
            'total_biaya'  => $total_biaya,
            'dp_dibayar'   => $dp_dibayar,
            'metode_bayar' => $this->request->getPost('metode_bayar'),
            'bukti_bayar'  => $namaFile,
            'status'       => 'menunggu_dp',
        ];
        $pemesananModel->insert($data);

        // Kirim email notifikasi (jika config email sudah diatur)
        if (!empty($data['email'])) {
            $email = \Config\Services::email();
            $email->setTo($data['email']);
            $email->setSubject('Pemesanan Kamar KostRegar Berhasil');
            $message = "
                <div style='font-family: Poppins, sans-serif;'>
                    <h2 style='color: #28a745;'>✅ Pemesanan Berhasil</h2>
                    <p>Terima kasih, <strong>{$data['nama_pemesan']}</strong>!</p>
                    <p>Kamar: <strong>{$kamar['nomor_kamar']} ({$kamar['tipe']})</strong></p>
                    <p>Total Biaya: <strong>Rp " . number_format($total_biaya, 0, ',', '.') . "</strong></p>
                    <p>DP Dibayar: <strong>Rp " . number_format($dp_dibayar, 0, ',', '.') . "</strong></p>
                    <p>Silakan konfirmasi pembayaran DP ke admin KostRegar.</p>
                </div>
            ";
            $email->setMessage($message);
            $email->send();
        }

        return redirect()->to('/katalog')->with('success', 'Pemesanan berhasil! Silakan lakukan pembayaran DP sesuai metode yang dipilih.');
    }
}