<?php
include "includes/config.php";

// Save Student
if (isset($_POST['save'])) {

    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);

    $sql = "INSERT INTO students (student_id, fullname, course)
            VALUES ('$student_id', '$fullname', '$course')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Student added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="sidebar">

    <h2>AMS</h2>

    <a href="OJT Projects.php" class="active">🏠 Dashboard</a>
    <a href="pages/students.php">👨‍🎓 Students</a>
    <a href="pages/classes.php">🎓 Classes</a>
    <a href="pages/attendance.php">📝 Attendance</a>
    <a href="pages/scanner.php">📷 QR Scanner</a>
    <a href="pages/reports.php">📊 Reports</a>
    <a href="#">⚙️ Settings</a>

</div>

<div class="main-content">

    <div class="container">

        <h1>Attendance Management System</h1>

        <p>Welcome to your Attendance Management System.</p>

    </div>

</div>

</body>
</html>