<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background: #232628;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" style="font-size:2rem; color:#ffe082;" href="admin.php">Brewtopia Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAdmin">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? ' active' : '' ?>" href="admin.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'orders.php' ? ' active' : '' ?>" href="orders.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'inventory.php' ? ' active' : '' ?>" href="inventory.php">Inventory</a></li>
                <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'manage_menu.php' ? ' active' : '' ?>" href="manage_menu.php">Manage Menu</a></li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>