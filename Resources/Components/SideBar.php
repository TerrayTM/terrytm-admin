<?php

$page_name = basename($_SERVER['PHP_SELF']);

?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <i class="fa fa-star"></i>
        <div class="sidebar-brand-text mx-3">API Terry<sup>TM</sup></div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item <?php echo($page_name === "dashboard.php" ? "active" : ""); ?>">
        <a class="nav-link" href="/dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Manage
    </div>
    <li class="nav-item <?php echo($page_name === "analytics.php" ? "active" : ""); ?>">
        <a class="nav-link" href="/analytics.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Analytics</span>
        </a>
    </li>
    <li class="nav-item <?php echo($page_name === "projects.php" ? "active" : ""); ?>">
        <a class="nav-link" href="/projects.php">
            <i class="fas fa-fw fa-project-diagram"></i>
            <span>Projects</span>
        </a>
    </li>
    <li class="nav-item <?php echo($page_name === "blog.php" ? "active" : ""); ?>">
        <a class="nav-link" href="/blog.php">
            <i class="fas fa-fw fa-blog"></i>
            <span>Blog</span>
        </a>
    </li>
    <li class="nav-item <?php echo($page_name === "images.php" ? "active" : ""); ?>">
        <a class="nav-link" href="/images.php">
            <i class="fas fa-fw fa-image"></i>
            <span>Images</span>
        </a>
    </li>
    <li class="nav-item <?php echo($page_name === "servers.php" ? "active" : ""); ?>">
        <a class="nav-link" href="/servers.php">
            <i class="fas fa-fw fa-server"></i>
            <span>Servers</span>
        </a>
    </li>
    <li class="nav-item <?php echo($page_name === "files.php" ? "active" : ""); ?>">
        <a class="nav-link" href="/files.php">
            <i class="fas fa-fw fa-file-word"></i>
            <span>Files</span>
        </a>
    </li>
    <li class="nav-item <?php echo($page_name === "errors.php" ? "active" : ""); ?>">
        <a class="nav-link" href="/errors.php">
            <i class="fas fa-fw fa-bomb"></i>
            <span>Errors</span>
        </a>
    </li>
    <li class="nav-item <?php echo($page_name === "ssl.php" ? "active" : ""); ?>">
        <a class="nav-link" href="/ssl.php">
            <i class="fas fa-fw fa-lock"></i>
            <span>SSL</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Subdomains
    </div>
    <li class="nav-item">
        <a class="nav-link" target="_blank" href="https://remotely.terrytm.com">
            <i class="fas fa-fw fa-rss"></i>
            <span>Remotely</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" target="_blank" href="https://myremindlist.terrytm.com">
            <i class="fas fa-fw fa-stopwatch"></i>
            <span>MyRemindList</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" target="_blank" href="https://api.terrytm.com">
            <i class="fas fa-fw fa-user-shield"></i>
            <span>Admin Panel</span>
        </a>
    </li>
</ul>