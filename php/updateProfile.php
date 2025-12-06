<?php
header('Content-Type: application/json');
require_once 'db.php';

$user_id = intval($_POST['user_id'] ?? 0);
$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? null;
$dob = $_POST['dob'] ?? null;
$contact = $_POST['contact'] ?? null;
$address = $_POST['address'] ?? null;

if(!$user_id){
    echo json_encode(['success'=>false,'message'=>'Invalid request']);
    exit;
}

// update name in MySQL using prepared statement
if($name){
    $stmt = $mysqli->prepare('UPDATE users SET name = ? WHERE id = ?');
    $stmt->bind_param('si', $name, $user_id);
    $stmt->execute();
    $stmt->close();
}

// update or upsert profile in MongoDB
if($mongoClient){
    try {
        $collection = $mongoClient->selectCollection('internship_task', 'profiles');
        $filter = ['mysql_id' => $user_id];
        $update = ['$set' => [
            'age' => $age,
            'dob' => $dob,
            'contact' => $contact,
            'address' => $address
        ]];
        $options = ['upsert' => true];
        $collection->updateOne($filter, $update, $options);
    } catch (Exception $e) {
        echo json_encode(['success'=>false,'message'=>'Mongo update failed.']);
        exit;
    }
}

echo json_encode(['success'=>true,'message'=>'Profile updated.']);
exit;
?>
