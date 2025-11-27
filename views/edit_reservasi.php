<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: reservasi.php"); exit; }

$reservasi = $model->getReservasiById($id);
$pelanggan = $model->getAllPelanggan();
$meja = $model->getAllMeja();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<h2>Edit Reservasi</h2>

<form action="proses_edit_reservasi.php" method="POST">

    <input type="hidden" name="id_reservasi" value="<?= $reservasi['id_reservasi'] ?>">

    <div class="form-group">
        <label>Pelanggan *</label>
        <select name="id_pelanggan" required>
            <option value="">-- Pilih Pelanggan --</option>
            <?php foreach ($pelanggan as $p): ?>
                <option value="<?= $p['id_pelanggan'] ?>"
                    <?= $reservasi['id_pelanggan'] == $p['id_pelanggan'] ? 'selected' : '' ?>>
                    <?= $p['nama'] ?> (<?= $p['no_telepon'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Meja *</label>
        <select name="id_meja" required>
            <option value="">-- Pilih Meja --</option>
            <?php foreach ($meja as $m): ?>
                <option value="<?= $m['id_meja'] ?>"
                    <?= $reservasi['id_meja'] == $m['id_meja'] ? 'selected' : '' ?>>
                    Meja <?= $m['nomor_meja'] ?> (Kapasitas: <?= $m['kapasitas'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Tanggal Reservasi *</label>
        <input type="date" name="tanggal_reservasi"
               value="<?= $reservasi['tanggal_reservasi'] ?>" required>
    </div>

    <div class="form-group">
        <label>Jam Reservasi *</label>
        <input type="time" name="jam_reservasi"
               value="<?= $reservasi['jam_reservasi'] ?>" required>
    </div>

    <div class="form-group">
        <label>Jumlah Orang *</label>
        <input type="number" name="jumlah_orang" min="1"
               value="<?= $reservasi['jumlah_orang'] ?>" required>
    </div>

    <div class="form-group">
        <label>Status Reservasi *</label>
        <select name="status_reservasi" required>
            <option value="Menunggu"     <?= $reservasi['status_reservasi']=='Menunggu' ? 'selected':'' ?>>Menunggu</option>
            <option value="Dikonfirmasi" <?= $reservasi['status_reservasi']=='Dikonfirmasi' ? 'selected':'' ?>>Dikonfirmasi</option>
            <option value="Selesai"      <?= $reservasi['status_reservasi']=='Selesai' ? 'selected':'' ?>>Selesai</option>
            <option value="Batal"        <?= $reservasi['status_reservasi']=='Batal' ? 'selected':'' ?>>Batal</option>
        </select>
    </div>

    <button type="submit">Simpan</button>
    <a href="reservasi.php" class="back-link">Kembali</a>

</form>

<?php include_once __DIR__ . '/layout/footer.php'; ?>
