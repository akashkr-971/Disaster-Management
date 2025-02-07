<?php
require 'db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit();
}

// Log incoming data for debugging
error_log('POST data received: ' . print_r($_POST, true));

$latitude = filter_input(INPUT_POST, 'latitude', FILTER_VALIDATE_FLOAT);
$longitude = filter_input(INPUT_POST, 'longitude', FILTER_VALIDATE_FLOAT);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

// Validate data
if ($latitude === false || $latitude === null) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid latitude value']);
    exit();
}

if ($longitude === false || $longitude === null) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid longitude value']);
    exit();
}

if (empty($description)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Description is required']);
    exit();
}

try {
    $stmt = $conn->prepare("INSERT INTO Roadblocks (latitude, longitude, description) VALUES (?, ?, ?)");
    $result = $stmt->execute([$latitude, $longitude, $description]);
    
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Roadblock saved successfully']);
    } else {
        throw new Exception('Failed to insert record');
    }
} catch (Exception $e) {
    error_log('Database error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 