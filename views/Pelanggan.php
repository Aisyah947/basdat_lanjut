<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil semua pelanggan
$dataPelanggan = $model->getAllPelanggan();
?>

<?php include 'layout/header.php'; ?>

<h2>Data Pelanggan</h2>

<a href="tambah_pelanggan.php" class="btn">Tambah Pelanggan</a>
<br><br>

<table border="1" cellpadding="10">
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
            <a href="edit_pelanggan.php?id=<?= $p['id_pelanggan'] ?>">Edit</a> |
            <a href="hapus_pelanggan.php?id=<?= $p['id_pelanggan'] ?>"
               onclick="return confirm('Yakin ingin menghapus?')">
               Hapus
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php include 'layout/footer.php'; ?>
