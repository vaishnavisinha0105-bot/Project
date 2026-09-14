<?php
include("../db.php");
session_start();

// ✅ Check doctor id
if (!isset($_GET['id'])) {
    die("Doctor ID is required.");
}
$doctor_id = intval($_GET['id']);

// ✅ Fetch doctor data
$doctor_result = $conn->query("SELECT * FROM doctors WHERE id = $doctor_id");
if ($doctor_result->num_rows == 0) {
    die("Doctor not found.");
}
$doctor = $doctor_result->fetch_assoc();

// ✅ Fetch categories for dropdown
$categories = $conn->query("SELECT id, name FROM categories");

// ✅ Handle doctor update (from modal form)
if (isset($_POST['update_doctor'])) {
    $name = $_POST['name'];
    $speciality = $_POST['speciality'];
    $experience = $_POST['experience'];
    $degree = $_POST['degree'];
    $hospital = $_POST['hospital'];
    $category_id = $_POST['category_id'];

    // Default: keep old image
    $relativePath = $doctor['image'];
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "../uploads/image/";
        $imageName = time() . "_" . basename($_FILES['image']["name"]); // unique name
        $targetPath = $targetDir . $imageName;

        if (move_uploaded_file($_FILES['image']["tmp_name"], $targetPath)) {
            $relativePath = "uploads/image/" . $imageName;
        }
    }

    // ✅ Update query
    $stmt = $conn->prepare("UPDATE doctors 
                            SET name=?, speciality=?, experience=?, degree=?, hospital=?, image=?, category_id=? 
                            WHERE id=?");
    $stmt->bind_param("ssssssii", $name, $speciality, $experience, $degree, $hospital, $relativePath, $category_id, $doctor_id);

    if ($stmt->execute()) {
        $_SESSION['update_success'] = true;
        header("Location: edit_doctor.php?id=" . $doctor_id);
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    .doctor-card {
        max-width: 600px;
        margin: 30px auto;
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .doctor-card h3 { color: #178181; }
    .doctor-card p strong { color: #178181; }
    .btn-edit { background: #178181; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; }
    .btn-edit:hover { background: #0f5a5a; }
    .modal-header { background: #178181; color: #fff; }
    .btn-save { background: #178181; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; }
    .btn-save:hover { background: #0f5a5a; }
    </style>
</head>
<body>

<div class="doctor-card">
    <h3><?= htmlspecialchars($doctor['name']) ?></h3>
    <p><strong>Speciality:</strong> <?= htmlspecialchars($doctor['speciality']) ?></p>
    <p><strong>Experience:</strong> <?= htmlspecialchars($doctor['experience']) ?></p>
    <p><strong>Degree:</strong> <?= htmlspecialchars($doctor['degree']) ?></p>
    <p><strong>Hospital:</strong> <?= htmlspecialchars($doctor['hospital']) ?></p>
    <p><strong>Category:</strong> <?= htmlspecialchars($doctor['category_id']) ?></p>
    <p><img src="../<?= htmlspecialchars($doctor['image']) ?>" style="max-width:150px;" class="rounded shadow"></p>

    <button class="btn-edit mt-3" data-bs-toggle="modal" data-bs-target="#updateModal">✏️ Edit Doctor</button>
</div>

<!-- ✅ Modal for Updating Doctor -->
<div class="modal fade" id="updateModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Update Doctor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3"><label>Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($doctor['name']) ?>" required>
            </div>
            <div class="mb-3"><label>Speciality</label>
                <input type="text" name="speciality" class="form-control" value="<?= htmlspecialchars($doctor['speciality']) ?>" required>
            </div>
            <div class="mb-3"><label>Experience</label>
                <input type="text" name="experience" class="form-control" value="<?= htmlspecialchars($doctor['experience']) ?>">
            </div>
            <div class="mb-3"><label>Degree</label>
                <input type="text" name="degree" class="form-control" value="<?= htmlspecialchars($doctor['degree']) ?>">
            </div>
            <div class="mb-3"><label>Hospital</label>
                <input type="text" name="hospital" class="form-control" value="<?= htmlspecialchars($doctor['hospital']) ?>" required>
            </div>
            <div class="mb-3"><label>Category</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Select Category --</option>
                    <?php $categories->data_seek(0); while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $doctor['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3"><label>Doctor Image</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <small>Current:</small><br>
                <img src="../<?= htmlspecialchars($doctor['image']) ?>" style="max-width:120px;" class="mt-2 rounded">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="update_doctor" class="btn-save">💾 Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
<?php if (isset($_SESSION['update_success']) && $_SESSION['update_success']): ?>
    Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: 'Doctor details have been updated successfully.',
        confirmButtonColor: '#178181'
    });
    <?php unset($_SESSION['update_success']); ?>
<?php endif; ?>
</script>

</body>
</html>
