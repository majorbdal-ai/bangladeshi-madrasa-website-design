<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
$page_title = 'গ্যালারি — ' . get_setting('site_name', 'ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা');
include('header.php');
$photos = [];
try {
    $photos = db()->query('SELECT title, image_path, category FROM gallery WHERE is_published = 1 ORDER BY sort_order, id')->fetchAll();
} catch (Throwable $e) {}
?>
<main class="container">
    <h2>ছবি গ্যালারি</h2>
    <?php if ($photos): ?>
        <div class="gallery-grid">
            <?php foreach ($photos as $p): ?>
            <figure class="gallery-item">
                <img src="<?php echo e($p['image_path']); ?>" alt="<?php echo e($p['title'] ?: 'মাদ্রাসার ছবি'); ?>" loading="lazy">
                <?php if (!empty($p['title'])): ?><figcaption><?php echo e($p['title']); ?></figcaption><?php endif; ?>
            </figure>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="muted">গ্যালারিতে এখনো কোনো ছবি যোগ করা হয়নি।</p>
    <?php endif; ?>
</main>
<?php include('footer.php'); ?>
