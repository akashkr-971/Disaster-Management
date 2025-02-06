<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Volunteer Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/w3.css">
    <script src="js/main.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: grey;
        }
        .w3-container {
            width: 50%;
            margin: auto;
        }
        .input-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px black;
        }
        .btn2 {
            background-color: darkslategrey;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            font-size: 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn2:hover {
            background-color: black;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid black;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="w3-container">
        <div class="input-box w3-card-2">
            <header>
                <h4>Volunteer Registration</h4>
            </header>
            <hr>
            <form action="process.php" method="post">
                <input type="hidden" name="origin" value="volunteerForm">
                
                <label for="name">Name:</label>
                <input type="text" class="w3-input w3-border" name="name" id="name" placeholder="Enter your name" required>
                
                <label for="district">District:</label>
                <select class="w3-input w3-border" name="district" id="district" required>
                    <option value="select" selected>Select</option>
                    <option value="kozhikode">Kozhikode</option>
                    <option value="trivandrum">Trivandrum</option>
                    <option value="malappuram">Malappuram</option>
                    <option value="aalappuzha">Aalappuzha</option>
                    <option value="kannur">Kannur</option>
                    <option value="kottayam">Kottayam</option>
                    <option value="thrissur">Thrissur</option>
                    <option value="kasargod">Kasargod</option>
                    <option value="wayanad">Wayanad</option>
                    <option value="idukki">Idukki</option>
                    <option value="palakkad">Palakkad</option>
                    <option value="eranakulam">Eranakulam</option>
                    <option value="pathanamthitta">Pathanamthitta</option>
                    <option value="kollam">Kollam</option>
                </select>
                
                <label for="phone">Phone:</label>
                <input type="tel" class="w3-input w3-border" name="phone" id="phone" placeholder="Enter phone number" required>
                
            
                
                <label for="area">Area of Volunteering:</label>
                <input type="text" class="w3-input w3-border" name="area" id="area" placeholder="Enter area of volunteering">
                
                <label for="address">Address:</label>
                <textarea class="w3-input w3-border" name="address" id="address" rows="4" placeholder="Enter your address..."></textarea>
                
                <br>
                <input type="submit" class="w3-btn w3-blue" value="Submit">
                <input type="reset" class="w3-btn w3-red" value="Reset">
            </form>
        </div>
    </div>
</body>
</html>