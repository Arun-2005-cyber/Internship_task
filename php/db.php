<?php
$host = getenv("MYSQLHOST");
$user = getenv("MYSQLUSER");
$pass = getenv("MYSQLPASSWORD");
$db   = getenv("MYSQLDATABASE");
$port = getenv("MYSQLPORT");
// $mongoUrl = getenv("MONGO_URL");


// try {
//     $mongoClient = new MongoDB\Client($mongoUrl);
// } catch (Exception $e) {
//     die(json_encode([
//         'success' => false,
//         'message' => 'MongoDB connection failed: ' . $e->getMessage()
//     ]));
// }

$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>


