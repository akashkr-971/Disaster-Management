<?php
session_start();
require 'db_connect.php'; 
// Check if the user is logged in and has "campaigner" role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'campaigner') {
    header("Location: login.php");
    exit();
}

// Fetch campaigner details
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT * FROM Campaigners WHERE user_id = ?");
$query->execute([$user_id]);
$campaigner = $query->fetch();

// If no campaigner is found, redirect to login
if (!$campaigner) {
    header("Location: login.php");
    exit();
}

// Fetch all campaigns related to the campaigner
$campaigns = $conn->prepare("SELECT * FROM Campaigners WHERE user_id = ?");
$campaigns->execute([$user_id]);
$campaigns = $campaigns->fetchAll();

// Handle supply requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO supply_requests (camp_name, food_items, medicines, funding_request, comments) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $campaigner['organization_name'], 
            $_POST['food_items'], 
            $_POST['medicines'], 
            $_POST['funding_request'], 
            $_POST['comments']
        ]);
        $success_message = "Request submitted successfully!";
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaigners Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="theme.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin.php">
                <i class="bi bi-shield-lock me-2"></i>Admin Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php" id="logoutBtn">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h2 class="text-center">Campaigners Dashboard</h2>

        <?php if (isset($success_message)) : ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <!-- Campaigner Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Campaigner Details</h4>
            </div>
            <div class="card-body">
                <p><strong>Organization Name:</strong> <?= htmlspecialchars($campaigner['organization_name']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($campaigner['organization_location']) ?></p>
                <p><strong>Campaign Type:</strong> <?= htmlspecialchars($campaigner['campaign_type']) ?></p>
                <p><strong>Description:</strong> <?= htmlspecialchars($campaigner['organization_description']) ?></p>
                <p><strong>Verification Status:</strong> <span class="badge bg-<?=
                    $campaigner['verification_status'] === 'verified' ? 'success' : 
                    ($campaigner['verification_status'] === 'pending' ? 'warning' : 'danger')
                ?>">
                    <?= htmlspecialchars($campaigner['verification_status']) ?>
                </span></p>
            </div>
        </div>

        <!-- Food, Medicine & Funding Requests -->
        <div class="card">
            <div class="card-header">
                <h4>Request Food, Medicines & Funding</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="food_items" class="form-label">Food Items Needed:</label>
                        <textarea name="food_items" id="food_items" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="medicines" class="form-label">Medicines Needed:</label>
                        <textarea name="medicines" id="medicines" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="funding_request" class="form-label">Funding Needed (Amount in USD):</label>
                        <input type="number" name="funding_request" id="funding_request" class="form-control" min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="comments" class="form-label">Additional Comments:</label>
                        <textarea name="comments" id="comments" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" name="submit_request" class="btn btn-primary">Submit Request</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
