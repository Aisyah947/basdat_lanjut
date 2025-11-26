<?php
$action = $_GET['action'] ?? 'dashboard';

include "views/layout/header.php";
include "views/layout/sidebar.php";
include "views/layout/topbar.php"; // kalau ada

echo '<div class="main-content"><div class="content">';

if ($action == 'dashboard') {
    include "views/dashboard.php";
}

echo '</div></div>';

include "views/layout/footer.php";
