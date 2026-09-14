<?php
include("../auth.php");
header("Location:dash.php"); 
exit();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style1.css">

</head>
<body>
    <div class="user-page">
        <h2>Welcome to Admin Page</h2>
        <p>User : <span><?php echo $_SESSION['admin']; ?></span></p>
        <a href="logout.php"><button class="btn font-weight-bold">Logout</button></a>
    </div>

</body>
</html>