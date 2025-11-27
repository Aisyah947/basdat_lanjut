<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$pelanggan = $model->getAllPelanggan();
$meja = $model->getAllMeja();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<h2>Tambah Reservasi</h2>

<form action="proses_tambah_reservasi.php" method="POST">

    <div class="form-group">
        <label>Pelanggan *</label>
        <select name="id_pelanggan" required>
            <option value="">-- Pilih Pelanggan --</option>
            <?php foreach ($pelanggan as $p): ?>
                <option value="<?= $p['id_pelanggan'] ?>">
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
                <option value="<?= $m['id_meja'] ?>">
                    Meja <?= $m['nomor_meja'] ?> (Kapasitas: <?= $m['kapasitas'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Tanggal Reservasi *</label>
        <input type="date" name="tanggal_reservasi" required>
    </div>

    <div class="form-group">
        <label>Jam Reservasi *</label>
        <input type="time" name="jam_reservasi" required>
    </div>

    <div class="form-group">
        <label>Jumlah Orang *</label>
        <input type="number" name="jumlah_orang" min="1" required>
    </div>

    <div class="form-group">
        <label>Status Reservasi *</label>
        <select name="status_reservasi" required>
            <option value="Menunggu">Menunggu</option>
            <option value="Dikonfirmasi">Dikonfirmasi</option>
            <option value="Selesai">Selesai</option>
            <option value="Batal">Batal</option>
        </select>
    </div>

    <button type="submit">Simpan</button>
    <a href="reservasi.php" class="back-link">Kembali</a>

</form>

<?php include_once __DIR__ . '/layout/footer.php'; ?>
