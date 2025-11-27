<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil kategori untuk dropdown
$kategori = $model->getAllKategori();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

<link rel="stylesheet" href="css/sty.css">

<div class="form-container">

    <h2 class="title">Tambah Menu Baru</h2>

    <form action="proses_tambah_menu.php" method="POST" enctype="multipart/form-data" class="form-box">

        <div class="form-group">
            <label>Nama Menu *</label>
            <input type="text" name="nama_menu" placeholder="Masukkan nama menu" required>
        </div>

        <div class="form-group">
            <label>Kategori Menu *</label>
            <select name="id_kategori" required>
                <option value="">*** Pilih Kategori ***</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id_kategori'] ?>"><?= $k['nama_kategori'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Harga (Rp) *</label>
            <input type="number" name="harga" placeholder="Contoh: 25000" required>
            <small>Isikan angka tanpa titik. Contoh: 500000 untuk 500 ribu.</small>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" placeholder="Masukkan deskripsi menu"></textarea>
        </div>

        <div class="form-group">
            <label>Status Ketersediaan *</label>
            <select name="status_ketersediaan" required>
                <option value="1">Tersedia</option>
                <option value="0">Tidak Tersedia</option>
            </select>
        </div>

        <div class="form-group">
            <label>Foto Menu</label>
            <input type="file" name="foto_menu" accept="image/*">
        </div>

        <div class="button-group">
            <button type="submit" class="btn-primary">Simpan Data</button>
            <a href="menu.php" class="btn-secondary">Kembali ke Daftar</a>
        </div>

    </form>

    

</div>

<?php include_once __DIR__ . '/layout/footer.php'; ?>
