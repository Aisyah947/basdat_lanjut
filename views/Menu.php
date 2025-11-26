<?php
include_once 'models/RestoranModel.php';
include_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil kategori
$kategori = $model->getAllKategori();

// Filter kategori
$id_kategori = $_GET['kategori'] ?? null;
$menuData = $id_kategori ? $model->getMenuByKategori($id_kategori) : $model->getAllMenu();

$action = $_GET['action'] ?? 'dashboard';
?>

<!-- Header -->
<div class="header">
    <div class="search-bar">
        <i class="fas fa-search" style="color: #999;"></i>
        <input type="text" placeholder="Search for...">
    </div>

    <div class="header-right">
        <button class="icon-btn">
            <i class="far fa-bell"></i>
            <span class="badge">3+</span>
        </button>
        <button class="icon-btn">
            <i class="far fa-envelope"></i>
            <span class="badge">7</span>
        </button>

        <div class="user-profile">
            <span style="color: #666;">Admin</span>
            <div class="user-avatar">AD</div>
        </div>
    </div>
</div>

<!-- CONTENT AREA -->
<div class="content">

    <div class="page-header">
        <h1 class="page-title">Daftar Menu Restoran</h1>

        <a href="index.php?action=Menu&add=true" class="btn-primary">
            Tambah Menu
        </a>
    </div>


    <!-- Filter Kategori -->
    <div style="margin-bottom:20px;">
        <a href="index.php?action=Menu" class="btn-kategori">Semua Kategori</a>

        <?php foreach($kategori as $kat): ?>
            <a href="index.php?action=Menu&kategori=<?= $kat['id_kategori'] ?>" class="btn-kategori">
                <?= $kat['nama_kategori'] ?>
            </a>
        <?php endforeach; ?>
    </div>


    <!-- MENU LIST -->
    <div class="grid">
        <?php foreach ($menuData as $menu): ?>
        <div class="card">

            <?php if(!empty($menu['foto_menu'])): ?>
                <img src="uploads/<?= $menu['foto_menu'] ?>" alt="<?= $menu['nama_menu'] ?>">
            <?php else: ?>
                <div class="no-photo">Tidak ada foto</div>
            <?php endif; ?>

            <div class="card-content">
                <h3><?= $menu['nama_menu'] ?></h3>
                <p>Kategori: <?= $menu['nama_kategori'] ?></p>
                <p class="<?= $menu['status_ketersediaan'] ? 'status-tersedia' : 'status-tidak' ?>">
                    <?= $menu['status_ketersediaan'] ? 'Tersedia' : 'Tidak Tersedia' ?>
                </p>
                <p>Harga: Rp <?= number_format($menu['harga'],0,',','.') ?></p>
                <p><?= $menu['deskripsi'] ?></p>
            </div>

            <div class="card-actions">
                <a href="index.php?action=Menu&edit=<?= $menu['id_menu'] ?>" class="edit-btn">Edit</a>
                <a href="hapus_menu.php?id=<?= $menu['id_menu'] ?>"
                   onclick="return confirm('Hapus menu ini?');"
                   class="delete-btn">Hapus</a>
            </div>

        </div>
        <?php endforeach; ?>
    </div>

</div>
