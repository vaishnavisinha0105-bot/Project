<?php
include("db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = intval($_POST['patient_id']);
    $doctor_id = intval($_POST['doctor_id']);
    $date = $_POST['date'];
    $time = $_POST['time'];

    $today = date('Y-m-d');
    $currentTime = date('H:i');

    // ✅ Validate: no past dates
    if ($date < $today) {
        die("<script>
            alert('❌ Invalid date! You cannot book an appointment in the past.');
            window.history.back();
        </script>");
    }

    // ✅ Validate: if booking for today, time must be in the future
    if ($date === $today && $time <= $currentTime) {
        die("<script>
            alert('❌ Invalid time! You cannot book an appointment in the past.');
            window.history.back();
        </script>");
    }

    // ✅ Validate: avoid double booking same slot
    $check = $conn->prepare("SELECT id FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'Cancelled'");
    $check->bind_param("iss", $doctor_id, $date, $time);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("<script>
            alert('❌ This slot is already booked. Please choose another time.');
            window.history.back();
        </script>");
    }

    $check->close();

    // ✅ Insert appointment
    $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) 
            VALUES ('$patient_id', '$doctor_id', '$date', '$time', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        echo <<<EOT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Booking Success</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Appointment Booked!',
                    text: 'Your appointment has been successfully booked.',
                    confirmButtonText: 'Go to Home',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
            </script>
        </body>
        </html>
        EOT;
    } else {
        echo <<<EOT
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Error</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Booking Failed',
                    text: 'Something went wrong: {$conn->error}',
                    confirmButtonText: 'Try Again',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.history.back();
                    }
                });
            </script>
        </body>
        </html>
        EOT;
    }
}
?>
