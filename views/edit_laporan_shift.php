<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// CEK: apakah id dikirim?
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ERROR: ID laporan shift tidak ditemukan!");
}

$id = $_GET['id'];

// Ambil data laporan
$data = $model->getLaporanShiftById($id);

// Cek apakah data ditemukan
if (!$data) {
    die("ERROR: Data laporan shift tidak ditemukan di database!");
}

// Ambil data server
$server = $model->getAllServer();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $model->editLaporanShift(
        $id,
        $_POST['id_server'],
        $_POST['tanggal'],
        $_POST['mulai'],
        $_POST['selesai'],
        $_POST['penjualan'],
        $_POST['pesanan'],
        $_POST['shift']
    );

    header("Location: LaporanSift.php?success=edit");
    exit;
}
?>


<?php include 'layout/header.php'; ?>

<h2>Edit Laporan Shift</h2>

<form method="POST">

    <label>Server:</label>
    <select name="id_server">
        <?php foreach($server as $s): ?>
        <option value="<?= $s['id_server'] ?>" 
            <?= $s['id_server'] == $data['id_server'] ? 'selected' : '' ?>>
            <?= $s['nama_server'] ?>
        </option>
        <?php endforeach; ?>
    </select>

    <label>Tanggal:</label>
    <input type="date" name="tanggal" value="<?= $data['tanggal'] ?>">

    <label>Waktu Mulai:</label>
    <input type="time" name="mulai" value="<?= $data['waktu_mulai'] ?>">

    <label>Waktu Selesai:</label>
    <input type="time" name="selesai" value="<?= $data['waktu_selesai'] ?>">

    <label>Total Penjualan:</label>
    <input type="number" name="penjualan" value="<?= $data['total_penjualan'] ?>">

    <label>Total Pesanan:</label>
    <input type="number" name="pesanan" value="<?= $data['total_pesanan'] ?>">

    <label>Shift:</label>
    <select name="shift">
        <option <?= $data['shift']=='Pagi'?'selected':'' ?> value="Pagi">Pagi</option>
        <option <?= $data['shift']=='Siang'?'selected':'' ?> value="Siang">Siang</option>
        <option <?= $data['shift']=='Malam'?'selected':'' ?> value="Malam">Malam</option>
    </select>

    <button type="submit">Update</button>
    <a href="LaporanSift.php" class="back-link">Batal</a>
</form>

<?php include 'layout/footer.php'; ?>
