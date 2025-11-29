<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$id = $_GET['id'];

$model->hapusLaporanShift($id);

header("Location: LaporanSift.php?success=hapus");
exit;
?>
