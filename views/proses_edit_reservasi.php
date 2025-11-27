<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id_reservasi'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_meja = $_POST['id_meja'];
    $tanggal = $_POST['tanggal_reservasi'];
    $jam = $_POST['jam_reservasi'];
    $jumlah = $_POST['jumlah_orang'];
    $status = $_POST['status_reservasi'];

    $result = $model->UpdateReservasi($id, $id_pelanggan, $id_meja, $tanggal, $jam, $jumlah, $status);

    if ($result) {
        header("Location: reservasi.php?success=edit");
        exit;
    } else {
        header("Location: reservasi.php?error=gagal_edit");
        exit;
    }

} else {
    header("Location: reservasi.php");
    exit;
}
