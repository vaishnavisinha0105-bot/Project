<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0fdfd;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      display: flex;
      min-height: 100vh;
    }
    /* Sidebar */
    .sidebar {
      background: linear-gradient(135deg, #009688, #4db6ac);
      color: white;
      width: 220px;
      transition: all 0.3s ease;
      border-top-right-radius: 20px;
      border-bottom-right-radius: 20px;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      padding-top: 20px;
    }
    .sidebar h4 {
      text-align: center;
      margin-bottom: 20px;
    }
    .sidebar a {
      color: #e0f7fa;
      padding: 12px 20px;
      display: block;
      text-decoration: none;
      font-size: 15px;
      border-left: 3px solid transparent;
      transition: 0.3s;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background: rgba(255,255,255,0.15);
      border-left: 3px solid #fff;
      border-radius: 10px;
    }
    /* Content */
    .content {
      flex: 1;
      padding: 20px;
    }
    .content iframe {
      width: 100%;
      min-height: 100vh; /* fix height issue */
      border: none;
      border-radius: 10px;
      display: block;
    }
    /* Mobile sidebar toggle */
    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
      .sidebar {
        position: fixed;
        top: 0;
        left: -220px;
        height: 100vh;
        z-index: 1000;
      }
      .sidebar.active {
        left: 0;
      }
      .content {
        width: 100%;
        margin-left: 0 !important;
        padding: 10px;
      }
      .toggle-btn {
        position: fixed;
        top: 10px;
        left: 10px;
        z-index: 1100;
        background: #009688;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
      }
    }
  </style>
</head>
<body>

<!-- Mobile toggle button -->
<button class="toggle-btn d-md-none"><i class="fas fa-bars"></i></button>

<!-- Sidebar -->
<div class="sidebar">
  <h4>Healthcare</h4>
  <a href="welcome.php" target="mainFrame" class="active"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
  <a href="doctor.php" target="mainFrame"><i class="fas fa-user-md me-2"></i> Doctors</a>
  <a href="patients.php" target="mainFrame"><i class="fas fa-user-injured me-2"></i> Patients</a>
  <a href="appointment.php" target="mainFrame"><i class="fas fa-calendar-check me-2"></i> Appointments</a>
  <a href="department.php" target="mainFrame"><i class="fas fa-building me-2"></i> Departments</a>
  <a href="users.php" target="mainFrame"><i class="fas fa-user me-2"></i> Users</a>
  <a href="message.php" target="mainFrame"><i class="fas fa-envelope me-2"></i> Messages</a>
  <a href="logout.php" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
</div>

<!-- Content -->
<div class="content">
  <iframe name="mainFrame" src="welcome.php"></iframe>
</div>

<script>
  document.querySelector(".toggle-btn").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.toggle("active");
  });
</script>
</body>
</html>
