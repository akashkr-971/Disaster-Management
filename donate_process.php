<?php
ob_start();
include 'header.php';
require 'db_connect.php';
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to make a donation";
    header("Location: login.php");
    exit();
}
if(isset($_POST['donate'])){
    $amount = $_POST['amount'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    try {
        $sql = "INSERT INTO donations (user_id, name, email, amount, message) VALUES (:user_id, :name, :email, :amount, :message)";
        $stmt = $conn->prepare($sql); // Use PDO prepare()
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':name' => $name,
            ':email' => $email,
            ':amount' => $amount,
            ':message' => $message
        ]);


        $_SESSION['success'] = "Donation successful! Thank you for your contribution.";
        header("Location: donate_success.php");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Now - Disaster Recovery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('img/donation-bg.jpg') no-repeat center center/cover;
            background-attachment: fixed;
        }
        .donation-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(5px);
            background: rgba(255, 255, 255, 0.9); /* Light background to contrast text */
        }
        .donate-title {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000; /* Changed to Black */
            margin-bottom: 10px;
        }
        .donate-description {
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: #333; /* Dark grey for readability */
            margin-bottom: 20px;
        }
        .form-control {
            background: rgba(255, 255, 255, 1);
            border: 1px solid #ccc;
            color: #000;
        }
        .form-control::placeholder {
            color: #777;
        }
        .btn-donate {
            background: #28a745;
            border: none;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s;
            color: white;
        }
        .btn-donate:hover {
            background: #218838;
        }
        .currency-symbol {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="donation-container">
        <h2 class="donate-title">Make a Difference</h2>
        <p class="donate-description">Every ₹ counts! Your contribution helps families rebuild their lives.</p>

        <form action="donate_process.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Donation Amount <span class="currency-symbol">₹</span></label>
                <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter amount in INR" min="10" required>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="message" class="form-control" placeholder="Enter your message" required></textarea>
            </div>


            <button type="submit" class="btn btn-donate w-100" name="donate">Proceed to Donate</button>
        </form>
    </div>
</div>

</body>
</html>
