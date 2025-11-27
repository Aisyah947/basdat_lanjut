<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';


$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil semua meja
$meja = $model->getAllMeja();
?>

<?php include 'layout/header.php'; ?>

<h2>Data Meja</h2>

<a href="tambah_meja.php" class="btn-add">Tambah Meja</a>
<br><br>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nomor Meja</th>
            <th>Status</th>
            <th>Kapasitas</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($meja as $m): ?>
        <tr>
            <td><?= $m['id_meja'] ?></td>
            <td><?= $m['nomor_meja'] ?></td>
            <td><?= $m['status_meja'] ?></td>
            <td><?= $m['kapasitas'] ?></td>
            <td>
                <a href="edit_meja.php?id=<?= $m['id_meja'] ?>" class="btn-edit">Edit</a>
                <a href="hapus_meja.php?id=<?= $m['id_meja'] ?>"
                   onclick="return confirm('Hapus meja ini?')" class="btn-delete">
                   Hapus
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'layout/footer.php'; ?>
