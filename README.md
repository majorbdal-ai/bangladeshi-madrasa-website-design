# ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা — ওয়েবসাইট

PHP + MySQL ভিত্তিক ডাইনামিক ওয়েবসাইট। InfinityFree শেয়ারড হোস্টিংয়ের জন্য অপ্টিমাইজড (কোনো ফ্রেমওয়ার্ক বা composer নির্ভরতা নেই)।

## ফিচার

- ডাইনামিক হোমপেজ (সাম্প্রতিক নোটিশ, শিক্ষক সংখ্যা)
- শিক্ষক ও কর্মচারী তালিকা
- নোটিশ বোর্ড (পিন করা নোটিশ সহ)
- ছবি গ্যালারি
- প্রশাসন ও গভর্নিং বডি
- যোগাযোগ ফর্ম (ডেটাবেজে সেভ হয়)
- অ্যাডমিন প্যানেল — লগইন, নোটিশ/শিক্ষক/গভর্নিং বডি/মেসেজ ব্যবস্থাপনা, পাসওয়ার্ড পরিবর্তন
- মোবাইল রেসপন্সিভ, সম্পূর্ণ বাংলা ইন্টারফেস

## ডেটাবেজ

স্কিমা ও সিড ডেটা আলাদা রিপোতে: **[madrasa-db](https://github.com/majorbdal-ai/madrasa-db)**

## লোকাল রান

```bash
# ১. ডেটাবেজ তৈরি
mysql -e "CREATE DATABASE madrasa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql madrasa_db < path/to/madrasa-db/schema.sql
mysql madrasa_db < path/to/madrasa-db/seed.sql

# ২. কনফিগ
cp includes/config.template.php includes/config.php
# DB_HOST/DB_NAME/DB_USER/DB_PASS বসান

# ৩. সার্ভার
php -S 127.0.0.1:8080
```

প্রয়োজন: PHP 8.x (`pdo_mysql` এক্সটেনশন), MySQL/MariaDB।

## অ্যাডমিন

`/admin/login.php` — ডিফল্ট `admin` / `admin123` (প্রথম লগইনে বদলান)

## ডিপ্লয়

বিস্তারিত: **[DEPLOY.md](DEPLOY.md)**

## কাঠামো

```
assets/css/style.css      স্টাইলশিট
includes/config.php       ডেটাবেজ ক্রেডেনশিয়াল (git-এ নেই)
includes/db.php           PDO কানেকশন
includes/helpers.php      হেল্পার ফাংশন (escape, বাংলা তারিখ/সংখ্যা, session)
header.php / footer.php   কমন লেআউট
index.php                 হোম
teachers.php              শিক্ষক তালিকা
notices.php               নোটিশ বোর্ড
gallery.php               গ্যালারি
administration.php        গভর্নিং বডি
contact.php               যোগাযোগ ফর্ম
admin/login.php           অ্যাডমিন লগইন
admin/dashboard.php       অ্যাডমিন প্যানেল
```
