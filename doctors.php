<?php
session_start();
$conn = new mysqli("localhost", "root", "", "doctor_directory");

// check if doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header("Location: loginuser.php");
    exit();
}

$doctorId = $_SESSION['doctor_id'];
$doctorName = $_SESSION['doctor_name'] ?? "Doctor";

// fetch appointments with patient details & cancel reason
$sql = "
  SELECT 
    a.id,
    a.appointment_date,
    a.appointment_time,
    a.status,
    a.cancel_reason,
    p.name AS patient_name,
    p.gender,
    p.age,
    p.email,
    p.phone,
    p.address,
    p.past_history
  FROM appointments a
  JOIN patients p ON p.id = a.patient_id
  WHERE a.doctor_id = ?
  ORDER BY a.appointment_date, a.appointment_time
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctorId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Dashboard - My Appointments</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      color: #333;
      position: relative;
      overflow-x: hidden;
    }
    body::before {
      content: "";
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: url('uploads/image/img1.jpg') no-repeat center center fixed;
      background-size: cover;
      filter: blur(5px);
      z-index: -1;
    }

    .navbar {
      background: #fff;
      padding: 15px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #178181;
      box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }
    .navbar .logo { font-size: 1.4rem; font-weight: bold; }
    .navbar .nav-links { display: flex; gap: 20px; align-items: center; }
    .navbar span { font-weight: 500; font-size: 1rem; }
    .navbar a.logout {
      background: #178181; color: #fff; padding: 8px 16px;
      border-radius: 6px; text-decoration: none;
      transition: background 0.3s;
    }
    .navbar a.logout:hover { background: #218838; }

    h2 { text-align: center; color: #00796b; margin: 30px 0 15px; font-size: 1.8rem; }

    .appointments-container {
      max-width: 95%;
      margin: 30px auto;
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 25px rgba(0,0,0,0.15);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      border-radius: 12px;
      overflow: hidden;
    }
    th, td { padding: 12px 15px; border: 1px solid #ddd; text-align: left; }
    th { background-color: #00796b; color: #fff; font-weight: 600; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    tr:hover { background-color: #eef7f7; }

    .status {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
      text-transform: capitalize;
      display: inline-block;
    }
    .status.confirmed { background: #e0f7fa; color: #00796b; }
    .status.pending { background: #fff3cd; color: #856404; }
    .status.completed { background: #d4edda; color: #155724; }
    .status.cancelled { background: #f8d7da; color: #721c24; }

    .btn {
      padding: 7px 14px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-size: 0.85rem;
      margin: 2px;
      transition: background 0.3s ease, transform 0.1s ease;
    }
    .btn:hover { transform: scale(1.05); }
    .btn-confirm { background: #00796b; color: #fff; }
    .btn-confirm:hover { background: #00695c; }
    .btn-cancel { background: #f44336; color: #fff; }
    .btn-cancel:hover { background: #d32f2f; }
    .btn-complete { background: #4caf50; color: #fff; }
    .btn-complete:hover { background: #388e3c; }

    .row-cancelled { opacity: 0.6; }
    td em { color:#999; }

    .no-appointments {
      text-align: center;
      margin-top: 50px;
      font-size: 1.1rem;
      color: #666;
    }
  </style>
</head>
<body>

<div class="navbar">
  <div class="logo">
    <i class="fas fa-notes-medical me-2 fs-4"></i> HealthCare
  </div>
  <div class="nav-links">
    <span>Welcome <?= htmlspecialchars($doctorName) ?></span>
    <a href="logout.php" class="logout">Logout</a>
  </div>
</div>

<h2>My Appointments</h2>

<div class="appointments-container">
  <?php if ($result->num_rows > 0) { ?>
    <table>
      <thead>
        <tr>
          <th>Patient Name</th>
          <th>Gender</th>
          <th>Age</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Past History</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
          <th>Reason</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()) { 
            $isCancelled = strtolower($row['status']) === 'cancelled'; ?>
          <tr class="<?= $isCancelled ? 'row-cancelled' : '' ?>">
            <td><?= htmlspecialchars($row['patient_name']) ?></td>
            <td><?= htmlspecialchars($row['gender']) ?></td>
            <td><?= htmlspecialchars($row['age']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= htmlspecialchars($row['past_history']) ?></td>
            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
            <td><?= htmlspecialchars($row['appointment_time']) ?></td>
            <td>
              <span class="status <?= strtolower($row['status']) ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
            </td>
            <td>
              <?php if ($isCancelled && !empty($row['cancel_reason'])): ?>
                <?= htmlspecialchars($row['cancel_reason']) ?>
              <?php else: ?>
                <em>—</em>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($isCancelled): ?>
                <em style="color:#777;">No actions available</em>
              <?php else: ?>
                <a href="update.php?id=<?= $row['id'] ?>&action=confirm">
                  <button class="btn btn-confirm">Confirm</button>
                </a>
                <a href="update.php?id=<?= $row['id'] ?>&action=cancel">
                  <button class="btn btn-cancel">Cancel</button>
                </a>
                <a href="update.php?id=<?= $row['id'] ?>&action=complete">
                  <button class="btn btn-complete">Complete</button>
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    <div class="no-appointments">🎉 No appointments scheduled.</div>
  <?php } ?>
</div>

</body>
</html>
