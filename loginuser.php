<?php
include("db.php");
include("navbar.php");

$alert = ""; 

if (isset($_POST['Submit'])) {
    $login = trim($_POST['login']); // email or username
    $password = $_POST['password'];

    // Fetch from user table
    $stmt = $conn->prepare("SELECT id, name, email, password, user_type 
                            FROM user 
                            WHERE email = ? OR name = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);

            $_SESSION['id']        = $row['id'];       // user table id
            $_SESSION['user']      = $row['email'];
            $_SESSION['username']  = $row['name'];
            $_SESSION['user_type'] = $row['user_type'];

            // ✅ If doctor, fetch from doctors table by email (not user_id)
            if ($row['user_type'] == 'doctor') {
                $stmt2 = $conn->prepare("SELECT id, name FROM doctors WHERE email = ?");
                $stmt2->bind_param("s", $row['email']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();

                if ($doctor = $result2->fetch_assoc()) {
                    $_SESSION['doctor_id'] = $doctor['id']; 
                    $_SESSION['doctor_name'] = $doctor['name'];
                }
                $stmt2->close();
            }

            // ✅ Redirect based on type
            $redirect = ($row['user_type'] == 'doctor') ? 'doctors.php' : 'user.php';

            $alert = "
                <script>
                    Swal.fire({
                        title: 'Login Successful!',
                        text: 'Welcome back, ".$row['name']."',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        window.location.href = '$redirect';
                    });
                </script>
            ";
        } else {
            $alert = "
                <script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Invalid password!',
                        icon: 'error',
                        confirmButtonText: 'Try Again',
                        confirmButtonColor: '#28a745'
                    });
                </script>
            ";
        }
    } else {
        $alert = "
            <script>
                Swal.fire({
                    title: 'Login Failed!',
                    text: 'No user found with this email/username!',
                    icon: 'error',
                    confirmButtonText: 'Retry',
                    confirmButtonColor: '#28a745'
                });
            </script>
        ";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - HealthCare</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f2f6f9;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      overflow: hidden;
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

    /* Left: Login */
    .login-section {
      flex: 1;
      background: #ffffff; /* solid white left side */
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: center;
    }

    .login-section h2 {
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #222;
    }

    .form-group {
      margin-bottom: 18px;
    }

    .form-group input {
      width: 100%;
      padding: 12px 14px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
      outline: none;
      color: #222;
      transition: 0.3s;
    }

    .form-group input:focus {
      border-color: #178181;
      box-shadow: 0 0 0 2px rgba(23,129,129,0.2);
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

    .btn:hover {
      background: #218838;
    }

    /* Right: Sign-up panel */
    .signup-section {
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

    .signup-section h2 {
      font-size: 30px;
      margin-bottom: 15px;
      color: #fff;
    }

    .signup-section p {
      margin-bottom: 25px;
      font-size: 16px;
      color: #f8f9fa;
    }

    .signup-btn {
      padding: 12px 28px;
      background: white;
      color: #0d6efd;
      border-radius: 25px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s;
    }

    .signup-btn:hover {
      background: #f2f2f2;
    }

    /* 🌐 Responsive for tablets and phones */
    @media (max-width: 992px) {
      .container {
        width: 90%;
        height: auto;
        flex-direction: column;
      }

      .login-section, .signup-section {
        width: 100%;
        height: auto;
        padding: 40px 25px;
      }

      .signup-section {
        border-top: 1px solid rgba(255, 255, 255, 0.3);
      }

      .signup-section h2 {
        font-size: 24px;
      }

      .signup-section p {
        font-size: 15px;
      }
    }

    @media (max-width: 576px) {
      .container {
        width: 95%;
        box-shadow: none;
      }

      .login-section h2 {
        font-size: 22px;
      }

      .btn {
        font-size: 15px;
      }

      .signup-btn {
        padding: 10px 20px;
      }
    }
  </style>
</head>

<body>
  <div class="main-content">
    <div class="container">
      <div class="login-section">
        <h2>Login to Your Account</h2>
        <form action="loginuser.php" method="post">
          <div class="form-group">
            <input type="text" name="login" placeholder="Username or Email" required>
          </div>
          <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
          </div>
          <button type="submit" name="Submit" class="btn">Sign In</button>
        </form>
      </div>

      <div class="signup-section">
        <h2><strong>New Here?</strong></h2>
        <p>Sign up and book appointments online to stay connected with your doctor and maintain better health.</p>
        <a href="registeruser.php" class="signup-btn">Sign Up</a>
      </div>
    </div>
  </div>

  <?php echo $alert; ?>
</body>
</html>