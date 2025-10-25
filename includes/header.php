<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/functions.php';

$settings = get_settings();
$categories = get_categories();
$states = get_states();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css"
        integrity="sha512-HHsOC+h3najWR7OKiGZtfhFIEzg5VRIPde0kB0bG2QRidTQqf+sbfcxCTB16AcFB93xMjnBIKE29/MjdzXE+qw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo $settings['site_name']; ?></title>
</head>
<body>
    <!-- Top Header Bar -->
    <div class="topHeader">
        <div class="topHeader-left">
            <div class="contact-item">
                <i class="fa-solid fa-envelope"></i>
                <span><?php echo $settings['contact_email']; ?></span>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <span><?php echo $settings['contact_phone']; ?></span>
            </div>
        </div>
        <div class="topHeader-right">
            <a href="<?php echo !empty($settings['instagram_url']) ? $settings['instagram_url'] : 'https://www.instagram.com/news9india_/'; ?>" target="_blank" class="social-icon">
                <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="<?php echo !empty($settings['facebook_url']) ? $settings['facebook_url'] : 'https://facebook.com/N9india'; ?>" target="_blank" class="social-icon">
                <i class="fa-brands fa-facebook-f"></i>
            </a>
            <a href="<?php echo !empty($settings['youtube_url']) ? $settings['youtube_url'] : 'https://www.youtube.com/@N9India'; ?>" target="_blank" class="social-icon">
                <i class="fa-brands fa-youtube"></i>
            </a>
            <a href="<?php echo !empty($settings['snapchat_url']) ? $settings['snapchat_url'] : 'https://www.snapchat.com/add/news9india'; ?>" target="_blank" class="social-icon">
                <i class="fa-brands fa-snapchat"></i>
            </a>
        </div>
    </div>

    <!-- Main Navigation -->
    <div class="header">
        <div class="logo">
            <a href="<?php echo url(); ?>">
                <?php if (!empty($settings['site_logo'])): ?>
                    <img src="<?php echo url($settings['site_logo']); ?>" alt="<?php echo $settings['site_name']; ?>">
                <?php else: ?>
                    <img src="<?php echo url('images/logo.jpg'); ?>" alt="<?php echo $settings['site_name']; ?>">
                <?php endif; ?>
            </a>
        </div>
        <div class="header-right">
            <div class="search-section">
                <form action="<?php echo url('search.php'); ?>" method="GET">
                    <input type="text" name="q" placeholder="Search news..." required>
                    <button type="submit"><i class="fa-solid fa-search"></i></button>
                </form>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo url(); ?>">Home</a></li>
                    <li><a href="<?php echo url('live.php'); ?>">Live</a></li>
                    
                    <!-- Dynamic Categories -->
                    <?php foreach ($categories as $category): ?>
                    <li><a href="<?php echo url('category.php?slug=' . $category['slug']); ?>"><?php echo $category['name']; ?></a></li>
                    <?php endforeach; ?>
                    
                    <!-- States Dropdown -->
                    <li class="dropdown">
                        <a href="#states">States <i class="fa-solid fa-chevron-down"></i></a>
                        <ul class="dropdown-menu states-grid">
                            <?php foreach ($states as $state): ?>
                            <li><a href="<?php echo url('state.php?slug=' . $state['slug']); ?>"><?php echo $state['name']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="bar">
            <i class="open fa-solid fa-bars-staggered"></i>
            <i class="close fa-solid fa-xmark"></i>
        </div>
    </div>
