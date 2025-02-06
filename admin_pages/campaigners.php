<?php
$conn = new mysqli("localhost", "root", "", "dmsdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch pending campaigners
$pending_campaigners = mysqli_query($conn, "SELECT * FROM Campaigners WHERE verification_status='pending'");

// Fetch approved campaigners
$approved_campaigners = mysqli_query($conn, "SELECT * FROM Campaigners WHERE verification_status='verified'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaigners</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4">Campaigners</h2>

    <!-- Pending Campaigners -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Pending Requests</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="pendingCampaignersTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($pending_campaigners)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['campaigner_id']) ?></td>
                                <td><?= htmlspecialchars($row['organization_name']) ?></td>
                                <td><?= htmlspecialchars($row['organization_location']) ?></td>
                                <td><?= htmlspecialchars($row['campaign_type']) ?></td>
                                <td><?= htmlspecialchars($row['organization_description']) ?></td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="updateCampaignerStatus(<?= $row['campaigner_id'] ?>, 'approved')">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="updateCampaignerStatus(<?= $row['campaigner_id'] ?>, 'rejected')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Approved Campaigners -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Approved Campaigners</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="approvedCampaignersTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($approved_campaigners)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['campaigner_id']) ?></td>
                                <td><?= htmlspecialchars($row['organization_name']) ?></td>
                                <td><?= htmlspecialchars($row['organization_location']) ?></td>
                                <td><?= htmlspecialchars($row['campaign_type']) ?></td>
                                <td><?= htmlspecialchars($row['organization_description']) ?></td>
                                <td><span class="badge bg-success">Approved</span></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS & jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#pendingCampaignersTable, #approvedCampaignersTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 10
    });
});
</script>

</body>
</html>
