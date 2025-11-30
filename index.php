<?php
include 'config/database.php';
include 'models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Statistik untuk dashboard
$menuStats = $model->getMenuStatistics();
$allPesanan = $model->getAllPesanan();
$allMeja = $model->getAllMeja();
?>

<?php include 'views/layout/header.php'; ?>

<!-- Main Content Area -->
<div class="content-wrapper">
    <div class="content-header">
        <h2>Dashboard Restoran</h2>
    </div>

    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Menu</h3>
            <div class="number"><?= $menuStats['total_menu'] ?></div>
        </div>
        <div class="card">
            <h3>Total Pesanan</h3>
            <div class="number"><?= count($allPesanan) ?></div>
        </div>
        <div class="card">
            <h3>Total Meja</h3>
            <div class="number"><?= count($allMeja) ?></div>
        </div>
    </div>

    <h3>Ringkasan Menu</h3>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Menu</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $menuData = $model->getAllMenu();
            foreach($menuData as $menu): ?>
            <tr>
                <td><?= $menu['id_menu'] ?></td>
                <td><?= $menu['nama_menu'] ?></td>
                <td><?= $menu['nama_kategori'] ?></td>
                <td>Rp <?= number_format($menu['harga'],0,',','.') ?></td>
                <td><?= $menu['status_ketersediaan'] ? 'Tersedia' : 'Tidak Tersedia' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <br><br>

    <h3>Ringkasan Pesanan Terakhir</h3>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Meja</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $recentPesanan = array_slice($allPesanan, -5); // 5 pesanan terakhir
            foreach($recentPesanan as $pesanan): ?>
            <tr>
                <td><?= $pesanan['id_pesanan'] ?></td>
                <td><?= $pesanan['nomor_meja'] ?></td>
                <td><?= $pesanan['nama_pelanggan'] ?></td>
                <td>Rp <?= number_format($model->hitungTotalPesanan($pesanan['id_pesanan']),0,',','.') ?></td>
                <td><?= $model->cekStatusPembayaran($pesanan['id_pesanan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/views/layout/footer.php';
 ?>

