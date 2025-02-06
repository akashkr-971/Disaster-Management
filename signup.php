<?php require 'header.php';
require 'db_connect.php';
session_start();

if(isset($_POST['signup-button'])) {
    try {
        $conn->beginTransaction();
        
        // Insert into Users table
        $stmt = $conn->prepare("INSERT INTO Users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['email'],
            $_POST['password'], // In production, use password_hash()
            $_POST['role']
        ]);
        
        $user_id = $conn->lastInsertId();
        
        // If role is volunteer, insert into Volunteers table
        if($_POST['role'] == 'volunteer') {
            $stmt = $conn->prepare("INSERT INTO Volunteers (user_id, skills, experience_level, availability, location) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $user_id,
                $_POST['skills'],
                $_POST['experience'],
                $_POST['availability'],
                $_POST['location']
            ]);
        }
        
        // If role is campaigner, insert into Campaigners table
        if($_POST['role'] == 'campaigner') {
            $stmt = $conn->prepare("INSERT INTO Campaigners (user_id, organization_name, organization_location, campaign_type, organization_description) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $user_id,
                $_POST['organization'],
                $_POST['organization_location'],
                $_POST['campaign_type'],
                $_POST['org_description']
            ]);
        }
        
        $conn->commit();
        $_SESSION['success'] = "Account created successfully! Please login.";
        header("Location: login.php");
        exit();
        
    } catch(PDOException $e) {
        $conn->rollBack();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}
?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-box">
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
                    }
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                        unset($_SESSION['success']);
                    }
                    ?>
                    <h2 class="text-center mb-4">Create Account</h2>
                    
                    <form id="signupForm" action="" method="POST" onsubmit="return validateForm()">
                        <!-- Basic Information -->
                        <div class="form-group mb-3">
                            <label class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text" style="margin-right: 10px;">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Email address</label>
                            <div class="input-group">
                                <span class="input-group-text" style="margin-right: 10px;">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text" style="margin-right: 10px;">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text" style="margin-right: 10px;">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="form-group mb-3">
                            <label class="form-label text-center w-100">Select Role</label>
                            <div class="d-flex justify-content-center gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" value="user" id="roleUser" checked onchange="toggleFields()">
                                    <label class="form-check-label" for="roleUser">User</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" value="volunteer" id="roleVolunteer" onchange="toggleFields()">
                                    <label class="form-check-label" for="roleVolunteer">Volunteer</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" value="campaigner" id="roleCampaigner" onchange="toggleFields()">
                                    <label class="form-check-label" for="roleCampaigner">Campaigner</label>
                                </div>
                            </div>
                            <hr class="my-4">
                        </div>

                        <!-- Volunteer Fields (Hidden by default) -->
                        <div id="volunteerFields" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Skills</label>
                                <select class="form-select" name="skills" required>
                                    <option value="">Select your primary skill</option>
                                    <option value="first_aid">First Aid</option>
                                    <option value="search_rescue">Search & Rescue</option>
                                    <option value="medical">Medical Professional</option>
                                    <option value="counseling">Counseling</option>
                                    <option value="logistics">Logistics</option>
                                    <option value="driver">Driver</option>
                                    <option value="communication">Communication</option>
                                    <option value="tech_support">Technical Support</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Experience Level</label>
                                <select class="form-select" name="experience" required>
                                    <option value="">Select your experience level</option>
                                    <option value="beginner">Beginner (0-1 years)</option>
                                    <option value="intermediate">Intermediate (1-3 years)</option>
                                    <option value="advanced">Advanced (3-5 years)</option>
                                    <option value="expert">Expert (5+ years)</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Availability</label>
                                <select class="form-select" name="availability" required>
                                    <option value="">Select your availability</option>
                                    <option value="full_time">Full Time</option>
                                    <option value="part_time">Part Time</option>
                                    <option value="weekends">Weekends Only</option>
                                    <option value="emergency">Emergency Only</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Location/Area</label>
                                <input type="text" class="form-control" name="location" placeholder="Enter your preferred working location" required>
                            </div>
                        </div>

                        <!-- Campaigner Fields (Hidden by default) -->
                        <div id="campaignerFields" style="display: none;">
                            <div class="form-group mb-3">
                                <label class="form-label">Organization Name</label>
                                <input type="text" class="form-control" name="organization">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Organization location</label>
                                <input type="text" class="form-control" name="organization_location">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Campaign Type</label>
                                <select class="form-select" name="campaign_type">
                                    <option value="fundraising">Fundraising</option>
                                    <option value="awareness">Awareness</option>
                                    <option value="relief">Relief Work</option>
                                    <option value="education">Education</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Organization Description</label>
                                <textarea class="form-control" name="org_description" rows="3"></textarea>
                            </div>
                        </div>

                        <button type="submit" name="signup-button" class="btn btn-primary w-100 mb-3">Create Account</button>
                        
                        <div class="text-center">
                            <span>Already have an account? </span>
                            <a href="login.php" class="text-primary text-decoration-none">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateForm() {
    var password = document.getElementsByName('password')[0].value;
    var confirm = document.getElementsByName('confirm_password')[0].value;
    
    if (password !== confirm) {
        alert("Passwords do not match!");
        return false;
    }
    
    if (password.length < 6) {
        alert("Password must be at least 6 characters long!");
        return false;
    }
    
    return true;
}

function toggleFields() {
    const volunteerFields = document.getElementById('volunteerFields');
    const campaignerFields = document.getElementById('campaignerFields');
    const roleVolunteer = document.getElementById('roleVolunteer');
    const roleCampaigner = document.getElementById('roleCampaigner');

    // Toggle Volunteer Fields
    volunteerFields.style.display = roleVolunteer.checked ? 'block' : 'none';
    
    // Toggle Campaigner Fields
    campaignerFields.style.display = roleCampaigner.checked ? 'block' : 'none';

    // Update required attributes
    const volunteerInputs = volunteerFields.querySelectorAll('select, input');
    const campaignerInputs = campaignerFields.querySelectorAll('select, input, textarea');

    volunteerInputs.forEach(input => {
        input.required = roleVolunteer.checked;
    });

    campaignerInputs.forEach(input => {
        input.required = roleCampaigner.checked;
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', toggleFields);
</script>