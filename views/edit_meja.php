<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Cek ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Meja tidak ditemukan.");
}

$id = $_GET['id'];

// Ambil data meja
$data = $model->getMejaById($id);

if (!$data) {
    die("Data meja tidak ditemukan.");
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nomor  = $_POST['nomor_meja'];
    $status = $_POST['status_meja'];
    $kapas  = $_POST['kapasitas'];

    if ($model->updateMeja($id, $nomor, $status, $kapas)) {
        header("Location: Meja.php?success=edit");

        exit;
    }
}
?>

<?php include 'layout/header.php'; ?>

<h2>Edit Meja</h2>

<form method="POST">

    <label>Nomor Meja:</label>
    <input type="number" name="nomor_meja" value="<?= $data['nomor_meja'] ?>" required>

    <label>Status Meja:</label>
    <select name="status_meja">
        <option value="Kosong" <?= $data['status_meja']=='Kosong'?'selected':'' ?>>Kosong</option>
        <option value="Terisi" <?= $data['status_meja']=='Terisi'?'selected':'' ?>>Terisi</option>
        <option value="Booking" <?= $data['status_meja']=='Booking'?'selected':'' ?>>Booking</option>
    </select>

    <label>Kapasitas:</label>
    <input type="number" name="kapasitas" value="<?= $data['kapasitas'] ?>" required>

    <button type="submit">Update</button>
    <a href="../Meja.php">Kembali</a>

</form>

<?php include 'layout/footer.php'; ?>
