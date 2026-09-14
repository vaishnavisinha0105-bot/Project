<?php
session_start();
include("../db.php");

$msg = '';

if (isset($_POST['Submit'])) {
    $name       = trim($_POST['name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $cpassword  = $_POST['cpassword'] ?? '';
    $user_type  = $_POST['user_type'] ?? '';

    // ✅ Validate name (letters + spaces only)
    if (!preg_match("/^[A-Za-z ]+$/", $name)) {
        $msg = "Name should contain only letters and spaces!";
    }
    // ✅ Validate email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format!";
    }
    // ✅ Validate password strength (min 8 chars, upper, lower, digit, special)
    elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $password)) {
        $msg = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character!";
    }
    // ✅ Confirm password
    elseif ($password !== $cpassword) {
        $msg = "Passwords do not match!";
    }
    else {
        // ✅ Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $msg = "User already exists!";
        } else {
            $stmt->close();

            // ✅ Hash password before saving
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO admin (name, email, password, user_type) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password_hashed, $user_type);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                $msg = "Something went wrong. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    /* ===== Reset & Base Styles ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    background: linear-gradient(to right, #eef2f3, #dfe9f3);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center; 
    margin: 0;
    height: 100vh;
}

/* ===== Form Container ===== */
.form {
    background: #ffffff;
    padding: 40px 30px;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 480px;
}

/* ===== Heading ===== */
.form h2 {
    font-size: 22px;
    font-weight: 600;
    text-transform: uppercase;
    text-align: center;
    color: #178181;
    margin-bottom: 30px;
    margin: 0 auto;
    position: relative; 
    padding-bottom: 10px;
}
 
.form h2 i {
    margin-right: 8px;
    color: #178181;
} 

.form h2::after {
    content: '';
    width: 60px;
    height: 3px;
    background-color: #178181;
    display: block;
    margin: 10px auto 0 auto;
    border-radius: 2px;
} 

label {
    font-weight: 600;
    color: #178181;
    display: block;
    margin-bottom: 6px;
}

/* ===== Input Group with Icon ===== */
.icon-input {
    position: relative;
    margin-bottom: 20px;
}

.icon-input i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #178181;
    font-size: 15px;
    z-index: 10;
    pointer-events: none;
}

/* ===== Input & Select Styling ===== */
.form-control {
    width: 100%;
    padding: 12px 14px;
    padding-left: 36px;
    font-size: 15px;
    border-radius: 8px;
    border: 1px solid #178181;
    transition: border 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    border-color: #178181;
    outline: none;
    box-shadow: 0 0 0 2px rgba(23, 129, 129, 0.2);
}

/* Select dropdown custom appearance */
.icon-input select.form-control {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: #fff;
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
}

/* ===== Button Styling ===== */
button.btn {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: 600;
    background-color: #178181;
    color: white;
    border: none;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

button.btn:hover {
    background-color: #0f5f5f;
}

.msg {
    text-align: center;
    color: #178181;
    font-size: 14px;
    margin-bottom: 10px;
}

.form p {
    text-align: center;
    margin-top: 15px;
    font-size: 14px;
}

.form a {
    color: #178181;
    text-decoration: none;
    font-weight: 500;
}

.form a:hover {
    text-decoration: underline;
}

.alert {
    padding: 10px;
    background-color: #e9ecef;
    border-left: 5px solid #178181;
    color: #178181;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <div class="form">
        <form action="" method="post">
        
        <h2><i class="fas fa-user-plus"></i> Registration</h2>
        <p class="msg"><?= htmlspecialchars($msg) ?></p>

        <div class="form-group icon-input">
        <i class="fas fa-user"></i>
        <input type="text" name="name" placeholder="Enter Your Name"
                class="form-control"
                pattern="[A-Za-z ]+"
                title="Name should contain only letters and spaces"
                required>
        </div>

        <div class="form-group icon-input">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" placeholder="Enter Your Email" class="form-control" required>
        </div>

        <div class="form-group icon-input">
        <i class="fas fa-users-cog"></i>
        <select name="user_type" class="form-control" required>
            <option value="" disabled selected>Select User Type</option>
            <option value="admin">Admin</option>
        </select>
        </div>

        <div class="form-group icon-input">
        <i class="fas fa-lock"></i>
        <input type="password" name="password"
                placeholder="Enter Your Password"
                class="form-control"
                minlength="8"
                title="Password must be at least 8 characters, with uppercase, lowercase, number, and special character"
                required>
        </div>

        <div class="form-group icon-input">
        <i class="fas fa-lock"></i>
        <input type="password" name="cpassword" placeholder="Confirm Your Password" class="form-control" required>
        </div>


        <?php if ($msg != '') { echo '<div class="alert alert-info mt-2">' . $msg . '</div>'; } ?>

        <button class="btn font-weight-bold" name="Submit">Register Now</button>
        <p>Already have a account?<a href="login.php">Login Now</a></p>
        </form>
    </div>
</body>
</html>