<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Pesanan tidak ditemukan.");
}

$id = $_GET['id'];

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$pesanan = $model->getPesananById($id);
$detail  = $model->getDetailPesanan($id);



include 'layout/header.php'; ?>

<div class="container mt-4">

    <!-- TOMBOL KEMBALI -->
    <div class="mb-2">
        <a href="pesanan.php" class="btn btn-sm btn-outline-secondary">
            ← Kembali
        </a>
    </div>

    <div class="row">

        <!-- INFO PESANAN -->
        <div class="col-lg-7 col-md-9 mb-3">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th width="20%">Pelanggan</th>
                            <td><?= $pesanan['nama']; ?></td>
                        </tr>
                        <tr>
                            <th>Nomor Meja</th>
                            <td>Meja <?= $pesanan['nomor_meja']; ?></td>
                        </tr>
                        <tr>
                            <th>Server</th>
                            <td><?= $pesanan['nama_server']; ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?= $pesanan['status_orderan']; ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td><?= $pesanan['tanggal_pesanan']; ?></td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td class="fw-bold">
                                Rp <?= number_format($pesanan['total_harga'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- DETAIL MENU -->
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <h5 class="mb-3 fw-bold">Detail Menu</h5>

                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Menu</th>
                                <th width="80">Jumlah</th>
                                <th width="120">Harga</th>
                                <th width="120">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($detail)) : ?>
                                <?php foreach ($detail as $d) : ?>
                                    <tr>
                                        <td><?= $d['nama_menu']; ?></td>
                                        <td class="text-center"><?= $d['jumlah']; ?></td>
                                        <td>
                                            Rp <?= number_format($d['harga_satuan'], 0, ',', '.'); ?>
                                        </td>
                                        <td>
                                            Rp <?= number_format(
                                                $d['jumlah'] * $d['harga_satuan'],
                                                0,
                                                ',',
                                                '.'
                                            ); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada menu
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                        <tfoot>
                            <tr class="fw-bold table-secondary">
                                <td colspan="3" class="text-end">Total</td>
                                <td>
                                    Rp <?= number_format($pesanan['total_harga'], 0, ',', '.'); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'layout/footer.php'; ?>
