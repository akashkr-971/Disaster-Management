<?php
require '../db_connect.php';

// Get updated statistics
$stats = [
    'volunteers' => $conn->query("SELECT COUNT(*) FROM Volunteers")->fetchColumn(),
    'campaigners' => $conn->query("SELECT COUNT(*) FROM Campaigners")->fetchColumn(),
    'pending_aid' => $conn->query("SELECT COUNT(*) FROM FinancialAidRequests WHERE status='pending'")->fetchColumn(),
    'skill_training' => $conn->query("SELECT COUNT(*) FROM SkillTraining WHERE status='pending'")->fetchColumn()
];

// Get verification requests
$pending_verifications = $conn->query("
    SELECT COUNT(*) FROM Campaigners 
    WHERE verification_status='pending'"
)->fetchColumn();
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-4">
        <div class="stat-card card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Active Volunteers</h5>
                <h2><?php echo $stats['volunteers']; ?></h2>
                <p class="mb-0"><small>Registered volunteers</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Pending Aid</h5>
                <h2><?php echo $stats['pending_aid']; ?></h2>
                <p class="mb-0"><small>Financial aid requests</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Campaigners</h5>
                <h2><?php echo $stats['campaigners']; ?></h2>
                <p class="mb-0"><small><?php echo $pending_verifications; ?> pending verifications</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Training Requests</h5>
                <h2><?php echo $stats['skill_training']; ?></h2>
                <p class="mb-0"><small>Pending applications</small></p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Applications</h5>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary">Export</button>
            <button type="button" class="btn btn-sm btn-outline-primary">Print</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Combined recent applications query
                    $recent_applications = $conn->query("
                        (SELECT 
                            created_at,
                            'Financial Aid' as type,
                            full_name,
                            status,
                            request_id as id
                        FROM FinancialAidRequests
                        ORDER BY created_at DESC
                        LIMIT 5)
                        UNION ALL
                        (SELECT 
                            created_at,
                            'Skill Training' as type,
                            full_name,
                            status,
                            training_id as id
                        FROM SkillTraining
                        ORDER BY created_at DESC
                        LIMIT 5)
                        ORDER BY created_at DESC
                        LIMIT 10
                    ");

                    while ($row = $recent_applications->fetch()) {
                        $status_class = match($row['status']) {
                            'pending' => 'bg-warning',
                            'approved' => 'bg-success',
                            'completed' => 'bg-info',
                            default => 'bg-secondary'
                        };
                        
                        echo "<tr>
                                <td>" . date('Y-m-d H:i', strtotime($row['created_at'])) . "</td>
                                <td>{$row['type']}</td>
                                <td>{$row['full_name']}</td>
                                <td><span class='badge {$status_class}'>{$row['status']}</span></td>
                                <td>
                                    <button class='btn btn-sm btn-success' onclick='window.alert(\"Approved\")'>
                                        <i class='bi bi-check-circle'></i>
                                    </button>
                                    <button class='btn btn-sm btn-danger' onclick='window.alert(\"Rejected\")'>
                                        <i class='bi bi-x-circle'></i>
                                    </button>
                                </td>
                            </tr>";


                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Camp Name</th>
                        <th>Food Items</th>
                        <th>Medicines</th>
                        <th>Funding Request</th>
                        <th>Comments</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $recent_requests = $conn->query("
                        SELECT 
                            request_id AS id,
                            camp_name,
                            food_items,
                            medicines,
                            funding_request,
                            comments,
                            requested_at,
                            status
                        FROM supply_requests
                        ORDER BY requested_at DESC
                        LIMIT 10
                    ");

                    while ($row = $recent_requests->fetch()) {
                        $status_class = match($row['status']) {
                            'pending' => 'bg-warning',
                            'approved' => 'bg-success',
                            'completed' => 'bg-info',
                            'rejected' => 'bg-danger',
                            default => 'bg-secondary'
                        };
                        
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['camp_name']}</td>
                                <td>{$row['food_items']}</td>
                                <td>{$row['medicines']}</td>
                                <td>{$row['funding_request']}</td>
                                <td>{$row['comments']}</td>
                                <td>" . date('Y-m-d H:i', strtotime($row['requested_at'])) . "</td>
                                <td><span class='badge {$status_class}'>{$row['status']}</span></td>
                                <td>
                                    <button class='btn btn-sm btn-success' onclick='updateStatus({$row['id']}, \"approved\")'>
                                        <i class='bi bi-check-circle'></i>
                                    </button>
                                    <button class='btn btn-sm btn-danger' onclick='updateStatus({$row['id']}, \"rejected\")'>
                                        <i class='bi bi-x-circle'></i>
                                    </button>
                                </td>
                            </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function viewDetails(type, id) {
    // Handle viewing details based on type and id
    console.log(`Viewing ${type} details for ID: ${id}`);
    // Implement modal or redirect as needed
}
</script> 