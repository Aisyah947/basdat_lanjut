<div class="sidebar">
    <div class="logo">
        <i class="fas fa-circle"></i>
        <span>Manajemen Restoran</span>
    </div>

    <div class="menu-item <?= $action == 'dashboard' ? 'active' : '' ?>">
        <a href="index.php?action=dashboard">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
    </div>

    <div class="menu-section">Data Master</div>
    <div class="menu-item <?= $action == 'Menu' ? 'active' : '' ?>">
    <a href="../Menu.php">
        <i class="fas fa-pizza-slice"></i> <span>Menu</span>
    </a>
</div>


    <div class="menu-item <?= $action == 'Pesanan' ? 'active' : '' ?>">
        <a href="index.php?action=Pesanan">
            <i class="fas fa-shopping-cart"></i> <span>Pesanan</span>
        </a>
    </div>

    <div class="menu-item <?= $action == 'Meja' ? 'active' : '' ?>">
        <a href="index.php?action=Meja">
            <i class="fas fa-chair"></i> <span>Meja</span>
        </a>
    </div>

    <div class="menu-item <?= $action == 'Reservasi' ? 'active' : '' ?>">
        <a href="index.php?action=Reservasi">
            <i class="fas fa-calendar-check"></i> <span>Reservasi</span>
        </a>
    </div>

    <div class="menu-section">Laporan</div>

    <div class="menu-item <?= $action == 'Laporan' ? 'active' : '' ?>">
        <a href="index.php?action=Laporan">
            <i class="fas fa-file-alt"></i> <span>Laporan</span>
        </a>
    </div>
</div>
