<?php
require 'header.php';
?>
<html>
    <body>
        <div style="margin-top: 80px;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center mb-4">Missing Persons List</h2>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>State</th>
                                        <th>Missing Date</th>
                                        <th>Address</th>
                                        <th>Reported By</th>
                                        <th>Contact Number</th>
                                        <th>Police Station</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require 'db_connect.php';
                                    try {
                                        $stmt = $conn->query("SELECT * FROM missing_persons ORDER BY created_at DESC");
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td>" . ($row['photo'] ? "<img src='" . htmlspecialchars($row['photo']) . "' class='missing-person-photo' alt='Missing Person'>" : "No Photo") . "</td>";
                                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['state']) . "</td>";
                                            // Format the date nicely
                                            $missing_date = !empty($row['missing_date']) ? date('d-m-Y', strtotime($row['missing_date'])) : 'Not specified';
                                            echo "<td>" . htmlspecialchars($missing_date) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['reported_by']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['reporters_number']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['police_station']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } catch(PDOException $e) {
                                        echo "<tr><td colspan='8' class='text-danger'>Error fetching data: " . $e->getMessage() . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="bg-dark text-white py-5" style="position:absolute;bottom:0px;width:100%;">
            <div class="container" >
                <div class="row" >
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5 class="mb-3">Renew Hope</h5>
                        <p class="text-muted mb-3">Empowering communities through disaster recovery and rehabilitation. We provide immediate assistance and long-term support to those affected by disasters.</p>
                        <div class="social-links">
                            <a href="#" class="me-3"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="me-3"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="me-3"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="me-3"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-md-2 mb-4 mb-md-0">
                        <h5 class="mb-3">Quick Links</h5>
                        <ul class="list-unstyled footer-links">
                            <li><a href="home.php#about">About Us</a></li>
                            <li><a href="home.php#services">Our Services</a></li>
                            <li><a href="home.php#contact">Contact Us</a></li>
                            <li><a href="login.php">Login</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h5 class="mb-3">Services</h5>
                        <ul class="list-unstyled footer-links">
                            <li><a href="missingpersonform.php">Report Missing Person</a></li>
                            <li><a href="aid.php">Apply for Aid</a></li>
                            <li><a href="volunteer.php">Volunteer</a></li>
                            <li><a href="donate.php">Make a Donation</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-md-3">
                        <h5 class="mb-3">Contact Info</h5>
                        <ul class="list-unstyled footer-contact">
                            <li class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i>123 Disaster Response Street, City, Country</li>
                            <li class="mb-2"><i class="bi bi-telephone-fill me-2"></i>Emergency: +1 234 567 890</li>
                            <li class="mb-2"><i class="bi bi-envelope-fill me-2"></i>help@renewhope.com</li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-top border-secondary pt-4 mt-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            <p class="mb-0">&copy; 2025 Renew Hope. All Rights Reserved.</p>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
                                <li class="list-inline-item">·</li>
                                <li class="list-inline-item"><a href="#">Terms of Use</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <style>
        .missing-person-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .table {
            margin-top: 20px;
            background-color: white;
        }

        .table th {
            vertical-align: middle;
        }

        .table td {
            vertical-align: middle;
        }

        /* Add responsive styles */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 14px;
            }
            
            .missing-person-photo {
                width: 40px;
                height: 40px;
            }
        }
        </style>

    </body>
</html>
<!-- Add some spacing after the navbar -->
