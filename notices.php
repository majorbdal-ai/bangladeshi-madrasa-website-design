<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
$page_title = 'নোটিশ বোর্ড — ' . get_setting('site_name', 'ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা');
include('header.php');
$notices = [];
try {
    $notices = db()->query('SELECT title, body, file_path, published_at, is_pinned FROM notices WHERE is_published = 1 ORDER BY is_pinned DESC, published_at DESC')->fetchAll();
} catch (Throwable $e) {}
?>
<main class="container">
    <h2>নোটিশ বোর্ড</h2>
    <?php if ($notices): ?>
        <div class="notice-board">
            <?php foreach ($notices as $n): ?>
            <article class="notice-card<?php echo $n['is_pinned'] ? ' pinned' : ''; ?>">
                <h3>
                    <?php if ($n['is_pinned']): ?><span class="pin">📌</span><?php endif; ?>
                    <?php echo e($n['title']); ?>
                </h3>
                <p class="muted">প্রকাশ: <?php echo bn_date($n['published_at']); ?></p>
                <?php if (!empty($n['body'])): ?><p><?php echo nl2br(e($n['body'])); ?></p><?php endif; ?>
                <?php if (!empty($n['file_path'])): ?>
                    <a class="btn" href="<?php echo e($n['file_path']); ?>" download>ডাউনলোড করুন</a>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="muted">এখন কোনো নোটিশ প্রকাশিত হয়নি।</p>
    <?php endif; ?>
</main>
<?php include('footer.php'); ?>
