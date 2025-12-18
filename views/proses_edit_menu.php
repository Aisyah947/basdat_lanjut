<?php
include '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

$id          = $_POST['id_menu'];
$nama        = $_POST['nama_menu'];
$id_kategori = $_POST['id_kategori'];
$harga       = $_POST['harga'];
$deskripsi   = $_POST['deskripsi'];
$status      = $_POST['status_ketersediaan'];

// Ambil foto lama
$stmt = $conn->prepare("SELECT foto_menu FROM menu WHERE id_menu = :id");
$stmt->execute([':id' => $id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);
$foto = $data['foto_menu'];

// Jika upload foto baru
if (isset($_FILES['foto_menu']) && $_FILES['foto_menu']['error'] === 0) {

    $tmp  = $_FILES['foto_menu']['tmp_name'];
    $type = mime_content_type($tmp); // image/jpeg / image/png

    // VALIDASI
    if (!in_array($type, ['image/jpeg', 'image/png'])) {
        die("Format gambar harus JPG atau PNG");
    }

    // SIMPAN BASE64 KE TEXT
    $foto = base64_encode(file_get_contents($tmp));
}

$stmt = $conn->prepare("
    UPDATE menu SET
        nama_menu = :nama,
        id_kategori = :kategori,
        harga = :harga,
        deskripsi = :deskripsi,
        status_ketersediaan = :status,
        foto_menu = :foto
    WHERE id_menu = :id
");

$stmt->execute([
    ':nama'     => $nama,
    ':kategori' => $id_kategori,
    ':harga'    => $harga,
    ':deskripsi'=> $deskripsi,
    ':status'   => $status,
    ':foto'     => $foto,
    ':id'       => $id
]);

header("Location: menu.php");
exit;
