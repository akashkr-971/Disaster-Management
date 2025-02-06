<?php
require '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['status'])) {
    $request_id = intval($_POST['request_id']);
    $status = in_array($_POST['status'], ['pending', 'approved', 'rejected', 'completed']) ? $_POST['status'] : 'pending';

    $stmt = $conn->prepare("UPDATE supply_requests SET status = ? WHERE request_id = ?");
    if ($stmt->execute([$status, $request_id])) {
        echo "Request status updated to " . ucfirst($status);
    } else {
        echo "Error updating request.";
    }
} else {
    echo "Invalid request.";
}
?>
