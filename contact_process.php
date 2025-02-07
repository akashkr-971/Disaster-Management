<?php
session_start();
require 'db_connect.php'; // Database connection file

echo "<script>console.log('Contact form submitted');</script>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);


    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['error'] = "All required fields must be filled.";
        header("Location: home.php#contact");
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: home.php#contact");
        exit();
    }

    try {
        // Insert data into the database
        $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, submitted_at) 
                VALUES (:name, :email, :phone, :subject, :message, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':subject' => $subject,
            ':message' => $message
        ]);
        $_SESSION['success'] = "Your message has been sent successfully!";
        header("Location: home.php#contact");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: home.php#contact");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: home.php#contact");
    exit();
}
?>