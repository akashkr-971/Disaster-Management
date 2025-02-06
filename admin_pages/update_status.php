<?php
require '../db_connect.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['request_id'], $_POST['status'])) {
    $request_id = intval($_POST['request_id']);
    $status = $_POST['status'];

    // Validate status input (allow only 'approved' or 'rejected')
    if (!in_array($status, ['approved', 'rejected'])) {
        echo "Invalid status!";
        exit;
    }

    try {
        // Update status in the database
        $stmt = $conn->prepare("UPDATE FinancialAidRequests SET status = ? WHERE request_id = ?");
        $stmt->execute([$status, $request_id]);

        echo "Status updated successfully!";
    } catch (PDOException $e) {
        echo "Error updating status: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}
?>
