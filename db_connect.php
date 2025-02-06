<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dmsdb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>