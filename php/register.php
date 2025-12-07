<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db.php';  // DB connection

// Read POST data
$name    = $_POST['name'] ?? '';
$email   = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$age     = $_POST['age'] ?? null;
$dob     = $_POST['dob'] ?? null;
$contact = $_POST['contact'] ?? null;
$address = $_POST['address'] ?? null;

// Basic validation
if (!$name || !$email || !$password) {
    echo json_encode(['success'=>false, 'message'=>'Missing required fields']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success'=>false, 'message'=>'Password too short']);
    exit;
}

// Check if email exists in userss table
$stmt = $mysqli->prepare("SELECT id FROM userss WHERE email = ? LIMIT 1");
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success'=>false, 'message'=>'Email already registered']);
    exit;
}
$stmt->close();

// Hash password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert user
$stmt = $mysqli->prepare("
    INSERT INTO userss (name, email, password, age, dob, contact, address)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssisss",
    $name,
    $email,
    $hashed_password,
    $age,
    $dob,
    $contact,
    $address
);

$ok = $stmt->execute();

if (!$ok) {
    echo json_encode(['success'=>false, 'message'=>'DB Error: ' . $stmt->error]);
    exit;
}

$user_id = $stmt->insert_id;
$stmt->close();

// Success
echo json_encode(['success'=>true, 'message'=>'Registered successfully', 'user_id'=>$user_id]);
exit;
?>
