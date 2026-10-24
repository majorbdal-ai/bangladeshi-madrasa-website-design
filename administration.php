<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
$page_title = 'প্রশাসন ও গভর্নিং বডি — ' . get_setting('site_name', 'ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা');
include('header.php');
$members = [];
try {
    $members = db()->query('SELECT name, designation, mobile FROM admin_body WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
} catch (Throwable $e) {}
?>
<main class="container">
    <h2>প্রশাসন ও গভর্নিং বডি</h2>
    <?php if ($members): ?>
        <table class="data-table">
            <thead>
                <tr><th>নাম</th><th>পদবি</th><th>মোবাইল</th></tr>
            </thead>
            <tbody>
                <?php foreach ($members as $m): ?>
                <tr>
                    <td><?php echo e($m['name']); ?></td>
                    <td><?php echo e($m['designation']); ?></td>
                    <td><?php echo e(bn_digits($m['mobile'] ?: '-')); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="muted">পরিচালনা পর্ষদের তালিকা শীঘ্রই আপডেট করা হবে।</p>
    <?php endif; ?>
</main>
<?php include('footer.php'); ?>
