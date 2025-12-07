<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';

// get POST data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$age = $_POST['age'] ?? null;
$dob = $_POST['dob'] ?? null;
$contact = $_POST['contact'] ?? null;
$address = $_POST['address'] ?? null;

if(!$name || !$email || !$password){
    echo json_encode(['success'=>false,'message'=>'Missing fields.']);
    exit;
}

if(strlen($password) < 6){
    echo json_encode(['success'=>false,'message'=>'Password too short.']);
    exit;
}

// check existing email
$stmt = $mysqli->prepare('SELECT email FROM users1 WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){
    echo json_encode(['success'=>false,'message'=>'Email already registered.']);
    exit;
}
$stmt->close();

// hash password
$hash = password_hash($password, PASSWORD_BCRYPT);

// insert user
$stmt = $mysqli->prepare('INSERT INTO users1 (name, email, password, age, dob, contact, address) VALUES (?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('sssisss', $name, $email, $hash, $age, $dob, $contact, $address);

$ok = $stmt->execute();

if(!$ok){
    echo json_encode(['success'=>false,'message'=>'DB error: '.$stmt->error]);
    exit;
}

echo json_encode(['success'=>true,'message'=>'Registered successfully.']);
exit;
?>
