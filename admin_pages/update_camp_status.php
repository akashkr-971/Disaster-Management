<?php
require '../db_connect.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['request_id'], $_POST['status'])) {
    $request_id = intval($_POST['request_id']);
    $status = $_POST['status'];

    if (!in_array($status, ['verified', 'rejected'])) {
        echo "Invalid status!";
        exit;
    }


    try {
        // Update status in the database
        $stmt = $conn->prepare("UPDATE Campaigners SET verification_status = ? WHERE campaigner_id = ?");
        $stmt->execute([$status, $request_id]);

        echo "Status updated successfully!";
    } catch (PDOException $e) {
        echo "Error updating status: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}
?>
