<?php
// basic configuration
$institution_name = "ধনুয়া কাচারী পাড়া আহাম্মদীয়া দাখিল মাদ্রাসা";
$eiin = "109409";
$address = "ধনুয়া কাচারীপাড়া, শ্রীপুর, গাজীপুর";
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $institution_name; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="top-bar">
            <div class="container">
                <div class="quran-ayah">
                    "পড় তোমার প্রভুর নামে যিনি সৃষ্টি করেছেন" (সূরা আলাক, আয়াত: ১)
                </div>
            </div>
        </div>
        <div class="main-header">
            <div class="container header-content">
                <div class="logo-area">
                    <h1><?php echo $institution_name; ?></h1>
                    <p><?php echo $address; ?> | EIIN: <?php echo $eiin; ?></p>
                </div>
            </div>
        </div>
        <nav class="navbar">
            <div class="container">
                <ul class="nav-links">
                    <li><a href="index.php">হোম</a></li>
                    <li><a href="#">আমাদের সম্পর্কে</a></li>
                    <li><a href="#">প্রশাসন</a></li>
                    <li><a href="#">শিক্ষক ও কর্মচারী</a></li>
                    <li><a href="#">ভর্তি</a></li>
                    <li><a href="#">রেজাল্ট</a></li>
                    <li><a href="#">যোগাযোগ</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero-slider">
            <div class="container">
                <div class="demo-slider">
                    <h2>স্লাইডার এরিয়া (DEMO)</h2>
                    <p>মাদরাসার সুন্দর দৃশ্য এখানে প্রদর্শিত হবে।</p>
                </div>
            </div>
        </section>

        <section class="notices">
            <div class="container">
                <div class="notice-ticker">
                    <strong>নোটিশ:</strong> <marquee>মাদরাসার সকল ছাত্র-ছাত্রীদের জানানো যাচ্ছে যে...</marquee>
                </div>
            </div>
        </section>

        <section class="speeches">
            <div class="container flex-row">
                <div class="speech-card">
                    <h3>সুপারভাইজারের বাণী</h3>
                    <div class="demo-photo">ছবি (DEMO)</div>
                    <p>"শিক্ষাই জাতির মেরুদণ্ড..."</p>
                </div>
                <div class="speech-card">
                    <h3>সভাপতির বাণী</h3>
                    <div class="demo-photo">ছবি (DEMO)</div>
                    <p>"আমাদের মাদরাসা একটি আদর্শ বিদ্যাপীঠ..."</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $institution_name; ?>. সর্বস্বত্ব সংরক্ষিত।</p>
        </div>
    </footer>
</body>
</html>
