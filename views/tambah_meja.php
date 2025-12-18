<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_meja = $_POST['nomor_meja'];
    $status_meja = $_POST['status_meja'];
    $kapasitas = $_POST['kapasitas'];

    if ($model->tambahMeja($nomor_meja, $status_meja, $kapasitas)) {
        header("Location: Meja.php?success=tambah");
        exit;
    } else {
        $error = "Gagal menambah data meja.";
    }
}
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<h2 class="page-title">Tambah Meja</h2>

<?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

<form method="POST">

    <label>Nomor Meja:</label>
    <input type="number" name="nomor_meja" required>

    <label>Status Meja:</label>
    <select name="status_meja">
        <option value="Kosong">Kosong</option>
        <option value="Terisi">Terisi</option>
        <option value="Dipesan">Dipesan</option>
    </select>

    <label>Kapasitas:</label>
    <input type="number" name="kapasitas" required>

    <button type="submit">Tambah</button>

    <a href="Meja.php" class="back-link">Kembali</a>
</form>


<?php include 'layout/footer.php'; ?>
