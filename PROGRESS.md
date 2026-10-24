# Progress Tracking

## সম্পন্ন

- [x] Initial GitHub Repo created and renamed.
- [x] Environment inspected.
- [x] Tracking files initialized.
- [x] Static structure: ৬টি পাবলিক পেজ + হেডার/ফুটার + CSS।
- [x] **ডেটাবেজ স্কিমা** — আলাদা রিপো [madrasa-db](https://github.com/majorbdal-ai/madrasa-db)-তে `schema.sql` (৯ টেবিল) ও `seed.sql`।
- [x] **PDO কানেকশন** — `includes/db.php`, `includes/config.template.php`, `includes/helpers.php`।
- [x] **ডাইনামিক পেজ** — index, teachers, notices, gallery, administration, contact সব ডেটাবেজ থেকে লোড হয়।
- [x] **যোগাযোগ ফর্ম** — ডেটাবেজে সেভ হয় (ভ্যালিডেশন সহ)।
- [x] **অ্যাডমিন প্যানেল** — লগইন, ড্যাশবোর্ড, নোটিশ/শিক্ষক/গভর্নিং বডি/মেসেজ ব্যবস্থাপনা, পাসওয়ার্ড পরিবর্তন।
- [x] **রেসপন্সিভ CSS** — মোবাইল মেনু সহ সম্পূর্ণ নতুন স্টাইল।
- [x] **ডিপ্লয়মেন্ট গাইড** — `DEPLOY.md`।
- [x] লোকাল টেস্ট: MariaDB-তে স্কিমা ইমপোর্ট, সব পেজ ২০০, অ্যাডমিন লগইন/CRUD, যোগাযোগ ফর্ম — সব যাচাইকৃত।

## বাকি (ভবিষ্যতে, ঐচ্ছিক)

- [ ] গ্যালারিতে ছবি আপলোড UI (এখন FTP + phpMyAdmin দিয়ে)
- [ ] অনলাইন ভর্তি ফর্ম (`admissions` টেবিল প্রস্তুত)
- [ ] পরীক্ষার ফলাফল ও মার্কশিট
- [ ] লাইব্রেরি ম্যানেজমেন্ট
- [ ] ভাষা সুইচ (বাংলা/ইংরেজি)
