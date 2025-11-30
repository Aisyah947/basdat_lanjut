<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';


$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Validasi ID
if (!isset($_GET['id'])) {
    header("Location: Pelanggan.php");
    exit;
}

$id = $_GET['id'];
$data = $model->getPelangganById($id);

if (!$data) {
    echo "Data tidak ditemukan";
    exit;
}

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $no_telepon = $_POST['no_telepon'];

    if ($model->updatePelanggan($id, $nama, $no_telepon)) {
        header("Location: Pelanggan.php?success=update");
        exit;
    } else {
        $error = "Gagal memperbarui data.";
    }
}
?>

<?php include 'layout/header.php'; ?>

<h2>Edit Pelanggan</h2>

<?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

<form method="POST">
    <label>Nama:</label>
    <input type="text" name="nama" value="<?= $data['nama'] ?>" required>

    <label>No Telepon:</label>
    <input type="text" name="no_telepon" value="<?= $data['no_telepon'] ?>" required>


    <button type="submit">Update</button>

    <a href="Pelanggan.php" class="back-link">Batal</a>
</form>

<?php include 'layout/footer.php'; ?>
