<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/project/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<!-- Navbar -->
<?php
session_start();
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <a class="navbar-brand" href="index.php">
      <i class="fas fa-notes-medical me-2 fs-4"></i> <span>HealthCare</span>
    </a>

    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-center" id="mainNavbar">
      <ul class="navbar-nav">
       <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="appointmentLink">
          <i class="fas fa-calendar-check"></i> Book Appointment</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-envelope"></i> Contact Us</a></li>
      </ul>

      <div class="nav-item d-lg-none mt-2">
        <?php if (isset($_SESSION['user'])): ?>
          <span id="welcome-text">Welcome, <?= htmlspecialchars($_SESSION['user']); ?></span>
          <button class="login-button mt-2" onclick="window.location.href='logout.php'">Logout</button>
        <?php else: ?>
          <button class="login-button" onclick="window.location.href='loginuser.php'">Login</button>
          <button class="sign-button" onclick="window.location.href='registeruser.php'">Sign Up</button>
        <?php endif; ?>
      </div>
    </div>

    <div class="d-flex align-items-center">
      <?php if (isset($_SESSION['user'])): ?>
        <span id="welcome-text" class="d-none d-lg-block">Welcome, <?= htmlspecialchars($_SESSION['user']); ?></span>
        <button class="login-button d-none d-lg-block ms-2" onclick="window.location.href='logout.php'">Logout</button>
      <?php else: ?>
        <button class="login-button d-none d-lg-block" onclick="window.location.href='loginuser.php'">Login</button>
        <button class="sign-button d-none d-lg-block" onclick="window.location.href='registeruser.php'">Sign Up</button>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const appointmentLink = document.getElementById("appointmentLink");

  appointmentLink?.addEventListener("click", function(e) {
    e.preventDefault();

    <?php if (!isset($_SESSION['user'])): ?>
      // User NOT logged in → Show SweetAlert
      Swal.fire({
        title: 'Login Required',
        text: "Please login first to book an appointment.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Login Now',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#28a745'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "loginuser.php";
        }
      });
    <?php else: ?>
      // User logged in → Redirect to user.php
      window.location.href = "user.php";
    <?php endif; ?>
  });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>