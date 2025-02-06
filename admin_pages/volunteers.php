<?php
require '../db_connect.php';

// Get all volunteers with their user information
$volunteers = $conn->query("
    SELECT 
        v.*, 
        u.full_name, 
        u.email,
        u.created_at as join_date
    FROM Volunteers v
    JOIN Users u ON v.user_id = u.user_id
    ORDER BY u.created_at DESC
")->fetchAll();
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Registered Volunteers</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="volunteersTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Skills</th>
                        <th>Experience</th>
                        <th>Availability</th>
                        <th>Status</th>
                        <th>Join Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($volunteers as $volunteer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($volunteer['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($volunteer['email']); ?></td>
                            <td><?php echo htmlspecialchars($volunteer['skills']); ?></td>
                            <td><?php echo ucfirst($volunteer['experience_level']); ?></td>
                            <td><?php echo str_replace('_', ' ', ucfirst($volunteer['availability'])); ?></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo match($volunteer['status']) {
                                        'active' => 'success',
                                        'inactive' => 'danger',
                                        'on_duty' => 'primary'
                                    };
                                ?>">
                                    <?php echo ucfirst($volunteer['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($volunteer['join_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#volunteersTable').DataTable({
        order: [[6, 'desc']], // Sort by join date by default
        pageLength: 25
    });
});
</script> 