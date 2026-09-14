<?php
session_start();
include '../db.php'; 

$sql = "SELECT a.id,
               p.name AS patient_name, p.phone, p.email,
               d.name AS doctor_name, d.speciality,
               a.appointment_date, a.appointment_time,
               a.status, a.cancel_reason, a.cancelled_by, a.created_at
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN doctors d ON a.doctor_id = d.id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointments - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
  body {
    background: #f0fdfd;
    font-family: 'Segoe UI', sans-serif;
    color: #0f4c5c;
  }

  .table-card {
    background: #fff;
    border-radius: 20px;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }

  .page-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 700;
    margin-bottom: 16px;
    color: #0f4c5c;
  }

  .custom-table {
    border-collapse: separate;
    border-spacing: 0 6px;
    width: 100%;
    font-size: 14px; /* ✅ smaller text */
    table-layout: auto; /* allows flexible shrinking */
  }

  .custom-table thead {
    background: #009688;
    color: #fff;
  }

  .custom-table thead th {
    padding: 10px 8px; /* ✅ tighter padding */
    border: none;
    font-weight: 600;
    white-space: nowrap; /* prevents wrapping in headers */
  }

  .custom-table tbody tr {
    background: #f9ffff;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
  }

  .custom-table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
  }

  .custom-table tbody td {
    padding: 10px 8px; /* ✅ smaller padding */
    border: none;
    vertical-align: middle;
    word-break: break-word; /* ✅ prevent overflow */
  }

  /* ✅ Optional: limit width of contact and reason columns */
  td:nth-child(3), /* contact */
  td:nth-child(9) { /* cancelled by / reason */
    max-width: 150px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  @media (max-width: 992px) {
    .custom-table {
      font-size: 13px;
    }
    .custom-table thead th, .custom-table tbody td {
      padding: 8px 6px;
    }
  }
</style>

</head>
<body>

<div class="container mt-4">
  <div class="table-card">
    <h3 class="page-title"><i class="bi bi-calendar-check-fill"></i> Appointments</h3>

    <div class="table-responsive">
      <table class="custom-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Patient</th>
            <th>Contact</th>
            <th>Doctor</th>
            <th>Speciality</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Cancelled By</th> <!-- ✅ New Column Added -->
            <th>Booked At</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $i = 1;
          while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
            <td>
              <?= htmlspecialchars($row['phone']) ?><br>
              <?= htmlspecialchars($row['email']) ?>
            </td>
            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
            <td><?= htmlspecialchars($row['speciality']) ?></td>
            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
            <td><?= htmlspecialchars(substr($row['appointment_time'],0,5)) ?></td>
            <td>
              <span class="badge bg-<?=
                $row['status']=='Pending' ? 'warning' : 
                ($row['status']=='Confirmed' ? 'success' : 
                ($row['status']=='Completed' ? 'primary' : 'danger')) 
              ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>

              <?php if ($row['status'] === 'Cancelled' && !empty($row['cancel_reason'])): ?>
                <div class="text-muted small mt-1">
                  <?= htmlspecialchars($row['cancel_reason']) ?>
                </div>
              <?php endif; ?>
            </td>

            <td>
              <?= $row['cancelled_by'] ? ucfirst(htmlspecialchars($row['cancelled_by'])) : '—' ?>
            </td> <!-- ✅ Display who cancelled -->

            <td><?= htmlspecialchars($row['created_at']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
