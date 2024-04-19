var emojis = [
    '💪', '🚀', '🌟', '😊', '🎉', '🌻', '🌠', '💫', '👏', '🙌', '🔥', '✨', '💖', '🎊', '🎈', '🎇', '🎆', '🌞',
    '🎖️', '🏆', '🥇', '🥊', '🏋️‍♂️', '🚴‍♀️', '🏃‍♀️', '🏅', '💥', '🏹', '🎯', '🏀', '⚽', '🎾', '🏐', '🎱', '🏓', '🏸'];
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
    var weekendDays = [];
    ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'].forEach(function(day) {
        if (document.getElementById(day).checked) {
            weekendDays.push(day);
        }
    });
    document.cookie = "user_timezone=" + timezone + "; path=/";
    document.cookie = "user_language=" + language + "; path=/";
    document.cookie = "weekend_days=" + weekendDays.join(',') + "; path=/"; // Save weekend days to cookies
    location.reload(); // Reload the page to apply changes
}


setInterval(updateClock, 1000);