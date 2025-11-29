<div class="sidebar">
    <div class="logo">
        <i class="fas fa-circle"></i>
        <span>Manajemen Restoran</span>
    </div>

    <div class="menu-item <?= $action == 'dashboard' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/index.php?action=dashboard">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
    </div>

    <div class="menu-section">Data Master</div>

    <div class="menu-item <?= $action == 'Menu' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/views/Menu.php">
            <i class="fas fa-pizza-slice"></i> <span>Menu</span>
        </a>
    </div>

    <div class="menu-item <?= $action == 'Pesanan' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/index2.php?action=Pesanan">
            <i class="fas fa-shopping-cart"></i> <span>Pesanan</span>
        </a>
    </div>

    <div class="menu-item <?= $action == 'Meja' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/index2.php?action=Meja">
            <i class="fas fa-chair"></i> <span>Meja</span>
        </a>
    </div>

    <div class="menu-item <?= $action == 'Reservasi' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/index2.php?action=Reservasi">
            <i class="fas fa-calendar-check"></i> <span>Reservasi</span>
        </a>
    </div>

    <div class="menu-item <?= $action == 'Pelanggan' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/index2.php?action=Pelanggan">
            <i class="fas fa-users"></i> <span>Pelanggan</span>
        </a>
    </div>

    <div class="menu-item <?= $action == 'Server' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/index2.php?action=Server">
            <i class="fas fa-users"></i> <span>Server</span>
        </a>
    </div>

    <div class="menu-section">Laporan</div>

    <div class="menu-item <?= $action == 'Laporan' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/index2.php?action=Laporan">
            <i class="fas fa-file-alt"></i> <span>Laporan</span>
        </a>
    </div>


    <div class="menu-item <?= $action == 'LaporanShift' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/index2.php?action=LaporanShift">
    <div class="menu-item <?= $action == 'LaporanSift' ? 'active' : '' ?>">
        <a href="/basdat_lanjut/index2.php?action=LaporanSift">
            <i class="fas fa-file-alt"></i> <span>Laporan Shift</span>
        </a>
    </div>
</div>
