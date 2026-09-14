<?php
include("../db.php");
session_start();

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['delete_success'] = true;
    } else {
        $_SESSION['delete_error'] = true;
    }
    header("Location: message.php");
    exit();
}

// Fetch all messages
$result = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
          background: #f0fdfd;
          font-family: 'Segoe UI', sans-serif;
          color: #0f4c5c;
        }

        /* Table card wrapper */
        .table-card {
          background: #ffffff;
          border-radius: 20px;
          padding: 20px;
          box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* Title */
        .page-title {
          display: flex;
          align-items: center;
          gap: 10px;
          font-weight: 700;
          margin-bottom: 20px;
          color: #0f4c5c;
        }

        /* Table */
        .custom-table {
          border-collapse: separate;
          border-spacing: 0 10px;
          width: 100%;
        }
        .custom-table thead {
          background: #009688;
          color: #fff;
          border-radius: 12px;
        }
        .custom-table thead th {
          padding: 14px;
          border: none;
          font-weight: 600;
        }
        .custom-table tbody tr {
          background: #f9ffff;
          border-radius: 12px;
          box-shadow: 0 2px 6px rgba(0,0,0,0.05);
          transition: all 0.3s ease;
        }
        .custom-table tbody tr:hover {
          transform: translateY(-3px);
          box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .custom-table tbody td {
          padding: 14px;
          border: none;
          vertical-align: middle;
        }

        /* Buttons */
        .btn-edit {
          background: #42a5f5;
          color: #fff;
          border-radius: 10px;
          padding: 5px 12px;
          font-size: 0.9rem;
          border: none;
        }
        .btn-edit:hover {
          background: #1e88e5;
          color: #fff;
        }
        .btn-delete {
          background: #ef5350;
          color: #fff;
          border-radius: 10px;
          padding: 5px 12px;
          font-size: 0.9rem;
          border: none;
        }
        .btn-delete:hover {
          background: #c62828;
          color: #fff;
        }
    </style>
</head>
<body>

<div class="container mt-5">
<div class="table-card">
    <div class="page-title">
        <h2>📩 Contact Messages</h2>
    </div>

   
        <table class="custom-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): 
                     $i = 1;?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['first_name'] . " " . $row['last_name']) ?></td>
                            <td><?= htmlspecialchars($row['mobile']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['message']) ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <button class="btn-delete btn-sm" onclick="confirmDelete(<?= $row['id'] ?>)">🗑 Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No messages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This message will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "message.php?delete_id=" + id;
        }
    });
}

<?php if (isset($_SESSION['delete_success'])): ?>
Swal.fire("Deleted!", "Message has been deleted.", "success");
<?php unset($_SESSION['delete_success']); endif; ?>

<?php if (isset($_SESSION['delete_error'])): ?>
Swal.fire("Error!", "Failed to delete message.", "error");
<?php unset($_SESSION['delete_error']); endif; ?>
</script>

</body>
</html>
