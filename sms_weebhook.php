<?php
// Log incoming SMS
file_put_contents('sms_log.txt', json_encode($_POST) . PHP_EOL, FILE_APPEND);

// Get the SMS details
$from = $_POST['From'];  // Sender's number
$body = $_POST['Body'];  // Message content

// Save to database (if needed)
require 'db_connect.php';
$stmt = $conn->prepare("INSERT INTO rescue_messages (sender, message) VALUES (?, ?)");
$stmt->execute([$from, $body]);

// Auto-reply to confirm receipt
header("Content-Type: text/xml");
echo "<Response><Message>We received your message. Help is on the way!</Message></Response>";
?>
