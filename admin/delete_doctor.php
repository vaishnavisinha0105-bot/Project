<?php
include("../db.php");
session_start();

// ✅ If no ID, stop
if (!isset($_GET['id'])) {
    die("Doctor ID is required.");
}

$doctor_id = intval($_GET['id']);

// ✅ Delete doctor logic
if (isset($_GET['confirm']) && $_GET['confirm'] == "yes") {
    // Fetch image path
    $doctor_result = $conn->query("SELECT image FROM doctors WHERE id = $doctor_id");
    if ($doctor_result->num_rows > 0) {
        $doctor = $doctor_result->fetch_assoc();
        $image_path = "../" . $doctor['image'];
        if (file_exists($image_path)) {
            unlink($image_path); // delete image
        }
    }

    // Delete doctor
    $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);

    if ($stmt->execute()) {
        $_SESSION['delete_success'] = true;
        header("Location: doctor.php"); // redirect to doctor list page
        exit();
    } else {
        echo "Error deleting doctor.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delete Doctor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
// ✅ SweetAlert Confirmation
Swal.fire({
    title: "Are you sure?",
    text: "This doctor will be permanently deleted!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!"
}).then((result) => {
    if (result.isConfirmed) {
        // Redirect with confirmation
        window.location.href = "delete_doctor.php?id=<?= $doctor_id ?>&confirm=yes";
    } else {
        // Go back if cancelled
        window.location.href = "doctor.php";
    }
});
</script>
</body>
</html>
