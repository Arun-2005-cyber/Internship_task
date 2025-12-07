<?php
header('Content-Type: application/json');
require_once 'db.php';  // MySQL connection

$name = $_POST['name'] ?? '';
$age = $_POST['age'] ?? null;
$dob = $_POST['dob'] ?? null;
$contact = $_POST['contact'] ?? null;
$address = $_POST['address'] ?? null;

if (!$name) {
    echo json_encode(['success' => false, 'message' => 'Name is required']);
    exit;
}

try {
    $stmt = $mysqli->prepare("
        UPDATE users1 
        SET age = ?, dob = ?, contact = ?, address = ?
        WHERE name = ?
    ");

    $stmt->bind_param("issss", $age, $dob, $contact, $address, $name);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed: '.$stmt->error]);
    }

    $stmt->close();
    exit;

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    exit;
}
?>
