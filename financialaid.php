<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';

// Verify database connection
try {
    $conn->query('SELECT 1');
} catch(PDOException $e) {
    $_SESSION['error'] = "Database connection error. Please try again later.";
    error_log("Database connection failed: " . $e->getMessage());
}

// Process form submission
if (isset($_POST['submit_aid'])) {
    try {
        $conn->beginTransaction();

        // Insert into main FinancialAidRequests table
        $stmt = $conn->prepare("INSERT INTO FinancialAidRequests (
            user_id, request_type, full_name, email, phone, amount, purpose, supporting_documents
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $_SESSION['user_id'],
            $_POST['request_type'],
            $_POST['full_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['amount'],
            $_POST['purpose'],
            $_FILES['supporting_documents']['name'] ?? null
        ]);

        $request_id = $conn->lastInsertId();

        // Insert into specific request type table
        if ($_POST['request_type'] == 'loan') {
            $stmt = $conn->prepare("INSERT INTO LoanRequests (
                request_id, monthly_income, employment_status, loan_duration
            ) VALUES (?, ?, ?, ?)");
            
            $stmt->execute([
                $request_id,
                $_POST['monthly_income'],
                $_POST['employment_status'],
                $_POST['loan_duration']
            ]);
        } else {
            $stmt = $conn->prepare("INSERT INTO InsuranceRequests (
                request_id, insurance_type, policy_number, incident_date, incident_description
            ) VALUES (?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $request_id,
                $_POST['insurance_type'],
                $_POST['policy_number'],
                $_POST['incident_date'],
                $_POST['incident_description']
            ]);
        }

        // Handle file upload
        if (isset($_FILES['supporting_documents']) && $_FILES['supporting_documents']['error'] == 0) {
            $upload_dir = "uploads/";
            move_uploaded_file($_FILES['supporting_documents']['tmp_name'], 
                             $upload_dir . $_FILES['supporting_documents']['name']);
        }

        $conn->commit();
        $_SESSION['success'] = "Your financial aid request has been submitted successfully!";
        header("Location: financialaid.php");
        exit();

    } catch(PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: financialaid.php");
        exit();
    }
}

// Include header after all redirects
require 'header.php';
?>

<!-- Custom CSS -->
<style>
.financial-aid-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 80px 0;
}

.aid-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.aid-card .card-header {
    background: linear-gradient(45deg, #1a237e, #0d47a1);
    border-radius: 15px 15px 0 0;
    padding: 20px;
}

.aid-card .card-header h3 {
    font-weight: 600;
    letter-spacing: 0.5px;
}

.nav-tabs {
    border-bottom: 2px solid #dee2e6;
    margin-bottom: 30px;
}

.nav-tabs .nav-link {
    border: none;
    color: #495057;
    font-weight: 500;
    padding: 12px 25px;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link.active {
    color: #0d47a1;
    border-bottom: 3px solid #0d47a1;
    background: transparent;
}

.form-label {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 8px;
}

.form-control, .form-select {
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ced4da;
    transition: all 0.3s ease;
    margin: 0 !important;
    height: 45px;
}

.form-control:focus, .form-select:focus {
    border-color: #0d47a1;
    box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.25);
}

.input-group {
    margin: 0 !important;
}

.input-group-text {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 8px 0 0 8px;
    margin: 0 !important;
    padding: 0 15px;
    height: 45px;
    display: flex;
    align-items: center;
}

.input-group > .form-control {
    border-left: none;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

textarea.form-control {
    height: auto;
}

/* Fix icon sizes */
.input-group-text i {
    font-size: 1.2rem;
    width: 20px;
    text-align: center;
}

/* Ensure consistent spacing between form groups */
.form-section .mb-3 {
    margin-bottom: 1.5rem !important;
}

/* Remove margin from the last form group in each section */
.form-section .mb-3:last-child {
    margin-bottom: 0 !important;
}

/* Adjust file input height */
input[type="file"].form-control {
    padding: 8px 12px;
}

.btn-submit {
    background: linear-gradient(45deg, #1a237e, #0d47a1);
    border: none;
    padding: 12px 30px;
    font-weight: 500;
    letter-spacing: 0.5px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(13, 71, 161, 0.3);
}

.form-section {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    border: 1px solid #eee;
}

.section-title {
    color: #0d47a1;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
}
</style>

<div class="financial-aid-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="aid-card card">
                    <div class="card-header">
                        <h3 class="text-white mb-0">Financial Aid Application</h3>
                        <p class="text-white-50 mb-0 mt-2">Please fill in the details to submit your application</p>
                    </div>
                    <div class="card-body p-4">
                        <?php
                        if (isset($_SESSION['error'])) {
                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ' . $_SESSION['error'] . '
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
                            unset($_SESSION['error']);
                        }
                        if (isset($_SESSION['success'])) {
                            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ' . $_SESSION['success'] . '
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
                            unset($_SESSION['success']);
                        }
                        ?>

                        <ul class="nav nav-tabs" id="aidTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="loan-tab" data-bs-toggle="tab" href="#loan" role="tab">
                                    <i class="bi bi-cash-coin me-2"></i>Interest-Free Loan
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="insurance-tab" data-bs-toggle="tab" href="#insurance" role="tab">
                                    <i class="bi bi-shield-check me-2"></i>Insurance Claim
                                </a>
                            </li>
                        </ul>

                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="request_type" value="loan" id="request_type">
                            
                            <!-- Personal Information Section -->
                            <div class="form-section">
                                <h4 class="section-title">Personal Information</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                                            <input type="text" class="form-control" name="full_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                            <input type="tel" class="form-control" name="phone" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Amount Required (₹)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">₹</span>
                                            <input type="number" class="form-control" name="amount" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content" id="aidTabContent">
                                <!-- Loan Fields -->
                                <div class="tab-pane fade show active" id="loan" role="tabpanel">
                                    <div class="form-section">
                                        <h4 class="section-title">Loan Details</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Monthly Income (₹)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">₹</span>
                                                    <input type="number" class="form-control loan-field" name="monthly_income">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Employment Status</label>
                                                <select class="form-select loan-field" name="employment_status">
                                                    <option value="">Select Status</option>
                                                    <option value="employed">Employed</option>
                                                    <option value="self-employed">Self-Employed</option>
                                                    <option value="unemployed">Unemployed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Loan Duration (months)</label>
                                            <input type="number" class="form-control loan-field" name="loan_duration">
                                        </div>
                                    </div>
                                </div>

                                <!-- Insurance Fields -->
                                <div class="tab-pane fade" id="insurance" role="tabpanel">
                                    <div class="form-section">
                                        <h4 class="section-title">Insurance Details</h4>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Insurance Type</label>
                                                <select class="form-select insurance-field" name="insurance_type">
                                                    <option value="">Select Type</option>
                                                    <option value="life">Life Insurance</option>
                                                    <option value="health">Health Insurance</option>
                                                    <option value="property">Property Insurance</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Policy Number</label>
                                                <input type="text" class="form-control insurance-field" name="policy_number">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Incident Date</label>
                                            <input type="date" class="form-control insurance-field" name="incident_date">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Incident Description</label>
                                            <textarea class="form-control insurance-field" name="incident_description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information Section -->
                            <div class="form-section">
                                <h4 class="section-title">Additional Information</h4>
                                <div class="mb-3">
                                    <label class="form-label">Purpose/Reason</label>
                                    <textarea class="form-control" name="purpose" rows="3" required 
                                              placeholder="Please explain why you need this financial assistance..."></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Supporting Documents</label>
                                    <input type="file" class="form-control" name="supporting_documents">
                                    <div class="form-text text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Upload relevant documents (PDF, JPG, PNG) - Max size 5MB
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" name="submit_aid" class="btn btn-primary btn-submit">
                                    <i class="bi bi-send me-2"></i>Submit Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loanTab = document.getElementById('loan-tab');
    const insuranceTab = document.getElementById('insurance-tab');
    const requestType = document.getElementById('request_type');
    const loanFields = document.querySelectorAll('.loan-field');
    const insuranceFields = document.querySelectorAll('.insurance-field');
    const loanPane = document.getElementById('loan');
    const insurancePane = document.getElementById('insurance');

    function toggleFields(showLoan) {
        // Toggle visibility of panes
        if (showLoan) {
            loanPane.classList.add('show', 'active');
            insurancePane.classList.remove('show', 'active');
            loanTab.classList.add('active');
            insuranceTab.classList.remove('active');
        } else {
            insurancePane.classList.add('show', 'active');
            loanPane.classList.remove('show', 'active');
            insuranceTab.classList.add('active');
            loanTab.classList.remove('active');
        }

        // Toggle required fields
        loanFields.forEach(field => {
            field.required = showLoan;
            field.disabled = !showLoan;
        });

        insuranceFields.forEach(field => {
            field.required = !showLoan;
            field.disabled = showLoan;
        });

        requestType.value = showLoan ? 'loan' : 'insurance';
    }

    // Add click event listeners
    loanTab.addEventListener('click', (e) => {
        e.preventDefault();
        toggleFields(true);
    });
    
    insuranceTab.addEventListener('click', (e) => {
        e.preventDefault();
        toggleFields(false);
    });

    // Initialize fields
    toggleFields(true);
});
</script>