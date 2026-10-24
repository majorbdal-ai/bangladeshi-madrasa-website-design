<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    try {
        $stmt = db()->prepare('SELECT id, password_hash, full_name FROM admin_users WHERE username = ? AND is_active = 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = (int)$user['id'];
            $_SESSION['admin_name'] = $user['full_name'] ?: $username;
            redirect('dashboard.php');
        } else {
            $error = 'ভুল ইউজারনেম বা পাসওয়ার্ড।';
        }
    } catch (Throwable $e) {
        $error = 'ডেটাবেজ সংযোগে সমস্যা। কনফিগারেশন চেক করুন।';
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>অ্যাডমিন লগইন</title>
</head>
<body class="login-page">
<main class="container login-box">
    <h2>অ্যাডমিন প্যানেল লগইন</h2>
    <?php if ($error): ?><p class="alert error"><?php echo e($error); ?></p><?php endif; ?>
    <form method="post" class="card contact-form">
        <label>ইউজারনেম
            <input type="text" name="username" required autofocus>
        </label>
        <label>পাসওয়ার্ড
            <input type="password" name="password" required>
        </label>
        <button type="submit" class="btn">লগইন</button>
    </form>
    <p class="muted"><a href="../index.php" style="color:#1a5928;">← ওয়েবসাইটে ফিরে যান</a></p>
</main>
</body>
</html>
