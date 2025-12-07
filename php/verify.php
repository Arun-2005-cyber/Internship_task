<?php
session_start();  // start PHP session
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

// fetch MongoDB profile (optional)
// $profile = new stdClass();
// if($mongoClient){
//     try {
//         $collection = $mongoClient->selectCollection('internship_task', 'profiles');
//         $doc = $collection->findOne(['email' => $user_email]);  // use email as identifier
//         if($doc){
//             $profile = $doc;
//         }
//     } catch (Exception $e) {
//         // ignore errors
//     }
// }

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
