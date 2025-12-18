<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

if (!isset($_GET['id'])) {
    die("ID Pesanan tidak ditemukan.");
}

$id = $_GET['id'];

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil data pesanan utama
$pesanan = $model->getPesananById($id);

// Ambil detail pesanan (list menu)
$detail = $model->getDetailPesanan($id);

?>
<?php include 'layout/header.php'; ?>

<a href="pesanan.php" class="btn btn-secondary mb-3">← Kembali</a>

<h2>Detail Pesanan #<?= $pesanan['id_pesanan'] ?></h2>

<div class="card mb-4 p-3">
    <h5 class="mb-3">Informasi Pesanan</h5>

    <table class="table table-bordered">
        <tr>
            <th>Pelanggan</th>
            <td><?= $pesanan['nama_pelanggan'] ?></td>
        </tr>
        <tr>
            <th>Nomor Meja</th>
            <td>Meja <?= $pesanan['nomor_meja'] ?></td>
        </tr>
        <tr>
            <th>Server</th>
            <td><?= $pesanan['nama_server'] ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= $pesanan['status_orderan'] ?></td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td><?= $pesanan['tanggal_pesanan'] ?></td>
        </tr>
        <tr>
            <th>Total Harga</th>
            <td>Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></td>
        </tr>
    </table>
</div>

<div class="card p-3">
    <h5 class="mb-3">Detail Menu</h5>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $grandTotal = 0;
            foreach ($detail as $d):
                $subtotal = $d['jumlah'] * $d['harga_satuan'];
                $grandTotal += $subtotal;
            ?>
            <tr>
                <td><?= $d['nama_menu'] ?></td>
                <td><?= $d['jumlah'] ?></td>
                <td>Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr class="table-secondary">
                <th colspan="3" class="text-end">Total</th>
                <th>Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
            </tr>
        </tfoot>

    </table>
</div>

<?php include 'layout/footer.php'; ?>
