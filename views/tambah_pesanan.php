<?php
include("../config/database.php");
include("../models/RestoranModel.php");

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$menu      = $model->getAllMenu();
$pelanggan = $model->getAllPelanggan();
$meja      = $model->getAllMeja();
$server    = $model->getAllKaryawan();  
$menu      = $model->getAllMenu();
$server    = $model->getAllServer();
$error = ""; // inisialisasi error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_pelanggan = $_POST['id_pelanggan'];
    $id_meja      = $_POST['id_meja'];
    $id_server    = $_POST['id_server'];
    $status_bayar = $_POST['status_pembayaran'];
    $metode = $_POST['metode_pembayaran'];
    $status       = $_POST['status_orderan'];

    // hitung total harga
    
    // hitung total harga dulu
    $total = 0;
    foreach ($_POST['menu'] as $menuId => $jumlah) {
        if ($jumlah > 0) {
            foreach ($menu as $mn) {
                if ($mn['id_menu'] == $menuId) {
                    $total += $mn['harga'] * $jumlah;
                }
            }
        }
    }

    $tanggal = date("Y-m-d");
    $error = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $id_meja = $_POST['id_meja'];
        $cekMeja = $model->getMejaById($id_meja);
    
        if ($cekMeja && $cekMeja['status_meja'] == 'TERISI') {
            $error = "Meja yang dipilih sedang TERISI! Pilih meja lain.";
        } else {
            try {
                $conn->beginTransaction();
    
                // Tambah pesanan utama
                $model->tambahPesanan($id_pelanggan, $id_meja, $id_server, $tanggal, $total, $status, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
                $id_pesanan = $conn->lastInsertId();
    
                // Tambah detail item
                foreach ($_POST['menu'] as $menuId => $jumlah) {
                    if ($jumlah > 0) {
                        $model->tambahDetailPesanan($id_pesanan, $menuId, $jumlah);
                    }
                }
    
                // Update status meja menjadi TERISI
                $model->updateStatusMeja($id_meja, 'TERISI');
    
                $conn->commit();
                header("Location: Pesanan.php?success=tambah");
                exit;
    
            } catch (Exception $e) {
                $conn->rollBack();
                $error = "Terjadi kesalahan: " . $e->getMessage();
            }
        }
    }
    // Tambah pesanan utama
    $id_pesanan = $model->tambahPesanan(
    $id_pelanggan,
    $id_meja,
    $id_server,
    $tanggal,
    $total,
    $status,
    $status_bayar,
    $metode
);

    // baru tambah detail pesanan
    foreach ($_POST['menu'] as $menuId => $jumlah) {
        if ($jumlah > 0) {
            $model->tambahDetailPesanan($id_pesanan, $menuId, $jumlah);
        }
    }


    header("Location: Pesanan.php?success=tambah");
    exit;
}
?>

<?php include 'layout/header.php'; ?>

<h2>Tambah Pesanan</h2>

<?php if (!empty($error)) : ?>
    <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    <label>Pelanggan:</label>
    <select name="id_pelanggan" required>
        <option value="">-- Pilih Pelanggan --</option>
        <?php foreach ($pelanggan as $p): ?>
        <option value="<?= $p['id_pelanggan'] ?>"><?= $p['nama'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Nomor Meja:</label>
<select name="id_meja" required>
    <option value="">-- Pilih Meja --</option>
    <?php foreach ($meja as $m): ?>
    <option value="<?= $m['id_meja'] ?>">
        Meja <?= $m['nomor_meja'] ?> <?= $m['status_meja'] == 'TERISI' ? '(Terisi)' : '' ?>
    </option>
    <?php endforeach; ?>
</select>


    <label>Nama Pelayan:</label>
    <select name="id_server" required>
        <option value="">-- Pilih Server --</option>
        <?php foreach ($server as $s): ?>
        <option value="<?= $s['id_server'] ?>"><?= $s['nama_server'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Status Orderan:</label>
    <select name="status_orderan" required>
        <option value="Diproses">Diproses</option>
        <option value="Selesai">Selesai</option>
    </select>
    
    <label>Status Pembayaran:</label>
    <select name="status_pembayaran" required>
        <option value="Belum Dibayar">Belum Dibayar</option>
        <option value="Sudah Dibayar">Sudah Dibayar</option>
    </select>


    <label>Metode Pembayaran:</label>
    <select name="metode_pembayaran" required>
        <option value="Tunai">Tunai</option>
        <option value="Transfer">Transfer</option>
        <option value="QRIS">QRIS</option>
    </select>

    <h3>Pilih Menu</h3>
    <?php foreach ($menu as $mn): ?>
        <label><?= $mn['nama_menu'] ?> (Rp <?= number_format($mn['harga'],0,',','.') ?>):</label>
        <input type="number" name="menu[<?= $mn['id_menu'] ?>]" min="0" value="0">
    <?php endforeach; ?>

    <br><br>
    <button type="submit">Tambah</button>
    <a href="Pesanan.php">Kembali</a>
</form>

<?php include 'layout/footer.php'; ?>
