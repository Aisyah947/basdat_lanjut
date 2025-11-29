<?php
$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'Menu':
        include 'views/Menu.php';
        break;

    case 'Pesanan':
        include 'views/Pesanan.php';
        break;

    case 'Meja':
        include 'views/Meja.php';
        break;

    case 'Reservasi':
        include 'views/Reservasi.php';
        break;

    case 'Pelanggan':
        include 'views/Pelanggan.php';
        break;

    case 'Server':
        include 'views/Server.php';
        break;

    case 'Laporan':
        include 'views/Laporan.php';
        break;

    case 'LaporanShift':
        include 'views/LaporanShift.php';
        break;

    default:
        include 'views/layout/dashboard.php';
}
?>