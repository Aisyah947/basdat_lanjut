<?php
include_once '../models/RestoranModel.php';
include_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();
$model = new RestoranModel($conn);

// Ambil semua kategori
try {
    $stmt = $conn->prepare("SELECT * FROM kategori");
    $stmt->execute();
    $kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Jika tabel kategori tidak ada atau terjadi error, gunakan array kosong
    $kategori = [];
}

// Cek apakah ada filter kategori dari query string
$id_kategori = $_GET['kategori'] ?? null;

$menuData = $id_kategori ? $model->getMenuByKategori($id_kategori) : $model->getAllMenu();
 
?>

<?php include 'header.php'; ?>

<!-- CSS khusus halaman menu, bisa juga pindahkan ke style.css -->
<style>
h2 { text-align:center; margin-bottom:20px; }
.action-buttons { text-align:center; margin-bottom:20px; }
.btn { padding:10px 15px; text-decoration:none; color:white; border-radius:5px; margin-right:5px; transition:0.3s; display:inline-block; margin-bottom:5px; }
.btn-laporan { background:#007bff; } .btn-laporan:hover { background:#0056b3; }
.btn-tambah { background:#28a745; } .btn-tambah:hover { background:#1e7e34; }
.btn-kategori { background:#6c757d; } .btn-kategori:hover { background:#5a6268; }

.grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap:20px; }
.card { background:white; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1); transition:transform 0.3s; display:flex; flex-direction:column; }
.card:hover { transform:translateY(-5px); }
.card img { width:100%; height:180px; object-fit:cover; }
.no-photo { height:180px; line-height:180px; text-align:center; color:#aaa; background:#f0f0f0; }
.card-content { padding:15px; flex-grow:1; }
.card-content h3 { margin:0 0 10px 0; font-size:18px; }
.card-content p { margin:0 0 8px 0; color:#555; font-size:14px; }
.status-tersedia { color:green; font-weight:bold; }
.status-tidak { color:red; font-weight:bold; }

.card-actions { padding:10px 15px; text-align:center; }
.card-actions a { padding:5px 10px; text-decoration:none; border-radius:3px; margin:0 3px; display:inline-block; font-size:14px; }
.edit-btn { background:#ffc107; color:white; }
.edit-btn:hover { background:#e0a800; }
.delete-btn { background:#dc3545; color:white; }
.delete-btn:hover { background:#a71d2a; }
</style>

<h2>Daftar Menu Restoran</h2>

<!-- Tombol laporan & tambah -->
<div class="action-buttons">
    <a href="laporan_menu.php" class="btn btn-laporan">Laporan Menu</a>
    <a href="tambah_menu.php" class="btn btn-tambah">Tambah Menu</a>
</div>

<!-- Filter Kategori -->
<div class="action-buttons">
    <a href="menu.php" class="btn btn-kategori">Semua Kategori</a>
    <?php foreach($kategori as $kat): ?>
        <a href="menu.php?kategori=<?= $kat['id_kategori'] ?>" class="btn btn-kategori">
            <?= $kat['nama_kategori'] ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- Grid Menu -->
<div class="grid">
    <?php foreach ($menuData as $menu): ?>
    <div class="card">
        <?php if(!empty($menu['foto_menu'])): ?>
            <img src="../uploads/<?= $menu['foto_menu'] ?>" alt="<?= $menu['nama_menu'] ?>">
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

        <!-- Tombol Edit & Hapus -->
        <div class="card-actions">
            <a href="edit_menu.php?id=<?= $menu['id_menu'] ?>" class="edit-btn">Edit</a>
            <a href="hapus_menu.php?id=<?= $menu['id_menu'] ?>" onclick="return confirm('Yakin ingin menghapus menu ini?');" class="delete-btn">Hapus</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>
