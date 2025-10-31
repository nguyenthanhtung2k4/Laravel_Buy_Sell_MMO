<?php
// clear.php - chạy qua cron để xóa/ghi log redirect_links expired or old used
require_once __DIR__.'/db.php';

$deleted = 0;
$st = $pdo->prepare("DELETE FROM redirect_links WHERE used = 1 AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
$st->execute();
$deleted += $st->rowCount();

$st2 = $pdo->prepare("DELETE FROM redirect_links WHERE expires_at < NOW() AND used = 0 AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
$st2->execute();
$deleted += $st2->rowCount();

echo "Cleared {$deleted} old redirect links\n";
