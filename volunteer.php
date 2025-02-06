<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a volunteer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'volunteer') {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';

// Get volunteer details
$stmt = $conn->prepare("SELECT * FROM Volunteers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$volunteer = $stmt->fetch();

// Update volunteer status
if (isset($_POST['update_status'])) {
    try {
        $stmt = $conn->prepare("UPDATE Volunteers SET status = ? WHERE user_id = ?");
        $stmt->execute([$_POST['status'], $_SESSION['user_id']]);
        $_SESSION['success'] = "Status updated successfully!";
        header("Location: volunteer.php");
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error updating status: " . $e->getMessage();
    }
}
?>

<style>
.volunteer-wrapper {
    padding-top: 80px;
    min-height: calc(100vh - 60px);
    background: #f8f9fa;
}

.profile-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.status-badge {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
}
</style>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Renew Hope</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="theme.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>

<!-- Admin Header -->
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
<div class="volunteer-wrapper">
    <div class="container">
        <!-- Profile Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="profile-card card">
                    <div class="card-body text-center">
                        <i class="bi bi-person-circle display-1 text-primary mb-3"></i>
                        <h4><?php echo htmlspecialchars($_SESSION['full_name']); ?></h4>
                        <span class="status-badge bg-success text-white">
                            <?php echo ucfirst($volunteer['status']); ?>
                        </span>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                Update Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="profile-card card">
                    <div class="card-body">
                        <h5 class="card-title">Volunteer Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Experience Level</label>
                                <p class="form-control-static"><?php echo ucfirst($volunteer['experience_level']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Availability</label>
                                <p class="form-control-static"><?php echo str_replace('_', ' ', ucfirst($volunteer['availability'])); ?></p>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Skills</label>
                                <p class="form-control-static"><?php echo $volunteer['skills']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update Modal -->
        <div class="modal fade" id="updateStatusModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <select name="status" class="form-select">
                                <option value="active" <?php echo $volunteer['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $volunteer['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                <option value="on_duty" <?php echo $volunteer['status'] == 'on_duty' ? 'selected' : ''; ?>>On Duty</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="update_status" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>