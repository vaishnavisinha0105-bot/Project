<?php
include "navbar.php";

if (!isset($_SESSION['user'])) {
    header("Location: loginuser.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "doctor_directory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['user'];
$patientData = null;
$apptResult = null;

// Check if patient exists
$result = $conn->query("SELECT * FROM patients WHERE email = '$email'");
if ($result && $result->num_rows > 0) {
    $patientData = $result->fetch_assoc();

    // Fetch appointments
    $apptResult = $conn->query("
        SELECT a.*, d.name AS doctor_name, d.speciality 
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.id
        WHERE a.patient_id = '{$patientData['id']}'
        ORDER BY a.id DESC");
}

// Register new patient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$patientData && !isset($_POST['update_patient']) && !isset($_POST['cancel_appointment'])) {
    $name = trim($_POST['name']);
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $address = trim($_POST['address']);
    $past_history = trim($_POST['past_history']);

    $sql = "INSERT INTO patients (name, gender, age, email, phone, address, past_history)
            VALUES ('$name', '$gender', '$age', '$email', '$phone', '$address', '$past_history')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['register_success'] = true;
        header("Location: user.php");
        exit();
    }
}

// Update patient
if (isset($_POST['update_patient'])) {
    $name = trim($_POST['name']);
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $address = trim($_POST['address']);
    $past_history = trim($_POST['past_history']);

    $sql = "UPDATE patients
            SET name='$name', gender='$gender', age='$age', phone='$phone', address='$address', past_history='$past_history'
            WHERE email='$email'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['update_success'] = true;
        header("Location: user.php");
        exit();
    }
}

// Cancel appointment
if (isset($_POST['cancel_appointment'])) {
    $apptId = intval($_POST['cancel_id']);
    $reason = $conn->real_escape_string(trim($_POST['reason']));
    
    $sql = "UPDATE appointments
            SET status='Cancelled',
                cancel_reason='$reason',
                cancelled_by='user'
            WHERE id=$apptId";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['cancel_success'] = true;
        header("Location: user.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">
      <style>
/* Heading */
.page-heading {
  text-align: center;
  font-size: 22px;
  margin: 35px 0;
  color: #115449;
  font-weight: bold;
}

/* Patient Card */
.patient-card {
  max-width: 600px;
  margin: 0 auto 30px auto;
  background: #fff;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  text-align: left;
}
.patient-card h3 {
  font-size: 22px;
  color: #178181;
  margin-bottom: 10px;
  border-bottom: 2px solid #178181;
  padding-bottom: 8px;
}
.patient-card p {
  font-size: 15px;
  margin: 6px 0;
  color: #333;
}
.patient-card strong {
  color: #178181;
}

/* TABLE */
.table-responsive {
  max-width: 95%;
  margin: 0 auto 30px auto;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
  overflow-x: auto;
}
.table {
  width: 100%;
  border-collapse: collapse;
}
.table thead {
  background: #178181;
  color: #fff;
}
.table th, .table td {
  padding: 12px 15px;
  text-align: center;
  font-size: 14px;
}
.table tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}
.table tbody tr:hover {
  background: #e8f6f3;
  transition: 0.3s ease;
}

/* BADGES */
.badge {
  display: inline-block;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: bold;
  color: #fff;
}
.bg-success { background: #2a9d8f; }
.bg-warning { background: #f4b400; }
.bg-danger  { background: #d9534f; }

/* Buttons */
.btn-update,
.btn-appointment {
  background: #178181;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 500;
  transition: 0.3s ease;
}
.btn-update:hover,
.btn-appointment:hover {
  background: #0f5a5a;
}

/* Modal Card */
.modal-content {
  border-radius: 15px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.2);
  border: none;
}
.modal-header {
  background: #178181;
  color: #fff;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
}
.modal-title { font-weight: 600; }
.modal-body label {
  font-weight: 500;
  color: #178181;
}
.modal-footer { border-top: none; }

/* Modal Buttons */
.btn-cancel {
  background: #ccc;
  color: #333;
  border: none;
  padding: 8px 18px;
  border-radius: 8px;
  font-weight: 500;
  transition: 0.3s;
}
.btn-cancel:hover {
  background: #999;
  color: #fff;
}
.btn-save {
  background: #178181;
  color: #fff;
  border: none;
  padding: 8px 18px;
  border-radius: 8px;
  font-weight: 500;
  transition: 0.3s;
}
.btn-save:hover { background: #0f5a5a; }

/* Cancel Button – same style as Pending badge */
.btn-cancel {
  background: #b52323ff; ;      
  color: #fff;               /* white text */
  font-weight: 600;
  font-size: 0.9rem;
  padding: 6px 14px;
  border: none;
  border-radius: 20px;       /* pill shape */
  cursor: pointer;
  transition: background 0.3s ease;
}

.btn-cancel:hover {
  background: #841513ff;       /* darker on hover */
}

    </style>
</head>
<body>
<section class="contact-section">
   <div class="form-page">

  <!-- ✅ Patient Registration -->
    <?php if (!$patientData): ?>
        <h2 class="page-heading">Fill the Patient Details</h2>
        <div class="form-container">
            <form method="POST" onsubmit="return validatePatientForm();">
                <div class="form-row">
                    <input type="text" name="name" placeholder="Full Name" required pattern="[A-Za-z ]+" title="Name should contain only letters and spaces">
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-row">
                    <input type="number" name="age" placeholder="Age" required min="0" max="120">
                    <input type="tel" name="phone" placeholder="Your Mobile Number" pattern="[0-9]{10}" title="10-digit phone number"  maxlength="10"  required>
                </div>
                <div class="form-row">
                    <input type="email" name="email" placeholder="Your Email ID" value="<?= htmlspecialchars($email) ?>" readonly>
                    <input type="text" name="address" placeholder="Address" required minlength="5">
                </div>
                <div class="form-row">
                    <textarea name="past_history" placeholder="Past Medical History"></textarea>
                </div>
                <div class="button-row">
                    <button type="submit" class="btn-appointment">Submit</button>
                </div>
            </form>
        </div>
    <?php endif; ?>


    <?php if ($patientData): ?>
        <h2 class="page-heading">Patient Details</h2>
        <div class="patient-card">
            <h3><?= htmlspecialchars($patientData['name']) ?></h3>
            <p><strong>Gender:</strong> <?= htmlspecialchars($patientData['gender']) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($patientData['age']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($patientData['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($patientData['phone']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($patientData['address']) ?></p>
            <p><strong>Past History:</strong> <?= htmlspecialchars($patientData['past_history']) ?></p>

            <button class="btn-update mt-3" data-bs-toggle="modal" data-bs-target="#updateModal">
                ✏️ Update Details
            </button>
        </div>

        <div style="margin-top:20px; text-align:center;">
        <?php if ($apptResult && $apptResult->num_rows > 0): ?>
            <h2 class="page-heading">Your Appointments</h2>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Doctor Name</th>
                            <th>Speciality</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $apptResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                                <td><?= htmlspecialchars($row['speciality']) ?></td>
                                <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                                <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                               <td>
                                    <span class="badge 
                                        <?= ($row['status'] === 'Confirmed') ? 'bg-success' : 
                                          (($row['status'] === 'Rejected' || $row['status'] === 'Cancelled') ? 'bg-danger' : 'bg-warning'); ?>">
                                        <?= htmlspecialchars($row['status']); ?>
                                    </span>

                                    <?php if ($row['status'] !== 'Cancelled'): ?>
                                        <button class="btn-cancel btn-sm mt-2" 
                                            data-id="<?= $row['id']; ?>"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cancelModal">Cancel
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($row['status'] === 'Cancelled' && !empty($row['cancel_reason'])): ?>
                                        <div style="font-size:12px; margin-top:5px; color:#b52323;">
                                            <?= htmlspecialchars($row['cancel_reason']); ?>
                                        </div>
                                    <?php endif; ?>
                              </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <a href="index.php?patient_id=<?= $patientData['id'] ?>#categories" class="btn-appointment">📅 Book Appointment</a>
        <?php endif; ?>
        </div>
    <?php endif; ?>

</div>
</section>

<!-- Cancel Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="cancelModalLabel">Cancel Appointment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="cancel_id" id="cancel_id">
            <label for="reason">Reason for cancellation:</label>
            <textarea name="reason" id="reason" class="form-control" required></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
          <button type="submit" name="cancel_appointment" class="btn-save">✅ Confirm Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ✅ Update Patient Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" onsubmit="return validateUpdateForm();">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Update Patient Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3"><label>Name</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($patientData['name']) ?>" required pattern="[A-Za-z ]+"></div>
            <div class="mb-3"><label>Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="Male" <?= $patientData['gender']=="Male"?"selected":"" ?>>Male</option>
                    <option value="Female" <?= $patientData['gender']=="Female"?"selected":"" ?>>Female</option>
                    <option value="Other" <?= $patientData['gender']=="Other"?"selected":"" ?>>Other</option>
                </select>
            </div>
            <div class="mb-3"><label>Age</label><input type="number" name="age" class="form-control" value="<?= htmlspecialchars($patientData['age']) ?>" min="0" max="120" required></div>
            <div class="mb-3"><label>Phone</label><input type="tel" name="phone" class="form-control"  maxlength="10"  value="<?= htmlspecialchars($patientData['phone']) ?>" pattern="[0-9]{10}" required></div>
            <div class="mb-3"><label>Address</label><input type="text" name="address" class="form-control" value="<?= htmlspecialchars($patientData['address']) ?>" required minlength="5"></div>
            <div class="mb-3"><label>Past History</label><textarea name="past_history" class="form-control"><?= htmlspecialchars($patientData['past_history']) ?></textarea></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="update_patient" class="btn-save">💾 Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('.btn-cancel').forEach(btn => {
    btn.addEventListener('click', function(){
        document.getElementById('cancel_id').value = this.dataset.id;
    });
});

// ✅ JS validation for new patient registration
function validatePatientForm() {
  const name = document.querySelector("[name='name']").value.trim();
  const gender = document.querySelector("[name='gender']").value;
  const age = parseInt(document.querySelector("[name='age']").value);
  const phone = document.querySelector("[name='phone']").value.trim();
  const address = document.querySelector("[name='address']").value.trim();

  if (!/^[A-Za-z ]+$/.test(name)) {
      alert("Name should only contain letters and spaces.");
      return false;
  }
  if (!gender) {
      alert("Please select a gender.");
      return false;
  }
  if (isNaN(age) || age < 0 || age > 120) {
      alert("Please enter a valid age between 0 and 120.");
      return false;
  }
  if (!/^[0-9]{10}$/.test(phone)) {
      alert("Phone must be exactly 10 digits.");
      return false;
  }
  if (address.length < 5) {
      alert("Address must be at least 5 characters long.");
      return false;
  }
  return true;
}

// ✅ JS validation for update form
function validateUpdateForm() {
  const name = document.querySelector("#updateModal [name='name']").value.trim();
  const age = parseInt(document.querySelector("#updateModal [name='age']").value);
  const phone = document.querySelector("#updateModal [name='phone']").value.trim();
  const address = document.querySelector("#updateModal [name='address']").value.trim();

  if (!/^[A-Za-z ]+$/.test(name)) {
      alert("Name should only contain letters and spaces.");
      return false;
  }
  if (isNaN(age) || age < 0 || age > 120) {
      alert("Please enter a valid age between 0 and 120.");
      return false;
  }
  if (!/^[0-9]{10}$/.test(phone)) {
      alert("Phone must be exactly 10 digits.");
      return false;
  }
  if (address.length < 5) {
      alert("Address must be at least 5 characters long.");
      return false;
  }
  return true;
}
</script>

</body>
</html>
