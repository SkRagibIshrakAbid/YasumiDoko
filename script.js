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

const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const points = [];
const maxDistance = 110;
let cursor = { x: 0, y: 0 };

function init() {
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;
generatePoints();
animate();
}

function generatePoints() {
const numPoints = 100;
for (let i = 0; i < numPoints; i++) {
    points.push({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    vx: Math.random() * 2 - 1,
    vy: Math.random() * 2 - 1
    });
}
}

function animate() {
requestAnimationFrame(animate);
ctx.clearRect(0, 0, canvas.width, canvas.height);
update();
draw();
}

function update() {
points.forEach(point => {
    point.x += point.vx;
    point.y += point.vy;
    if (point.x < 0 || point.x > canvas.width) {
    point.vx *= -1;
    }
    if (point.y < 0 || point.y > canvas.height) {
    point.vy *= -1;
    }
});

// Connect points
for (let i = 0; i < points.length; i++) {
    for (let j = i + 1; j < points.length; j++) {
    const distance = Math.sqrt((points[i].x - points[j].x) ** 2 + (points[i].y - points[j].y) ** 2);
    if (distance < maxDistance) {
        ctx.beginPath();
        ctx.moveTo(points[i].x, points[i].y);
        ctx.lineTo(points[j].x, points[j].y);
        ctx.strokeStyle = 'rgba(255, 255, 255, ' + (1 - distance / maxDistance) + ')';
        ctx.stroke();
        ctx.closePath();
    }
    }
}

// Connect cursor
points.forEach(point => {
    const distance = Math.sqrt((point.x - cursor.x) ** 2 + (point.y - cursor.y) ** 2);
    if (distance < maxDistance) {
    ctx.beginPath();
    ctx.moveTo(point.x, point.y);
    ctx.lineTo(cursor.x, cursor.y);
    ctx.strokeStyle = 'rgba(255, 255, 255, ' + (1 - distance / maxDistance) + ')';
    ctx.stroke();
    ctx.closePath();
    }
});
}

function draw() {
    points.forEach(point => {
        // Draw points
        ctx.beginPath();
        ctx.arc(point.x, point.y, 2, 0, Math.PI * 2);
        ctx.fillStyle = 'orange'; // Change point color to orange
        ctx.fill();
        ctx.closePath();
    });
}

window.addEventListener('mousemove', e => {
cursor.x = e.clientX;
cursor.y = e.clientY;
});

window.addEventListener('resize', () => {
init();
});

init();