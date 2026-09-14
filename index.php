<?php 
  $conn = new mysqli("localhost", "root", "", "doctor_directory");
  $result = $conn->query("SELECT * FROM categories");
?>

<?php
require 'db.php';
include("navbar.php");

// Check if a category is selected
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Fetch doctors in the selected category
    $query = "SELECT * FROM doctors WHERE category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $is_doctor_view = true;
} else {
    // Fetch all categories
    $query = "SELECT * FROM categories";
    $result = $conn->query($query);
    $is_doctor_view = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Documents</title>
  
  
  <link rel="stylesheet" href="/project/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="footer.css">
</head>

<body>


<!-- Hero Section -->
<section class="hero-section d-flex justify-content-center align-items-center mt-5 pt-5">
  <div class="text-center">
    <h1 class="display-5 fw-bold">Stay at Home. Stay Healthy. Book Doctor Online.</h1>
    <h2 class="display-5 fw-bold">Connecting You to Better Health.</h2>
    <p class="fw-bold">Book your Appointment</p>
  <a href="<?php echo isset($_SESSION['user']) ? 'user.php' : 'loginuser.php'; ?>" 
   class="btn-book mt-3" 
   style="z-index: 10; position: relative;">
   Book Now <span class="fas fa-chevron-right"></span>
</a>
</section>
<section id="categories">
<h2 class="our-doctors-heading">
    <span class="our">OUR</span> <span class="doctors"><?= $is_doctor_view ? 'DOCTORS' : 'CATEGORIES' ?></span>
</h2>

<div class="<?= $is_doctor_view ? 'doctor-grid' : 'category-grid' ?>">
<?php
while ($row = mysqli_fetch_assoc($result)) {
    $name = htmlspecialchars($row['name']);
    $image = htmlspecialchars($row['image']);

    if ($is_doctor_view) {
        // Showing DOCTORS
        $speciality = htmlspecialchars($row['speciality']);
        $experience = htmlspecialchars($row['experience']);
        $degree = htmlspecialchars($row['degree']);
        $hospital = htmlspecialchars($row['hospital']);
        ?>
        <div class="doctor-card">
            <img src="<?= $image ?>" alt="<?= $name ?>">
            <h3><?= $name ?></h3>
            <p> <?= $speciality ?></p>
            <p><strong>Experience:</strong> <?= $experience ?></p>
            <p><i class="fas fa-light fa-graduation-cap"></i><?= $degree ?></p>
            <p><i class="fas fa-map-marker-alt"></i> <?= $hospital ?></p>

             <a href="book_appointment.php?id=<?= $row['id'] ?>"
              class="btn" style="padding:10px 20px; background:#178181; color:white; text-decoration:none; border-radius:5px;">
                <i class="bi bi-calendar-check"></i> Book Appointment
             </a>
        </div>
        <?php
    } else {
        // Showing CATEGORIES
        ?>
        <a href="?category_id=<?= $row['id'] ?>" class="category-card" style="text-decoration: none; color: inherit;">
            <img src="<?= $image ?>" alt="<?= $name ?>">
            <h3><?= $name ?></h3>
        </a>
        <?php
    }
}
?>
</section>
</div>


     <?php if ($is_doctor_view): ?>
    <div style="text-align:center; margin-top: 20px;">
        <a href="index.php" class="btn-book">
            ← Back to Categories
        </a>
    </div>
<?php endif; ?>



<section id="online-consultation">
  <div class="benefits">
    <div class="card">
      
      <h2>Online Doctor Appointment with HealthCare</h2>
      <p>
        Online Doctor Appointment with HealthCare makes it easy to connect with top doctors anytime, anywhere.
        You can book appointments in just a few clicks, healthcare becomes simple, seamless, and accessible for everyone.
      </p>

      <!-- Benefits -->
      <h3>Benefits of Online Doctor Appointment</h3>
      <ul>
        <li><strong>Convenience:</strong> Access MD-level doctors.</li>
        <li><strong>Time-saving:</strong> Schedule a doctor’s appointment in under 10 minutes.</li>
        <li><strong>Instant Booking:</strong> Get confirmed appointments in just a few clicks.</li>
        <li><strong>Family Appointments:</strong> Schedule consultations for your loved ones too.</li>
        <li><strong>Flexibility:</strong> Connect with a doctor at your convenience.</li>
      </ul>

      <!-- Specialties -->
      <h3>Top Asked Specialities for Online Doctor Appointment</h3>
      <ul>
        <li><strong>General Medicine:</strong> Diagnosis and non-surgical treatment of internal organ diseases.</li>
        <li><strong>Dermatology:</strong> A specialty that covers hair, nails, and skin disorders with both medical and surgical aspects.</li>
        <li><strong>Gynecology:</strong> Deals with women's health issues.</li>
        <li><strong>Obstetrics:</strong> Care during pre-conception, pregnancy, childbirth, and post-delivery.</li>
        <li><strong>Neurology:</strong> Study and treatment of nervous system disorders.</li>
        <li><strong>Orthopaedics:</strong> Diagnosis and treatment of disorders related to bones, joints, ligaments, tendons, and muscles.</li>
        <li><strong>Cardiology:</strong> Diagnosis and treatment of heart and blood vessel disorders.</li>
      </ul>

    </div>
  </div>
<section id="faq" class="py-5 bg-light">
  <div class="container">
    <h2 class="text-center mb-5 fw-bold text-success">Frequently Asked Questions</h2>
    <div class="row g-4">

      <!-- Left Column -->
      <div class="col-md-6">
        <div class="accordion" id="faqAccordion1">

          <!-- Q1 -->
          <div class="accordion-item faq-card mb-3">
            <h2 class="accordion-header" id="faq1">
              <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#answer1" aria-expanded="false" aria-controls="answer1">
                <span class="faq-icon">?</span>
                How do I schedule an online appointment with a doctor?
              </button>
            </h2>
            <div id="answer1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion1">
              <div class="accordion-body text-muted">
                You can schedule an online appointment by logging into your account and selecting your preferred doctor and time slot.
              </div>
            </div>
          </div>

          <!-- Q2 -->
          <div class="accordion-item faq-card mb-3">
            <h2 class="accordion-header" id="faq2">
              <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#answer2" aria-expanded="false" aria-controls="answer2">
                <span class="faq-icon">?</span>
                Can I book an appointment for a family member?
              </button>
            </h2>
            <div id="answer2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion1">
              <div class="accordion-body text-muted">
                Yes, you can schedule appointments for your family members under your account.
              </div>
            </div>
          </div>

          <!-- Q3 -->
          <div class="accordion-item faq-card mb-3">
            <h2 class="accordion-header" id="faq3">
              <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#answer3" aria-expanded="false" aria-controls="answer3">
                <span class="faq-icon">?</span>
                Can I have an online appointment with my regular doctor?
              </button>
            </h2>
            <div id="answer3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion1">
              <div class="accordion-body text-muted">
                Yes, if your doctor is available online, you can book an appointment with them.
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- Right Column -->
      <div class="col-md-6">
        <div class="accordion" id="faqAccordion2">

          <!-- Q4 -->
          <div class="accordion-item faq-card mb-3">
            <h2 class="accordion-header" id="faq4">
              <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#answer4" aria-expanded="false" aria-controls="answer4">
                <span class="faq-icon">?</span>
                What if I miss my online appointment?
              </button>
            </h2>
            <div id="answer4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion2">
              <div class="accordion-body text-muted">
                If you miss your appointment, you can reschedule or contact support for further assistance.
              </div>
            </div>
          </div>

          <!-- Q5 -->
          <div class="accordion-item faq-card mb-3">
            <h2 class="accordion-header" id="faq5">
              <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#answer5" aria-expanded="false" aria-controls="answer5">
                <span class="faq-icon">?</span>
                Can I cancel or reschedule an appointment?
              </button>
            </h2>
            <div id="answer5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion2">
              <div class="accordion-body text-muted">
                Yes, you can easily cancel or reschedule from your account dashboard.
              </div>
            </div>
          </div>

          <!-- Q6 -->
          <div class="accordion-item faq-card mb-3">
            <h2 class="accordion-header" id="faq6">
              <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#answer6" aria-expanded="false" aria-controls="answer6">
                <span class="faq-icon">?</span>
                Can I book an appointment without creating an account?
              </button>
            </h2>
            <div id="answer6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion2">
              <div class="accordion-body text-muted">
                No, for security and medical record purposes, you need to create an account to book.
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<?php include("footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>