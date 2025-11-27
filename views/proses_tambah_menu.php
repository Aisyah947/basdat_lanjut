<?php
include '../config/koneksi.php';
include '../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$nama        = $_POST['nama_menu'];
$id_kategori = $_POST['id_kategori'];
$harga       = floatval($_POST['harga']);
$deskripsi   = $_POST['deskripsi'];
$status      = $_POST['status_ketersediaan'] == "1" ? true : false;

$foto = null;

if (!empty($_FILES['foto_menu']['name'])) {
    $foto = time() . "_" . $_FILES['foto_menu']['name'];
    move_uploaded_file($_FILES['foto_menu']['tmp_name'], "../uploads/" . $foto);
}

$model->insertMenu($nama, $id_kategori, $harga, $deskripsi, $status, $foto);

header("Location: menu.php");
exit();

