<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil semua laporan shift
$laporan = $model->getAllLaporanShift();
?>

<?php include 'layout/header.php'; ?>

<h2>Laporan Shift</h2>

<div>
    <a href="views/tambah_laporan_sift.php">
    <a href="tambah_laporan_shift.php" class="btn btn-tambah">
    + Tambah Laporan Shift
    </a>
</div>

<br>

<div class="action-buttons top-buttons">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Server</th>
            <th>Tanggal</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Total Penjualan</th>
            <th>Total Pesanan</th>
            <th>Shift</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($laporan as $l): ?>
        <tr>
            <td><?= $l['id_laporan'] ?></td>
            <td><?= $l['nama_server'] ?></td>
            <td><?= $l['tanggal'] ?></td>
            <td><?= $l['waktu_mulai'] ?></td>
            <td><?= $l['waktu_selesai'] ?></td>
            <td>Rp <?= number_format($l['total_penjualan'],0,',','.') ?></td>
            <td><?= $l['total_pesanan'] ?></td>
            <td><?= $l['shift'] ?></td>

            <td>
            <a href="edit_laporan_shift.php?id=<?= $l['id_laporan'] ?>" class="btn-edit">Edit</a>

    <a href="hapus_laporan_shift.php?id=<?= $l['id_laporan'] ?>" class="btn-delete"
       onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php include 'layout/footer.php'; ?>
