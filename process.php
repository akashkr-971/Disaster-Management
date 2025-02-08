<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Handle file upload
        $photo_path = '';
        if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $photo_path = $target_dir . time() . '_' . basename($_FILES["photo"]["name"]);
            move_uploaded_file($_FILES["photo"]["tmp_name"], $photo_path);
        }

        // Get the date components from POST
        $day = isset($_POST['day']) ? $_POST['day'] : '';
        $month = isset($_POST['month']) ? $_POST['month'] : '';
        $year = isset($_POST['year']) ? $_POST['year'] : '';

        // Construct missing date
        $missing_date = null;
        if (!empty($year) && !empty($month) && !empty($day)) {
            $missing_date = sprintf('%04d-%02d-%02d', 
                intval($year), 
                intval($month), 
                intval($day)
            );
        }

        // Prepare SQL statement
        $sql = "INSERT INTO missing_persons (state, name, photo, missing_date, address, 
                reported_by, reporters_number, police_station) 
                VALUES (:state, :name, :photo, :missing_date, :address, 
                :reported_by, :reporters_number, :police_station)";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':state', $_POST['state']);
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':photo', $photo_path);
        $stmt->bindParam(':missing_date', $missing_date);
        $stmt->bindParam(':address', $_POST['address']);
        $stmt->bindParam(':reported_by', $_POST['reportedBy']);
        $stmt->bindParam(':reporters_number', $_POST['reportersNumber']);
        $stmt->bindParam(':police_station', $_POST['policeStation']);
        
        // Execute the statement
        $stmt->execute();
        
        // Send success response
        echo json_encode(['status' => 'success', 'message' => 'Missing person reported successfully']);
    } catch(PDOException $e) {
        // Send error response
        echo json_encode(['status' => 'error', 'message' => 'Error reporting missing person']);
    }
}
?> 