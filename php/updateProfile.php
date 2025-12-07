<?php
header('Content-Type: application/json');
require_once 'db.php';  // only for MongoDB now

$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? null;
$dob = $_POST['dob'] ?? null;
$contact = $_POST['contact'] ?? null;
$address = $_POST['address'] ?? null;

if (!$name) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

if (!$mongoClient) {
    echo json_encode(['success' => false, 'message' => 'MongoDB not connected']);
    exit;
}

try {
    $collection = $mongoClient->selectCollection('internship_task', 'profiles');

    $filter = ['name' => $name];
    $update = [
        '$set' => [
            'name' => $name,
            'age' => $age,
            'dob' => $dob,
            'contact' => $contact,
            'address' => $address
        ]
    ];
    $options = ['upsert' => true];

    $collection->updateOne($filter, $update, $options);

    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    exit;

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'MongoDB update failed: ' . $e->getMessage()]);
    exit;
}
?>
