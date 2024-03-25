<?php
// Set the timezone to Japan
date_default_timezone_set('Asia/Tokyo');

$currentDateTime = new DateTime();
$daysLeft = 5 - date('w'); // Assuming weekends are Saturday (6) and Sunday (0)
$motivationalQuotes = [
    'Monday' => 'It’s a new day, a new week. Stay motivated!',
    'Tuesday' => 'Stay focused, stay positive, stay strong!',
    'Wednesday' => 'Success is not built on success. It’s built on failure. It’s built on frustration. Sometimes it’s built on catastrophe.',
    'Thursday' => 'Keep pushing, keep hustling. Success is just around the corner!',
    'Friday' => 'Finish strong! The weekend is almost here!',
    'Saturday' => 'Enjoy your weekend!',
    'Sunday' => 'Rest, recharge, and get ready for the week ahead!'
];
$dayOfWeek = date('l');
$motivationalQuote = isset($motivationalQuotes[$dayOfWeek]) ? $motivationalQuotes[$dayOfWeek] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yasumi Doko</title>
    <style>
        /* Add custom styles */
        body {
            background: #4A90E2;
            color: #fff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
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
                Current Date and Time
            </div>
            <div class="card-body">
                <h5 class="card-title" id="currentDateTime"><?= date('Y-m-d H:i:s') ?></h5>
                <p class="card-text">Days Left Till Weekend: <?= $daysLeft ?></p>
            </div>
            <div class="card-footer text-muted" id="motivationalQuote"><?= $motivationalQuote ?> <span id="emojiPlaceholder"></span></div>
        </div>
    </div>
    <script>
        // Array of emojis
        var emojis = ['💪', '🚀', '🌟', '😊', '🎉', '🌻'];

        // Function to select a random emoji
        function getRandomEmoji() {
            var randomIndex = Math.floor(Math.random() * emojis.length);
            return emojis[randomIndex];
        }

        // Function to update clock and random emoji
        function updateClock() {
            var now = new Date();
            var options = {
                timeZone: 'Asia/Tokyo',
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            };
            var dateTimeString = now.toLocaleString('ja-JP', options);
            document.getElementById('currentDateTime').textContent = dateTimeString;

            // Update random emoji
            var emojiPlaceholder = document.getElementById('emojiPlaceholder');
            emojiPlaceholder.textContent = getRandomEmoji();
        }

        // Update clock and random emoji every second
        setInterval(updateClock, 1000);
    </script>
</body>
</html>

