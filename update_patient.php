<?php
session_start();
$conn = new mysqli("localhost", "root", "", "doctor_directory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $past_history = $_POST['past_history'];

    $sql = "UPDATE patients 
            SET name='$name', age='$age', phone='$phone', address='$address', past_history='$past_history' 
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: user.php?updated=1");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
$conn->close();
?>
