<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "teacher"){
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
</head>
<body>

<h1>Teacher Dashboard</h1>

<h3>Welcome, <?php echo $_SESSION['fullname']; ?></h3>

<p>You are logged in as <b>Teacher</b>.</p>

<a href="../logout.php">Logout</a>

</body>
</html>