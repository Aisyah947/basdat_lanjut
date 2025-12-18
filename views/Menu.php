<?php
include_once __DIR__ . '/../models/RestoranModel.php';
include_once __DIR__ . '/../config/database.php';


$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);


$kategori = $model->getAllKategori();
$id_kategori = $_GET['kategori'] ?? null;


$menuData = $id_kategori 
    ? $model->getMenuByKategori($id_kategori) 
    : $model->getAllMenu();

// ====== Uji Coba ACID ======
$hasil_test = "";

if (isset($_GET['test'])) {

    if ($_GET['test'] == "commit") {
        // Percobaan berhasil
        $hasil_test = $model->updateMenuSafe(3, 30000, true);
    }

    if ($_GET['test'] == "rollback") {
        // Percobaan gagal -> harga negatif
        $hasil_test = $model->updateMenuSafe(3, -1000, true);
    }
}


?>


<?php include_once __DIR__ . '/layout/header.php'; ?>

<div class="content">

    <h2 class="page-title">Daftar Menu Restoran</h2>

    <!-- Tombol laporan & tambah -->
    <div class="action-buttons top-buttons">
        <a href="laporan_menu.php" class="btn btn-laporan">
            <i class="fa fa-file"></i> Laporan Menu
        </a>

        <a href="tambah_menu.php" class="btn btn-tambah">
            <i class="fa fa-plus"></i> Tambah Menu
        </a>
    </div>

    <!-- Filter Kategori -->
    <div class="kategori-filter">
        <a href="Menu.php" 
           class="btn btn-kategori <?= $id_kategori ? '' : 'active' ?>">
            Semua
        </a>

        <?php foreach ($kategori as $kat): ?>
            <a href="Menu.php?kategori=<?= $kat['id_kategori'] ?>"
               class="btn btn-kategori <?= ($id_kategori == $kat['id_kategori']) ? 'active' : '' ?>">
                <?= $kat['nama_kategori'] ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Grid Menu -->
    <div class="menu-grid">
        <?php foreach ($menuData as $menu): ?>
        
            <div class="menu-card">
            <?php if (!empty($menu['foto_menu'])): ?>
                <img 
                    src="data:image/jpeg;base64,<?= $menu['foto_menu'] ?>"
                    class="menu-img"
                >
            <?php else: ?>
                <div class="no-photo">Tidak ada foto</div>
            <?php endif; ?>

                <div class="menu-info">
                    <h3><?= $menu['nama_menu'] ?></h3>

                    <span class="badge kategori">
                        <?= $menu['nama_kategori'] ?>
                    </span>

                    <span class="badge <?= $menu['status_ketersediaan'] ? 'tersedia' : 'tidak' ?>">
                        <?= $menu['status_ketersediaan'] ? 'Tersedia' : 'Tidak Tersedia' ?>
                    </span>

                    <p class="harga">
                        Rp <?= number_format($menu['harga'], 0, ',', '.') ?>
                    </p>

                    <p class="deskripsi">
                        <?= $menu['deskripsi'] ?>
                    </p>
                </div>

                <div class="card-actions">
                    <a href="edit_menu.php?id=<?= $menu['id_menu'] ?>" class="edit-btn">Edit</a>

                    <a href="hapus_menu.php?id=<?= $menu['id_menu'] ?>" 
                       class="delete-btn"
                       onclick="return confirm('Yakin ingin menghapus menu ini?');">
                        Hapus
                    </a>
                </div>

            </div>

        <?php endforeach; ?>
    </div>

