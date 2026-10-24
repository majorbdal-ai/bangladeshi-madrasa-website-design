<?php
/**
 * helpers.php — কমন হেল্পার ফাংশন
 */
session_start();

/* কিছু শেয়ারড হোস্টিংয়ে mbstring থাকে না — নিরাপদ ফলব্যাক */
if (!function_exists('mb_strlen')) {
    function mb_strlen(string $s, ?string $enc = null): int {
        return strlen(preg_replace('/[\x80-\xBF]/', '', $s) ?? $s);
    }
}

function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function bn_digits(string $s): string {
    return str_replace(
        ['0','1','2','3','4','5','6','7','8','9'],
        ['০','১','২','৩','৪','৫','৬','৭','৮','৯'],
        $s
    );
}

/** তারিখ → বাংলা ফরম্যাট (যেমন ২৩ অক্টোবর ২০২৬) */
function bn_date(string $date): string {
    $months = [1=>'জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'];
    $ts = strtotime($date);
    if ($ts === false) return e($date);
    return bn_digits(date('j', $ts)) . ' ' . $months[(int)date('n', $ts)] . ' ' . bn_digits(date('Y', $ts));
}

function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}

function is_logged_in(): bool {
    return !empty($_SESSION['admin_id']);
}
