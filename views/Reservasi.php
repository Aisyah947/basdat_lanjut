<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$reservasi = $model->getAllReservasi();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<h2>Data Reservasi</h2>

<div>
    <a href="views/tambah_reservasi.php" class="btn btn-tambah">
        <i class="fa fa-plus"></i> Tambah Reservasi
    </a>
</div>

<br>

<div class="action-buttons top-buttons">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Pelanggan</th>
            <th>Meja</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Jumlah Orang</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($reservasi as $r): ?>
        <tr>
            <td><?= $r['id_reservasi'] ?></td>
            <td><?= $r['nama_pelanggan'] ?></td>
            <td>Meja <?= $r['nomor_meja'] ?></td>
            <td><?= $r['tanggal_reservasi'] ?></td>
            <td><?= $r['jam_reservasi'] ?></td>
            <td><?= $r['jumlah_orang'] ?></td>
            <td><?= $r['status_reservasi'] ?></td>
            <td>
                <a href="edit_reservasi.php?id=<?= $r['id_reservasi'] ?>" class="btn-edit">Edit</a>
                
                <a href="hapus_reservasi.php?id=<?= $r['id_reservasi'] ?>"
                    onclick="return confirm('Yakin ingin menghapus?')"
                    class="btn-delete">
                    Hapus
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include_once __DIR__ . '/layout/footer.php'; ?>
