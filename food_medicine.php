<?php require 'header.php';
require 'db_connect.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Prepare request type (combine selected checkboxes)
        $request_types = [];
        if (isset($_POST['foodRequest'])) $request_types[] = 'food';
        if (isset($_POST['medicineRequest'])) $request_types[] = 'medicine';
        $request_type = implode(',', $request_types);

        $stmt = $conn->prepare("
            INSERT INTO FoodMedicineRequests 
            (request_type, full_name, phone, priority_level, delivery_address, special_requirements) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $request_type,
            $_POST['full_name'],
            $_POST['phone'],
            $_POST['priority_level'],
            $_POST['delivery_address'],
            $_POST['special_requirements'] ?? null
        ]);

        echo "<div class='alert alert-success alert-dismissible fade show mt-5' role='alert'>
                Your request has been submitted successfully. Our team will contact you shortly.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } catch(PDOException $e) {
        echo "<div class='alert alert-danger alert-dismissible fade show mt-5' role='alert'>
                Error submitting request. Please try again or contact support.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food & Medicine Support - Renew Hope</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .form-section {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .emergency-contact {
            background-color: #f8d7da;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .resource-card {
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

        .resource-card:hover {
            transform: translateY(-5px);
        }

        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    
    <div class="container py-5">
        <!-- Emergency Contact Section -->
        <div class="emergency-contact text-center" style="margin-top: 50px;">
            <h3><i class="bi bi-exclamation-triangle-fill"></i> Emergency Contacts</h3>
            <p class="mb-2">Medical Emergency: <strong>911</strong></p>
            <p class="mb-0">24/7 Support Hotline: <strong>1-800-DISASTER</strong></p>
        </div>

        <!-- Request Form -->
        <div class="form-section">
            <h3 class="mb-4">Submit Support Request</h3>
            <form id="supportRequestForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Request Type *</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="foodRequest" value="food" id="foodRequest">
                            <label class="form-check-label" for="foodRequest">Food Support</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="medicineRequest" value="medicine" id="medicineRequest">
                            <label class="form-check-label" for="medicineRequest">Medicine Support</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Priority Level *</label>
                        <select class="form-select" name="priority_level" required>
                            <option value="">Select Priority</option>
                            <option value="urgent">Urgent (Within 24 hours)</option>
                            <option value="high">High (2-3 days)</option>
                            <option value="medium">Medium (4-7 days)</option>
                            <option value="low">Low (7+ days)</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" name="full_name" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Number *</label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Delivery Address *</label>
                        <textarea class="form-control" name="delivery_address" rows="3" required></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Special Requirements/Notes</label>
                        <textarea class="form-control" name="special_requirements" rows="3"></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Available Resources -->
        <h3 class="mb-4">Available Resources</h3>
        <div class="row g-4">
            <!-- Food Bank -->
            <div class="col-md-4">
                <div class="card resource-card">
                    <div class="card-body">
                        <span class="status-badge bg-success">Available</span>
                        <h5 class="card-title"><i class="bi bi-basket"></i> Food Bank</h5>
                        <p class="card-text">Emergency food supplies available for immediate assistance.</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle"></i> Non-perishable food items</li>
                            <li><i class="bi bi-check-circle"></i> Fresh produce (limited)</li>
                            <li><i class="bi bi-check-circle"></i> Baby food and formula</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Medical Supplies -->
            <div class="col-md-4">
                <div class="card resource-card">
                    <div class="card-body">
                        <span class="status-badge bg-warning">Limited</span>
                        <h5 class="card-title"><i class="bi bi-capsule"></i> Medical Supplies</h5>
                        <p class="card-text">Essential medical supplies and first aid kits.</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle"></i> First aid supplies</li>
                            <li><i class="bi bi-check-circle"></i> Basic medications</li>
                            <li><i class="bi bi-check-circle"></i> Medical equipment</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Special Dietary Needs -->
            <div class="col-md-4">
                <div class="card resource-card">
                    <div class="card-body">
                        <span class="status-badge bg-info">Available</span>
                        <h5 class="card-title"><i class="bi bi-shield-check"></i> Special Dietary Needs</h5>
                        <p class="card-text">Specialized food options for dietary restrictions.</p>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle"></i> Gluten-free options</li>
                            <li><i class="bi bi-check-circle"></i> Diabetic-friendly meals</li>
                            <li><i class="bi bi-check-circle"></i> Allergen-free foods</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('supportRequestForm').addEventListener('submit', function(e) {
            // Validate that at least one request type is selected
            if (!document.getElementById('foodRequest').checked && 
                !document.getElementById('medicineRequest').checked) {
                e.preventDefault();
                alert('Please select at least one request type (Food or Medicine)');
            }
        });
    </script>
</body>
</html>