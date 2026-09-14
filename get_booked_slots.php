<?php
include("db.php");

$doctor_id = intval($_GET['doctor_id']);
$date = $_GET['date'];

$today = date('Y-m-d');
$currentTime = date('H:i');

// ❌ Reject past dates immediately
if ($date < $today) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$sql = "SELECT appointment_time 
        FROM appointments 
        WHERE doctor_id = $doctor_id 
        AND appointment_date = '$date' 
        AND status != 'Cancelled'";

$result = $conn->query($sql);
$slots = [];

while ($row = $result->fetch_assoc()) {
    $time = substr($row['appointment_time'], 0, 5); // HH:MM

    // ✅ For today, ignore already passed times
    if ($date === $today && $time <= $currentTime) {
        continue;
    }

    $slots[] = $time;
}

header('Content-Type: application/json');
echo json_encode($slots);
