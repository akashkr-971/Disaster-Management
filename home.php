<?php require 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disaster Recovery & Rehabilitation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        /* Ensuring all cards have equal height */
        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .card-body {
            flex-grow: 1;
        }
        /* Custom styling for the missing person report section */
        #missing-person {
            background-color: #f8d7da; /* Light red background for emergency */
            color: #721c24; /* Dark red text for emergency */
            border: 1px solid #f5c6cb; /* Light border */
            padding: 40px 0;
            border-radius: 30px;
            margin: 40px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        #missing-person h2 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        #missing-person .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            font-size: 1.2rem;
            padding: 15px 30px;
            transition: all 0.3s ease;
        }
        #missing-person .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
        #missing-person .emergency-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        /* Hero section styles */
        .hero {
            position: relative;
            height: 100vh !important;
            width: 100%;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Dark overlay */
        }
        .hero .container {
            position: relative;
            z-index: 2;
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        .hero .lead {
            font-size: 1.5rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
        .hero .btn {
            margin: 10px;
            padding: 12px 30px;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            .hero .lead {
                font-size: 1.2rem;
            }
            .hero .btn {
                padding: 10px 20px;
                font-size: 1rem;
            }
        }
        /* Divider styling */
        .divider {
            margin: 1rem 0;
        }
        
        .divider .line {
            width: 60px;
            height: 2px;
        }

        .divider i {
            font-size: 1.5rem;
        }

        /* Section spacing */
        #services {
            background-color: #ffffff;
        }

        @media (max-width: 768px) {
            .divider .line {
                width: 40px;
            }
        }

        #about img {
            max-height: 500px;
            object-fit: cover;
        }

        #about .bi {
            font-size: 1.5rem;
        }

        #about .lead {
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            #about img {
                max-height: 300px;
            }
        }

        #contact {
            background-color: #f8f9fa;
        }

        #contact .card {
            border: none;
            transition: transform 0.2s;
        }

        #contact .card:hover {
            transform: translateY(-5px);
        }

        #contact .form-control,
        #contact .form-select {
            padding: 0.8rem;
            border-radius: 0.5rem;
        }

        #contact .form-control:focus,
        #contact .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        #contact .social-links a {
            text-decoration: none;
            transition: color 0.2s;
        }

        #contact .social-links a:hover {
            color: #0056b3 !important;
        }

        @media (max-width: 768px) {
            #contact .card {
                margin-bottom: 20px;
            }
        }

        .chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            z-index: 1000;
        }

        .chat-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            float: right;
        }

        .chat-box {
            display: none;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0 0 10px 10px;
            height: 500px;
            margin-bottom: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .chat-header {
            padding: 15px;
            background: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #0056b3;
        }

        .chat-messages {
            height: 380px;
            overflow-y: auto;
            padding: 15px;
        }

        .chat-input-container {
            padding: 10px;
            border-top: 1px solid #ddd;
            display: flex;
            gap: 5px;
        }

        .chat-input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .chat-send {
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .message {
            margin: 8px 0;
            padding: 10px;
            border-radius: 10px;
            max-width: 80%;
            word-wrap: break-word;
        }

        .user-message {
            background: #007bff;
            color: white;
            margin-left: auto;
            margin-right: 5px;
        }

        .bot-message {
            background: #f0f0f0;
            margin-right: auto;
            margin-left: 5px;
            white-space: pre-wrap;
            font-family: monospace;
        }

        /* Card styling improvements */
        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.08) !important;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .card .btn {
            border-radius: 25px;
            padding: 8px 25px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Section styling improvements */
        .section-padding {
            padding: 80px 0;
        }

        /* About section improvements */
        #about img {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        /* Missing person section improvements */
        #missing-person {
            border-radius: 30px;
            margin: 40px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        #missing-person .emergency-img {
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Contact section improvements */
        #contact .card {
            border-radius: 20px;
        }

        #contact .form-control,
        #contact .form-select {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }

        #contact .btn {
            border-radius: 25px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Chat container improvements */
        .chat-container {
            z-index: 1050;
        }

        .chat-box {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .chat-header {
            border-radius: 20px 20px 0 0;
        }

        .chat-input {
            border-radius: 20px;
            padding: 10px 20px;
        }

        .chat-send {
            border-radius: 20px;
            padding: 10px 25px;
        }

        .chat-button {
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 500;
            letter-spacing: 0.5px;
            box-shadow: 0 5px 15px rgba(0,123,255,0.2);
            transition: all 0.3s ease;
        }

        .chat-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,123,255,0.3);
        }

        /* Message styling improvements */
        .message {
            border-radius: 15px;
        }

        .user-message {
            border-radius: 15px 15px 0 15px;
        }

        .bot-message {
            border-radius: 15px 15px 15px 0;
        }

        /* Button hover effects */
        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Divider improvements */
        .divider .line {
            border-radius: 5px;
        }

        /* Social media icons */
        .social-links a {
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            transform: translateY(-3px);
        }

        /* Hero section improvements */
        .hero .btn {
            border-radius: 30px;
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: 1px;
            padding: 15px 35px;
            transition: all 0.3s ease;
        }

        .hero .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .section-padding {
                padding: 50px 0;
            }

            .card-img-top {
                height: 180px;
            }

            .hero .btn {
                padding: 12px 25px;
            }
        }

        /* Service cards specific styling */
        #services .card {
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,123,255,0.1) !important;
        }

        #services .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: rgba(0,123,255,0.2) !important;
        }

        #services .card-body {
            padding: 1.5rem;
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        #services .card-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        #services .card-text {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        /* Make sure images fit properly */
        #services .card-img-top {
            border-bottom: 1px solid rgba(0,0,0,0.05);
            object-fit: cover;
            height: 200px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            #services .card {
                margin-bottom: 1rem;
            }
            
            #services .card-img-top {
                height: 180px;
            }
        }

        /* Overall page background */
        body {
            background-color: #f5f5f5; /* Light off-white color */
        }

        /* Update section backgrounds */
        #about {
            background-color: #f5f5f5 !important; /* Match body background */
        }

        #contact {
            background-color: #f5f5f5 !important; /* Match body background */
        }

        .container-fluid.bg-light {
            background-color: #f5f5f5 !important; /* Match body background */
        }

        /* Keep contact cards white */
        #contact .card {
            background: #ffffff;
        }

        /* Update chat box to white */
        .chat-box {
            background: #ffffff;
        }

        /* Missing person section - keep distinct */
        #missing-person {
            background-color: #f8d7da; /* Keep the emergency section distinct */
        }

        /* Service section heading area */
        #servicessection {
            background-color: #f5f5f5 !important;
        }

        /* Update divider styling for better visibility */
        .divider .line {
            background-color: #007bff !important;
            opacity: 1;
        }

        /* Emergency icon styling */
        .emergency-icon {
            font-size: 5rem;
            color: #dc3545;
            margin: 2rem 0;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <header class="hero text-center text-white d-flex align-items-center" style="background-image: url('static/home/hero-bg.jpg');">
        <div class="container">
            <h1 class="display-4 mb-4">Welcome to Renew Hope</h1>
            <h1 class="display-4 mb-4">Rebuilding Lives, Together</h1>
            <p class="lead mb-5">Providing aid, donations, and mental health support to disaster victims.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#apply" class="btn btn-primary btn-lg">Get Help</a>
                <a href="#donate" class="btn btn-success btn-lg">Donate Now</a>
            </div>
        </div>
    </header>

    <!-- Add after the hero section and before the services section -->
    <div class="container-fluid bg-light py-5" id="servicessection">
        <div class="container">
            <div class="row text-center">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-3">Our Services</h2>
                    <div class="divider d-flex align-items-center justify-content-center mb-4">
                        <div class="line bg-primary"></div>
                        <div class="mx-3"><i class="bi bi-heart-fill text-primary"></i></div>
                        <div class="line bg-primary"></div>
                    </div>
                    <p class="lead text-muted">Comprehensive support services for disaster recovery and rehabilitation</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Section for Aid, Donate, Mental Health, and Updates -->
    <section id="services" class="py-5" style="background-color: #f5f5f5;">
        <div class="container">
            <div class="row g-4">
                <!-- Aid Application -->
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <img src="static/home/aid.jpg" class="card-img-top" alt="Aid Assistance">
                        <div class="card-body text-center">
                            <h5 class="card-title">Apply for Financial Aid</h5>
                            <p class="card-text">Submit your request for financial assistance, and insurance claims.</p>
                            <a href="aid.php" class="btn btn-primary">Apply Now</a>
                        </div>
                    </div>
                </div>

                <!-- Donate Section -->
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <img src="static/home/donate.jpg" class="card-img-top" alt="Donation">
                        <div class="card-body text-center">
                            <h5 class="card-title">Make a Difference - Donate</h5>
                            <p class="card-text">Your contributions help disaster victims recover faster.</p>
                            <a href="donate_process.php" class="btn btn-success">Donate Now</a>
                        </div>
                    </div>
                </div>

                <!-- Mental Health Support -->
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <img src="static/home/ment.jpg" class="card-img-top" alt="Mental Health Support">
                        <div class="card-body text-center">
                            <h5 class="card-title">Mental Health Support</h5>
                            <p class="card-text">Connect with professionals or join support groups.</p>
                            <a href="mentalhealth.php" class="btn btn-info">Get Support</a>
                        </div>
                    </div>
                </div>

                <!-- Shelter Request -->
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <img src="static/home/shelter.jpg" class="card-img-top" alt="Shelter">
                        <div class="card-body text-center">
                            <h5 class="card-title">Temporary Shelter Request</h5>
                            <p class="card-text">Request for temporary shelters when in need.</p>
                            <a href="shelter.php" class="btn btn-warning">Request Shelter</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Skill Training CTA Section -->
    <section class="py-5 bg-light text-center">
        <div class="container">
            <h2 class="display-5 fw-bold mb-4">Skill Training Programs</h2>
            <p class="lead mb-4">Discover opportunities to learn new skills and rebuild your future through our comprehensive training programs.</p>
            <a href="skilltraining.php" class="btn btn-primary btn-lg">Explore Training Programs</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-3">About Renew Hope</h2>
                    <div class="divider d-flex align-items-center justify-content-center mb-4">
                        <div class="line bg-primary"></div>
                        <div class="mx-3"><i class="bi bi-info-circle-fill text-primary"></i></div>
                        <div class="line bg-primary"></div>
                    </div>
                </div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="static/home/about-img.jpg" alt="About ResQAI" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h3 class="mb-4">Empowering Communities Through Technology</h3>
                    <p class="lead mb-4">ResQAI is a cutting-edge disaster management platform that combines artificial intelligence with human compassion to provide immediate and effective disaster response.</p>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-check-circle-fill text-primary fs-4 me-2"></i>
                                <div>
                                    <h5>24/7 Support</h5>
                                    <p>Round-the-clock assistance for emergency situations</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-lightning-fill text-primary fs-4 me-2"></i>
                                <div>
                                    <h5>Rapid Response</h5>
                                    <p>Quick deployment of resources and personnel</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-people-fill text-primary fs-4 me-2"></i>
                                <div>
                                    <h5>Community First</h5>
                                    <p>Focus on community-driven disaster recovery</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-graph-up text-primary fs-4 me-2"></i>
                                <div>
                                    <h5>Data-Driven</h5>
                                    <p>AI-powered decision making and resource allocation</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <a href="#servicessection" class="btn btn-primary">Our Services</a>
                        <a href="#contact" class="btn btn-outline-primary">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Missing Person Report Section -->
    <section id="missing-person" class="text-center">
        <div class="container">
            <h2 class="display-4">Emergency! Report a Missing Person</h2>
            <div class="emergency-icon mb-4">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <p class="lead">If you know of someone who is missing after the disaster, please help us by reporting their location. Time is crucial!</p>
            <a href="report_missing.php" class="btn btn-danger btn-lg">Report Missing Person</a>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-3">Contact Us</h2>
                    <div class="divider d-flex align-items-center justify-content-center mb-4">
                        <div class="line bg-primary"></div>
                        <div class="mx-3"><i class="bi bi-envelope-fill text-primary"></i></div>
                        <div class="line bg-primary"></div>
                    </div>
                    <p class="lead text-muted">Get in touch with us for any queries or assistance</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <form id="contactForm">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Your Name</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" id="phone" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subject" class="form-label">Subject</label>
                                            <select class="form-select" id="subject" name="subject" required>
                                                <option value="">Select a subject</option>
                                                <option value="general">General Inquiry</option>
                                                <option value="support">Support Request</option>
                                                <option value="volunteer">Volunteer Information</option>
                                                <option value="donation">Donation Query</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="message" class="form-label">Message</label>
                                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-lg w-100">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Contact Information</h4>
                            
                            <div class="d-flex mb-4">
                                <i class="bi bi-geo-alt-fill text-primary fs-4 me-3"></i>
                                <div>
                                    <h5 class="mb-1">Address</h5>
                                    <p class="mb-0">123 Disaster Response Street, City, Country</p>
                                </div>
                            </div>

                            <div class="d-flex mb-4">
                                <i class="bi bi-telephone-fill text-primary fs-4 me-3"></i>
                                <div>
                                    <h5 class="mb-1">Phone</h5>
                                    <p class="mb-0">Emergency: +1 234 567 890</p>
                                    <p class="mb-0">Support: +1 234 567 891</p>
                                </div>
                            </div>

                            <div class="d-flex mb-4">
                                <i class="bi bi-envelope-fill text-primary fs-4 me-3"></i>
                                <div>
                                    <h5 class="mb-1">Email</h5>
                                    <p class="mb-0">support@resqai.com</p>
                                    <p class="mb-0">info@resqai.com</p>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">Follow Us</h5>
                            <div class="d-flex gap-3">
                                <a href="#" class="text-primary fs-4"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="text-primary fs-4"><i class="bi bi-twitter"></i></a>
                                <a href="#" class="text-primary fs-4"><i class="bi bi-linkedin"></i></a>
                                <a href="#" class="text-primary fs-4"><i class="bi bi-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Chatbot -->
    <div class="chat-container">
        <div class="chat-box" id="chatBox">
            <div class="chat-header">ResQAI Assistant</div>
            <div class="chat-messages" id="chatMessages">
                <div class="message bot-message">Hello! How can I help you today?</div>
            </div>
            <div class="chat-input-container">
                <input type="text" class="chat-input" id="userInput" placeholder="Type your message...">
                <button class="chat-send" onclick="sendMessage()">Send</button>
            </div>
        </div>
        <button class="chat-button" id="toggleChat">Chat with ResQAI</button>
    </div>

    <?php require 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would typically send the form data to your server
            // For now, we'll just show a success message
            alert('Thank you for your message! We will get back to you soon.');
            this.reset();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.getElementById("chatBox");
            const chatContainer = document.querySelector(".chat-container");
            const userInput = document.getElementById("userInput");
            
            document.getElementById("toggleChat").addEventListener("click", function(e) {
                e.stopPropagation();
                chatBox.style.display = chatBox.style.display === "none" ? "block" : "none";
                if (chatBox.style.display === "block") {
                    userInput.focus();
                }
            });
            
            document.addEventListener('click', function(e) {
                if (chatBox.style.display === "block" && 
                    !chatContainer.contains(e.target)) {
                    chatBox.style.display = "none";
                }
            });
            
            chatBox.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        function sendMessage() {
            let userInput = document.getElementById("userInput");
            let userMessage = userInput.value.trim();
            
            if (userMessage === "") return;
            
            const chatMessages = document.getElementById("chatMessages");
            
            chatMessages.innerHTML += `<div class="message user-message">${userMessage}</div>`;
            
            const typingIndicator = `<div class="message bot-message" id="typing">Typing...</div>`;
            chatMessages.innerHTML += typingIndicator;
            
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            userInput.value = "";
            userInput.focus();

            fetch("http://localhost:5000/chat", {
                method: "POST",
                body: JSON.stringify({ message: userMessage }),
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "Access-Control-Allow-Origin": "*"
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                document.getElementById("typing").remove();
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const formattedResponse = data.response
                    .replace(/\n/g, '<br>')
                    .replace(/ {2,}/g, function(match) {
                        return '&nbsp;'.repeat(match.length);
                    });
                
                chatMessages.innerHTML += `<div class="message bot-message">${formattedResponse}</div>`;
                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById("typing")?.remove();
                
                chatMessages.innerHTML += `<div class="message bot-message">Sorry, there was an error connecting to the AI service. Please try again later.</div>`;
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
        }

        document.getElementById("userInput").addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                sendMessage();
            }
        });
    </script>
</body>
</html>