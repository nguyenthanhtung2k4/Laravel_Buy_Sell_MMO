<?php
// redirect.php
require_once __DIR__.'/db.php';
header('Content-Type: text/plain; charset=utf-8');

$tok = $_GET['token'] ?? '';
if (!$tok) {
    http_response_code(400);
    echo "Missing token";
    exit;
}

// find link
$st = $pdo->prepare("SELECT rl.*, l.license_key FROM redirect_links rl
                    JOIN licenses l ON rl.license_id = l.id
                    WHERE rl.token = :tok LIMIT 1");
$st->execute(['tok'=>$tok]);
$row = $st->fetch();

if (!$row) {
    http_response_code(404);
    echo "Link not found";
    exit;
}
if ($row['used']) {
    http_response_code(410);
    echo "Link already used";
    exit;
}
if (strtotime($row['expires_at']) < time()) {
    http_response_code(410);
    echo "Link expired";
    exit;
}

// mark used
$up = $pdo->prepare("UPDATE redirect_links SET used = 1 WHERE id = :id");
$up->execute(['id'=>$row['id']]);

// fetch content from target_url server-side and echo back as plain text
$target = $row['target_url'];

// Simple fetch (server side). Use curl for robustness.
$ch = curl_init($target);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 8);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$content = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

if ($content === false || $httpcode >= 400) {
    http_response_code(502);
    echo "Failed to fetch target";
    exit;
}

// Prevent caching
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Return content (likely code) as plain text
echo $content;
exit;
