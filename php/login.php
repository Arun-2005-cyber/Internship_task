<?php
header('Content-Type: application/json');
require_once 'db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if(!$email || !$password){
    echo json_encode(['success'=>false,'message'=>'Missing credentials.']);
    exit;
}

// fetch user
$stmt = $mysqli->prepare('SELECT id, password, name FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($id, $hash, $name);
if(!$stmt->fetch()){
    echo json_encode(['success'=>false,'message'=>'Invalid email or password.']);
    exit;
}
$stmt->close();

// verify password
if(!password_verify($password, $hash)){
    echo json_encode(['success'=>false,'message'=>'Invalid email or password.']);
    exit;
}

// create a simple session token in Redis (optional)
$sessionKey = null;
if($redis){
    $sessionKey = 'session_user_'.$id;
    $redis->set($sessionKey, time());
    // set expiry 24 hours
    $redis->expire($sessionKey, 24*3600);
}

// return success and user id
echo json_encode(['success'=>true,'message'=>'Login successful.','user_id'=>$id]);
exit;
?>
