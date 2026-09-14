<?php
include("../db.php"); 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctors - Admin Panel</title>
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

    /* Add button */
    .add-btn {
      background: linear-gradient(135deg, #26c6da, #0097a7);
      color: #fff;
      border-radius: 12px;
      padding: 8px 18px;
      font-weight: 600;
      border: none;
    }
    .add-btn:hover {
      background: #00838f;
      color: #fff;
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

    /* Buttons */
    .btn-edit {
      background: #42a5f5;
      color: #fff;
      border-radius: 10px;
      padding: 5px 12px;
      font-size: 0.9rem;
      border: none;
    }
    .btn-edit:hover {
      background: #1e88e5;
      color: #fff;
    }
    .btn-delete {
      background: #ef5350;
      color: #fff;
      border-radius: 10px;
      padding: 5px 12px;
      font-size: 0.9rem;
      border: none;
    }
    .btn-delete:hover {
      background: #c62828;
      color: #fff;
    }
  </style>
</head>
<body>

<div class="container mt-4">
  <div class="table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="page-title"><i class="bi bi-person-badge-fill"></i> Doctor List</h3>
      <a href="add_doctor.php" class="btn add-btn">
        <i class="bi bi-plus-circle"></i> Add Doctor
      </a>
    </div>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Specialization</th>
            <th style="width: 180px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $result = $conn->query("SELECT * FROM doctors");
          $i = 1;
          while ($row = $result->fetch_assoc()):
          ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['speciality'] ?: 'N/A') ?></td>
            <td>
              <div class="d-flex gap-2">
                <a href="edit_doctor.php?id=<?= $row['id'] ?>" class="btn btn-edit">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="delete_doctor.php?id=<?= $row['id'] ?>" 
                   class="btn btn-delete">
                  <i class="bi bi-trash"></i> Delete
                </a>
              </div>
            </td>
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
