<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$current_user = get_current_admin();
$settings = get_settings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-panel">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-newspaper"></i>
            <h2>News CMS</h2>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="articles.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'articles.php' || basename($_SERVER['PHP_SELF']) == 'article-form.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-newspaper"></i>
                <span>Articles</span>
            </a>
            
            <a href="categories.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-folder"></i>
                <span>Categories</span>
            </a>
            
            <a href="states.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'states.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-map-marker-alt"></i>
                <span>States</span>
            </a>
            
            <a href="live-settings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'live-settings.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-video"></i>
                <span>Live Settings</span>
            </a>
            
            <a href="settings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-cog"></i>
                <span>Settings</span>
            </a>
            
            <div class="nav-divider"></div>
            
            <a href="<?php echo url(); ?>" target="_blank" class="nav-item">
                <i class="fa-solid fa-external-link-alt"></i>
                <span>View Website</span>
            </a>
            
            <a href="logout.php" class="nav-item">
                <i class="fa-solid fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <i class="fa-solid fa-user-circle"></i>
                <div>
                    <strong><?php echo htmlspecialchars($current_user['username']); ?></strong>
                    <small><?php echo htmlspecialchars($current_user['email']); ?></small>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="admin-main">
        <header class="admin-header">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <div class="header-title">
                <h1><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?></h1>
            </div>
            
            <div class="header-actions">
                <a href="<?php echo url(); ?>" target="_blank" class="btn btn-sm btn-outline">
                    <i class="fa-solid fa-eye"></i> View Site
                </a>
            </div>
        </header>
        
        <div class="admin-content">
