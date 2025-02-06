<?php
require 'header.php';
require 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to request shelter";
    header("Location: login.php");
    exit();
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Prepare and execute the insert statement
        $stmt = $conn->prepare("INSERT INTO ShelterRequests (
            user_id, 
            full_name, 
            phone, 
            family_members, 
            current_location,
            urgency_level
        ) VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $_SESSION['user_id'],
            $_POST['full_name'],
            $_POST['phone'],
            $_POST['family_members'],
            $_POST['current_location'],
            $_POST['urgency_level']
        ]);

        $_SESSION['success'] = "Your shelter request has been submitted successfully!";
        echo "<script>window.location.href='request_shelter.php';</script>";
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

// Show success/error messages if any
if (isset($_SESSION['success']) || isset($_SESSION['error'])) {
    echo '<div class="container mt-5 pt-4">';
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Temporary Shelter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('img/shelter-bg.jpg') no-repeat center center/cover;
            background-attachment: fixed;
        }
        .shelter-container {
            max-width: 500px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .shelter-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: black;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="shelter-container">
        <h2 class="shelter-title">Request Temporary Shelter</h2>
        <p class="text-center">Submit your request for a safe shelter at a nearby refugee camp.</p>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="full_name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" name="phone" id="contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Current Location</label>
                <input type="text" name="current_location" id="location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="people_count" class="form-label">Number of People</label>
                <input type="number" name="family_members" id="people_count" class="form-control" min="1" required>
            </div>
            <div class="mb-3">
                <label for="urgency" class="form-label">Urgency Level</label>
                <select name="urgency_level" id="urgency" class="form-control" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <button type="submit" class="btn btn-danger w-100">Request Shelter</button>
        </form>
    </div>
</div>
</body>
</html>
