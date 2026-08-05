<?php

include "../includes/config.php";

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM students WHERE id=$id");

$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){

    $student_id = mysqli_real_escape_string($conn,$_POST['student_id']);
    $fullname   = mysqli_real_escape_string($conn,$_POST['fullname']);
    $course     = mysqli_real_escape_string($conn,$_POST['course']);

    mysqli_query($conn,

        "UPDATE students SET

        student_id='$student_id',
        fullname='$fullname',
        course='$course'

        WHERE id=$id"

    );

    header("Location: students.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Student</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="container">

<h1>Edit Student</h1>

<form method="POST">

<label>Student ID</label>

<input
type="text"
name="student_id"
value="<?= htmlspecialchars($row['student_id']) ?>"
required>

<label>Full Name</label>

<input
type="text"
name="fullname"
value="<?= htmlspecialchars($row['fullname']) ?>"
required>

<label>Course</label>

<input
type="text"
name="course"
value="<?= htmlspecialchars($row['course']) ?>"
required>

<button name="update">

Update Student

</button>

</form>

</div>

</body>

</html>