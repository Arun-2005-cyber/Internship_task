<?php
header('Content-Type: application/json');
require_once 'db.php';

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
$stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
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

// insert into MySQL
$stmt = $mysqli->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $email, $hash);
$ok = $stmt->execute();
if(!$ok){
    echo json_encode(['success'=>false,'message'=>'DB error: '.$stmt->error]);
    exit;
}
$user_id = $stmt->insert_id;
$stmt->close();

// insert profile into MongoDB if available
$profileInserted = false;
if($mongoClient){
    try {
        $collection = $mongoClient->selectCollection('internship_task', 'profiles');
        $doc = [
            'mysql_id' => intval($user_id),
            'age' => $age,
            'dob' => $dob,
            'contact' => $contact,
            'address' => $address
        ];
        $collection->insertOne($doc);
        $profileInserted = true;
    } catch (Exception $e) {
        // ignore or log
        $profileInserted = false;
    }
}

// success
echo json_encode(['success'=>true,'message'=>'Registered successfully.','user_id'=>$user_id]);
exit;
?>
