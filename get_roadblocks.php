<?php
require 'db_connect.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT id, latitude, longitude, description FROM Roadblocks");
    $stmt->execute();
    $roadblocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($roadblocks);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 

