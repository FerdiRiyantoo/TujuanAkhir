<?= $this->extend('layout/admin_template') ?>

<?= $this->section('title') ?>
    Dashboard Admin
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <h2>Selamat Datang, <?= esc($nama_admin) ?>!</h2>
    <p>Ini adalah halaman ringkasan untuk Admin.</p>
    
    <hr>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Penjualan Hari Ini</div>
                <div class="card-body">
                    <h5 class="card-title">Rp <?= number_format($penjualan_hari_ini, 0, ',', '.') ?></h5>
                    <p class="card-text">Total transaksi yang sudah dibayar hari ini.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card text-dark bg-warning mb-3">
                <div class="card-header">Pesanan Pending (FIFO)</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $pesanan_pending ?> Pesanan</h5>
                    <p class="card-text">Jumlah pesanan yang sedang menunggu di antrian.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-dark bg-info mb-3">
                <div class="card-header">Total Menu</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $total_menu ?> Menu</h5>
                    <p class="card-text">Jumlah menu yang terdaftar di sistem.</p>
                    <a href="<?= base_url('/admin/menu') ?>" class="btn btn-dark">Kelola Menu</a>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>