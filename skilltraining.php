<?php
require 'header.php';
require 'db_connect.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Please login to register for skill training";
        header("Location: login.php");
        exit();
    }
    
    try {
        $stmt = $conn->prepare("INSERT INTO SkillTraining (user_id, full_name, email, phone, skill_type, experience_level, preferred_time, comments) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $_SESSION['user_id'],
            $_POST['full_name'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['skill_type'],
            $_POST['experience_level'],
            $_POST['preferred_time'],
            $_POST['comments']
        ]);

        $_SESSION['success'] = "Your skill training registration has been submitted successfully!";
        header("Location: skilltraining.php");
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

// Show success/error messages if any
if (isset($_SESSION['success']) || isset($_SESSION['error'])) {
    echo '<div class="container mt-5 pt-4">';
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Disaster Response Skill Training</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .skill-card {
            transition: transform 0.2s;
            height: 100%;
            cursor: pointer;
        }

        .skill-card:hover {
            transform: translateY(-5px);
        }

        .skill-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .logo-small {
            width: 50px;
            height: 50px;
            margin-bottom: 1rem;
        }

        .modal-backdrop {
            background-color: rgba(0,0,0,0.5);
        }

        /* Add responsive styles */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .card-img-top {
                height: 150px; /* Smaller images on mobile */
            }

            .skill-card {
                margin-bottom: 15px;
            }

            h1 {
                font-size: 1.8rem;
            }

            .lead {
                font-size: 1rem;
            }

            .modal-dialog {
                margin: 10px;
            }
        }

        /* Tablet adjustments */
        @media (min-width: 769px) and (max-width: 1024px) {
            .card-img-top {
                height: 175px;
            }
        }

        /* Grid adjustments */
        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 1.5rem;
        }

        /* Card content adjustments */
        .card-body {
            padding: 1.25rem;
        }

        .card-title {
            font-size: calc(1.1rem + 0.3vw);
            margin-bottom: 0.75rem;
        }

        .card-text {
            font-size: calc(0.875rem + 0.1vw);
        }

        /* Modal responsive adjustments */
        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Form responsive adjustments */
        .form-control, .form-select {
            font-size: calc(0.875rem + 0.1vw);
            padding: 0.5rem;
        }

        /* Button adjustments */
        .btn {
            padding: 0.5rem 1rem;
            font-size: calc(0.875rem + 0.1vw);
        }

        /* Improved spacing for mobile */
        .mb-5 {
            margin-bottom: 3rem !important;
        }

        @media (max-width: 576px) {
            .mb-5 {
                margin-bottom: 2rem !important;
            }

            .modal-body {
                padding: 1rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .text-end {
                text-align: center !important;
            }

            .modal-dialog {
                margin: 0.5rem;
            }
        }

        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .btn-primary {
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-3 py-md-5">
        <div class="text-center mb-5">
            <h1>Disaster Response Training Programs</h1>
            <p class="lead text-muted">Professional training for emergency response and disaster management</p>
        </div>
        
        <div class="row g-3 g-md-4">
            <!-- First Aid Training -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="skill-card card h-100 shadow-sm" onclick="openModal('first-aid')">
                    <img src="static/skilltrain/first-aid.jpg" class="card-img-top" alt="First Aid Training">
                    <div class="card-body text-center">
                        <h5 class="card-title">First Aid Training</h5>
                        <p class="card-text text-muted">Learn essential first aid skills for emergency response including CPR, wound care, and basic medical assistance.</p>
                    </div>
                </div>
            </div>

            <!-- Search and Rescue -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="skill-card card h-100 shadow-sm" onclick="openModal('search-rescue')">
                    <img src="static/skilltrain/search-rescue.jpg" class="card-img-top" alt="Search and Rescue">
                    <div class="card-body text-center">
                        <h5 class="card-title">Search and Rescue</h5>
                        <p class="card-text text-muted">Training in search and rescue operations, including techniques for locating and extracting victims safely.</p>
                    </div>
                </div>
            </div>

            <!-- Emergency Communication -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="skill-card card h-100 shadow-sm" onclick="openModal('emergency-comm')">
                    <img src="static/skilltrain/emergency-comm.jpg" class="card-img-top" alt="Emergency Communication">
                    <div class="card-body text-center">
                        <h5 class="card-title">Emergency Communication</h5>
                        <p class="card-text text-muted">Learn to operate emergency communication systems and coordinate disaster response efforts.</p>
                    </div>
                </div>
            </div>

            <!-- Disaster Assessment -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="skill-card card h-100 shadow-sm" onclick="openModal('disaster-assessment')">
                    <img src="static/skilltrain/disaster-assessment.jpg" class="card-img-top" alt="Disaster Assessment">
                    <div class="card-body text-center">
                        <h5 class="card-title">Disaster Assessment</h5>
                        <p class="card-text text-muted">Training in evaluating disaster impacts and conducting needs assessments for effective response.</p>
                    </div>
                </div>
            </div>

            <!-- Emergency Shelter Management -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="skill-card card h-100 shadow-sm" onclick="openModal('shelter-management')">
                    <img src="static/skilltrain/shelter.jpg" class="card-img-top" alt="Emergency Shelter">
                    <div class="card-body text-center">
                        <h5 class="card-title">Emergency Shelter Management</h5>
                        <p class="card-text text-muted">Learn to set up and manage emergency shelters for displaced populations.</p>
                    </div>
                </div>
            </div>

            <!-- Water Safety -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="skill-card card h-100 shadow-sm" onclick="openModal('water-safety')">
                    <img src="static/skilltrain/water-safety.jpg" class="card-img-top" alt="Water Safety">
                    <div class="card-body text-center">
                        <h5 class="card-title">Water Safety</h5>
                        <p class="card-text text-muted">Training in water rescue operations and safety procedures during floods and water-related disasters.</p>
                    </div>
                </div>
            </div>

            <!-- NEW: Fire Safety -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="skill-card card h-100 shadow-sm" onclick="openModal('fire-safety')">
                    <img src="static/skilltrain/fire-safety.jpg" class="card-img-top" alt="Fire Safety">
                    <div class="card-body text-center">
                        <h5 class="card-title">Fire Safety & Response</h5>
                        <p class="card-text text-muted">Learn fire prevention, suppression techniques, and safe evacuation procedures.</p>
                    </div>
                </div>
            </div>

            <!-- NEW: Mental Health Support -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="skill-card card h-100 shadow-sm" onclick="openModal('mental-health')">
                    <img src="static/skilltrain/mental-health.jpg" class="card-img-top" alt="Mental Health">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mental Health Support</h5>
                        <p class="card-text text-muted">Training in psychological first aid and crisis counseling for disaster survivors.</p>
                    </div>
                </div>
            </div>

            <!-- NEW: Logistics Management -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="skill-card card h-100 shadow-sm" onclick="openModal('logistics')">
                    <img src="static/skilltrain/supply-chain.jpg" class="card-img-top" alt="Logistics">
                    <div class="card-body text-center">
                        <h5 class="card-title">Logistics Management</h5>
                        <p class="card-text text-muted">Learn to manage supply chains and resource distribution during emergencies.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Training Registration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <input type="hidden" id="trainingType" name="skill_type">
                        
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" 
                                   value="<?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Experience Level</label>
                            <select class="form-select" name="experience_level" required>
                                <option value="">Select level</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preferred Time</label>
                            <select class="form-select" name="preferred_time" required>
                                <option value="">Select time</option>
                                <option value="morning">Morning (9 AM - 12 PM)</option>
                                <option value="afternoon">Afternoon (2 PM - 5 PM)</option>
                                <option value="evening">Evening (6 PM - 9 PM)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Additional Comments</label>
                            <textarea class="form-control" name="comments" rows="3"></textarea>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
        require 'footer.php';
        ?>

    <!-- Bootstrap and other required scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let modal;
        
        document.addEventListener('DOMContentLoaded', function() {
            modal = new bootstrap.Modal(document.getElementById('registrationModal'));
        });

        function openModal(trainingType) {
            const titleMap = {
                'first-aid': 'First Aid Training',
                'search-rescue': 'Search and Rescue Training',
                'emergency-comm': 'Emergency Communication Training',
                'disaster-assessment': 'Disaster Assessment Training',
                'shelter-management': 'Emergency Shelter Management Training',
                'water-safety': 'Water Safety Training',
                'fire-safety': 'Fire Safety & Response Training',
                'mental-health': 'Mental Health Support Training',
                'logistics': 'Logistics Management Training',
            };
            
            <?php if(!isset($_SESSION['user_id'])): ?>
                // Set error message in session and redirect
                <?php $_SESSION['error'] = "Please login to register for training"; ?>
                window.location.href = 'login.php';
            <?php else: ?>
                document.getElementById('modalTitle').textContent = titleMap[trainingType];
                document.getElementById('trainingType').value = trainingType;
                modal.show();
            <?php endif; ?>
        }
    </script>
</body>
</html>
