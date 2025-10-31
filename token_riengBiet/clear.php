<?php
// file: cleanup.php
require_once __DIR__ . '/db.php';
try {
    $pdo = get_pdo();
    $cutoff_dt = new DateTime('-'.INACTIVE_DAYS.' days', new DateTimeZone('UTC'));
    $cutoff = $cutoff_dt->format('Y-m-d H:i:s');
    $stmt = $pdo->prepare("DELETE FROM devices WHERE last_seen < :cutoff");
    $stmt->execute([':cutoff'=>$cutoff]);
    $deleted = $stmt->rowCount();
    write_file_log('INFO','Cleanup run', ['deleted'=>$deleted,'cutoff'=>$cutoff]);
    echo "Deleted " . $deleted . " inactive devices (last_seen < $cutoff)\n";
} catch (Exception $e) {
    write_file_log('ERROR','Cleanup error', ['message'=>$e->getMessage()]);
    echo "Cleanup error: " . $e->getMessage() . "\n";
}
