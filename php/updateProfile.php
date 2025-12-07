<?php
header('Content-Type: application/json');
require_once 'db.php';  // only for MongoDB

$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? null;
$dob = $_POST['dob'] ?? null;
$contact = $_POST['contact'] ?? null;
$address = $_POST['address'] ?? null;

if (!$name) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

if ($mongoClient) {
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

        echo json_encode(['success' => true, 'message' => 'Profile updated successfully (MongoDB only).']);
        exit;

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Mongo update failed: ' . $e->getMessage()]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'MongoDB not connected']);
exit;
?>
