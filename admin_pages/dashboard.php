<?php
require '../db_connect.php';

// Get updated statistics
$stats = [
    'volunteers' => $conn->query("SELECT COUNT(*) FROM Volunteers")->fetchColumn(),
    'campaigners' => $conn->query("SELECT COUNT(*) FROM Campaigners")->fetchColumn(),
    'pending_aid' => $conn->query("SELECT COUNT(*) FROM FinancialAidRequests WHERE status='pending'")->fetchColumn(),
    'skill_training' => $conn->query("SELECT COUNT(*) FROM SkillTraining WHERE status='pending'")->fetchColumn(),
    'total_donations' => $conn->query("SELECT COALESCE(SUM(amount), 0) FROM Donations")->fetchColumn()
];

// Get verification requests
$pending_verifications = $conn->query("SELECT COUNT(*) FROM Campaigners WHERE verification_status='pending'")->fetchColumn();
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-4">
        <div class="stat-card card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Donations</h5>
                <h2>₹<?php echo number_format($stats['total_donations'], 2); ?></h2>
                <p class="mb-0"><small>Amount received</small></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Active Volunteers</h5>
                <h2><?php echo $stats['volunteers']; ?></h2>
                <p class="mb-0"><small>Registered volunteers</small></p>
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

<!-- Reviews Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Recent Reviews</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Date</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $reviews = $conn->query("SELECT name, email, phone, subject, message, submitted_at FROM contact_messages ORDER BY submitted_at DESC LIMIT 5");
                    while ($row = $reviews->fetch()) {
                        echo "<tr>
                                <td>{$row['name']}</td>

                                <td>{$row['email']}</td>
                                <td>{$row['phone']}</td>
                                <td>{$row['subject']}</td>
                                <td>{$row['message']}</td>
                                <td>" . date('Y-m-d H:i', strtotime($row['submitted_at'])) . "</td>
                              </tr>";

                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Donations History Section -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Donations History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Message</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $donations = $conn->query("SELECT name, email,  amount, donation_date, message FROM Donations ORDER BY donation_date DESC LIMIT 10");
                    while ($row = $donations->fetch()) {
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>₹" . number_format($row['amount'], 2) . "</td>
                                <td>" . date('Y-m-d H:i', strtotime($row['donation_date'])) . "</td>
                                <td>{$row['message']}</td>
                                </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
