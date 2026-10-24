# InfinityFree ডিপ্লয়মেন্ট গাইড

ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা — ডাইনামিক ওয়েবসাইট

---

## ধাপ ১: ডেটাবেজ তৈরি (phpMyAdmin)

1. InfinityFree কন্ট্রোল প্যানেল → **MySQL Databases** → নতুন ডেটাবেজ তৈরি করুন। নাম যেমন `if0_XXXXXXX_madrasa`।
2. ডেটাবেজের **MySQL Hostname**, **Username**, **Password** কপি করে রাখুন (`sqlXXX.infinityfree.com` এর মতো হবে)।
3. কন্ট্রোল প্যানেল → **phpMyAdmin** → বাম দিক থেকে নিজের ডেটাবেজ সিলেক্ট করুন।
4. **Import** ট্যাব → প্রথমে `schema.sql`, তারপর `seed.sql` আপলোড করুন (দুটোই [madrasa-db](https://github.com/majorbdal-ai/madrasa-db) রিপোতে)।
5. ৯টি টেবিল তৈরি হয়েছে কিনা দেখে নিন।

## ধাপ ২: ফাইল আপলোড (FTP)

1. কন্ট্রোল প্যানেল → **FTP Accounts** → হোস্ট, ইউজারনেম, পাসওয়ার্ড নিন।
2. FileZilla বা যেকোনো FTP ক্লায়েন্ট দিয়ে সংযোগ করুন → `htdocs/` ফোল্ডারে যান।
3. এই রিপোর **সব ফাইল** `htdocs/`-এ আপলোড করুন — তবে `includes/config.php` **আপলোড করবেন না** (নিচের ধাপে নিজের বানাবেন)।
4. ফোল্ডার স্ট্রাকচার এমন হবে:
   ```
   htdocs/
   ├── index.php
   ├── header.php
   ├── footer.php
   ├── notices.php, teachers.php, gallery.php, contact.php, administration.php
   ├── assets/css/style.css
   ├── includes/  (config.php, db.php, helpers.php)
   └── admin/     (login.php, dashboard.php, logout.php)
   ```

## ধাপ ৩: config.php তৈরি

`includes/` ফোল্ডারে `config.template.php` কপি করে নাম দিন `config.php`, তারপর ভেতরে নিজের ক্রেডেনশিয়াল বসান:

```php
define('DB_HOST', 'sqlXXX.infinityfree.com');  // MySQL Hostname
define('DB_NAME', 'if0_XXXXXXX_madrasa');
define('DB_USER', 'if0_XXXXXXX');
define('DB_PASS', 'আপনার-পাসওয়ার্ড');
```

> এই ফাইল কখনো GitHub-এ কমিট করবেন না।

## ধাপ ৪: অ্যাডমিন লগইন

- ঠিকানা: `https://আপনার-সাইট/admin/login.php`
- ইউজারনেম: `admin` · পাসওয়ার্ড: `admin123`
- **লগইন করার পরপরই** অ্যাডমিন প্যানেল → পাসওয়ার্ড → পাসওয়ার্ড বদলে ফেলুন।

## ধাপ ৫: কনটেন্ট যোগ করা

অ্যাডমিন প্যানেল (`admin/dashboard.php`) থেকে:

- **নোটিশ** — শিরোনাম ও বিবরণ দিয়ে যোগ করুন, পিন করে উপরে রাখতে পারেন
- **শিক্ষক** — নাম, পদবি, বিষয়, মোবাইল
- **গভর্নিং বডি** — সদস্যদের নাম ও পদবি
- **মেসেজ** — যোগাযোগ ফর্মের মেসেজ দেখা ও পড়া হিসেবে চিহ্নিত করা
- **ভর্তি আবেদন** — আবেদনের তালিকা (যখন ভর্তি ফর্ম চালু হবে)

## গ্যালারিতে ছবি যোগ করা

`gallery` টেবিলে সরাসরি phpMyAdmin থেকে রেকর্ড যোগ করতে হবে:

1. ছবি আপলোড করুন `assets/images/` ফোল্ডারে (FTP দিয়ে)।
2. phpMyAdmin → `gallery` টেবিল → Insert → `image_path` = `assets/images/ছবির-নাম.jpg`, `title` = বিবরণ।

## সমস্যা হলে

| সমস্যা | সমাধান |
|---|---|
| সাদা পেজ / ৫০০ এরর | `includes/config.php`-এ ক্রেডেনশিয়াল ঠিক আছে কিনা দেখুন |
| "ডেটাবেজ সংযোগে সমস্যা" | MySQL Hostname `localhost` নয় — InfinityFree-এর দেওয়া হোস্টনেম বসান |
| বাংলা অক্ষর ভাঙা দেখাচ্ছে | ডেটাবেজ character set `utf8mb4` কিনা যাচাই করুন |
| ফাইল আপলোড হচ্ছে না | FTP পোর্ট 21, passive mode চালু করুন |

## নিরাপত্তা

- ডিফল্ট পাসওয়ার্ড (`admin123`) বদলানো বাধ্যতামূলক
- `includes/config.php` কখনো পাবলিক রিপোতে দেবেন না
- নিয়মিত phpMyAdmin থেকে ডেটাবেজ এক্সপোর্ট করে ব্যাকআপ রাখুন
