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
                                    require '../db_connect.php';
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
