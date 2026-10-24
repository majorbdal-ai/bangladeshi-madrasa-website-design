<?php
/**
 * db.php — PDO কানেকশন হেল্পার (utf8mb4, এক্সেপশন মোড)
 * ব্যবহার: $pdo = db();
 */
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        // সংযোগ ব্যর্থ হলে PDOException ছোড়া হয়; কলিং কোড try/catch দিয়ে হ্যান্ডল করে,
        // ফলে ডেটাবেজ ডাউন থাকলেও পেজ ভেঙে পড়ে না (ফাঁকা/ফলব্যাক কনটেন্ট দেখায়)।
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}

/** সেটিংস টেবিল থেকে একটি ভ্যালু আনা */
function get_setting(string $key, string $default = ''): string {
    try {
        $stmt = db()->prepare('SELECT setting_value FROM settings WHERE setting_key = ?');
        $stmt->execute([$key]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : $default;
    } catch (Throwable $e) {
        return $default;
    }
}
