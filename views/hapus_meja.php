<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

if (!isset($_GET['id'])) {
    header("Location: Meja.php");
    exit;
}

$id = $_GET['id'];

$model->hapusMeja($id);

header("Location: Meja.php?success=hapus");
exit;
