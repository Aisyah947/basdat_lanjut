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

    case 'Laporan':
        include 'views/Laporan.php';
        break;

    default:
        include 'views/dashboard.php';
}
?>