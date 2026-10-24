<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
$page_title = 'শিক্ষক ও কর্মচারী — ' . get_setting('site_name', 'ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা');
include('header.php');
$teachers = [];
try {
    $teachers = db()->query('SELECT name, designation, subject, mobile FROM teachers WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
} catch (Throwable $e) {}
?>
<main class="container">
    <h2>শিক্ষক ও কর্মচারী</h2>
    <?php if ($teachers): ?>
        <table class="data-table">
            <thead>
                <tr><th>নাম</th><th>পদবি</th><th>বিষয়</th><th>মোবাইল</th></tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $t): ?>
                <tr>
                    <td><?php echo e($t['name']); ?></td>
                    <td><?php echo e($t['designation']); ?></td>
                    <td><?php echo e($t['subject'] ?: '-'); ?></td>
                    <td><?php echo e(bn_digits($t['mobile'] ?: '-')); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="muted">শিক্ষক তালিকা শীঘ্রই আপডেট করা হবে।</p>
    <?php endif; ?>
</main>
<?php include('footer.php'); ?>
