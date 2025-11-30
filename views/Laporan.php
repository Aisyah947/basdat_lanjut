<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil data laporan dari model
$getPenjualanPerMenu = $model->getPenjualanPerMenu();
$laporanPerShift = $model->getPenjualanPerShift();
$laporanPerServer = $model->getPenjualanPerServer();
$menuLaris = $model->getMenuTerlaris();
?>

<?php include 'layout/header.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<h2>Laporan Penjualan</h2>
<hr>

<ul class="nav nav-tabs" id="laporanTabs">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#menu">Per Menu</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#shift">Per Shift</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#server">Per Server</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#laris">Menu Paling Laris</a>
    </li>
</ul>

<div class="tab-content mt-3">

    <!-- SECTION LAPORAN PER MENU -->
    <div class="tab-pane fade show active" id="menu">
        <h4>Laporan Penjualan per Menu</h4>
        <table class="table table-striped">
            <tr>
                <th>Menu</th>
                <th>Total Qty</th>
                <th>Pendapatan</th>
            </tr>
            <?php 
            $totalQty = 0;
            $totalPendapatan = 0;
            foreach($getPenjualanPerMenu as $row): 
                $totalQty += $row['total_terjual'];
                $totalPendapatan += $row['total_pendapatan'];
            ?>
            <tr>
                <td><?= $row['nama_menu'] ?></td>
                <td><?= $row['total_terjual'] ?></td>
                <td><?= $row['total_pendapatan'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="fw-bold">
                <td>Total</td>
                <td><?= $totalQty ?></td>
                <td><?= $totalPendapatan ?></td>
            </tr>
        </table>
    </div>

    <!-- SECTION LAPORAN PER SHIFT -->
    <div class="tab-pane fade" id="shift">
        <h4>Laporan Penjualan Per Shift</h4>
        <table class="table table-striped">
            <tr><th>Shift</th><th>Total Pesanan</th><th>Total Pendapatan</th></tr>
            <?php 
            $totalPesananShift = 0;
            $totalPendapatanShift = 0;
            foreach($laporanPerShift as $row): 
                $totalPesananShift += $row['total_pesanan'];
                $totalPendapatanShift += $row['total_penjualan'];
            ?>
            <tr>
                <td><?= $row['shift'] ?></td>
                <td><?= $row['total_pesanan'] ?></td>
                <td><?= $row['total_penjualan'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="fw-bold">
                <td>Total</td>
                <td><?= $totalPesananShift ?></td>
                <td><?= $totalPendapatanShift ?></td>
            </tr>
        </table>
    </div>

    <!-- SECTION PER SERVER -->
    <div class="tab-pane fade" id="server">
        <h4>Laporan Penjualan Per Server</h4>
        <table class="table table-striped">
            <tr><th>Server</th><th>Total Pesanan</th><th>Pendapatan</th></tr>
            <?php 
            $totalPesananServer = 0;
            $totalPendapatanServer = 0;
            foreach($laporanPerServer as $row):
                $totalPesananServer += $row['total_pesanan'];
                $totalPendapatanServer += $row['total_penjualan'];
            ?>
            <tr>
                <td><?= $row['nama_server'] ?></td>
                <td><?= $row['total_pesanan'] ?></td>
                <td><?= $row['total_penjualan'] ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="fw-bold">
                <td>Total</td>
                <td><?= $totalPesananServer ?></td>
                <td><?= $totalPendapatanServer ?></td>
            </tr>
        </table>
    </div>

    <!-- SECTION MENU PALING LARIS -->
    <div class="tab-pane fade" id="laris">
        <h4>Analisis Menu Paling Laris</h4>
        <table class="table table-striped">
            <tr><th>Menu</th><th>Total Qty</th><th>Pendapatan</th></tr>
            <tr>
                <td><?= $menuLaris['nama_menu'] ?></td>
                <td><?= $menuLaris['total_terjual'] ?></td>
                <td><?= $menuLaris['total_pendapatan'] ?></td>
            </tr>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'layout/footer.php'; ?>