<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_pelanggan = $_POST['id_pelanggan'];
    $id_meja = $_POST['id_meja'];
    $tanggal = $_POST['tanggal_reservasi'];
    $jam = $_POST['jam_reservasi'];
    $jumlah_orang = $_POST['jumlah_orang'];
    $status = $_POST['status_reservasi'];

    // Panggil fungsi tambahReservasi
    $success = $model->tambahReservasi(
        $id_pelanggan,
        $id_meja,
        $tanggal,
        $jam,
        $jumlah_orang,
        $status
    );

    if ($success) {
        header("Location: reservasi.php?success=tambah");
        exit;
    } else {
        header("Location: reservasi.php?error=gagal");
        exit;
    }

} else {
    header("Location: reservasi.php");
    exit;
}
