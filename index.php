<?php
// Function to get user preferences from cookies
function getUserPreferences() {
    $preferences = [];
    if(isset($_COOKIE['user_timezone'])) {
        $preferences['timezone'] = $_COOKIE['user_timezone'];
    }
    if(isset($_COOKIE['user_language'])) {
        $preferences['language'] = $_COOKIE['user_language'];
    }
    if(isset($_COOKIE['weekend_days'])) { // Retrieve weekend days from cookies
        $preferences['weekend_days'] = $_COOKIE['weekend_days'];
    }
    return $preferences;
}

// Function to set user preferences as cookies
function setUserPreferences($timezone, $language, $weekendDays) {
    setcookie('user_timezone', $timezone, time() + (86400 * 30), "/"); // 30 days expiration
    setcookie('user_language', $language, time() + (86400 * 30), "/"); // 30 days expiration
    setcookie('weekend_days', $weekendDays, time() + (86400 * 30), "/"); // Save weekend days to cookies
}

// Set user preferences if available, otherwise set defaults
$userPreferences = getUserPreferences();
$userTimezone = isset($userPreferences['timezone']) ? $userPreferences['timezone'] : 'Asia/Tokyo';
$userLanguage = isset($userPreferences['language']) ? $userPreferences['language'] : 'en';

// Set the timezone based on user preference
date_default_timezone_set($userTimezone);

$currentDateTime = new DateTime(null, new DateTimeZone($userTimezone));
$currentDateTimeFormatted = $currentDateTime->format('d/m/Y h:i:s A'); // Adjusted format

if ($currentDateTime === false) {
    // Handle error, perhaps log it or display a user-friendly message
    die("Error occurred while setting date and timezone.");
}

// Define weekend days based on user preferences (default: Saturday and Sunday)
$weekendDays = ['Saturday', 'Sunday']; // Default weekend
if(isset($userPreferences['weekend_days'])) {
    $weekendDays = explode(',', $userPreferences['weekend_days']);
}

$daysLeft = 0;
$today = date('l');
if(in_array($today, $weekendDays)) {
    $daysLeft = count($weekendDays); // If today is a weekend day, no days left till weekend
} else {
    foreach($weekendDays as $day) {
        $diff = (new DateTime($day))->diff($currentDateTime)->days;
        if($diff >= 0) {
            $daysLeft = $diff;
            break;
        }
    }
}

// Define motivational quotes for different languages
$motivationalQuotes = [
    'en' => [
        'Monday' => 'It’s a new day, a new week. Stay motivated!',
        'Tuesday' => 'Stay focused, stay positive, stay strong!',
        'Wednesday' => 'Success is not built on success. It’s built on failure. It’s built on frustration. Sometimes it’s built on catastrophe.',
        'Thursday' => 'Keep pushing, keep hustling. Success is just around the corner!',
        'Friday' => 'Finish strong! The weekend is almost here!',
        'Saturday' => 'Enjoy your weekend!',
        'Sunday' => 'Rest, recharge, and get ready for the week ahead!'
    ],
    'ja' => [
        'Monday' => '新しい一日、新しい週です。モチベーションを保ってください！',
        'Tuesday' => '集中し、前向きで、強くあり続けてください！',
        'Wednesday' => '成功は成功に基づいて構築されるのではない。失敗に基づいて構築される。それは失望に基づいて構築されることもあります。時には災害に基づいて構築されることもあります。',
        'Thursday' => 'プッシュし続け、ハッスルし続けてください。成功はすぐそこにあります！',
        'Friday' => '最後まで頑張ろう！週末はもうすぐそこです！',
        'Saturday' => '週末を楽しんでください！',
        'Sunday' => '休息し、充電し、今週に備えてください！'
    ]
];

// Get motivational quote based on user language
$dayOfWeek = date('l');
$motivationalQuote = isset($motivationalQuotes[$userLanguage][$dayOfWeek]) ? $motivationalQuotes[$userLanguage][$dayOfWeek] : '';

// Set language direction for CSS based on user language
$languageDirection = ($userLanguage == 'ar') ? 'rtl' : 'ltr';
?>

<!DOCTYPE html>
<html lang="<?= $userLanguage ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YasumiDoko - Track Days Till Weekend | Motivational Quotes</title>
    <meta name="description" content="Stay motivated and track the days left till the weekend with YasumiDoko. Choose your timezone, language, and weekend days preferences.">
    <meta name="keywords" content="Yasumi Doko, YasumiDoko, track days till weekend, motivational quotes, user preferences, timezone, language, weekend days, weekend countdown">
    <!-- Add link tag for favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="imagecropped.png">
    <!-- Your existing styles and scripts -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Welcome to Yasumi Doko!</h1>
            <p class="lead">Stay motivated and track the days left till the weekend.</p>
        </div>
        <div class="card text-center">
            <div class="card-header">
                Current Date, Time and Weather
            </div>
            <div class="card-body">
                <div id="ww_94dfb078cfb" v='1.3' loc='auto' a='{"t":"responsive","lang":"en","sl_lpl":1,"ids":[],"font":"Arial","sl_ics":"one_a","sl_sot":"celsius","cl_bkg":"#FFFFFF00","cl_font":"rgba(255,255,255,1)","cl_cloud":"#d4d4d4","cl_persp":"#2196F3","cl_sun":"#FFC107","cl_moon":"#FFC107","cl_thund":"#FF5722","cl_odd":"#00000000"}'>More forecasts: <a href="https://oneweather.org/london/30_days/" id="ww_94dfb078cfb_u" target="_blank">London weather forecast</a></div><script async src="https://app2.weatherwidget.org/js/?id=ww_94dfb078cfb"></script>
                <h5 class="card-title" id="currentDateTime"><?= $currentDateTimeFormatted ?></h5>
                <h2 class="card-text">Days Left Till Weekend: <?= $daysLeft ?></h2>
            </div>
            <div class="card-footer text-muted" id="motivationalQuote"><?= $motivationalQuote ?> <span id="emojiPlaceholder"></span></div>
        </div>
        <div class="card text-center">
            <div class="card-header">
                User Preferences
            </div>
            <div class="card-body">
                <label for="timezone">Select Timezone:</label>
                <select id="timezone">
                    <?php
                    // Get list of timezones
                    $timezones = DateTimeZone::listIdentifiers();
                    foreach ($timezones as $tz) {
                        $selected = ($tz === $userTimezone) ? 'selected' : '';
                        echo "<option value=\"$tz\" $selected>$tz</option>";
                    }
                    ?>
                </select><br><br>
                <label for="language">Select Language:</label>
                <select id="language">
                    <option value="en" <?= ($userLanguage === 'en') ? 'selected' : '' ?>>English</option>
                    <option value="ja" <?= ($userLanguage === 'ja') ? 'selected' : '' ?>>Japanese</option>
                    <!-- Add more languages here -->
                </select><br><br>
                <div class="card-body">
                    <label>Select Weekend Days:</label><br>
                    <input type="checkbox" id="Monday" <?= (in_array('Monday', $weekendDays)) ? 'checked' : '' ?>><label for="Monday">Monday</label><br>
                    <input type="checkbox" id="Tuesday" <?= (in_array('Tuesday', $weekendDays)) ? 'checked' : '' ?>><label for="Tuesday">Tuesday</label><br>
                    <input type="checkbox" id="Wednesday" <?= (in_array('Wednesday', $weekendDays)) ? 'checked' : '' ?>><label for="Wednesday">Wednesday</label><br>
                    <input type="checkbox" id="Thursday" <?= (in_array('Thursday', $weekendDays)) ? 'checked' : '' ?>><label for="Thursday">Thursday</label><br>
                    <input type="checkbox" id="Friday" <?= (in_array('Friday', $weekendDays)) ? 'checked' : '' ?>><label for="Friday">Friday</label><br>
                    <input type="checkbox" id="Saturday" <?= (in_array('Saturday', $weekendDays)) ? 'checked' : '' ?>><label for="Saturday">Saturday</label><br>
                    <input type="checkbox" id="Sunday" <?= (in_array('Sunday', $weekendDays)) ? 'checked' : '' ?>><label for="Sunday">Sunday</label><br><br>
                    <button onclick="savePreferences()">Save Preferences</button>
                </div>
            </div>
        </div>
    </div>
    <footer>
        &copy; 2024 <a href="https://github.com/SkRagibIshrakAbid">Sk Ragib Ishrak Abid</a>. All rights reserved.
    </footer>
    <script src="script.js"></script>
</body>
</html>