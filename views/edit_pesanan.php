<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Validasi ID
if (!isset($_GET['id'])) {
    header("Location: Pesanan.php");
    exit;
}

$id = $_GET['id'];

// Data utama pesanan
$pesanan = $model->getPesananById($id);

// Data item pesanan
$detail = $model->getDetailPesanan($id);

// Data dropdown
$pelanggan = $model->getAllPelanggan();
$meja      = $model->getAllMeja();
$server    = $model->getAllServer();
$menu      = $model->getAllMenu();

if (!$pesanan) {
    echo "Pesanan tidak ditemukan!";
    exit;
}

// Proses edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_pelanggan = $_POST['id_pelanggan'];
    $id_meja      = $_POST['id_meja'];
    $id_server    = $_POST['id_server'];
    $status       = $_POST['status_orderan'];

    // Update pesanan utama
    $model->updatePesanan($id, $id_pelanggan, $id_meja, $id_server, $status);

    // Hapus detail lama
    $model->hapusDetailPesanan($id);

    // Masukkan ulang item pesanan
    foreach ($_POST['menu'] as $menuId => $jumlah) {
        if ($jumlah > 0) {
            $model->tambahDetailPesanan($id, $menuId, $jumlah);
        }
    }

    header("Location: Pesanan.php?success=update");
    exit;
}

?>

<?php include 'layout/header.php'; ?>

<h2>Edit Pesanan</h2>

<form method="POST">

    <label>Pelanggan:</label>
    <select name="id_pelanggan" required>
        <?php foreach ($pelanggan as $p): ?>
        <option value="<?= $p['id_pelanggan'] ?>" 
            <?= $p['id_pelanggan'] == $pesanan['id_pelanggan'] ? 'selected' : '' ?>>
            <?= $p['nama'] ?>
        </option>
        <?php endforeach; ?>
    </select>

    <label>Meja:</label>
    <select name="id_meja" required>
        <?php foreach ($meja as $m): ?>
        <option value="<?= $m['id_meja'] ?>" 
            <?= $m['id_meja'] == $pesanan['id_meja'] ? 'selected' : '' ?>>
            Meja <?= $m['nomor_meja'] ?>
        </option>
        <?php endforeach; ?>
    </select>

    <label>Server:</label>
    <select name="id_server" required>
        <?php foreach ($server as $s): ?>
        <option value="<?= $s['id_server'] ?>"
            <?= $s['id_server'] == $pesanan['id_server'] ? 'selected' : '' ?>>
            <?= $s['nama_server'] ?>
        </option>
        <?php endforeach; ?>
    </select>

    <label>Status Pesanan:</label>
    <select name="status_orderan">
        <option value="Diproses" <?= $pesanan['status_orderan'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
        <option value="Selesai"  <?= $pesanan['status_orderan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
    </select>

    <h3>Edit Item Menu</h3>

    <?php
    // buat mapping jumlah lama
    $jumlahLama = [];
    foreach ($detail as $d) {
        $jumlahLama[$d['id_menu']] = $d['jumlah'];
    }
    ?>

    <?php foreach ($menu as $mn): ?>
        <?php
        $jumlah = isset($jumlahLama[$mn['id_menu']]) ? $jumlahLama[$mn['id_menu']] : 0;
        ?>
        <label><?= $mn['nama_menu'] ?> (Rp <?= number_format($mn['harga'],0,',','.') ?>)</label>
        <input type="number" name="menu[<?= $mn['id_menu'] ?>]" min="0" value="<?= $jumlah ?>">
    <?php endforeach; ?>

    <br><br>

    <button type="submit">Update</button>
    <a href="Pesanan.php" class="back-link">Batal</a>
</form>

<?php include 'layout/footer.php'; ?>
