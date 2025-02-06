<?php require 'header.php'; ?>

<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-box">
                    <h2 class="text-center mb-4">Create Account</h2>
                    
                    <form id="signupForm" action="process_signup.php" method="POST">
                        <div class="form-group mb-3">
                            <label class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text" style="margin-right: 10px;">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>

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
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text" style="margin-right: 10px;">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Create Account</button>
                        
                        <div class="text-center">
                            <span>Already have an account? </span>
                            <a href="login.php" class="text-primary text-decoration-none">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>