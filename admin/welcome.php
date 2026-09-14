<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'doctor_directory';

include("../auth.php");
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Count queries
$doctorCount = $conn->query("SELECT COUNT(*) AS total FROM doctors")->fetch_assoc()['total'];
$patientCount = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];
$appointmentCount = $conn->query("SELECT COUNT(*) AS total FROM appointments")->fetch_assoc()['total'];
$departmentCount = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
$userCount = $conn->query("SELECT COUNT(*) AS total FROM user")->fetch_assoc()['total'];
$messageCount = $conn->query("SELECT COUNT(*) AS total FROM contact_messages")->fetch_assoc()['total'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: #f0fdfd;
      font-family: 'Segoe UI', sans-serif;
      color: #0f4c5c;
    }
    .dashboard-card {
      padding: 25px;
      border-radius: 20px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    }
    .dashboard-card h5 {
      margin: 0;
      font-size: 1.1rem;
      font-weight: 600;
    }
    .dashboard-card span {
      font-size: 1.8rem;
      font-weight: bold;
    }
    .dashboard-icon {
      font-size: 2.8rem;
      opacity: 0.85;
    }
    .card-doctor { background: linear-gradient(135deg, #42a5f5, #1e88e5); }
    .card-patient { background: linear-gradient(135deg, #66bb6a, #388e3c); }
    .card-appointment { background: linear-gradient(135deg, #ffca28, #f57f17); color: #333 !important; }
    .card-department { background: linear-gradient(135deg, #26c6da, #0097a7); }
    .card-user { background: linear-gradient(135deg, #ab47bc, #7b1fa2); }
    .card-message { background: linear-gradient(135deg, #ff7043, #e64a19); }

  </style>
</head>
<body>
<div class="container mt-4">
  <h3 class="mb-4">Welcome, <?= htmlspecialchars($_SESSION['admin']); ?> 👋</h3>
  <div class="row g-4">
    <div class="col-lg-4 col-md-6 col-12">
      <a href="doctor.php" target="mainFrame" class="text-decoration-none">
        <div class="dashboard-card card-doctor">
          <div>
            <h5>Doctors</h5>
            <span><?= $doctorCount ?></span>
          </div>
          <i class="fas fa-user-md dashboard-icon"></i>
        </div>
      </a>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
      <a href="patients.php" target="mainFrame" class="text-decoration-none">
        <div class="dashboard-card card-patient">
          <div>
            <h5>Patients</h5>
            <span><?= $patientCount ?></span>
          </div>
          <i class="fas fa-user-injured dashboard-icon"></i>
        </div>
      </a>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
      <a href="appointment.php" target="mainFrame" class="text-decoration-none">
        <div class="dashboard-card card-appointment">
          <div>
            <h5>Appointments</h5>
            <span><?= $appointmentCount ?></span>
          </div>
          <i class="fas fa-calendar-check dashboard-icon"></i>
        </div>
      </a>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
      <a href="department.php" target="mainFrame" class="text-decoration-none">
        <div class="dashboard-card card-department">
          <div>
            <h5>Departments</h5>
            <span><?= $departmentCount ?></span>
          </div>
          <i class="fas fa-building dashboard-icon"></i>
        </div>
      </a>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
      <a href="users.php" target="mainFrame" class="text-decoration-none">
        <div class="dashboard-card card-user">
          <div>
            <h5>Users</h5>
            <span><?= $userCount ?></span>
          </div>
          <i class="fas fa-users dashboard-icon"></i>
        </div>
      </a>
    </div>
    <div class="col-lg-4 col-md-6 col-12">
      <a href="message.php" target="mainFrame" class="text-decoration-none">
        <div class="dashboard-card card-message">
          <div>
            <h5>Messages</h5>
            <span><?= $messageCount ?></span>
          </div>
          <i class="fas fa-envelope dashboard-icon"></i>
        </div>
      </a>
    </div>

  </div>
</div>
</body>
</html>
