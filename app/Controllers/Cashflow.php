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
    $db = \Config\Database::connect();
    
    // Ambil input filter tanggal
    $tgl_mulai = $this->request->getGet('tgl_mulai') ?? date('Y-m-01');
    $tgl_akhir = $this->request->getGet('tgl_akhir') ?? date('Y-m-t');
    
    // Data untuk tabel (difilter)
    $data['cashflow'] = $this->model
        ->where('tanggal >=', $tgl_mulai)
        ->where('tanggal <=', $tgl_akhir)
        ->orderBy('tanggal', 'DESC')
        ->findAll();
    
    $data['title'] = 'Manajemen Keuangan';
    $data['tgl_mulai'] = $tgl_mulai;
    $data['tgl_akhir'] = $tgl_akhir;
    
    // Total sesuai filter
    $data['total_masuk'] = $db->table('tbl_cashflow')
        ->where('tipe', 'pemasukan')
        ->where('tanggal >=', $tgl_mulai)
        ->where('tanggal <=', $tgl_akhir)
        ->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        
    $data['total_keluar'] = $db->table('tbl_cashflow')
        ->where('tipe', 'pengeluaran')
        ->where('tanggal >=', $tgl_mulai)
        ->where('tanggal <=', $tgl_akhir)
        ->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        
    $data['saldo'] = $data['total_masuk'] - $data['total_keluar'];
    
    // Data grafik (tetap full tahun ini)
    $tahun = date('Y');
    $bulan = [];
    $pemasukanBulan = [];
    $pengeluaranBulan = [];
    for ($i = 1; $i <= 12; $i++) {
        $bulan[] = date('F', mktime(0, 0, 0, $i, 1));
        $masuk = $db->table('tbl_cashflow')->where('tipe', 'pemasukan')
            ->where('MONTH(tanggal)', $i)->where('YEAR(tanggal)', $tahun)
            ->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $pemasukanBulan[] = (int)$masuk;
        
        $keluar = $db->table('tbl_cashflow')->where('tipe', 'pengeluaran')
            ->where('MONTH(tanggal)', $i)->where('YEAR(tanggal)', $tahun)
            ->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $pengeluaranBulan[] = (int)$keluar;
    }
    $data['grafik_bulan'] = json_encode($bulan);
    $data['grafik_pemasukan'] = json_encode($pemasukanBulan);
    $data['grafik_pengeluaran'] = json_encode($pengeluaranBulan);
    
    // Data grafik pie (metode bayar, full)
    $metode = $db->table('tbl_cashflow')->select('metode_bayar, SUM(jumlah) as total')
        ->groupBy('metode_bayar')->get()->getResultArray();
    $labelMetode = [];
    $dataMetode = [];
    foreach ($metode as $m) {
        $labelMetode[] = ucfirst($m['metode_bayar']);
        $dataMetode[] = (int)$m['total'];
    }
    $data['grafik_label_metode'] = json_encode($labelMetode);
    $data['grafik_data_metode'] = json_encode($dataMetode);
    
    return view('cashflow/index', $data);
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