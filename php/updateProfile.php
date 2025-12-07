<?php
header('Content-Type: application/json');
require_once 'db.php';

$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? null;
$dob = $_POST['dob'] ?? null;
$contact = $_POST['contact'] ?? null;
$address = $_POST['address'] ?? null;

if (!$name) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

// update name in MySQL using prepared statement
try {
    // check if user exists
    $stmtCheck = $mysqli->prepare('SELECT id FROM users1 WHERE name = ?');
    $stmtCheck->bind_param('s', $name);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        // user exists, you can update if needed (here just keeping name same)
        $stmtCheck->close();
    } else {
        // user not exists, insert new
        $stmtInsert = $mysqli->prepare('INSERT INTO users1 (name) VALUES (?)');
        $stmtInsert->bind_param('s', $name);
        $stmtInsert->execute();
        $stmtInsert->close();
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'MySQL operation failed: ' . $e->getMessage()]);
    exit;
}

// update or upsert profile in MongoDB
if ($mongoClient) {
    try {
        $collection = $mongoClient->selectCollection('internship_task', 'profiles');
        $filter = ['name' => $name]; // using name as unique key
        $update = ['$set' => [
            'age' => $age,
            'dob' => $dob,
            'contact' => $contact,
            'address' => $address
        ]];
        $options = ['upsert' => true];
        $collection->updateOne($filter, $update, $options);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Mongo update failed: ' . $e->getMessage()]);
        exit;
    }
}

echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
exit;
?>
