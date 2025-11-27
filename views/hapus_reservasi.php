<?php
include_once __DIR__ . '/../models/ReservasiModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Pastikan ada id
if (!isset($_GET['id'])) {
    echo "<script>alert('ID reservasi tidak ditemukan!'); window.location.href='Reservasi.php';</script>";
    exit;
}

$id = $_GET['id'];

// Proses hapus
$hapus = $model->hapusReservasi($id);

if ($hapus) {
    echo "<script>
            alert('Reservasi berhasil dihapus!');
            window.location.href='Reservasi.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus reservasi!');
            window.location.href='Reservasi.php';
          </script>";
}
?>
