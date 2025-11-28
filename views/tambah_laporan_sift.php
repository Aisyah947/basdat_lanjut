<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$server = $model->getAllServer();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $model->tambahLaporanShift(
        $_POST['id_server'],
        $_POST['tanggal'],
        $_POST['mulai'],
        $_POST['selesai'],
        $_POST['penjualan'],
        $_POST['pesanan'],
        $_POST['shift']
    );

    header("Location: LaporanSift.php?success=tambah");
    exit;
}
?>

<?php include 'layout/header.php'; ?>

<h2>Tambah Laporan Shift</h2>

<form method="POST">

    <label>Server:</label>
    <select name="id_server" required>
        <option value="">-- Pilih Server --</option>
        <?php foreach($server as $s): ?>
        <option value="<?= $s['id_server'] ?>"><?= $s['nama_server'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Tanggal:</label>
    <input type="date" name="tanggal" required>

    <label>Waktu Mulai:</label>
    <input type="time" name="mulai" required>

    <label>Waktu Selesai:</label>
    <input type="time" name="selesai" required>

    <label>Total Penjualan:</label>
    <input type="number" name="penjualan" required>

    <label>Total Pesanan:</label>
    <input type="number" name="pesanan" required>

    <label>Shift:</label>
    <select name="shift" required>
        <option value="Pagi">Pagi</option>
        <option value="Siang">Siang</option>
        <option value="Malam">Malam</option>
    </select>

    <br><br>
    <button type="submit">Tambah</button>
    <a href="LaporanSift.php" class="back-link">Kembali</a>
</form>

<?php include 'layout/footer.php'; ?>
