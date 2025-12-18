<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

if (!isset($_GET['id'])) {
    header("Location: Pesanan.php");
    exit;
}

$id = $_GET['id'];

// Hapus detail dulu
$model->hapusDetailPesanan($id);

// Baru hapus pesanan utama
$model->hapusPesanan($id);

header("Location: Pesanan.php?success=hapus");
exit;
