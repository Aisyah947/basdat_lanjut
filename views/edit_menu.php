<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

$id = $_GET['id'] ?? null;
if(!$id) { header("Location: menu.php"); exit; }

// Ambil data menu
$menu = $model->getMenuById($id);
$kategori = $model->getAllKategori();
?>

<?php include_once __DIR__ . '/layout/header.php'; ?>

    <h2>Edit Menu</h2>

    <form action="proses_edit_menu.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_menu" value="<?= $menu['id_menu'] ?>">

        <div class="form-group">
            <label>Nama Menu *</label>
            <input type="text" name="nama_menu" value="<?= $menu['nama_menu'] ?>" required>
        </div>

        <div class="form-group">
            <label>Kategori Menu *</label>
            <select name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($kategori as $k): ?>
                    <option value="<?= $k['id_kategori'] ?>" 
                        <?= $menu['id_kategori']==$k['id_kategori'] ? 'selected' : '' ?>>
                        <?= $k['nama_kategori'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Harga (Rp) *</label>
            <input type="number" name="harga" value="<?= $menu['harga'] ?>" required>
            <small>Contoh: 25000 (tanpa titik)</small>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi"><?= $menu['deskripsi'] ?></textarea>
        </div>

        <div class="form-group">
            <label>Status *</label>
            <select name="status_ketersediaan">
                <option value="1" <?= $menu['status_ketersediaan']==1 ? 'selected' : '' ?>>Tersedia</option>
                <option value="0" <?= $menu['status_ketersediaan']==0 ? 'selected' : '' ?>>Tidak Tersedia</option>
            </select>
        </div>

        <div class="form-group">
            <label>Foto Menu</label>

            <?php if (!empty($menu['foto_menu'])): ?>
                <img src="../uploads/<?= $menu['foto_menu'] ?>" width="150" class="preview-img">
            <?php endif; ?>

            <input type="file" name="foto_menu" accept="image/*">
        </div>
        <button type="submit">Simpan</button>
    <a href="Menu.php" class="back-link">Kembali</a>

    </form>
<?php include_once __DIR__ . '/layout/footer.php'; ?>