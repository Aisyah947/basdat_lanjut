<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$menuData = $model->getAllMenu();
$menuStats = $model->getMenuStatistics();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<h2>Laporan Menu</h2>
<div class="dashboard-cards">
    <div class="card">Total Menu<br><?= $menuStats['total_menu'] ?></div>
    <div class="card">Makanan<br><?= $menuStats['makanan'] ?></div>
    <div class="card">Minuman<br><?= $menuStats['minuman'] ?></div>
    <div class="card">Dessert<br><?= $menuStats['dessert'] ?></div>
    <div class="card">Snack<br><?= $menuStats['snack'] ?></div>
</div>

<table>
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
        <?php foreach($menuData as $menu): ?>
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

<?php include_once __DIR__ . '/layout/footer.php'; ?>

