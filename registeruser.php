<?php
include("db.php");
include("navbar.php");

$alert = "";

if (isset($_POST['Register'])) {
    // Read POST values safely (avoid undefined index warnings)
    $name         = trim($_POST['name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $password_raw = $_POST['password'] ?? '';    // <<<--- IMPORTANT: make sure this is set BEFORE validation
    $userType     = $_POST['user_type'] ?? '';

    // Basic server-side validation
    if ($name === '' || $email === '' || $password_raw === '' || $userType === '') {
        $alert = "
            <script>
                Swal.fire('Error!', 'Please fill in all required fields.', 'error');
            </script>
        ";
    }
    elseif (!preg_match("/^[A-Za-z ]+$/", $name)) {
        $alert = "
            <script>
                Swal.fire('Error!', 'Name must contain only letters and spaces.', 'error');
            </script>
        ";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert = "
            <script>
                Swal.fire('Error!', 'Please enter a valid email address.', 'error');
            </script>
        ";
    }
    // Password strength: min 8 chars, 1 upper, 1 lower, 1 digit, 1 special
    elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password_raw)) {
        $alert = "
            <script>
                Swal.fire('Error!', 'Password must be at least 8 characters and include uppercase, lowercase, number and special character.', 'error');
            </script>
        ";
    }
    else {
        $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

        // Check if email already registered
        $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $alert = "
                <script>
                    Swal.fire('Error!', 'Email already registered!', 'error');
                </script>
            ";
            $stmt->close();
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO user (name, email, password, user_type) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password_hashed, $userType);

            if ($stmt->execute()) {
                $alert = "
                    <script>
                        Swal.fire({
                            title: 'Registration Successful!',
                            text: 'You can now log in.',
                            icon: 'success',
                            confirmButtonText: 'Login',
                            confirmButtonColor: '#28a745'
                        }).then(() => { window.location.href = 'loginuser.php'; });
                    </script>
                ";
            } else {
                $alert = "
                    <script>
                        Swal.fire('Error!', 'Something went wrong during registration.', 'error');
                    </script>
                ";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - HealthCare</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f2f6f9;
    }
     /* Wrapper under navbar */
    .main-content {
      margin-top: 80px; /* same as navbar height */
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: calc(100vh - 80px);
    }

    .container {
      width: 900px;
      max-width: 95%;
      height: 500px;
      display: flex;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      overflow: hidden;
      
    }

    /* Left Side - Registration Form */
    .register-section {
      flex: 1;
      background: #ffffff; /* solid white left side */
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: center;
    }
    .register-section h2 {
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 20px;
      text-align: center;
      color: #222;
    }
    .form-group {
      margin-bottom: 18px;
    }
    .form-group input, 
    .form-group select {
      width: 100%;
      padding: 12px 14px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
      outline: none;
      transition: 0.3s;
    }
    .form-group input:focus,
    .form-group select:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 2px rgba(0,123,255,0.2);
    }
    .btn {
      width: 100%;
      padding: 12px;
      background: #178181;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn:hover { background: #218838; }

    /* Right Side - Login Panel */
    .login-section {
      flex: 1;
      background: linear-gradient(135deg, #59c7ba, #115449);
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 40px;
    }
    .login-section h2 {
       font-size: 30px;
      margin-bottom: 15px;
      color: #fff;
    }
    .login-section p {
     margin-bottom: 25px;
      font-size: 16px;
      color: #f8f9fa;
    }
    .login-btn {
      padding: 12px 28px;
      background: white;
      color: #007bff;
      border-radius: 25px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s;
    }
    .login-btn:hover { background: #f2f2f2; }

     @media (max-width: 992px) {
      .container {
        flex-direction: column;
        height: auto;
      }

      .register-section, .login-section {
        width: 100%;
        padding: 40px 25px;
      }

      .register-section h2 {
        font-size: 24px;
      }

      .login-section {
        border-top: 2px solid rgba(255,255,255,0.2);
      }

      .login-section h2 {
        font-size: 26px;
      }

      .login-section p {
        font-size: 15px;
      }

      .login-btn {
        padding: 10px 22px;
      }
    }

    @media (max-width: 576px) {
      .container {
        width: 95%;
        box-shadow: none;
        border-radius: 0;
      }

      .register-section, .login-section {
        padding: 30px 20px;
      }

      .register-section h2 {
        font-size: 22px;
      }

      .form-group input, .form-group select {
        font-size: 14px;
        padding: 10px 12px;
      }

      .btn {
        font-size: 15px;
        padding: 10px;
      }

      .login-section h2 {
        font-size: 22px;
      }

      .login-section p {
        font-size: 14px;
      }

      .login-btn {
        font-size: 15px;
        padding: 8px 18px;
      }
    }
  </style>
</head>
<body>
   <div class="main-content">
    <div class="container">
    <div class="register-section">
      <h2>Create a New Account</h2>
      <!-- HTML5 validation attributes included -->
      <form action="registeruser.php" method="post" onsubmit="return validateForm();">
        <div class="form-group">
          <input type="text" name="name" placeholder="Full Name" required
                 pattern="[A-Za-z ]+" title="Name should contain only letters and spaces">
        </div>
        <div class="form-group">
          <input type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
          <input type="password" name="password" placeholder="Password" required
                 minlength="8" title="At least 8 characters">
        </div>
        <div class="form-group">
          <select name="user_type" required>
            <option value="">Select Role</option>
            <option value="doctor">Doctor</option>
            <option value="patient">Patient</option>
          </select>
        </div>
        <button type="submit" name="Register" class="btn">Sign Up</button>
      </form>
    </div>

    <div class="login-section">
      <h2><strong>Already Registered?</strong></h2>
      <p>Login to book appointments and manage your health records easily.</p>
      <a href="loginuser.php" class="login-btn">Login</a>
    </div>
  </div>
  </div>

  <!-- Optional JS + SweetAlert2 validation for better UX -->
  <script>
  function validateForm() {
      // Basic JS checks (optional) to give instant feedback before submission
      var name = document.querySelector("[name='name']").value.trim();
      var pwd  = document.querySelector("[name='password']").value;

      var nameRegex = /^[A-Za-z ]+$/;
      if (!nameRegex.test(name)) {
          Swal.fire('Invalid Name', 'Name should only contain letters and spaces.', 'error');
          return false;
      }

      var passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
      if (!passRegex.test(pwd)) {
          Swal.fire('Weak Password', 'Password must be at least 8 characters and include uppercase, lowercase, number and special character.', 'error');
          return false;
      }

      return true;
  }
  </script>

  <?php echo $alert; ?>
</body>
</html>