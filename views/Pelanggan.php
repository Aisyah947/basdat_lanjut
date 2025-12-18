<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$dataPelanggan = $model->getAllPelanggan();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<h2>Data Pelanggan</h2>

<div>
    <a href="tambah_pelanggan.php" class="btn btn-tambah">
        <i class="fa fa-plus"></i> Tambah Pelanggan
    </a>
</div>

<br>

<div class="action-buttons top-buttons">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>No Telepon</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($dataPelanggan as $p): ?>
        <tr>
            <td><?= $p['id_pelanggan'] ?></td>
            <td><?= $p['nama'] ?></td>
            <td><?= $p['no_telepon'] ?></td>
            <td>
                <a href="edit_pelanggan.php?id=<?= $p['id_pelanggan'] ?>" class="btn-edit">Edit</a>
                <a href="hapus_pelanggan.php?id=<?= $p['id_pelanggan'] ?>"
                    onclick="return confirm('Yakin ingin menghapus?')"
                    class="btn-delete">
                    Hapus
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include 'layout/footer.php'; ?>
