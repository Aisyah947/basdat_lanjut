<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$allPesanan = $model->getAllPesanan();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<h2>Data Pesanan</h2>

<div>
    <a href="views/tambah_pesanan.php" class="btn btn-tambah">
        <i class="fa fa-plus"></i> Tambah Pesanan Baru
    </a>
</div>

<br>

<div class="action-buttons top-buttons">
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Meja</th>
            <th>Pelanggan</th>
            <th>Item Pesanan</th>
            <th>Total</th>
            <th>Status Bayar</th>
            <th>Status Pesanan</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($allPesanan as $pesanan): ?>
        <tr>
            <td><?= $pesanan['id_pesanan'] ?></td>
            <td>Meja <?= $pesanan['nomor_meja'] ?></td>
            <td><?= $pesanan['nama_pelanggan'] ?></td>

            <td>
                <?php
                    $items = $model->getDetailPesanan($pesanan['id_pesanan']);
                    $namaMenu = array_map(
                        fn($i)=>$i['nama_menu']." (".$i['jumlah'].")", 
                        $items
                    );
                    echo implode(", ", $namaMenu);
                ?>
            </td>
            <td>Rp 
                <?= number_format($model->hitungTotalPesanan($pesanan['id_pesanan']) 
                ?? 0, 0, ',', '.') 
                ?>
            </td>
            <td><?= $model->cekStatusPembayaran($pesanan['id_pesanan']) ?></td>

            <td><?= $pesanan['status_orderan'] ?></td>

            <td>
<<<<<<< HEAD
                <a href="views/edit_pesanan.php?id=<?= $pesanan['id_pesanan'] ?>" class="btn-edit">Edit</a>
                <a href="hapus_pesanan.php?id=<?= $pesanan['id_pesanan'] ?>"
                   onclick="return confirm('Hapus meja ini?')" class="btn-delete">Hapus</a>
                </td>
=======
                <a href="views/edit_pesanan.php?id=<?= $pesanan['id_pesanan'] ?>" class="btn-edit">
                    Edit
                </a>

                <a href="hapus_pesanan.php?id=<?= $pesanan['id_pesanan'] ?>" 
                    class="btn-delete"
                    onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                    Hapus
                </a>
            </td>
>>>>>>> f9b22a9c617832fef65d7a93c9af62d8da5cf006
        </tr>
        <?php endforeach; ?>

    </table>
</div>

<?php include_once __DIR__ . '/layout/footer.php'; ?>
