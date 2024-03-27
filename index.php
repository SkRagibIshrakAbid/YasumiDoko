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
    <title>Yasumi Doko</title>
    <!-- Add link tag for favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="imagecropped.png">
    <!-- Your existing styles and scripts -->
    <style>
        /* Add custom styles */
        body {
            background: #4A90E2;
            color: #fff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            direction: <?= $languageDirection ?>;
        }
        .container {
            padding: 50px;
            text-align: center;
        }
        .jumbotron {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
        }
        .card {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 10px;
            margin-top: 20px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            font-size: 1.5rem;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .card-body, .card-footer {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.2rem;
        }
        .card-body {
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .card-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 10px;
            margin-top: 20px;
            font-style: italic;
        }

        /* Add media queries for responsiveness */
        @media only screen and (max-width: 600px) {
            .container {
                padding: 20px;
            }
            .card {
                margin-top: 10px;
            }
        }
    </style>
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
                <label for="weekendDays">Select Weekend Days:</label>
                <select id="weekendDays" multiple>
                    <option value="Monday" <?= (in_array('Monday', $weekendDays)) ? 'selected' : '' ?>>Monday</option>
                    <option value="Tuesday" <?= (in_array('Tuesday', $weekendDays)) ? 'selected' : '' ?>>Tuesday</option>
                    <option value="Wednesday" <?= (in_array('Wednesday', $weekendDays)) ? 'selected' : '' ?>>Wednesday</option>
                    <option value="Thursday" <?= (in_array('Thursday', $weekendDays)) ? 'selected' : '' ?>>Thursday</option>
                    <option value="Friday" <?= (in_array('Friday', $weekendDays)) ? 'selected' : '' ?>>Friday</option>
                    <option value="Saturday" <?= (in_array('Saturday', $weekendDays)) ? 'selected' : '' ?>>Saturday</option>
                    <option value="Sunday" <?= (in_array('Sunday', $weekendDays)) ? 'selected' : '' ?>>Sunday</option>
                </select><br><br>
                <button onclick="savePreferences()">Save Preferences</button>
            </div>
        </div>
    </div>
    <script>
        var emojis = ['💪', '🚀', '🌟', '😊', '🎉', '🌻'];
        var emojiPlaceholder = document.getElementById('emojiPlaceholder');
        
        function getRandomEmoji() {
            var randomIndex = Math.floor(Math.random() * emojis.length);
            return emojis[randomIndex];
        }

        function updateClock() {
            var timezone = document.getElementById('timezone').value;

            var now = new Date();
            var serverTime = new Date(now.toLocaleString('en-US', { timeZone: timezone })); // Adjust server time according to timezone

            var options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric',
                hour12: true // Use 12-hour format
            };

            var formattedDate = ('0' + serverTime.getDate()).slice(-2) + '/' + ('0' + (serverTime.getMonth() + 1)).slice(-2) + '/' + serverTime.getFullYear();
            var hours = serverTime.getHours() % 12 || 12;
            var ampm = serverTime.getHours() >= 12 ? 'PM' : 'AM';
            var formattedTime = ('0' + hours).slice(-2) + ':' + ('0' + serverTime.getMinutes()).slice(-2) + ':' + ('0' + serverTime.getSeconds()).slice(-2) + ' ' + ampm;

            var formattedDateTime = formattedDate + ' ' + formattedTime;

            document.getElementById('currentDateTime').textContent = formattedDateTime;


            emojiPlaceholder.textContent = getRandomEmoji();
        }

        function savePreferences() {
            var timezone = document.getElementById('timezone').value;
            var language = document.getElementById('language').value;
            var weekendDays = Array.from(document.getElementById('weekendDays').selectedOptions).map(option => option.value);
            document.cookie = "user_timezone=" + timezone + "; path=/";
            document.cookie = "user_language=" + language + "; path=/";
            document.cookie = "weekend_days=" + weekendDays.join(',') + "; path=/"; // Save weekend days to cookies
            location.reload(); // Reload the page to apply changes
        }

        setInterval(updateClock, 1000);
    </script>
</body>
</html>