<?php
require 'header.php';
?>
<!-- Add W3.CSS stylesheet link -->
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        console.log("Geolocation is not supported by this browser.");
    }
}

function showPosition(position) {
    // You can use these coordinates to auto-fill location fields if needed
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;
    console.log("Latitude: " + latitude + ", Longitude: " + longitude);
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            console.log("User denied the request for Geolocation.");
            break;
        case error.POSITION_UNAVAILABLE:
            console.log("Location information is unavailable.");
            break;
        case error.TIMEOUT:
            console.log("The request to get user location timed out.");
            break;
        case error.UNKNOWN_ERROR:
            console.log("An unknown error occurred.");
            break;
    }
}

// Call getLocation when the page loads
getLocation();
</script>
<style>
    /* Custom styles */
    .input-box {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
    }

    input[type=text].w3-input,
    input[type=radio].w3-input,
    input[type=file].w3-input,
    textarea.w3-input {
        margin-bottom: 20px;
    }

    .w3-container.w3-pale-green {
        margin: 20px 0;
        padding: 15px;
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {
        .input-box {
            margin: 10px;
            padding: 15px;
        }
    }

    /* Header styling */
    .m-btm {
        margin-bottom: 20px;
    }

    /* Form validation styles */
    .error-message {
        color: red;
        font-size: 0.9em;
        margin-top: 5px;
    }

    /* Button styling */
    .w3-btn {
        margin-right: 10px;
    }
</style>
</head>
<body>

<p><?php if (!empty($_GET['status'])) {
    echo $_GET['status'];
}
?></p>
<div class="w3-container">
    <div class="input-box w3-card-2">
        <header>
            <h2 style="color: #dc3545;text-align: center;">Missing Person Form</h2>
        </header>
        <hr>
        <form action="process.php" method="post" id="ajaxForm" enctype="multipart/form-data">

            <!-- Other form fields -->
            <label>State</label>
            <input type="text" class="w3-input w3-border" name="state" id="state">
            <label>Missing person's name</label>
            <input type="text" class="w3-input w3-border" name="name" id="name">
            <!-- Other form fields -->
            
            <!-- Missing Person's Photo Upload -->
            <label for="photo">Missing person's photo</label>
            <input type="file" class="w3-input w3-border" name="photo" id="photo" accept="image/*">
            
            <!-- Missing Date Section -->
            Missing date
            <div class="w3-row">
                <div class="w3-col m4 l4 s4">
                    <input type="number" class="w3-input w3-border" name="day" id="day" placeholder="dd">
                </div>
                <div class="w3-col m4 l4 s4">
                    <input type="number" class="w3-input w3-border" name="month" id="month" placeholder="mm">
                </div>
                <div class="w3-col m4 l4 s4">
                    <input type="number" class="w3-input w3-border" name="year" id="year" placeholder="yyyy">
                </div>
            </div>
            <p id="date-error-message" style="color: red; margin-top: 10px;"></p>
            
            <!-- Other form fields -->
            <label for="address">Missing person's address</label>
            <textarea class="w3-input w3-border" name="address" id="address"></textarea>
            <label for="reportedBy">Reported by (Your name)</label>
            <input type="text" class="w3-input w3-border" name="reportedBy" id="reportedBy">
            <label for="reportersNumber">Reporter's (Your) phone number</label>
            <input type="text" class="w3-input w3-border" name="reportersNumber" id="reportersNumber">
            <label for="policeStation">Nearest Police station</label>
            <input type="text" class="w3-input w3-border" name="policeStation" id="policeStation">

            <div class="w3-container w3-pale-green w3-leftbar w3-border-green">
                <p>We will send a link to these numbers in few days if duplicate entries are found, to avoid duplication. Stay connected.</p>
            </div>

            <br><br>
            <input type="submit" class="w3-btn w3-blue" value="Submit">
            <input type="reset" class="w3-btn w3-red" value="Reset">
        </form>
    </div>
</div>

<script>
// Function to validate the missing date section
function validateMissingDate() {
    var day = document.getElementById("day").value;
    var month = document.getElementById("month").value;
    var year = document.getElementById("year").value;
    var errorMessage = document.getElementById("date-error-message");

    // Clear error message initially
    errorMessage.textContent = "";

    // Validate day
    if (day.length > 0) {
        var dayNum = parseInt(day, 10);
        if (isNaN(dayNum) || dayNum < 1 || dayNum > 31) {
            errorMessage.textContent = "Day must be between 1 and 31.";
            return false;
        }
    }

    // Validate month
    if (month.length > 0) {
        var monthNum = parseInt(month, 10);
        if (isNaN(monthNum) || monthNum < 1 || monthNum > 12) {
            errorMessage.textContent = "Month must be between 1 and 12.";
            return false;
        }
    }

    // Validate year
    if (year.length > 0) {
        var yearNum = parseInt(year, 10);
        var currentYear = new Date().getFullYear();
        if (isNaN(yearNum) || yearNum < 1900 || yearNum > currentYear) {
            errorMessage.textContent = "Year must be between 1900 and " + currentYear + ".";
            return false;
        }
    }

    // Validate day and month together (e.g., February doesn't have 30 days)
    if (day.length > 0 && month.length > 0 && year.length > 0) {
        var daysInMonth = new Date(year, month, 0).getDate();
        if (day > daysInMonth) {
            errorMessage.textContent = "Invalid day for the given month.";
            return false;
        }
    }

    // If all validations pass
    return true;
}

// Add event listeners for real-time validation
document.getElementById("day").addEventListener("input", validateMissingDate);
document.getElementById("month").addEventListener("input", validateMissingDate);
document.getElementById("year").addEventListener("input", validateMissingDate);

// Add this after the existing JavaScript code
document.getElementById('ajaxForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!validateMissingDate()) {
        return false;
    }

    let formData = new FormData(this);
    
    fetch('process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Missing person reported successfully');
            window.location.href = 'home.php'; // Redirect to home page
        } else {
            alert('Error reporting missing person. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error reporting missing person. Please try again.');
    });
});
</script>

<?php
require 'footer.php';
?>