<?php
include 'header.php'; // Include the common header
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Health Support - Disaster Recovery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            font-family: 'Arial', sans-serif;
        }
        .support-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        .support-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .support-description {
            text-align: center;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .list-group-item {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            color: #333;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .btn-contact, .btn-book {
            background: #ff9800;
            border: none;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            color: white;
            border-radius: 10px;
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }
        .btn-contact:hover, .btn-book:hover {
            background: #e68900;
        }
        .modal-content {
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="support-container">
        <h2 class="support-title">Mental Health Support</h2>
        <p class="support-description">Connect with experts who can help you navigate emotional challenges after a disaster.</p>

        <ul class="list-group">
            <li class="list-group-item"><strong>Dr. Aditi Verma</strong> - Clinical Psychologist (Mumbai) <br> 📧 aditi.verma@mentalcare.com <br> 📞 +91 98765 43210</li>
            <li class="list-group-item"><strong>Dr. Rajesh Iyer</strong> - Psychiatrist (New Delhi) <br> 📧 rajesh.iyer@mindhealing.org <br> 📞 +91 98234 56789</li>
            <li class="list-group-item"><strong>Dr. Neha Kapoor</strong> - Psychotherapist (Bengaluru) <br> 📧 neha.kapoor@mentalwellness.in <br> 📞 +91 99876 54321</li>
            <li class="list-group-item"><strong>Dr. Sameer Joshi</strong> - Psychiatric Social Worker (Pune) <br> 📧 sameer.joshi@resilienceminds.org <br> 📞 +91 97654 32109</li>
            <li class="list-group-item"><strong>Dr. Priyanka Sharma</strong> - Trauma Specialist (Kolkata) <br> 📧 priyanka.sharma@healinghands.com <br> 📞 +91 98987 65432</li>
        </ul>
        <button class="btn btn-contact mb-3" data-bs-toggle="modal" data-bs-target="#bookingModal">Book an Appointment</button>
        <h3 class="support-title mt-4">Upcoming Conferences</h3>
        <ul class="list-group">
            <li class="list-group-item"><strong>ANCIPS 2025</strong> - National Psychiatry Conference (Hyderabad) <br> 📅 Date: To be announced</li>
            <li class="list-group-item"><strong>World Congress on Women's Mental Health</strong> - (Bangalore) <br> 📅 March 5-8, 2025</li>
        </ul>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Book an Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="doctor" class="form-label">Preferred Doctor</label>
                        <select class="form-control" id="doctor" required>
                            <option value="Dr. Aditi Verma">Dr. Aditi Verma</option>
                            <option value="Dr. Rajesh Iyer">Dr. Rajesh Iyer</option>
                            <option value="Dr. Neha Kapoor">Dr. Neha Kapoor</option>
                            <option value="Dr. Sameer Joshi">Dr. Sameer Joshi</option>
                            <option value="Dr. Priyanka Sharma">Dr. Priyanka Sharma</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-contact w-100">Confirm Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("bookingForm").addEventListener("submit", function(event) {
        event.preventDefault();
        alert("Your booking has been confirmed!");
        window.location.href = 'home.php';
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
