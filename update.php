<?php
session_start();
include("db.php");

// ✅ Only doctors can update appointments
if (!isset($_SESSION['id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: loginuser.php");
    exit;
}

$message = "";
$icon    = "error";

if (isset($_GET['id'], $_GET['action'])) {
    $appointment_id = intval($_GET['id']);
    $action         = $_GET['action'];
    $doctor_id      = $_SESSION['doctor_id'];

    // 🔹 1) Fetch current appointment
    $stmt = $conn->prepare("SELECT status FROM appointments WHERE id = ? AND doctor_id = ?");
    $stmt->bind_param("ii", $appointment_id, $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appt   = $result->fetch_assoc();
    $stmt->close();

    if (!$appt) {
        $message = "Appointment not found!";
    } else {
        // 🔹 2) Prevent modifying already cancelled by patient
        if (strtolower($appt['status']) === 'cancelled') {
            $message = "This appointment was already cancelled and cannot be changed.";
            $icon    = "warning";
        } else {
            // 🔹 3) Define allowed actions
            $allowed = [
                "confirm"  => "Confirmed",
                "cancel"   => "Cancelled",
                "complete" => "Completed",
                "pending"  => "Pending"
            ];
            $status = $allowed[$action] ?? null;

            if ($status) {
                // 🔹 4) Handle cancellation (add cancelled_by)
                if ($status === "Cancelled") {
                    $reason = isset($_POST['reason']) && trim($_POST['reason']) !== ''
                        ? trim($_POST['reason'])
                        : "Cancelled by doctor";

                    $stmt = $conn->prepare("
                        UPDATE appointments 
                        SET status = ?, cancel_reason = ?, cancelled_by = 'doctor' 
                        WHERE id = ? AND doctor_id = ?
                    ");
                    $stmt->bind_param("ssii", $status, $reason, $appointment_id, $doctor_id);

                } else {
                    // 🔹 5) Other actions (confirm/complete/pending)
                    $stmt = $conn->prepare("
                        UPDATE appointments 
                        SET status = ?, cancel_reason = NULL, cancelled_by = NULL 
                        WHERE id = ? AND doctor_id = ?
                    ");
                    $stmt->bind_param("sii", $status, $appointment_id, $doctor_id);
                }

                if ($stmt->execute()) {
                    $message = "Appointment has been {$status} successfully!";
                    $icon    = "success";
                } else {
                    $message = "Error updating appointment.";
                }

                $stmt->close();
            } else {
                $message = "Invalid action!";
            }
        }
    }
} else {
    header("Location: doctors.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Appointment</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
Swal.fire({
    icon: "<?php echo $icon; ?>",
    title: "<?php echo ucfirst($icon); ?>",
    text: "<?php echo $message; ?>",
    confirmButtonColor: '#28a745'
}).then(() => {
    window.location.href = "doctors.php";
});
</script>
</body>
</html>
