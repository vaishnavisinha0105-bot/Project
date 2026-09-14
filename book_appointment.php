<?php
include("db.php");
include("navbar.php");

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: loginuser.php");
    exit();
}

// Fetch patient info
$email = $_SESSION['user'];
$patient = $conn->query("SELECT * FROM patients WHERE email = '$email'")->fetch_assoc();

// If patient not found
if (!$patient) {
    echo <<<EOT
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Redirecting...</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please register as a patient first!',
                confirmButtonText: 'Go to Registration',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'user.php';
                }
            });
        </script>
    </body>
    </html>
    EOT;
    exit();
}

$patient_id = $patient['id'];

// Doctor ID validation
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Doctor ID is missing.");
}
$doctor_id = intval($_GET['id']);
$doctor = $conn->query("SELECT * FROM doctors WHERE id = $doctor_id")->fetch_assoc();
if (!$doctor) {
    die("❌ Doctor not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      color: #333;
      position: relative; /* to stack content above pseudo-element */
      overflow-x: hidden;
    }

    /* Blurred background image */
    body::before {
      content: "";
      position: fixed;
      top: 0; 
      left: 0;
      width: 100%; 
      height: 100%;
      background: url('uploads/image/img1.jpg') no-repeat center center fixed;
      background-size: cover;
      filter: blur(5px); /* increase value for stronger blur */
      z-index: -1; /* keep it behind everything */
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        margin-top:70px;
    }

</style>
</head>
<body class="bg-light p-4">

<div class="container">
  <div class="card p-4 shadow-lg">
    <div class="d-flex align-items-center">
      <img src="<?= $doctor['image'] ?>" width="120" class="rounded-circle me-3">
      <div>
        <h3><?= htmlspecialchars($doctor['name']) ?></h3>
        <p><?= htmlspecialchars($doctor['speciality']) ?> | <?= htmlspecialchars($doctor['experience']) ?></p>
        <p><strong><?= htmlspecialchars($doctor['degree']) ?></strong></p>
        <p><?= htmlspecialchars($doctor['hospital']) ?></p>
      </div>
    </div>

    <hr>
    <h4>Select Appointment Slot</h4>

    <form method="POST" action="confirm_booking.php">
      <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
      <input type="hidden" name="patient_id" value="<?= $patient_id ?>">

      <!-- Date Picker -->
      <div class="mb-3 col-md-4">
        <label class="form-label">Choose Date</label>
        <input 
          type="date" 
          id="appointmentDate" 
          class="form-control" 
          name="date" 
          required 
          min="<?= date('Y-m-d'); ?>" >
        
      </div>

      <!-- Time Slots -->
      <div id="timeSlots" class="d-flex flex-wrap gap-2"></div>
      <input type="hidden" name="time" id="selectedTime">

      <button type="submit" class="btn btn-success mt-3">Confirm Booking</button>
    </form>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const timeSlotsContainer = document.getElementById("timeSlots");
    const selectedTimeInput = document.getElementById("selectedTime");
    const dateInput = document.getElementById("appointmentDate");

    function generateSlots(start, end, interval) {
        let slots = [];
        let startTime = new Date("1970-01-01T" + start + ":00");
        let endTime = new Date("1970-01-01T" + end + ":00");

        while (startTime < endTime) {
            let hours = startTime.getHours().toString().padStart(2, "0");
            let minutes = startTime.getMinutes().toString().padStart(2, "0");
            slots.push(`${hours}:${minutes}`);
            startTime.setMinutes(startTime.getMinutes() + interval);
        }
        return slots;
    }

    dateInput.addEventListener("change", function () {
        const selectedDate = dateInput.value;
        if (!selectedDate) return;

        const today = new Date().toISOString().split("T")[0];

        fetch(`get_booked_slots.php?doctor_id=<?= $doctor_id ?>&date=${selectedDate}`)
            .then(res => res.json())
            .then(bookedSlots => {
                timeSlotsContainer.innerHTML = "";
                let slots = generateSlots("09:00", "17:00", 60); // every 60 mins

                const now = new Date();
                let currentTime = now.getHours().toString().padStart(2, "0") + ":" + 
                                  now.getMinutes().toString().padStart(2, "0");

                slots.forEach(slot => {
                    // ✅ Skip past slots if booking for today
                    if (selectedDate === today && slot <= currentTime) {
                        return;
                    }

                    let btn = document.createElement("button");
                    btn.type = "button";
                    btn.className = "btn btn-outline-primary";
                    btn.textContent = slot;

                    // Disable if already booked
                    if (bookedSlots.includes(slot)) {
                        btn.classList.remove("btn-outline-primary");
                        btn.classList.add("btn-danger");
                        btn.disabled = true;
                    }

                    btn.addEventListener("click", function () {
                        document.querySelectorAll("#timeSlots button").forEach(b => b.classList.remove("active"));
                        btn.classList.add("active");
                        selectedTimeInput.value = slot;
                    });

                    timeSlotsContainer.appendChild(btn);
                });

                if (timeSlotsContainer.innerHTML === "") {
                    timeSlotsContainer.innerHTML = "<p class='text-danger'>No slots available for this date.</p>";
                }
            });
    });
});
</script>

</body>
</html>
