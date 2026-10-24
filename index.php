<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
$page_title = 'হোম — ' . get_setting('site_name', 'ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা');
include('header.php');

// সাম্প্রতিক নোটিশ (সর্বোচ্চ ৫টি)
$recent_notices = [];
$teacher_count = 0;
try {
    $recent_notices = db()->query('SELECT title, published_at FROM notices WHERE is_published = 1 ORDER BY is_pinned DESC, published_at DESC LIMIT 5')->fetchAll();
    $teacher_count = (int)db()->query('SELECT COUNT(*) FROM teachers WHERE is_active = 1')->fetchColumn();
} catch (Throwable $e) { /* ডেটাবেজ না থাকলে খালি রাখবে */ }
?>
<main class="container">
    <section class="hero">
        <h1><?php echo e(get_setting('site_name', 'ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা')); ?></h1>
        <p class="tagline"><?php echo e(get_setting('site_tagline', '')); ?></p>
        <p>ইআইআইএন নং: <?php echo e(bn_digits(get_setting('eiin_no', '-'))); ?></p>
    </section>

    <section class="home-grid">
        <div class="card">
            <h2>সাম্প্রতিক নোটিশ</h2>
            <?php if ($recent_notices): ?>
                <ul class="notice-list">
                    <?php foreach ($recent_notices as $n): ?>
                        <li>
                            <a href="notices.php"><?php echo e($n['title']); ?></a>
                            <span class="muted"><?php echo bn_date($n['published_at']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="muted">এখন কোনো নোটিশ নেই।</p>
            <?php endif; ?>
            <a class="btn" href="notices.php">সব নোটিশ</a>
        </div>

        <div class="card">
            <h2>এক নজরে</h2>
            <ul class="stats-list">
                <li><strong><?php echo bn_digits((string)$teacher_count); ?></strong> জন শিক্ষক ও কর্মচারী</li>
                <li><strong>১০</strong> টি জামাত (প্রথম–দশম)</li>
            </ul>
            <a class="btn" href="teachers.php">শিক্ষক তালিকা</a>
        </div>
    </section>
</main>
<?php include('footer.php'); ?>
