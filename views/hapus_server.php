<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$id = $_GET['id'];

if ($model->hapusServer($id)) {
    header("Location: Server.php?success=hapus");
    exit;
} else {
    echo "Gagal menghapus server.";
}
?>
