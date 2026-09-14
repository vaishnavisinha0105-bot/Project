<?php
include("../db.php"); // Database connection

if (isset($_GET['id'])) {
    $category_id = intval($_GET['id']); // integer conversion for safety

    // Category details
    $cat_query = "SELECT * FROM categories WHERE id = $category_id";
    $cat_result = mysqli_query($conn, $cat_query);
    $category = mysqli_fetch_assoc($cat_result);

    // Doctors of this category
    $doc_query = "SELECT * FROM doctors WHERE category_id = $category_id";
    $doc_result = mysqli_query($conn, $doc_query);
} else {
    die("Category ID not provided in URL.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $category['name']; ?> - Doctors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }
        .custom-card:hover {
            transform: translateY(-5px);
        }
       .doctor-img {
            height: 250px;      
            object-fit: cover; 
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .category-img {
            max-height: 200px;
            object-fit: contain;
        }
    </style>
</head>
<body class="bg-light">

    <div class="container py-4">
        
        <!-- Category Card -->
        <div class="card custom-card mb-5">
            <div class="row g-0 align-items-center">
                <div class="col-md-3 text-center p-3">
                    <img src="../<?php echo $category['image']; ?>" class="img-fluid category-img" alt="<?php echo $category['name']; ?>">
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <h1 class="card-title"><?php echo $category['name']; ?> Department</h1>
                        <p class="text-muted">Explore top doctors specializing in <?php echo $category['name']; ?>.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doctors Section -->
        <div class="row g-4">
        <?php while ($doc = mysqli_fetch_assoc($doc_result)) { ?>
        <div class="col-md-6 col-lg-4 d-flex">
            <div class="card custom-card h-100 flex-fill">
                <img src="../<?php echo $doc['image']; ?>" class="doctor-img card-img-top" alt="<?php echo $doc['name']; ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo $doc['name']; ?></h5>
                    <p class="card-text"><b>Speciality:</b> <?php echo $doc['speciality']; ?></p>
                    <p class="card-text"><b>Experience:</b> <?php echo $doc['experience']; ?></p>
                    <p class="card-text"><b>Degree:</b> <?php echo $doc['degree']; ?></p>
                    <p class="card-text"><b>Hospital:</b> <?php echo $doc['hospital']; ?></p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
