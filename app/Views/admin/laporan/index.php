<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/admin/menu') ?>">Daftar Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('/admin/laporan') ?>">Laporan Penjualan</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <a href="<?= base_url('/logout') ?>">Logout</a>
                </span>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Laporan Penjualan</h2>
        <hr>

        <div class="card bg-light p-3 mb-4">
            <form action="<?= base_url('/admin/laporan') ?>" method="get">
                <div class="row g-3 align-items-end">
                    <div class="col-md-2">
                        <label for="filter" class="form-label">Tipe Filter</label>
                        <select name="filter" id="filter" class="form-select">
                            <option value="">--Pilih Tipe--</option>
                            <option value="harian" <?= ($filter_data['filter'] ?? '') == 'harian' ? 'selected' : '' ?>>Harian</option>
                            <option value="mingguan" <?= ($filter_data['filter'] ?? '') == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
                            <option value="bulanan" <?= ($filter_data['filter'] ?? '') == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                            <option value="tahunan" <?= ($filter_data['filter'] ?? '') == 'tahunan' ? 'selected' : '' ?>>Tahunan</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?= $filter_data['tanggal'] ?? '' ?>">
                    </div>

                    <div class="col-md-2">
                        <label for="bulan" class="form-label">Bulan</label>
                        <input type="month" name="bulan" id="bulan" class="form-control" value="<?= $filter_data['bulan'] ?? '' ?>">
                    </div>

                    <div class="col-md-2">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" placeholder="Contoh: 2025" value="<?= $filter_data['tahun'] ?? '' ?>">
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                        <a href="<?= base_url('/admin/laporan') ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Hasil Laporan</h3>
            <a href="<?= base_url('/admin/laporan/excel?' . http_build_query($filter_data)) ?>" class="btn btn-success">
                Cetak Rekap (Excel)
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Waktu Bayar</th>
                            <th>Kode Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Metode Bayar</th>
                            <th>Sumber</th>
                            <th>Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporan)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data untuk filter yang dipilih.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($laporan as $item): ?>
                            <tr>
                                <td><?= $item['waktu_bayar'] ?></td>
                                <td><?= esc($item['kode_pesanan']) ?></td>
                                <td><?= esc($item['nama_pelanggan']) ?></td>
                                <td><?= $item['metode_pembayaran'] ?></td>
                                <td><?= $item['sumber_pesanan'] ?></td>
                                <td class="text-end"><?= number_format($item['jumlah_bayar'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5" class="text-end">TOTAL PENDAPATAN (dari filter)</th>
                            <th class="text-end h5">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
    </div>
</body>
</html>