<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransaksiModel; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends BaseController
{
    protected $transaksiModel;
    protected $db;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $builder = $this->db->table('tbl_transaksi'); 
        $builder->select('tbl_transaksi.*, tbl_pesanan.kode_pesanan, tbl_pesanan.nama_pelanggan, tbl_pesanan.sumber_pesanan');
        $builder->join('tbl_pesanan', 'tbl_pesanan.id_pesanan = tbl_transaksi.id_pesanan');
 
        $filter = $this->request->getGet('filter');
        $tanggal = $this->request->getGet('tanggal'); 
        $bulan = $this->request->getGet('bulan');     
        $tahun = $this->request->getGet('tahun');   

        if ($filter == 'harian' && !empty($tanggal)) {
            $builder->where('DATE(tbl_transaksi.waktu_bayar)', $tanggal);
        }
        
        if ($filter == 'mingguan' && !empty($tanggal)) {
            $tanggal_awal = date('Y-m-d', strtotime('-6 days', strtotime($tanggal)));
            $builder->where('DATE(tbl_transaksi.waktu_bayar) >=', $tanggal_awal);
            $builder->where('DATE(tbl_transaksi.waktu_bayar) <=', $tanggal);
        }

        if ($filter == 'bulanan' && !empty($bulan)) {
            $builder->where('DATE_FORMAT(tbl_transaksi.waktu_bayar, "%Y-%m")', $bulan);
        }

        if ($filter == 'tahunan' && !empty($tahun)) {
            $builder->where('YEAR(tbl_transaksi.waktu_bayar)', $tahun);
        }

        $builder->where('tbl_pesanan.status_pesanan', 'selesai'); 
        $builder->orderBy('tbl_transaksi.waktu_bayar', 'DESC');
        
        $data['laporan'] = $builder->get()->getResultArray();

        $total = 0;
        foreach ($data['laporan'] as $item) {
            $total += $item['jumlah_bayar'];
        }
        
        $data['total_pendapatan'] = $total;
        $data['filter_data'] = $this->request->getGet();

        return view('admin/laporan/index', $data);
    }
    
    public function exportExcel()
    {
        $builder = $this->db->table('tbl_transaksi');
        $builder->select('tbl_transaksi.*, tbl_pesanan.kode_pesanan, tbl_pesanan.nama_pelanggan, tbl_pesanan.sumber_pesanan');
        $builder->join('tbl_pesanan', 'tbl_pesanan.id_pesanan = tbl_transaksi.id_pesanan');
        
        $filter = $this->request->getGet('filter');
        $tanggal = $this->request->getGet('tanggal');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        $nama_file = 'laporan_penjualan_';

        if ($filter == 'harian' && !empty($tanggal)) {
            $builder->where('DATE(tbl_transaksi.waktu_bayar)', $tanggal);
            $nama_file .= $tanggal;
        }
        if ($filter == 'mingguan' && !empty($tanggal)) {
            $tanggal_awal = date('Y-m-d', strtotime('-6 days', strtotime($tanggal)));
            $builder->where('DATE(tbl_transaksi.waktu_bayar) >=', $tanggal_awal);
            $builder->where('DATE(tbl_transaksi.waktu_bayar) <=', $tanggal);
            $nama_file .= $tanggal_awal . '_sd_' . $tanggal;
        }
        if ($filter == 'bulanan' && !empty($bulan)) {
            $builder->where('DATE_FORMAT(tbl_transaksi.waktu_bayar, "%Y-%m")', $bulan);
            $nama_file .= $bulan;
        }
        if ($filter == 'tahunan' && !empty($tahun)) {
            $builder->where('YEAR(tbl_transaksi.waktu_bayar)', $tahun);
            $nama_file .= $tahun;
        }
        if (empty($filter)) {
            $nama_file .= 'semua';
        }

        $builder->where('tbl_pesanan.status_pesanan', 'selesai');
        $builder->orderBy('tbl_transaksi.waktu_bayar', 'ASC');
        $laporan = $builder->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Waktu Bayar');
        $sheet->setCellValue('C1', 'Kode Pesanan');
        $sheet->setCellValue('D1', 'Nama Pelanggan');
        $sheet->setCellValue('E1', 'Metode Bayar');
        $sheet->setCellValue('F1', 'Sumber Pesanan');
        $sheet->setCellValue('G1', 'Total Bayar (Rp)');

        $row = 2;
        $no = 1;
        $total = 0;
        foreach ($laporan as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item['waktu_bayar']);
            $sheet->setCellValue('C' . $row, $item['kode_pesanan']);
            $sheet->setCellValue('D' . $row, $item['nama_pelanggan']);
            $sheet->setCellValue('E' . $row, $item['metode_pembayaran']);
            $sheet->setCellValue('F' . $row, $item['sumber_pesanan']);
            $sheet->setCellValue('G' . $row, $item['jumlah_bayar']);
            $total += $item['jumlah_bayar'];
            $row++;
        }

        $sheet->setCellValue('F' . $row, 'TOTAL');
        $sheet->setCellValue('G' . $row, $total);
        $sheet->getStyle('F' . $row . ':G' . $row)->getFont()->setBold(true);

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        $fileName = $nama_file . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $fileName .'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}