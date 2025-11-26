<?php
include '../config/koneksi.php';
include '../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();

$id = $_POST['id_menu'];
$nama = $_POST['nama_menu'];
$id_kategori = $_POST['id_kategori'];
$harga = $_POST['harga'];
$deskripsi = $_POST['deskripsi'];
$status = $_POST['status_ketersediaan'];

// Ambil foto lama dulu
$stmt = $conn->prepare("SELECT foto_menu FROM menu WHERE id_menu=:id");
$stmt->execute([':id'=>$id]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);
$foto = $menu['foto_menu'];

// Upload foto baru jika ada
if(!empty($_FILES['foto_menu']['name'])){
    $foto = time().'_'.preg_replace("/[^a-zA-Z0-9\._-]/", "", $_FILES['foto_menu']['name']);
    move_uploaded_file($_FILES['foto_menu']['tmp_name'], "../uploads/".$foto);
}

$stmt = $conn->prepare("
    UPDATE menu SET
        nama_menu=:nama,
        id_kategori=:kategori,
        harga=:harga,
        deskripsi=:deskripsi,
        status_ketersediaan=:status,
        foto_menu=:foto
    WHERE id_menu=:id
");

$stmt->execute([
    ':nama'=>$nama,
    ':kategori'=>$id_kategori,
    ':harga'=>$harga,
    ':deskripsi'=>$deskripsi,
    ':status'=>$status,
    ':foto'=>$foto,
    ':id'=>$id
]);

header("Location: menu.php");
exit();
