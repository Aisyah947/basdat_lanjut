<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

if (!isset($_GET['id'])) {
    header("Location: Pelanggan.php");
    exit;
}

$id = $_GET['id'];

$model->hapusPelanggan($id);

header("Location: Pelanggan.php?success=hapus");
exit;
