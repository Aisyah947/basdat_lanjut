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



<!DOCTYPE html>
<html lang="id">
<?php include_once __DIR__ . '/layout/header.php'; ?>
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <style>
        body {
            font-family: Arial;
            background: #f3f3f3;
            padding: 30px;
        }
        h2 { text-align: center; margin-bottom: 25px; }
        .form-container {
            background: white;
            width: 500px;
            margin: auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        label { font-weight: bold; margin-top: 15px; display: block; }
        input[type="text"],
        input[type="number"],
        textarea,
        select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        textarea { height: 100px; }
        .btn-submit {
            background: #28a745;
            color: white;
            padding: 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 20px;
        }
        .btn-submit:hover { background: #1e7e34; }
        .back { text-align: center; margin-top: 15px; }
        .back a { text-decoration: none; color: #007bff; }
        .back a:hover { text-decoration: underline; }
        .current-photo { margin-top: 10px; }
    </style>
</head>
<body>

<h2>Edit Menu</h2>

<div class="form-container">
    <form action="proses_edit_menu.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_menu" value="<?= $menu['id_menu'] ?>">

        <label>Nama Menu</label>
        <input type="text" name="nama_menu" value="<?= $menu['nama_menu'] ?>" required>

        <label>Kategori Menu</label>
        <select name="id_kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id_kategori'] ?>" <?= $menu['id_kategori']==$k['id_kategori']?'selected':'' ?>>
                    <?= $k['nama_kategori'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Harga</label>
        <input type="number" name="harga" value="<?= $menu['harga'] ?>" required min="0">

        <label>Deskripsi</label>
        <textarea name="deskripsi"><?= $menu['deskripsi'] ?></textarea>

        <label>Status Ketersediaan</label>
        <select name="status_ketersediaan" required>
            <option value="1" <?= $menu['status_ketersediaan']==1?'selected':'' ?>>Tersedia</option>
            <option value="0" <?= $menu['status_ketersediaan']==0?'selected':'' ?>>Tidak Tersedia</option>
        </select>

        <label>Foto Menu</label>
        <?php if(!empty($menu['foto_menu'])): ?>
            <div class="current-photo">
                <img src="../uploads/<?= $menu['foto_menu'] ?>" width="150" alt="Foto Menu">
            </div>
        <?php endif; ?>
        <input type="file" name="foto_menu" accept="image/*">

        <button type="submit" class="btn-submit">Simpan Perubahan</button>
    </form>

    <div class="back">
        <a href="menu.php">← Kembali ke Daftar Menu</a>
    </div>
</div>

</body>
</html>
