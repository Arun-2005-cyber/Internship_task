<?php
// db.php - central DB connectors for MySQL, MongoDB, Redis
// Configure credentials as per your local environment

// MySQL - mysqli
$MYSQL_HOST = '127.0.0.1';
$MYSQL_USER = 'root';
$MYSQL_PASS = '';
$MYSQL_DB   = 'internship_task';

$mysqli = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB);
if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'MySQL connection failed: '.$mysqli->connect_error]);
    exit;
}

// MongoDB - using mongodb/mongodb (composer) or mongodb extension
$MONGO_URI = 'mongodb://127.0.0.1:27017';
$mongoClient = null;
try {
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require_once __DIR__ . '/../vendor/autoload.php';
        $mongoClient = new MongoDB\Client($MONGO_URI);
    } else {
        // Attempt using MongoDB extension classes
        if (class_exists('MongoDB\Driver\Manager')) {
            $mongoClient = new MongoDB\Client($MONGO_URI);
        } else {
            // Not fatal here; downstream php files handle missing client.
            $mongoClient = null;
        }
    }
} catch (Exception $e) {
    $mongoClient = null;
}

// Redis - using phpredis (Redis class)
$redis = null;
try {
    if (class_exists('Redis')) {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
    } else {
        $redis = null;
    }
} catch (Exception $e) {
    $redis = null;
}
?>
