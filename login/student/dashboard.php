<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != "student"){
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
</head>
<body>

<h1>Student Dashboard</h1>

<h3>Welcome, <?php echo $_SESSION['fullname']; ?></h3>

<p>You are logged in as <b>Student</b>.</p>

<a href="../logout.php">Logout</a>

</body>
</html>