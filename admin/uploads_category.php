<?php
include("../auth.php");
$conn = new mysqli("localhost", "root", "", "doctor_directory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $image = $_FILES['image'];

    // Define path
    $targetDir = "../uploads/image/";
    $imageName = basename($_FILES["image"]["name"]);
    $targetPath = $targetDir . $imageName;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
        $relativePath = "uploads/image/" . $imageName; // ✅ This is what the browser uses
        $stmt = $conn->prepare("INSERT INTO categories (name, image) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $relativePath);
        $stmt->execute();
        echo "Uploaded successfully";


    } else {
        echo "Image upload failed!";
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
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
    margin: 0;     /* Remove default body margin */
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
    color: #333;
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
    color: #444;
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
    color: #777;
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
    border: 1px solid #ccc;
    transition: border 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    border-color: #178181;
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
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
    background-color: #379a9aff;;
}

.msg {
    text-align: center;
    color: red;
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
    color: #333;
    margin-top: 10px;
}
  

    </style>
</head>
<body>
    <div class="form">
    
        <form method="POST" enctype="multipart/form-data">
            <h2>Add Doctor Category</h2>
            <div class="form-group icon-input">
            <label>Category Name:</label><br>
            <input type="text" name="name"  class="form-control" required><br>
            </div>

            <div class="form-group icon-input">
            <label>Category Image:</label><br>
            <input type="file" name="image" accept=".webp,.jpg,.png"   class="form-control" required><br>
            </div>

            <button class="btn font-weight-bold" type="submit">Submit</button>
        </form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
