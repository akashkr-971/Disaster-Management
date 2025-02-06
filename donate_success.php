<?php
// Get donor's name from the URL parameter
$donor_name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Generous Donor';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Successful</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/confetti-js/0.0.18/confetti.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #00b894, #0984e3);
            color: #fff;
            font-family: 'Arial', sans-serif;
            text-align: center;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            position: relative;
            z-index: 2;
        }
        .success-card {
            background: rgba(255, 255, 255, 0.15);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 90%;
            max-width: 500px;
            animation: fadeIn 1.2s ease-in-out;
        }
        .success-icon {
            font-size: 80px;
            color: #ffcc00;
            animation: popUp 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes popUp {
            0% { transform: scale(0); opacity: 0; }
            70% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }
        .btn-home {
            background: #ffcc00;
            color: #000;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-home:hover {
            background: #f1c40f;
        }
        /* Full-screen confetti canvas */
        canvas#confetti-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
    </style>
</head>
<body>

<canvas id="confetti-canvas"></canvas>

<div class="success-container">
    <div class="success-card">
        <div class="success-icon">🎉</div>
        <h2>Thank You, <?php echo $donor_name; ?>!</h2>
        <p>Your generous donation has been successfully received. Your support is making a real difference in disaster recovery.</p>
        <a href="home.php" class="btn btn-home">Return to Home</a>
    </div>
</div>

<script>
    // Initialize Confetti Animation
    var confettiSettings = { target: 'confetti-canvas', max: 300, size: 1.5, animate: true };
    var confetti = new ConfettiGenerator(confettiSettings);
    confetti.render();
</script>

</body>
</html>
