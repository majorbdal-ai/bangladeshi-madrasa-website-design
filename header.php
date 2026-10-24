<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';

$site_name = get_setting('site_name', 'ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা');
$page_title = $page_title ?? $site_name;
$active_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title><?php echo e($page_title); ?></title>
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="index.php" class="site-brand"><?php echo e($site_name); ?></a>
            <button class="menu-toggle" id="menuToggle" aria-label="মেনু">☰</button>
        </div>
        <nav class="navbar">
            <div class="container">
                <ul class="nav-links" id="navLinks">
                    <li><a href="index.php" class="<?php echo $active_page === 'index.php' ? 'active' : ''; ?>">হোম</a></li>
                    <li><a href="administration.php" class="<?php echo $active_page === 'administration.php' ? 'active' : ''; ?>">প্রশাসন</a></li>
                    <li><a href="teachers.php" class="<?php echo $active_page === 'teachers.php' ? 'active' : ''; ?>">শিক্ষক ও কর্মচারী</a></li>
                    <li><a href="notices.php" class="<?php echo $active_page === 'notices.php' ? 'active' : ''; ?>">নোটিশ</a></li>
                    <li><a href="gallery.php" class="<?php echo $active_page === 'gallery.php' ? 'active' : ''; ?>">গ্যালারি</a></li>
                    <li><a href="contact.php" class="<?php echo $active_page === 'contact.php' ? 'active' : ''; ?>">যোগাযোগ</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <script>
        document.getElementById('menuToggle')?.addEventListener('click', function () {
            document.getElementById('navLinks').classList.toggle('open');
        });
    </script>
