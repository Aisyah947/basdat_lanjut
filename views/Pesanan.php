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

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Meja</th>
            <th>Pelanggan</th>
            <th>Item Pesanan</th>
            <th>Total</th>
            <th>Status Bayar</th>
            <th>Metode Pembayaran</th>
            <th>Status Pesanan</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($allPesanan as $pesanan): ?>
        <tr>
            <td><?= $pesanan['id_pesanan'] ?></td>

            <td>
                <?= date("d-m-Y H:i", strtotime($pesanan['tanggal_pesanan'])) ?>
            </td>

            <td>Meja <?= $pesanan['nomor_meja'] ?></td>

            <td><?= $pesanan['nama_pelanggan'] ?></td>

            <!-- ITEM PESANAN -->
            <td>
                <?php
                $items = $model->getDetailPesanan($pesanan['id_pesanan']) ?? [];

                $grouped = [];
                foreach ($items as $i) {
                    $nama = $i['nama_menu'];
                    if (!isset($grouped[$nama])) {
                        $grouped[$nama] = 0;
                    }
                    $grouped[$nama] += $i['jumlah'];
                }

                $namaMenuFix = [];
                foreach ($grouped as $nama => $qty) {
                    $namaMenuFix[] = "$nama ($qty)";
                }

                echo !empty($namaMenuFix)
                    ? implode(", ", $namaMenuFix)
                    : '-';
                ?>
            </td>

            <!-- TOTAL -->
            <td>
                Rp <?= number_format(
                    $model->hitungTotalPesanan($pesanan['id_pesanan']),
                    0, ',', '.'
                ) ?>
            </td>

            <!-- STATUS -->
            <td><?= $pesanan['status_pembayaran'] ?? 'Belum Dibayar' ?></td>
            <td><?= $pesanan['metode_pembayaran'] ?? '-' ?></td>
            <td><?= $pesanan['status_orderan'] ?></td>

            <!-- AKSI -->
            <td>
                <a href="pesanan_detail.php?id=<?= $pesanan['id_pesanan'] ?>"class="btn btn-info">
                    Detail
                </a>    
                <a href="edit_pesanan.php?id=<?= $pesanan['id_pesanan'] ?>" class="btn-edit">
                    Edit
                </a>

                <a href="hapus_pesanan.php?id=<?= $pesanan['id_pesanan'] ?>"
                   class="btn-delete"
                   onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                    Hapus
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include_once __DIR__ . '/layout/footer.php'; ?>
