<?php
include("../db.php");
session_start();

$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Departments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f0fdfd;
    }
    .page-card {
      background: white;
      border-radius: 20px;
      padding: 25px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    /* Header button (same as Add Doctor) */
    .btn-add {
      background: linear-gradient(to right, #26c6da, #0097a7);
      color: white;
      border-radius: 12px;
      font-weight: 500;
      padding: 8px 18px;
      border: none;
    }
    .btn-add:hover {
      background: #00838f;
      color: white;
    }
    /* Equal card size for departments */
    .dept-card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: transform 0.2s ease-in-out;
      text-align: center;
      padding: 20px;
      height: 160px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    .dept-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.12);
    }
    .dept-img {
      height: 60px;
      width: 60px;
      object-fit: contain;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<div class="container mt-4">
  <div class="page-card">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="m-0 fw-bold text-teal-800">
        <i class="bi bi-building me-2"></i> Department List
      </h2>
      <a href="uploads_category.php" class="btn btn-add">
        <i class="bi bi-plus-circle"></i> Add Category
      </a>
    </div>

    <!-- Department Cards -->
    <div class="row g-4">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-6 col-md-4 col-lg-2">
          <a href="dep_doc.php?id=<?php echo $row['id']; ?>" class="text-decoration-none text-dark">
            <div class="dept-card">
              <img src="../<?php echo $row['image']; ?>" class="dept-img" alt="<?php echo $row['name']; ?>">
              <h6 class="card-title"><?php echo $row['name']; ?></h6>
            </div>
          </a>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
