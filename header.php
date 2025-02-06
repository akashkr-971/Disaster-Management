<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renew Hope - Disaster Recovery & Rehabilitation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="theme.css">
</head>
<body>
    <?php
    // Start session at the very beginning of the file
    if(!isset($_SESSION)) {
        session_start();
    }
    ?>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="height: 65px;">
        <div class="container">
            <a class="navbar-brand" href="home.php">Renew Hope</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home.php#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="report_missing.php" style="margin:0px;font-weight:600;">Report Missing Person</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="home.php#contact">Contact</a>
                    </li>
                    <?php if(!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-4" href="login.php">Login</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item text-white" href="logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add this style to make sure sections are properly scrolled to considering fixed navbar -->
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        /* Offset scroll position for fixed navbar */
        :target {
            scroll-margin-top: 70px;
        }

        /* Style for the Report Missing Person link */
        .navbar-nav .text-danger {
            font-weight: 500;
        }
        
        .navbar-nav .text-danger:hover {
            color: #dc3545 !important;
        }

        /* Dropdown styling to match navbar */
        .dropdown-menu {
            background-color: #212529;
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 10px;
            padding: 8px 0;
        }

        .dropdown-item {
            color: #fff !important;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #2c3237;
            color: white;
            padding-left: 25px;
        }

        .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Add a subtle arrow to the dropdown */
        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -8px;
            right: 20px;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid #212529;
        }
    </style>
</body>
</html>
