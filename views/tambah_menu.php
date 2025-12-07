<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$kategori = $model->getAllKategori();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

    <h2>Tambah Menu Baru</h2>

    <form action="proses_tambah_menu.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Nama Menu *</label>
            <input type="text" name="nama_menu" required>
        </div>

        <div class="form-group">
            <label>Kategori Menu *</label>
            <select name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Harga (Rp) *</label>
            <input type="number" name="harga" required>
            <small>Contoh: 25000 (tanpa titik)</small>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi"></textarea>
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status_ketersediaan" required>
                <option value="1">Tersedia</option>
                <option value="0">Tidak Tersedia</option>
            </select>
        </div>

        <div class="form-group">
            <label>Foto Menu</label>
            <input type="file" name="foto_menu" accept="image/*">
        </div>

        <button type="submit">Simpan</button>
    <a href="Menu.php" class="back-link">Kembali</a>

    </form>


<?php include_once __DIR__ . '/layout/footer.php'; ?>
