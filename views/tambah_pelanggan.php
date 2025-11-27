<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Proses tambah pelanggan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];

    if ($model->tambahPelanggan($nama, $no_telepon)) {
        header("Location: Pelanggan.php?success=tambah");
        exit;
    } else {
        $error = "Gagal menambah pelanggan.";
    }
}
?>

<?php include 'layout/header.php'; ?>

<h2>Tambah Pelanggan</h2>

<?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

<form method="POST">
    <label>Nama:</label>
    <input type="text" name="nama" required>

    <label>No Telepon:</label>
    <input type="text" name="no_telepon" required>

    <button type="submit">Simpan</button>
    <a href="Pelanggan.php">Kembali</a>
</form>

<?php include 'layout/footer.php'; ?>
