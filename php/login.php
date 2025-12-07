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
$stmt = $mysqli->prepare('SELECT password, name FROM users1 WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($hash, $name);
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

// login successful
echo json_encode(['success'=>true,'message'=>'Login successful.','user_email'=>$email]);
exit;
?>
