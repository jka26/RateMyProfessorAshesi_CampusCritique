<?php
session_start();

$loginError = $_SESSION['login_error'] ?? '';
$loginSuccess = $_SESSION['login_success'] ?? false;

$signupError = $_SESSION['signup_error'] ?? '';
$signupSuccess = $_SESSION['signup_success'] ?? false;

// Unset messages so they don’t persist unnecessarily
unset($_SESSION['login_error'], $_SESSION['login_success']);
unset($_SESSION['signup_error'], $_SESSION['signup_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Campus Critique</title>
  <link rel="stylesheet" href="../RateMyProfessorAshesi/public/styles/home.css">
  <link rel="stylesheet" href="../RateMyProfessorAshesi/public/styles/loginsignup.css">
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header class="header">
  <h2 class="site-title">Campus Critique</h2>
  <div class="nav-buttons">
    <button class="open-modal" onclick="openModal('login-modal')">Login</button>
    <button class="open-modal" onclick="openModal('signup-modal')">Sign Up</button>
  </div>
</header>

<section class="hero">
  <div class="hero-content">
    <img src="../RateMyProfessorAshesi/assets/logo_campusCritique.png" alt="Campus Critique Logo" class="logo">
    <h1>Start exploring <span class="bold">Ashesi courses & professors</span></h1>
    <p>Search professors by name or browse recent reviews!</p>
  </div>
</section>

<section class="features">
  <div class="feature">
    <img src="../RateMyProfessorAshesi/assets/manage.jpg" alt="Manage Ratings">
    <h2>Manage and edit your ratings</h2>
  </div>
  <div class="feature">
    <img src="../RateMyProfessorAshesi/assets/anonymous.jpg" alt="Anonymous Ratings">
    <h2>Your ratings are always anonymous</h2>
  </div>
  <div class="feature">
    <img src="../RateMyProfessorAshesi/assets/like_dislike.jpg" alt="Like or Dislike">
    <h2>Like or dislike ratings</h2>
  </div>
</section>

<section class="about">
  <h2>Why Campus Critique?</h2>
  <p>Campus Critique is a platform designed to help students at Ashesi University share their experiences and opinions about professors and courses.</p>
  <p>We believe every student should have a voice. Join us in creating a transparent, informed academic community.</p>
  <div class="button-container">
    <button class="open-modal" onclick="openModal('signup-modal')">Sign Up Now!</button>
  </div>
</section>

<section class="footer">
  <p>&copy; <?php echo date("Y"); ?> Campus Critique. All rights reserved.</p>
  <div class="social-media">
    <a href="#"><img src="../RateMyProfessorAshesi/assets/facebook.jpeg" alt="Facebook"></a>
    <a href="#"><img src="../RateMyProfessorAshesi/assets/Twitter.png" alt="Twitter"></a>
    <a href="#"><img src="../RateMyProfessorAshesi/assets/instagram.jpeg" alt="Instagram"></a>
  </div>
</section>

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

<script>
  function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.style.display = "flex";
    modal.classList.add("show");
  }

  function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove("show");
    setTimeout(() => {
      modal.style.display = "none";
    }, 300);
  }

  window.onclick = function (event) {
    let modals = document.querySelectorAll(".modal");
    modals.forEach(modal => {
      if (event.target === modal) {
        closeModal(modal.id);
      }
    });
  };

  document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", function (e) {
      const inputs = this.querySelectorAll("input[required]");
      let valid = true;

      inputs.forEach(input => {
        if (!input.value.trim()) {
          input.style.borderColor = "#D9534F";
          valid = false;
        } else {
          input.style.borderColor = "#ccc";
        }
      });

      if (!valid) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Oops!',
          text: 'Please fill in all fields properly.',
        });
      }
    });
  });

  document.addEventListener("DOMContentLoaded", function () {
    <?php if (!empty($loginError)): ?>
      openModal('login-modal');
    <?php endif; ?>

    <?php if (!empty($signupError)): ?>
      openModal('signup-modal');
    <?php endif; ?>

    <?php if ($signupSuccess): ?>
      Swal.fire({
        icon: 'success',
        title: 'Signup Successful!',
        text: 'You can now log in to your account!',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Great!'
      });
    <?php endif; ?>

    <?php if ($loginSuccess): ?>
      Swal.fire({
        icon: 'success',
        title: 'Login Successful!',
        text: 'Welcome back!',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Continue'
      });
    <?php endif; ?>
  });
</script>

</body>
</html>
