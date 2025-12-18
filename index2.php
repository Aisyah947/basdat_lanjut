<?php
include 'config/database.php';
include 'models/RestoranModel.php';

$action = $_GET['action'] ?? 'dashboard';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

switch ($action) {
    case 'Menu':
        include '../views/Menu.php';
        exit;

    case 'Pesanan':
        include 'views/Pesanan.php';
        exit;

    case 'Meja':
        include 'views/Meja.php';
        exit;

    case 'Reservasi':
        include 'views/Reservasi.php';
        exit;

    case 'Reservasi_Tambah':
        include 'views/tambah_reservasi.php';
        exit;
    
    case 'Reservasi_Edit':
        include 'views/edit_reservasi.php';
        exit;

    case 'Reservasi_Hapus':
        include 'views/hapus_reservasi.php';
        exit;

    case 'Pelanggan':
        include 'views/Pelanggan.php';
        exit;

    case 'Server'  :
        include 'views/Server.php';
        exit;

    case 'Laporan':
        include 'views/Laporan.php';
        exit;

    case 'LaporanShift':
        include 'views/LaporanShift.php';
        exit;
}
?>
