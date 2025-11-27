<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';


$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil semua meja
$meja = $model->getAllMeja();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<h2>Data Meja</h2>

<div>
    <a href="views/tambah_meja.php" class="btn btn-tambah">
            <i class="fa fa-plus"></i> Tambah Meja
        </a>
</div>
<br>

<div class="action-buttons top-buttons">
        <table class="table">
        <tr>
            <th>ID</th>
            <th>Nomor Meja</th>
            <th>Status</th>
            <th>Kapasitas</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($meja as $m): ?>
        <tr>
            <td><?= $m['id_meja'] ?></td>
            <td><?= $m['nomor_meja'] ?></td>
            <td><?= $m['status_meja'] ?></td>
            <td><?= $m['kapasitas'] ?></td>
            <td>
                <a href="views/edit_meja.php?id=<?= $m['id_meja'] ?>" class="btn-edit">Edit</a>
                <a href="hapus_meja.php?id=<?= $m['id_meja'] ?>"
                   onclick="return confirm('Hapus meja ini?')" class="btn-delete">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>


<?php include 'layout/footer.php'; ?>
