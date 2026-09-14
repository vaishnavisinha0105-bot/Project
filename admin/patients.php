<?php
include '../db.php';
$result = $conn->query("SELECT * FROM patients ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Patients - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f0fdfd;
      font-family: 'Segoe UI', sans-serif;
      color: #0f4c5c;
    }

    /* Table card wrapper */
    .table-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    /* Title */
    .page-title {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      margin-bottom: 20px;
      color: #0f4c5c;
    }

    /* Table */
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
  </style>
</head>
<body>

<div class="container mt-4">
  <div class="table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="page-title"><i class="bi bi-people-fill"></i> Patients List</h3>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Past History</th>
            <th>Registered On</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $i = 1;
          while ($row = $result->fetch_assoc()): 
          ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['age']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= htmlspecialchars($row['past_history']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
