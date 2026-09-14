<?php
include("db.php");
include("navbar.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $mobile     = trim($_POST['mobile']);
    $email      = trim($_POST['email']);
    $message    = trim($_POST['message']);

    // Validate names (only alphabets and spaces)
    if (!preg_match("/^[a-zA-Z ]+$/", $first_name)) {
        $_SESSION['msg_error'] = "First name should contain only alphabets!";
        header("Location: contact.php"); exit;
    }
    if (!empty($last_name) && !preg_match("/^[a-zA-Z ]+$/", $last_name)) {
        $_SESSION['msg_error'] = "Last name should contain only alphabets!";
        header("Location: contact.php"); exit;
    }

    // Validate mobile (exactly 10 digits)
    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $_SESSION['msg_error'] = "Mobile number must be exactly 10 digits!";
        header("Location: contact.php"); exit;
    }

    // Now proceed with DB insert
    $stmt = $conn->prepare("INSERT INTO contact_messages (first_name, last_name, mobile, email, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $mobile, $email, $message);

    if ($stmt->execute()) {
        $_SESSION['msg_success'] = "Your message has been submitted successfully!";
    } else {
        $_SESSION['msg_error'] = "Error: " . $stmt->error;
    }

    header("Location: contact.php"); // refresh to show SweetAlert
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contact Us</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="footer.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

  <section class="contact-section">
    <h2 class="contact-heading">How Can We Help You?</h2>

    <div class="contact-box">
      <p class="contact-subtitle">
        If you are a <strong>Patient</strong>, or wish to <strong>Partner with us</strong>
      </p>
      <p class="contact-description">
        Contact our customer support team for your queries
      </p>

      <hr />

      <div class="contact-method">
        <i class="fas fa-envelope"></i>
          <strong>Email:</strong>
          <a href="mailto:support@healthcare.in" class="highlight">support@healthcare.in</a>
        
      </div>

      <div class="contact-method">
        <i class="fa-brands fa-square-whatsapp"></i>
         <strong>WhatsApp:</strong>
          <a href="https://wa.me/919818093267" class="highlight">+91 9818093267</a>
          <small>(Available 9 AM – 8 PM, Monday–Sunday)</small>
      </div>

      <div class="contact-method">
        <i class="fa-solid fa-phone"></i>
         <strong>Call:</strong>
          <a href="tel:01141183001" class="highlight">(011)–4118 3001</a>
          <small>(Available 9 AM – 8 PM, Monday–Sunday)</small>
      </div>

    </div>

    <p class="form-heading"><strong>Or Fill in the Form Below</strong></p>

    <form class="contact-form" method="POST" action="">

      <div class="form-row">
      <input type="text" name="first_name" placeholder="First Name" 
            pattern="[A-Za-z ]+" title="Only alphabets allowed" required>
      <input type="text" name="last_name" placeholder="Last Name" 
            pattern="[A-Za-z ]+" title="Only alphabets allowed">
      </div>

      <div class="form-row">
      <input type="tel" name="mobile" placeholder="Your Mobile Number" 
            pattern="[0-9]{10}" maxlength="10" 
            title="Enter a valid 10-digit mobile number" required>
      <input type="email" name="email" placeholder="Your Email ID" required>
      </div>
      <textarea name="message" placeholder="Ask Your Question" required></textarea>

      <div class="button-row">
          <button type="submit">Submit</button>
      </div>
    </form>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(isset($_SESSION['msg_success'])): ?>
<script>
Swal.fire("✅ Success", "<?= $_SESSION['msg_success'] ?>", "success");
</script>
<?php unset($_SESSION['msg_success']); endif; ?>

<?php if(isset($_SESSION['msg_error'])): ?>
<script>
Swal.fire("❌ Error", "<?= $_SESSION['msg_error'] ?>", "error");
</script>
<?php unset($_SESSION['msg_error']); endif; ?>
<?php include("footer.php"); ?>
</body>
</html>
