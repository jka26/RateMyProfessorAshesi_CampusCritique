<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Campus Critique</title>

    <!-- CSS Links -->
    <link href="../RateMyProfessorAshesi/css/bootstrap.css" rel="stylesheet">
    <link href="../RateMyProfessorAshesi/js/dl-menu/component.css" rel="stylesheet">
    <link rel="stylesheet" href="css/slick.css">
    <link href="../RateMyProfessorAshesi/css/font-awesome.min.css" rel="stylesheet">
    <link href="../RateMyProfessorAshesi/css/svg.css" rel="stylesheet">
    <link href="../RateMyProfessorAshesi/css/prettyPhoto.css" rel="stylesheet">
    <link href="../RateMyProfessorAshesi/css/shortcodes.css" rel="stylesheet">
    <link href="../RateMyProfessorAshesi/css/widget.css" rel="stylesheet">
    <link href="../RateMyProfessorAshesi/css/typography.css" rel="stylesheet">
    <link href="../RateMyProfessorAshesi/style.css" rel="stylesheet">
    <link href="../RateMyProfessorAshesi/css/color.css" rel="stylesheet">
    <link href="../RateMyProfessorAshesi/css/responsive.css" rel="stylesheet">
    <link href="../public/styles/loginsignup.css" rel="stylesheet">


    <style>
        /* Header Styling */
        .iq_top_strip {
            background-color: #990000;
            color: white;
            padding: 10px 0;
        }
        
        .iq_top_contact a, .iq_time_wrap {
            color: white;
            margin-right: 15px;
            font-size: 14px;
        }
        
        .iq_header_1 {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
            z-index: 100;
        }
        
        .iq_logo {
            padding: 15px 0;
        }
        
        .navigation-wrapper {
            display: flex;
            align-items: center;
            height: 80px;
        }
        
        .navigation ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .navigation ul li {
            margin: 0 15px;
        }
        
        .navigation ul li a {
            color: #333;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .navigation ul li a:hover, .navigation ul li.active a {
            color: #990000;
        }
        
        .auth-buttons {
            display: flex;
            margin-left: 20px;
        }
        
        .auth-button {
            background-color: #990000;
            color: white;
            padding: 8px 20px;
            border-radius: 4px;
            margin-left: 10px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #990000;
            text-decoration: none;
            display: inline-block;
        }
        
        .auth-button:hover {
            background-color: white;
            color: #990000;
            text-decoration: none;
        }
        
        .auth-button.outline {
            background-color: transparent;
            color: #990000;
        }
        
        .auth-button.outline:hover {
            background-color: #990000;
            color: white;
        }
        
        /* Modal Styling */
        .modal-overlay {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.6);
        }

        .modal-box {
            background: white;
            margin: 10% auto;
            padding: 30px;
            max-width: 400px;
            border-radius: 8px;
            position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        
        .modal-box h4 {
            color: #990000;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        .close-modal {
            position: absolute;
            top: 15px; right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #999;
            transition: all 0.3s ease;
        }
        
        .close-modal:hover {
            color: #990000;
        }
        
        .modal-box input {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .modal-box button {
            width: 100%;
            padding: 12px;
            background-color: #990000;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .modal-box button:hover {
            background-color: #7a0000;
        }
    </style>
</head>
<body>
<div class="iq_wrapper">
    <!-- Top Strip Start -->
    <div class="iq_top_strip">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="iq_top_contact">
                        <a href="tel:+233567858976"><i class="fa fa-phone"></i> Call us: +233 567 858 976</a>
                        <a href="mailto:info@campuscritique.com"><i class="fa fa-envelope"></i> Email: info@campuscritique.com</a>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <div class="iq_time_wrap"><i class="fa fa-clock-o"></i> Send your Feedback Anytime!</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top Strip End -->
    
    <!-- Header Start -->
    <header class="iq_header_1">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="iq_logo">
                        <a href="index.php">
                            <img src="images/campuscritique.jpg" alt="Campus Critique" style="height: 60px; width: auto;">
                        </a>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <div class="navigation-wrapper">
                        <div class="navigation">
                            <ul>
                                <li class="active"><a href="index.php">Home</a></li>
                                <li><a href="review-courses.html">Reviews</a></li>
                                <li><a href="about-us.html">About Us</a></li>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <li><a href="../RateMyProfessorAshesi/php_files/dashboard.php">Dashboard</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <div class="auth-buttons">
                                <a class="auth-button outline" onclick="openModal('login-modal')">Login</a>
                                <a class="auth-button" onclick="openModal('signup-modal')">Sign Up</a>
                            </div>
                        <?php else: ?>
                            <div class="auth-buttons">
                                <a class="auth-button" href="../RateMyProfessorAshesi/php_files/Logout.php">Logout</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->

    <!-- Banner Start -->
    <div class="iq_banner">
        <ul class="slider">
            <li>
                <a href="#" data-rel="prettyphoto[]" target="_blank">
                    <img src="images/Ashesi_Class2018_Welcomed.jpg" alt="Image here">
                    <div class="iq-caption-wrapper text-center">
                        <div class="iq_banner_caption container">
                            <div class="iq-caption">
                                <div class="iq-caption-title">Your Campus, Your Voice</div>
                                <div class="iq-caption-text">Find the Best Professors, Courses, and Facilities</div>
                                <div class="iq-caption-contant">Read honest reviews from students like you!</div>
                            </div>
                        </div>
                    </div>
                </a>
            </li>
        </ul>
    </div>
    <!-- Banner End -->

    <!-- Content Start -->
    <div class="iq_content_wrap">
        <div class="we_professional_bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <section>
                            <div class="iq_heading_1 text-left">
                                <h4>Explore <span>Student Reviews</span></h4>
                                <p>Campus Critique empowers students to share and discover feedback about courses, professors, departments, and campus life.</p>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="iq_professional_services">
                                        <span class="icon-lecture"></span>
                                        <h5><a href="reviews.html">Course Reviews</a></h5>
                                        <p>Discover which courses challenge you, inspire you, and prepare you best.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="iq_professional_services">
                                        <span class="icon-doctor-2"></span>
                                        <h5><a href="reviews.html">Professor Ratings</a></h5>
                                        <p>Learn about professors' teaching styles and engagement.</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="iq_professional_services">
                                        <span class="icon-medical-3"></span>
                                        <h5><a href="reviews.html">Campus Facilities</a></h5>
                                        <p>Evaluate facilities through the eyes of your fellow students.</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-md-4">
                        <div class="iq_search_courses">
                            <h4>Search Reviews</h4>
                            <form>
                                <div class="iq-input">
                                    <input type="text" placeholder="Search Course, Professor, Facility">
                                </div>
                                <div class="iq-input">
                                    <select name="Review_Category">
                                        <option value="">Select Category</option>
                                        <option>Professor</option>
                                        <option>Course</option>
                                        <option>Campus Facility</option>
                                    </select>
                                </div>
                                <div class="iq-input">
                                    <input class="iq_link_1" type="submit" value="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Categories -->
        <section>
            <div class="container">
                <div class="iq_heading_1 text-center">
                    <h4>Top Rated <span>Categories</span></h4>
                    <p>Explore what students love most about their academic experiences.</p>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <div class="iq_course_list">
                            <figure>
                                <img src="images/Professors/Nathan_Amanquah.jpeg" alt="Top Professors">
                            </figure>
                            <div class="iq_course_list_des">
                                <h5><a href="review-professors.html">Top Professors</a></h5>
                                <p>Faculty members who inspire, challenge, and make a difference.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="iq_course_list">
                            <figure>
                                <img src="images/Professors/courses.jpeg" alt="Popular Courses">
                            </figure>
                            <div class="iq_course_list_des">
                                <h5><a href="review-courses.html">Most Popular Courses</a></h5>
                                <p>Courses rated highly for content, balance, and preparation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Content End -->

    <!-- Footer -->
    <footer class="iq_footer_bg" style="background-color: #990000; color: white; padding: 40px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h5>About Campus Critique</h5>
                    <p>Helping students make smarter academic and campus life decisions.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="index.php" style="color: white;">Home</a></li>
                        <li><a href="review-courses.html" style="color: white;">See Reviews</a></li>
                        <li><a href="about-us.html" style="color: white;">About Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact Us</h5>
                    <p>Email: <a href="mailto:info@campuscritique.com" style="color: white;">info@campuscritique.com</a></p>
                    <p>Phone: +233 567 858 976</p>
                </div>
                <div class="col-md-3">
                    <h5>Newsletter</h5>
                    <form style="display: flex; margin-top: 10px;">
                        <input type="text" placeholder="Your email" style="flex: 1; padding: 8px;">
                        <button type="submit" style="padding: 8px 12px;"><i class="fa fa-paper-plane"></i></button>
                    </form>
                    <h5 style="margin-top: 20px;">Our Connected</h5>
                    <ul style="display: flex; gap: 10px; list-style: none; padding: 0;">
                        <li><a href="#"><i class="fa fa-facebook" style="color: white;"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" style="color: white;"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram" style="color: white;"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest" style="color: white;"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="login-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('login-modal')">&times;</span>
        <h2>Login</h2>
        <?php if (!empty($loginError)): ?>
        <p class="error-message"><?php echo $loginError; ?></p>
        <?php endif; ?>
        <form action="../RateMyProfessorAshesi/php_files/login.php" method="POST">
        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <button class="continue-btn" type="submit">Continue</button>
        </form>
    </div>
    </div>

    <!-- Signup Modal -->
    <div id="signup-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('signup-modal')">&times;</span>
        <h2><strong>Student</strong> Sign Up</h2>
        <p>Are you a professor? <a href="#" onclick="openModal('professor-signup-modal')">Sign up here</a></p>
        <?php if (!empty($signupError)): ?>
        <p class="error-message"><?php echo $signupError; ?></p>
        <?php endif; ?>
        <form action="../RateMyProfessorAshesi/php_files/signup.php" method="POST">
        <label>Full Name:</label>
        <input type="text" name="name" placeholder="Enter your name" required>

        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Create a password" required>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" placeholder="Confirm your password" required>

        <label>Username:</label>
        <input type="text" name="username" placeholder="Enter a username" required>

        <button class="continue-btn" type="submit">Continue</button>

        <p style="font-size: 12px; color: #666; margin-top: 15px;">
            Campus Critique is designed for the Ashesi University community.
        </p>
        </form>
    </div>
    </div>

    <!-- Professor Signup Modal -->
    <div id="professor-signup-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('professor-signup-modal')">&times;</span>
            <h2><strong>Professor</strong> Sign Up</h2>
            <form action="../RateMyProfessorAshesi/php_files/professor_signup.php" method="POST">
            <label>Full Name:</label>
            <input type="text" name="name" placeholder="Enter your full name" required>

            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Create a password" required>

            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" placeholder="Confirm your password" required>

            <label>Department:</label>
            <input type="text" name="department" placeholder="Enter your department" required>

            <button class="continue-btn" type="submit">Sign Up</button>
            </form>
        </div>
    </div>

<!-- Scripts -->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/dl-menu/modernizr.custom.js"></script>
<script src="js/dl-menu/jquery.dlmenu.js"></script>
<script src="js/jquery.prettyPhoto.js"></script>
<script src="js/custom.js"></script>
<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
</body>
</html>
