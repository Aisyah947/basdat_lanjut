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
    <style>
        /* Reset body untuk layout yang lebih baik */
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        /* Container utama dengan flexbox */
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background-color: #4169E1;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            color: white;
        }
        
        /* Content area */
        .content-wrapper {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 2rem;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        /* Card untuk konten */
        .content-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 100%;
        }
        
        /* Responsif untuk mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            
            .content-wrapper {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="caction-buttons top-buttons">
    <div class="content-card">
        <h2 class="mb-4">Laporan Penjualan</h2>
        
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
        
        <div class="my-3">
            <input id="searchInput" type="text" class="form-control" placeholder="Cari disini...">
        </div>

        <div class="tab-content mt-3">

            <!-- SECTION LAPORAN PER MENU -->
            <div class="tab-pane fade show active" id="menu">
                <h4 class="mb-3">Laporan Penjualan per Menu</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th>Total Qty</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <td>Rp <?= number_format($row['total_pendapatan'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td>Total</td>
                                <td><?= $totalQty ?></td>
                                <td>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- SECTION LAPORAN PER SHIFT -->
            <div class="tab-pane fade" id="shift">
                <h4 class="mb-3">Laporan Penjualan Per Shift</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Shift</th>
                                <th>Total Pesanan</th>
                                <th>Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <td>Rp <?= number_format($row['total_penjualan'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td>Total</td>
                                <td><?= $totalPesananShift ?></td>
                                <td>Rp <?= number_format($totalPendapatanShift, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- SECTION PER SERVER -->
            <div class="tab-pane fade" id="server">
                <h4 class="mb-3">Laporan Penjualan Per Server</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Server</th>
                                <th>Total Pesanan</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <td>Rp <?= number_format($row['total_penjualan'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td>Total</td>
                                <td><?= $totalPesananServer ?></td>
                                <td>Rp <?= number_format($totalPendapatanServer, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- SECTION MENU PALING LARIS -->
            <div class="tab-pane fade" id="laris">
                <h4 class="mb-3">Analisis Menu Paling Laris</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th>Total Qty</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?= $menuLaris['nama_menu'] ?></td>
                                <td><?= $menuLaris['total_terjual'] ?></td>
                                <td>Rp <?= number_format($menuLaris['total_pendapatan'], 0, ',', '.') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById("searchInput").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();
    let tables = document.querySelectorAll(".table.table-striped");

    tables.forEach(table => {
        let rows = table.querySelectorAll("tbody tr");

        rows.forEach(row => {
            let rowText = row.innerText.toLowerCase();
            row.style.display = rowText.includes(value) ? "" : "none";
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'layout/footer.php'; ?>