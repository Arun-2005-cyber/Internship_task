<?php
$MYSQL_HOST = getenv("DB_HOST") ?: "mysql.railway.internal";
$MYSQL_USER = getenv("DB_USER");
$MYSQL_PASS = getenv("DB_PASS");
$MYSQL_DB   = getenv("DB_NAME");
$MYSQL_PORT = getenv("DB_PORT") ?: 3306;

$mysqli = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASS, $MYSQL_DB, $MYSQL_PORT);

if ($mysqli->connect_errno) {
    http_response_code(500);
    echo "MySQL connection failed: " . $mysqli->connect_error;
    exit;
}
?>
