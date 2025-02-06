<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require 'db_connect.php';

// Handle status updates
if (isset($_POST['update_status'])) {
    try {
        $stmt = $conn->prepare("UPDATE FinancialAidRequests SET status = ? WHERE request_id = ?");
        $stmt->execute([$_POST['status'], $_POST['request_id']]);
        $_SESSION['success'] = "Status updated successfully!";
        header("Location: admin.php");
        exit();
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error updating status: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Renew Hope</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="theme.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>

<!-- Admin Header -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php">
            <i class="bi bi-shield-lock me-2"></i>Admin Dashboard
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" id="logoutBtn">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .admin-wrapper {
        display: flex;
        min-height: calc(100vh - 60px);
        margin-top: 60px;
    }

    .admin-content {
        flex: 1;
        padding: 2rem;
        background: #f8f9fa;
    }

    .stat-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .nav-pills .nav-link {
        color: var(--primary-color);
        padding: 0.8rem 1.5rem;
        margin-bottom: 0.5rem;
        border-radius: 10px;
    }

    .nav-pills .nav-link.active {
        background-color: var(--primary-color);
        color: white;
    }

    .nav-pills .nav-link:hover {
        background-color: rgba(13, 71, 161, 0.1);
    }
</style>

<div class="admin-wrapper">
    <div class="admin-content">
        <!-- Navigation Pills -->
        <ul class="nav nav-pills mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#" data-page="dashboard">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="financial">
                    <!--<i class="bi bi-currency-rupee"></i> -->
                     ₹ Financial Aid
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="volunteers">
                    <i class="bi bi-people me-2"></i>Volunteers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-page="campaigners">
                    <i class="bi bi-people me-2"></i>Campaigners
                </a>
            </li>
        </ul>

        <!-- Main Content Area -->
        <div id="mainContent">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    function updatevolenteerStatus(requestId, newStatus) {
        if (!confirm("Are you sure you want to update the status to " + newStatus + "?")) {
            return;
        }

        fetch('admin_pages/update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `request_id=${requestId}&status=${newStatus}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);  // Show response message
            location.reload();  // Reload page to reflect changes
        })
        .catch(error => console.error('Error:', error));
    }
    function updateCampaignerStatus(requestId, newStatus) {
        if (!confirm("Are you sure you want to update the status to " + newStatus + "?")) {
            return;
        }
        fetch('admin_pages/update_camp_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `request_id=${requestId}&status=${newStatus}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);  // Show response message
            location.reload();  // Reload page to reflect changes
        })
        .catch(error => console.error('Error:', error));
        
    }
    function updateStatus(requestId, status) {
        if (confirm(`Are you sure you want to mark this request as ${status}?`)) {
            fetch('admin_pages/update_request_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `request_id=${requestId}&status=${status}`
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload(); // Refresh the page to reflect status change
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadPage('dashboard');

    document.querySelectorAll('.nav-link:not(#logoutBtn)').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.dataset.page;
            loadPage(page);
            
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

function loadPage(page) {
    const mainContent = document.getElementById('mainContent');
    mainContent.innerHTML = '<div class="text-center p-5"><div class="spinner-border text-primary"></div></div>';
    
    fetch(`admin_pages/${page}.php`)
        .then(response => response.text())
        .then(html => {
            mainContent.innerHTML = html;
            initializeComponents();
        })
        .catch(error => {
            mainContent.innerHTML = '<div class="alert alert-danger">Error loading content</div>';
        });
}

function initializeComponents() {
    if (typeof $('.table').DataTable === 'function') {
        $('.table').DataTable();
    }
}
</script>

</body>
</html>