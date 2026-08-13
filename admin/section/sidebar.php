<?php $current = basename($_SERVER['PHP_SELF']); ?>

<div class="sidebar">
    <a href="dashboard.php" class="sidebar-brand">
        <i class="fa-solid fa-mobile-button"></i>
        <span>SellMyPhone</span>
    </a>

    <ul class="sidebar-nav">
        <li><a href="dashboard.php" class="<?php echo $current == 'dashboard.php' ? 'active' : ''; ?>"><i class="fa-solid fa-house"></i> Dashboard</a></li>
        <li><a href="leads.php" class="<?php echo $current == 'leads.php' ? 'active' : ''; ?>"><i class="fa-solid fa-list"></i>Leads</a></li>
        <li><a href="pricing.php" class="<?php echo $current == 'pricing.php' ? 'active' : ''; ?>"><i class="fa-solid fa-tag"></i> Pricing</a></li>
        <li><a href="models.php" class="<?php echo $current == 'models.php' ? 'active' : ''; ?>"><i class="fa-solid fa-mobile-screen"></i> Models</a></li>
        <li><a href="admin-users.php" class="<?php echo $current == 'admin-users.php' ? 'active' : ''; ?>"><i class="fa-solid fa-user-shield"></i> Admin Users</a></li>
    </ul>

    <a href="logout.php" class="sidebar-logout">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
    </a>
</div>