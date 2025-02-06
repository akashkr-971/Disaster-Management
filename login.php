<?php 
require 'header.php';
require 'db_connect.php';

// Process login form
if(isset($_POST['login-button'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    try {
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if($user) {
            if($password == $user['password']) { // In production, use password_verify()
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on role
                switch($user['role']) {
                    case 'admin':
                        header("Location: admin.php");
                        break;
                    case 'volunteer':
                        header("Location: volunteer.php");
                        break;
                    case 'campaigner':
                        header("Location: camp.php");
                        break;
                    default:
                        header("Location: home.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Invalid password!";
            }
        } else {
            $_SESSION['error'] = "No account found with this email!";
        }
    } catch(PDOException $e) {
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
                    <h2 class="text-center mb-4">Welcome Back</h2>
                    
                    <form id="loginForm" action="" method="POST">
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
                                <input type="password" class="form-control" name="password"  required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="#" class="text-primary text-decoration-none">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3" name="login-button">Login</button>
                        
                        <div class="text-center">
                            <span>Don't have an account? </span>
                            <a href="signup.php" class="text-primary text-decoration-none">Sign up</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>