<?php
include '../config/database.php';
include '../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$nama        = $_POST['nama_menu'];
$id_kategori = $_POST['id_kategori'];
$harga       = floatval($_POST['harga']);
$deskripsi   = $_POST['deskripsi'];
$status      = $_POST['status_ketersediaan'] == "1";

$foto = null;

if (isset($_FILES['foto_menu']) && $_FILES['foto_menu']['error'] === 0) {
    $tmp = $_FILES['foto_menu']['tmp_name'];
    $mime = mime_content_type($tmp);

    if (!in_array($mime, ['image/jpeg', 'image/png'])) {
        die("Format gambar harus JPG atau PNG");
    }

    $foto = base64_encode(file_get_contents($tmp));
}

$model->insertMenu($nama, $id_kategori, $harga, $deskripsi, $status, $foto);

header("Location: menu.php");
exit;
