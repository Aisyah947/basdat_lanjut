<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Validasi ID
if (!isset($_GET['id'])) {
    header("Location: Meja.php");
    exit;
}

$id = $_GET['id'];
$data = $model->getMejaById($id);

if (!$data) {
    echo "Data meja tidak ditemukan!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_meja = $_POST['nomor_meja'];
    $status_meja = $_POST['status_meja'];
    $kapasitas = $_POST['kapasitas'];

    if ($model->updateMeja($id, $nomor_meja, $status_meja, $kapasitas)) {
        header("Location: Meja.php?success=update");
        exit;
    } else {
        $error = "Gagal memperbarui data meja.";
    }
}
?>

<?php include 'layout/header.php'; ?>

<h2>Edit Meja</h2>

<?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

<form method="POST">

    <label>Nomor Meja:</label>
    <input type="number" name="nomor_meja" value="<?= $data['nomor_meja'] ?>" required>

    <label>Status Meja:</label>
    <select name="status_meja">
        <option value="Kosong" <?= $data['status_meja'] == 'Kosong' ? 'selected' : '' ?>>Kosong</option>
        <option value="Terisi" <?= $data['status_meja'] == 'Terisi' ? 'selected' : '' ?>>Terisi</option>
        <option value="Dipesan" <?= $data['status_meja'] == 'Dipesan' ? 'selected' : '' ?>>Dipesan</option>
    </select>

    <label>Kapasitas:</label>
    <input type="number" name="kapasitas" value="<?= $data['kapasitas'] ?>" required>

    <button type="submit">Update</button>
    <a href="Meja.php">Batal</a>

</form>

<?php include 'layout/footer.php'; ?>
