<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

if (!is_logged_in()) {
    redirect('login.php');
}

function require_admin(): void {}

$section = $_GET['section'] ?? 'overview';
$allowed = ['overview', 'notices', 'teachers', 'gallery', 'messages', 'admissions', 'admin_body', 'password'];
if (!in_array($section, $allowed, true)) {
    $section = 'overview';
}

$message = '';
$error = '';

// ---------- অ্যাকশন হ্যান্ডলিং (প্রতিটি টেবিলের জন্য) ----------
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
        $error = 'সেশন মেয়াদ শেষ। আবার চেষ্টা করুন।';
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'add_notice') {
            db()->prepare('INSERT INTO notices (title, body, published_at) VALUES (?, ?, CURDATE())')
                ->execute([trim($_POST['title']), trim($_POST['body'])]);
            $message = 'নোটিশ যোগ হয়েছে।';
        } elseif ($action === 'delete_notice') {
            db()->prepare('DELETE FROM notices WHERE id = ?')->execute([(int)$_POST['id']]);
            $message = 'নোটিশ মুছে ফেলা হয়েছে।';
        } elseif ($action === 'toggle_pin') {
            db()->prepare('UPDATE notices SET is_pinned = 1 - is_pinned WHERE id = ?')->execute([(int)$_POST['id']]);
        } elseif ($action === 'add_teacher') {
            db()->prepare('INSERT INTO teachers (name, designation, subject, mobile) VALUES (?, ?, ?, ?)')
                ->execute([trim($_POST['name']), trim($_POST['designation']), trim($_POST['subject']), trim($_POST['mobile'])]);
            $message = 'শিক্ষক যোগ হয়েছে।';
        } elseif ($action === 'delete_teacher') {
            db()->prepare('DELETE FROM teachers WHERE id = ?')->execute([(int)$_POST['id']]);
            $message = 'শিক্ষক মুছে ফেলা হয়েছে।';
        } elseif ($action === 'add_member') {
            db()->prepare('INSERT INTO admin_body (name, designation, mobile) VALUES (?, ?, ?)')
                ->execute([trim($_POST['name']), trim($_POST['designation']), trim($_POST['mobile'])]);
            $message = 'সদস্য যোগ হয়েছে।';
        } elseif ($action === 'delete_member') {
            db()->prepare('DELETE FROM admin_body WHERE id = ?')->execute([(int)$_POST['id']]);
            $message = 'সদস্য মুছে ফেলা হয়েছে।';
        } elseif ($action === 'delete_message') {
            db()->prepare('DELETE FROM contact_messages WHERE id = ?')->execute([(int)$_POST['id']]);
            $message = 'মেসেজ মুছে ফেলা হয়েছে।';
        } elseif ($action === 'mark_read') {
            db()->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = ?')->execute([(int)$_POST['id']]);
        } elseif ($action === 'change_password') {
            $current = $_POST['current_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            if (strlen($new) < 6) {
                $error = 'নতুন পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
            } else {
                $stmt = db()->prepare('SELECT password_hash FROM admin_users WHERE id = ?');
                $stmt->execute([(int)$_SESSION['admin_id']]);
                $hash = $stmt->fetchColumn();
                if ($hash && password_verify($current, $hash)) {
                    db()->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')
                        ->execute([password_hash($new, PASSWORD_DEFAULT), (int)$_SESSION['admin_id']]);
                    $message = 'পাসওয়ার্ড বদলে গেছে।';
                } else {
                    $error = 'বর্তমান পাসওয়ার্ড ভুল।';
                }
            }
        }
    }
} catch (Throwable $e) {
    $error = 'অ্যাকশন সম্পন্ন হয়নি: ' . $e->getMessage();
}

// ---------- ডেটা লোড ----------
$data = ['notices' => [], 'teachers' => [], 'members' => [], 'messages' => [], 'admissions' => [], 'classes' => []];
try {
    $pdo = db();
    $data['notices'] = $pdo->query('SELECT * FROM notices ORDER BY published_at DESC, id DESC')->fetchAll();
    $data['teachers'] = $pdo->query('SELECT * FROM teachers ORDER BY sort_order, id')->fetchAll();
    $data['members'] = $pdo->query('SELECT * FROM admin_body ORDER BY sort_order, id')->fetchAll();
    $data['messages'] = $pdo->query('SELECT * FROM contact_messages ORDER BY id DESC LIMIT 100')->fetchAll();
    $data['admissions'] = $pdo->query('SELECT a.*, c.class_name FROM admissions a LEFT JOIN classes c ON a.class_id = c.id ORDER BY a.id DESC LIMIT 100')->fetchAll();
    $data['classes'] = $pdo->query('SELECT * FROM classes ORDER BY sort_order, id')->fetchAll();
    $counts = [
        'notices' => $pdo->query('SELECT COUNT(*) FROM notices')->fetchColumn(),
        'teachers' => $pdo->query('SELECT COUNT(*) FROM teachers')->fetchColumn(),
        'messages' => $pdo->query('SELECT COUNT(*) FROM contact_messages WHERE is_read = 0')->fetchColumn(),
        'admissions' => $pdo->query("SELECT COUNT(*) FROM admissions WHERE status = 'pending'")->fetchColumn(),
    ];
} catch (Throwable $e) {
    $counts = [];
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
$bn = fn($s) => str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], (string)$s);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>অ্যাডমিন প্যানেল</title>
    <style>
        .admin-wrap { display: flex; gap: 20px; margin-top: 20px; flex-wrap: wrap; }
        .admin-nav { min-width: 180px; flex: 0 0 180px; }
        .admin-nav ul { list-style: none; padding: 0; margin: 0; }
        .admin-nav a { display: block; padding: 10px 14px; color: #2c3e50; text-decoration: none; border-radius: 4px; }
        .admin-nav a:hover, .admin-nav a.active { background: #1a5928; color: #fff; }
        .admin-content { flex: 1; min-width: 300px; }
        .mini-form { display: grid; gap: 8px; margin-bottom: 20px; }
        .mini-form input, .mini-form textarea { padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; }
        table.data-table td, table.data-table th { padding: 7px 10px; font-size: 0.92em; }
        .danger { color: #c0392b; }
        .inline-form { display: inline; }
        .stat-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; }
        .stat-cards .card { text-align: center; }
        .stat-cards .card strong { font-size: 1.8em; color: #1a5928; display: block; }
    </style>
</head>
<body>
<header class="site-header">
    <div class="container header-inner">
        <a href="dashboard.php" class="site-brand">অ্যাডমিন প্যানেল</a>
        <span><?php echo h($_SESSION['admin_name'] ?? ''); ?></span>
        <a class="btn" href="logout.php">লগআউট</a>
    </div>
</header>
<main class="container admin-wrap">
    <nav class="admin-nav card">
        <ul>
            <li><a href="?section=overview" class="<?php echo $section === 'overview' ? 'active' : ''; ?>">ওভারভিউ</a></li>
            <li><a href="?section=notices" class="<?php echo $section === 'notices' ? 'active' : ''; ?>">নোটিশ</a></li>
            <li><a href="?section=teachers" class="<?php echo $section === 'teachers' ? 'active' : ''; ?>">শিক্ষক</a></li>
            <li><a href="?section=admin_body" class="<?php echo $section === 'admin_body' ? 'active' : ''; ?>">গভর্নিং বডি</a></li>
            <li><a href="?section=messages" class="<?php echo $section === 'messages' ? 'active' : ''; ?>">মেসেজ</a></li>
            <li><a href="?section=admissions" class="<?php echo $section === 'admissions' ? 'active' : ''; ?>">ভর্তি আবেদন</a></li>
            <li><a href="?section=password" class="<?php echo $section === 'password' ? 'active' : ''; ?>">পাসওয়ার্ড</a></li>
        </ul>
    </nav>
    <div class="admin-content">
        <?php if ($message): ?><p class="alert success"><?php echo h($message); ?></p><?php endif; ?>
        <?php if ($error): ?><p class="alert error"><?php echo h($error); ?></p><?php endif; ?>

        <?php if ($section === 'overview'): ?>
            <div class="stat-cards">
                <div class="card"><strong><?php echo $bn($counts['notices'] ?? 0); ?></strong> নোটিশ</div>
                <div class="card"><strong><?php echo $bn($counts['teachers'] ?? 0); ?></strong> শিক্ষক</div>
                <div class="card"><strong><?php echo $bn($counts['messages'] ?? 0); ?></strong> অপঠিত মেসেজ</div>
                <div class="card"><strong><?php echo $bn($counts['admissions'] ?? 0); ?></strong> অপেক্ষমাণ আবেদন</div>
            </div>

        <?php elseif ($section === 'notices'): ?>
            <form method="post" class="card mini-form">
                <input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="action" value="add_notice">
                <input type="text" name="title" placeholder="শিরোনাম" required>
                <textarea name="body" rows="3" placeholder="বিস্তারিত"></textarea>
                <button class="btn" type="submit">নোটিশ যোগ করুন</button>
            </form>
            <table class="data-table">
                <tr><th>শিরোনাম</th><th>তারিখ</th><th>অ্যাকশন</th></tr>
                <?php foreach ($data['notices'] as $n): ?>
                <tr>
                    <td><?php echo h($n['title']); ?><?php echo $n['is_pinned'] ? ' 📌' : ''; ?></td>
                    <td><?php echo h($n['published_at']); ?></td>
                    <td>
                        <form class="inline-form" method="post"><input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                            <input type="hidden" name="action" value="toggle_pin"><input type="hidden" name="id" value="<?php echo (int)$n['id']; ?>">
                            <button class="btn" type="submit">পিন</button></form>
                        <form class="inline-form" method="post" onsubmit="return confirm('নিশ্চিত?');"><input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                            <input type="hidden" name="action" value="delete_notice"><input type="hidden" name="id" value="<?php echo (int)$n['id']; ?>">
                            <button class="btn danger" type="submit">মুছুন</button></form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

        <?php elseif ($section === 'teachers'): ?>
            <form method="post" class="card mini-form">
                <input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="action" value="add_teacher">
                <input type="text" name="name" placeholder="নাম" required>
                <input type="text" name="designation" placeholder="পদবি" required>
                <input type="text" name="subject" placeholder="বিষয়">
                <input type="text" name="mobile" placeholder="মোবাইল">
                <button class="btn" type="submit">শিক্ষক যোগ করুন</button>
            </form>
            <table class="data-table">
                <tr><th>নাম</th><th>পদবি</th><th>বিষয়</th><th>মোবাইল</th><th>অ্যাকশন</th></tr>
                <?php foreach ($data['teachers'] as $t): ?>
                <tr>
                    <td><?php echo h($t['name']); ?></td>
                    <td><?php echo h($t['designation']); ?></td>
                    <td><?php echo h($t['subject']); ?></td>
                    <td><?php echo h($t['mobile']); ?></td>
                    <td>
                        <form class="inline-form" method="post" onsubmit="return confirm('নিশ্চিত?');"><input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                            <input type="hidden" name="action" value="delete_teacher"><input type="hidden" name="id" value="<?php echo (int)$t['id']; ?>">
                            <button class="btn danger" type="submit">মুছুন</button></form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

        <?php elseif ($section === 'admin_body'): ?>
            <form method="post" class="card mini-form">
                <input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="action" value="add_member">
                <input type="text" name="name" placeholder="নাম" required>
                <input type="text" name="designation" placeholder="পদবি" required>
                <input type="text" name="mobile" placeholder="মোবাইল">
                <button class="btn" type="submit">সদস্য যোগ করুন</button>
            </form>
            <table class="data-table">
                <tr><th>নাম</th><th>পদবি</th><th>মোবাইল</th><th>অ্যাকশন</th></tr>
                <?php foreach ($data['members'] as $m): ?>
                <tr>
                    <td><?php echo h($m['name']); ?></td>
                    <td><?php echo h($m['designation']); ?></td>
                    <td><?php echo h($m['mobile']); ?></td>
                    <td>
                        <form class="inline-form" method="post" onsubmit="return confirm('নিশ্চিত?');"><input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                            <input type="hidden" name="action" value="delete_member"><input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>">
                            <button class="btn danger" type="submit">মুছুন</button></form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

        <?php elseif ($section === 'messages'): ?>
            <?php foreach ($data['messages'] as $m): ?>
            <div class="card" style="margin-bottom:12px;">
                <strong><?php echo h($m['name']); ?></strong>
                <?php if ($m['mobile']): ?> · <?php echo h($m['mobile']); ?><?php endif; ?>
                <span class="muted"> — <?php echo h($m['created_at']); ?><?php echo $m['is_read'] ? '' : ' 🔴'; ?></span>
                <p><?php echo nl2br(h($m['message'])); ?></p>
                <form class="inline-form" method="post"><input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                    <input type="hidden" name="action" value="mark_read"><input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>">
                    <button class="btn" type="submit">পড়া হয়েছে</button></form>
                <form class="inline-form" method="post" onsubmit="return confirm('নিশ্চিত?');"><input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                    <input type="hidden" name="action" value="delete_message"><input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>">
                    <button class="btn danger" type="submit">মুছুন</button></form>
            </div>
            <?php endforeach; ?>
            <?php if (!$data['messages']): ?><p class="muted">কোনো মেসেজ নেই।</p><?php endif; ?>

        <?php elseif ($section === 'admissions'): ?>
            <table class="data-table">
                <tr><th>নাম</th><th>পিতা</th><th>জামাত</th><th>মোবাইল</th><th>স্ট্যাটাস</th><th>তারিখ</th></tr>
                <?php foreach ($data['admissions'] as $a): ?>
                <tr>
                    <td><?php echo h($a['student_name']); ?></td>
                    <td><?php echo h($a['father_name']); ?></td>
                    <td><?php echo h($a['class_name']); ?></td>
                    <td><?php echo h($a['mobile']); ?></td>
                    <td><?php echo h($a['status']); ?></td>
                    <td><?php echo h($a['created_at']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php if (!$data['admissions']): ?><p class="muted">কোনো আবেদন নেই।</p><?php endif; ?>

        <?php elseif ($section === 'password'): ?>
            <form method="post" class="card mini-form">
                <input type="hidden" name="csrf" value="<?php echo h($csrf); ?>">
                <input type="hidden" name="action" value="change_password">
                <input type="password" name="current_password" placeholder="বর্তমান পাসওয়ার্ড" required>
                <input type="password" name="new_password" placeholder="নতুন পাসওয়ার্ড (৬+ অক্ষর)" required minlength="6">
                <button class="btn" type="submit">পাসওয়ার্ড বদলান</button>
            </form>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
