<?php
header('Content-Type: application/json');
require 'db_connect.php';

try {
    $stmt = $conn->prepare("SELECT id, latitude, longitude, description FROM Roadblocks ORDER BY id DESC");
    $stmt->execute();
    $roadblocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($roadblocks);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
