<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil semua data server
$serverList = $model->getAllServer();
?>

<?php include 'layout/header.php'; ?>

<h2>Data Server</h2>

<div>
    <a href="views/tambah_server.php" class="btn btn-tambah">
        <i class="fa fa-plus"></i> Tambah Server
    </a>
</div>

<br>

<div class="action-buttons top-buttons">
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Server</th>
            <th>No Telepon</th>
            <th>Shift</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($serverList): ?>
            <?php foreach ($serverList as $s): ?>
            <tr>
                <td><?= $s['id_server'] ?></td>
                <td><?= $s['nama_server'] ?></td>
                <td><?= $s['no_telepon'] ?></td>
                <td><?= $s['shift'] ?></td>
                <td>
                    <a class="btn-edit" href="views/edit_server.php?id=<?= $s['id_server'] ?>">Edit</a>
                    <a class="btn-delete" onclick="return confirm('Yakin mau hapus?')" href="hapus_server.php?id=<?= $s['id_server'] ?>">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">Belum ada data server.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
        </div>

<?php include 'layout/footer.php'; ?>
