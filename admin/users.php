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

// Fetch counts by role
$counts = $conn->query("SELECT user_type, COUNT(*) as total FROM user GROUP BY user_type");

// Store in array
$roleCounts = ['admin' => 0, 'doctor' => 0, 'patient' => 0];
while ($row = $counts->fetch_assoc()) {
    $roleCounts[$row['user_type']] = $row['total'];
}

// Fetch all users
$users = $conn->query("SELECT id, name, email, user_type FROM user ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background: #f0fdfd;
      font-family: 'Segoe UI', sans-serif;
      color: #0f4c5c;
    }

     .table-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Page Title Styling */
      .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f4c5c;
        display: flex;
        align-items: center;
        gap: 10px;
      }
      .page-title i {
        font-size: 1.8rem;
      }


    /* Dashboard-style cards */
    .stat-card {
      border-radius: 12px;
      padding: 20px;
      color: #fff;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .stat-card h5 {
      font-size: 18px;
      margin-bottom: 6px;
    }
    .stat-card p {
      font-size: 28px;
      margin: 0;
    }
    .stat-icon {
      font-size: 40px;
      opacity: 0.8;
    }

    .bg-admin { background: #1976d2; }   /* Blue */
    .bg-doctor { background: #2e7d32; }  /* Green */
    .bg-patient { background: #f9a825; } /* Yellow */

    /* Table like doctors list */
    .custom-table {
      border-collapse: separate;
      border-spacing: 0 10px;
      width: 100%;
    }
    .custom-table thead {
      background: #009688;
      color: #fff;
    }
    .custom-table thead th {
      padding: 14px;
      border: none;
      font-weight: 600;
    }
    .custom-table tbody tr {
      background: #f9ffff;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
    }
    .custom-table tbody tr:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .custom-table tbody td {
      padding: 14px;
      border: none;
      vertical-align: middle;
    }


    /* Action buttons */
    .btn-edit {
      background: #42a5f5;
      color: #fff;
      border-radius: 8px;
      padding: 5px 12px;
      font-size: 0.9rem;
      border: none;
    }
    .btn-edit:hover { background: #1e88e5; }

    .btn-delete {
      background: #ef5350;
      color: #fff;
      border-radius: 8px;
      padding: 5px 12px;
      font-size: 0.9rem;
      border: none;
    }
    .btn-delete:hover { background: #c62828; }
  </style>
</head>
<body class="p-4">
  <div class="table-card">
<h3 class="page-title mb-4">
  <i class="bi bi-person-circle"></i> System Users
</h3>

  <!-- Summary Cards -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="stat-card bg-admin">
        <div>
          <h5>Admins</h5>
          <p><?= $roleCounts['admin'] ?></p>
        </div>
        <i class="bi bi-shield-lock stat-icon"></i>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card bg-doctor">
        <div>
          <h5>Doctors</h5>
          <p><?= $roleCounts['doctor'] ?></p>
        </div>
        <i class="bi bi-person stat-icon"></i>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card bg-patient">
        <div>
          <h5>Patients</h5>
          <p><?= $roleCounts['patient'] ?></p>
        </div>
        <i class="bi bi-people stat-icon"></i>
      </div>
    </div>
  </div>

  <!-- Users List -->
  <h3 class="page-title mb-4">
  <i class="bi bi-person-fill"></i>All Users
</h3>
  <table class="custom-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
      </tr>
    </thead>
    <tbody>
      <?php $i=1; while($row = $users->fetch_assoc()): ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><span class="badge bg-secondary"><?= htmlspecialchars($row['user_type']) ?></span></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  </div>
</body>
</html>
