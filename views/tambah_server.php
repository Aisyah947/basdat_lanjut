<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = $_POST['nama_server'];
    $telp   = $_POST['no_telepon'];
    $shift  = $_POST['shift'];

    if ($model->tambahServer($nama, $telp, $shift)) {
        header("Location: Server.php?success=tambah");
        exit;
    }
}
?>

<?php include 'layout/header.php'; ?>

<h2>Tambah Server</h2>

<form method="POST">
    <label>Nama Server:</label>
    <input type="text" name="nama_server" required>

    <label>No Telepon:</label>
    <input type="text" name="no_telepon" required>

    <label>Shift:</label>
    <select name="shift" required>
        <option value="Pagi">Pagi</option>
        <option value="Siang">Siang</option>
        <option value="Malam">Malam</option>
    </select>

    <button type="submit">Simpan</button>
    <a href="Server.php">Kembali</a>
</form>

<?php include 'layout/footer.php'; ?>
