<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\PesananModel;
use App\Models\TransaksiModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $menuModel = new MenuModel();
        $pesananModel = new PesananModel();
        $transaksiModel = new TransaksiModel();

        $data['total_menu'] = $menuModel->countAllResults();
        
        $data['pesanan_pending'] = $pesananModel
                                    ->where('status_pesanan', 'pending')
                                    ->countAllResults();
        
        $data['penjualan_hari_ini'] = $transaksiModel
                                    ->selectSum('jumlah_bayar', 'total')
                                    ->where('DATE(waktu_bayar)', date('Y-m-d'))
                                    ->get()
                                    ->getRow()
                                    ->total ?? 0; 

        $data['nama_admin'] = session()->get('nama_lengkap');

        return view('admin/dashboard/index', $data);
    }
}