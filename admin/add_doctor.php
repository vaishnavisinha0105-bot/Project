<?php
include("../auth.php");

$conn = new mysqli("localhost", "root", "", "doctor_directory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['add_doctor'])) {
    $name = $_POST['name'];
    $speciality = $_POST['speciality'];
    $experience = $_POST['experience'];
    $degree = $_POST['degree'];
    $hospital = $_POST['hospital'];
    $category_id = $_POST['category_id'];
    $image = $_FILES['image'];

    $targetDir = "../uploads/image/";
    $imageName = time() . "_" . basename($image["name"]); // unique name
    $targetPath = $targetDir . $imageName;

    if (move_uploaded_file($image["tmp_name"], $targetPath)) {
        $relativePath = "uploads/image/" . $imageName;

        $stmt = $conn->prepare("INSERT INTO doctors (name, speciality, experience, degree, hospital, image, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $name, $speciality, $experience, $degree, $hospital, $relativePath, $category_id);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = $stmt->error;
        }
    } else {
        $error = "Image upload failed!";
    }
}

// Fetch categories for the dropdown
$categories = $conn->query("SELECT id, name FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: #f0fdfd; font-family: 'Segoe UI', sans-serif; color: #0f4c5c; }
        .btn-add { background: #178181; color: #fff; border-radius: 10px; padding: 10px 20px; font-weight: 600; border: none; }
        .btn-add:hover { background: #0f5a5a; color: #fff; }
        .modal-header { background: #178181; color: #fff; }
        .btn-save { background: #178181; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; }
        .btn-save:hover { background: #0f5a5a; }
    </style>
</head>
<body>

<div class="container mt-5 text-center">
    <h2 class="mb-4">📩 Add New Doctor</h2>
    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addDoctorModal">➕ Add Doctor</button>
</div>

<!-- Add Doctor Modal -->
<div class="modal fade" id="addDoctorModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Add New Doctor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Speciality</label>
                <input type="text" name="speciality" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Experience</label>
                <input type="text" name="experience" class="form-control">
            </div>
            <div class="mb-3">
                <label>Degree</label>
                <input type="text" name="degree" class="form-control">
            </div>
            <div class="mb-3">
                <label>Hospital</label>
                <input type="text" name="hospital" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Select Category --</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Doctor Image</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="add_doctor" class="btn-save">💾 Save Doctor</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php if (isset($success) && $success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Added!',
    text: 'Doctor has been added successfully.',
    confirmButtonColor: '#178181'
});
</script>
<?php endif; ?>

<?php if (isset($error)): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: '<?= addslashes($error) ?>',
    confirmButtonColor: '#178181'
});
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
