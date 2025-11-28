<?php
include_once __DIR__ . '/../config/database.php';
include_once __DIR__ . '/../models/RestoranModel.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$id = $_GET['id'];
$data = $model->getServerById($id);

if (!$data) {
    die("Server tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = $_POST['nama_server'];
    $telp   = $_POST['no_telepon'];
    $shift  = $_POST['shift'];

    if ($model->updateServer($id, $nama, $telp, $shift)) {
        header("Location: Server.php?success=edit");
        exit;
    }
}
?>

<?php include 'layout/header.php'; ?>

<h2>Edit Server</h2>

<form method="POST">
    <label>Nama Server:</label>
    <input type="text" name="nama_server" value="<?= $data['nama_server'] ?>" required>

    <label>No Telepon:</label>
    <input type="text" name="no_telepon" value="<?= $data['no_telepon'] ?>" required>

    <label>Shift:</label>
    <select name="shift">
        <option <?= $data['shift']=='Pagi'?'selected':'' ?>>Pagi</option>
        <option <?= $data['shift']=='Siang'?'selected':'' ?>>Siang</option>
        <option <?= $data['shift']=='Malam'?'selected':'' ?>>Malam</option>
    </select>

    <button type="submit">Update</button>
    <a href="Server.php">Kembali</a>
</form>

<?php include 'layout/footer.php'; ?>
