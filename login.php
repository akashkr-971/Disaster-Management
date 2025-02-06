<?php require 'header.php'; ?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-box">
                    <h2 class="text-center mb-4">Welcome Back</h2>
                    
                    <form id="loginForm" action="process_login.php" method="POST">
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

                        <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
                        
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
