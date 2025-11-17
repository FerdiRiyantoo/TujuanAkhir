<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Menu</title>
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
                        <a class="nav-link active" href="<?= base_url('/admin/menu') ?>">Daftar Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/admin/laporan') ?>">Laporan Penjualan</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <a href="<?= base_url('/logout') ?>">Logout</a>
                </span>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Manajemen Daftar Menu</h2>
            <a href="<?= base_url('/admin/menu/new') ?>" class="btn btn-primary">Tambah Menu Baru</a>
        </div>

        <?php if (session()->getFlashdata('sukses')): ?>
            <div class="alert alert-success mt-3" role="alert">
                <?= session()->getFlashdata('sukses') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <hr>

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Gambar</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($menu)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data menu.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($menu as $item): ?>
                    <tr>
                        <td>
                            <img src="<?= base_url('uploads/menu/' . $item['gambar']) ?>" alt="<?= esc($item['nama_menu']) ?>" width="100">
                        </td>
                        <td><?= esc($item['nama_menu']) ?></td>
                        <td><?= ucfirst($item['kategori']) ?></td>
                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge <?= $item['status'] == 'tersedia' ? 'bg-success' : 'bg-danger' ?>">
                                <?= $item['status'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= base_url('/admin/menu/edit/' . $item['id_menu']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="<?= base_url('/admin/menu/delete/' . $item['id_menu']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus menu ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>