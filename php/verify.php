<?php
header('Content-Type: application/json');
require_once 'db.php';

$user_id = intval($_POST['user_id'] ?? 0);
if(!$user_id){
    echo json_encode(['success'=>false,'message'=>'Invalid session.']);
    exit;
}

// check Redis session if available
if($redis){
    $sessionKey = 'session_user_'.$user_id;
    if(!$redis->exists($sessionKey)){
        echo json_encode(['success'=>false,'message'=>'Session expired.']);
        exit;
    }
}

// fetch MySQL user
$stmt = $mysqli->prepare('SELECT id, name, email, created_at FROM users WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($id, $name, $email, $created_at);
if(!$stmt->fetch()){
    echo json_encode(['success'=>false,'message'=>'User not found.']);
    exit;
}
$stmt->close();

// fetch MongoDB profile
$profile = new stdClass();
if($mongoClient){
    try {
        $collection = $mongoClient->selectCollection('internship_task', 'profiles');
        $doc = $collection->findOne(['mysql_id' => $user_id]);
        if($doc){
            $profile = $doc;
        }
    } catch (Exception $e) {
        // ignore
    }
}

echo json_encode(['success'=>true,'data'=>[
    'name'=>$name,
    'email'=>$email,
    'created_at'=>$created_at,
    'profile'=>$profile
]]);
exit;
?>
