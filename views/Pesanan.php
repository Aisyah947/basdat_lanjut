<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$allPesanan = $model->getAllPesanan();
?>

<?php include_once('layout/header.php'); ?>     
<a href="tambah_pesanan.php" class="btn-add">Tambah Pesanan Baru</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Meja</th>
            <th>Pelanggan</th>
            <th>Item</th>
            <th>Total</th>
            <th>Status Bayar</th>
            <th>Status Pesanan</th>
            <th>Aksi</th>
        </tr> 
    </thead>
    <tbody>
        <?php foreach($allPesanan as $pesanan): ?>
        <tr>
            <td><?= $pesanan['id_pesanan'] ?></td>
            <td><?= $pesanan['nomor_meja'] ?></td>
            <td><?= $pesanan['nama_pelanggan'] ?></td>
            <td>
                <?php
                $items = $model->getDetailPesanan($pesanan['id_pesanan']);
                $namaMenu = array_map(fn($i)=>$i['nama_menu']."(".$i['jumlah'].")", $items);
                echo implode(", ", $namaMenu);
                ?>
            </td>
            <td>Rp <?= number_format($model->hitungTotalPesanan($pesanan['id_pesanan']),0,',','.') ?></td>
            <td><?= $model->cekStatusPembayaran($pesanan['id_pesanan']) ?></td>
            <td><?= $pesanan['status_orderan'] ?></td>
            <td>
                <a href="edit_pesanan.php?id=<?= $pesanan['id_pesanan'] ?>" class="btn-edit">Edit</a>
                <a href="hapus_pesanan.php?id=<?= $pesanan['id_pesanan'] ?>" 
                    class="btn-delete" 
                    onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
        </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include_once __DIR__ . '/layout/footer.php';?>
