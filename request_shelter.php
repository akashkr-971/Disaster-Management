<?php
include 'header.php'; // Include the common header

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $location = $_POST['location'] ?? '';
    $people_count = $_POST['people_count'] ?? '';
    $urgency = $_POST['urgency'] ?? '';

    // Basic validation
    if (empty($name) || empty($contact) || empty($location) || empty($people_count) || empty($urgency)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit;
    }

    // Insert the request into the database
    $query = "INSERT INTO shelter_requests (name, contact, location, people_count, urgency, status) VALUES (?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssds", $name, $contact, $location, $people_count, $urgency);
    if ($stmt->execute()) {
        echo "<script>alert('Shelter request submitted successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error submitting request. Please try again.');</script>";
    }
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
        <form action="shelter_request.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact Number</label>
                <input type="text" name="contact" id="contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Current Location</label>
                <input type="text" name="location" id="location" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="people_count" class="form-label">Number of People</label>
                <input type="number" name="people_count" id="people_count" class="form-control" min="1" required>
            </div>
            <div class="mb-3">
                <label for="urgency" class="form-label">Urgency Level</label>
                <select name="urgency" id="urgency" class="form-control" required>
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
