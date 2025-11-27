<?php
include '../config/koneksi.php';
include '../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();

$id = $_GET['id'] ?? null;
if($id){
    // Ambil foto dulu untuk dihapus
    $stmt = $conn->prepare("SELECT foto_menu FROM menu WHERE id_menu=:id");
    $stmt->execute([':id'=>$id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty($menu['foto_menu']) && file_exists('../uploads/'.$menu['foto_menu'])){
        unlink('../uploads/'.$menu['foto_menu']); // hapus file
    }

    // Hapus menu
    $stmt = $conn->prepare("DELETE FROM menu WHERE id_menu=:id");
    $stmt->execute([':id'=>$id]);
}

header("Location: menu.php");
exit();
