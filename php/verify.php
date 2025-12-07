<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

// check if user is logged in
if(!isset($_SESSION['user_email'])){
    echo json_encode(['success'=>false,'message'=>'Session invalid.']);
    exit;
}

$user_email = $_SESSION['user_email'];

// fetch MySQL user
$stmt = $mysqli->prepare('SELECT name, email FROM users1 WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $user_email);
$stmt->execute();
$stmt->bind_result($name, $email);
if(!$stmt->fetch()){
    echo json_encode(['success'=>false,'message'=>'User not found.']);
    exit;
}
$stmt->close();

// initialize profile to empty object
$profile = new stdClass();

// if you add MongoDB later, you can fill $profile here

echo json_encode([
    'success' => true,
    'data' => [
        'name' => $name,
        'email' => $email,
        'profile' => $profile
    ]
]);
exit;
?>
