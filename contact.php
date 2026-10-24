<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
$page_title = 'যোগাযোগ — ' . get_setting('site_name', 'ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা');
include('header.php');

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || $message === '') {
        $error = 'নাম এবং মেসেজ অবশ্যই দিতে হবে।';
    } elseif (mb_strlen($name) > 100 || mb_strlen($message) > 2000) {
        $error = 'নাম বা মেসেজ খুব বড় হয়ে গেছে।';
    } else {
        try {
            $stmt = db()->prepare('INSERT INTO contact_messages (name, mobile, message) VALUES (?, ?, ?)');
            $stmt->execute([$name, $mobile, $message]);
            $success = true;
        } catch (Throwable $e) {
            $error = 'মেসেজ পাঠানো যায়নি। অনুগ্রহ করে পরে আবার চেষ্টা করুন।';
        }
    }
}
?>
<main class="container">
    <h2>যোগাযোগ</h2>
    <div class="contact-info card">
        <p>মোবাইল: <?php echo e(bn_digits(get_setting('contact_mobile', '-'))); ?></p>
        <p>ইমেইল: <?php echo e(get_setting('contact_email', '-')); ?></p>
        <p>ঠিকানা: <?php echo e(get_setting('address', '-')); ?></p>
    </div>

    <?php if ($success): ?>
        <p class="alert success">আপনার মেসেজ পাঠানো হয়েছে। ধন্যবাদ!</p>
    <?php elseif ($error): ?>
        <p class="alert error"><?php echo e($error); ?></p>
    <?php endif; ?>

    <form method="post" class="contact-form card">
        <label>আপনার নাম *
            <input type="text" name="name" maxlength="100" required value="<?php echo e($_POST['name'] ?? ''); ?>">
        </label>
        <label>মোবাইল
            <input type="tel" name="mobile" maxlength="20" value="<?php echo e($_POST['mobile'] ?? ''); ?>">
        </label>
        <label>মেসেজ *
            <textarea name="message" maxlength="2000" required rows="5"><?php echo e($_POST['message'] ?? ''); ?></textarea>
        </label>
        <button type="submit" class="btn">পাঠান</button>
    </form>
</main>
<?php include('footer.php'); ?>
