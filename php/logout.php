<?php
header('Content-Type: application/json');
require_once 'db.php';

$user_id = intval($_POST['user_id'] ?? 0);
if($user_id && $redis){
    $sessionKey = 'session_user_'.$user_id;
    $redis->del($sessionKey);
}
echo json_encode(['success'=>true]);
exit;
?>
