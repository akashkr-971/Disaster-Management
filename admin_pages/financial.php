<?php
require '../db_connect.php';

// Handle status updates if any
if (isset($_POST['update_status'])) {
    try {
        $stmt = $conn->prepare("UPDATE FinancialAidRequests SET status = ? WHERE request_id = ?");
        $stmt->execute([$_POST['status'], $_POST['request_id']]);
        echo "<div class='alert alert-success'>Status updated successfully!</div>";
    } catch(PDOException $e) {
        echo "<div class='alert alert-danger'>Error updating status: " . $e->getMessage() . "</div>";
    }
}

// Get all financial aid requests with their details
$requests = $conn->query("
    SELECT 
        f.*, 
        l.monthly_income, l.employment_status, l.loan_duration,
        i.insurance_type, i.policy_number, i.incident_date
    FROM FinancialAidRequests f
    LEFT JOIN LoanRequests l ON f.request_id = l.request_id
    LEFT JOIN InsuranceRequests i ON f.request_id = i.request_id
    ORDER BY f.created_at DESC
")->fetchAll();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Financial Aid Requests</h5>
        <div class="btn-group">
            <button class="btn btn-sm btn-outline-primary" onclick="exportToExcel()">
                <i class="bi bi-download me-1"></i>Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="financialTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?php echo $request['request_id']; ?></td>
                            <td><?php echo ucfirst($request['request_type']); ?></td>
                            <td><?php echo htmlspecialchars($request['full_name']); ?></td>
                            <td>₹<?php echo number_format($request['amount'], 2); ?></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo match($request['status']) {
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                    };
                                ?>">
                                    <?php echo ucfirst($request['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($request['created_at'])); ?></td>
                            <td>
                                <button class='btn btn-sm btn-success' onclick="updatevolenteerStatus(<?php echo $request['request_id']; ?>, 'approved')">
                                    <i class='bi bi-check-circle'></i>
                                </button>
                                <button class='btn btn-sm btn-danger' onclick="updatevolenteerStatus(<?php echo $request['request_id']; ?>, 'rejected')">
                                    <i class='bi bi-x-circle'></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>
</div>



<script>

function showDetails(request) {
    let content = `
        <div class="row">
            <div class="col-md-6">
                <h6>Personal Information</h6>
                <p><strong>Name:</strong> ${request.full_name}</p>
                <p><strong>Email:</strong> ${request.email}</p>
                <p><strong>Phone:</strong> ${request.phone}</p>
            </div>
            <div class="col-md-6">
                <h6>Request Information</h6>
                <p><strong>Type:</strong> ${request.request_type}</p>
                <p><strong>Amount:</strong> ₹${request.amount}</p>
                <p><strong>Purpose:</strong> ${request.purpose}</p>
            </div>
        </div>`;

    if (request.request_type === 'loan') {
        content += `
            <div class="mt-3">
                <h6>Loan Details</h6>
                <p><strong>Monthly Income:</strong> ₹${request.monthly_income}</p>
                <p><strong>Employment Status:</strong> ${request.employment_status}</p>
                <p><strong>Loan Duration:</strong> ${request.loan_duration} months</p>
            </div>`;
    } else {
        content += `
            <div class="mt-3">
                <h6>Insurance Details</h6>
                <p><strong>Insurance Type:</strong> ${request.insurance_type}</p>
                <p><strong>Policy Number:</strong> ${request.policy_number}</p>
                <p><strong>Incident Date:</strong> ${request.incident_date}</p>
            </div>`;
    }

    document.getElementById('modalContent').innerHTML = content;
    new bootstrap.Modal(document.getElementById('detailsModal')).show();
}

function updateStatus(select, requestId) {
    if (!select.value) return;
    
    fetch('admin_pages/update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `request_id=${requestId}&status=${select.value}`
    })
    .then(response => response.text())
    .then(() => window.location.reload())
    .catch(error => console.error('Error:', error));
}

function exportToExcel() {
    // Implement export functionality
    alert('Export functionality will be implemented here');
}

// Initialize DataTable
$(document).ready(function() {
    $('#financialTable').DataTable({
        order: [[6, 'desc']], // Sort by date by default
        pageLength: 25
    });
});
</script> 