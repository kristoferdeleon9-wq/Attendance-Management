<?php
include "includes/config.php";

date_default_timezone_set("Asia/Manila");

$today = date("Y-m-d");

// Total Students
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM students
"));

// Total Classes
$totalClasses = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM classes
"));

// Present Today
$presentToday = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM attendance
WHERE attendance_date='$today'
AND status='Present'
"));

// Absent Today
$absentToday = $totalStudents['total'] - $presentToday['total'];

// Today's Classes
$todayClasses = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM classes
WHERE class_date='$today'
"));

// ======================================
// RECENT ATTENDANCE (LAST 10)
// ======================================

$recentAttendance = mysqli_query($conn,"
SELECT
    attendance.student_id,
    students.fullname,
    classes.class_name,
    attendance.time_in,
    attendance.attendance_date

FROM attendance

INNER JOIN students
ON attendance.student_id = students.student_id

INNER JOIN classes
ON attendance.class_id = classes.id

ORDER BY attendance.attendance_date DESC,
attendance.time_in DESC

LIMIT 10
");

// ======================================
// WEEKLY ATTENDANCE
// ======================================

$week = [];

for($i=6;$i>=0;$i--){

    $date = date("Y-m-d", strtotime("-$i days"));

    $count = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT COUNT(*) AS total
    FROM attendance
    WHERE attendance_date='$date'
    "));

    $week[] = [
        "day" => date("D", strtotime($date)),
        "total" => $count['total']
    ];
}

// ======================================
// TODAY'S CLASS SCHEDULE
// ======================================

$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");

$todaySchedule = mysqli_query($conn,"
SELECT
    classes.*,
    sections.section_name
FROM classes
LEFT JOIN sections
ON classes.section_id = sections.id
WHERE class_date='$currentDate'
ORDER BY start_time ASC
");

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
    <link rel="stylesheet" href="css/dashboard.css">
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
    <a href="login/logout.php">⏻ Logout</a>

</div>

<div class="main-content">

    <div class="container">

        <h1>Attendance Management System</h1>

<p>Welcome to your Attendance Management System.</p>

<div class="dashboard-cards">

<div class="dashboard-card students">
<div class="icon">👨‍🎓</div>
<h2><?= $totalStudents['total']; ?></h2>
<p>Total Students</p>
</div>

<div class="dashboard-card classes">
<div class="icon">🏫</div>
<h2><?= $totalClasses['total']; ?></h2>
<p>Total Classes</p>
</div>

<div class="dashboard-card present">
<div class="icon">✅</div>
<h2><?= $presentToday['total']; ?></h2>
<p>Present Today</p>
</div>

<div class="dashboard-card absent">
<div class="icon">❌</div>
<h2><?= $absentToday; ?></h2>
<p>Absent Today</p>
</div>

<div class="dashboard-card today">
<div class="icon">📅</div>
<h2><?= $todayClasses['total']; ?></h2>
<p>Today's Classes</p>
</div>

</div>

    </div>
<hr>

<h2>📝 Recent Attendance</h2>

<table class="attendance-table">

<thead>

<tr>

<th>Student ID</th>
<th>Student Name</th>
<th>Class</th>
<th>Date</th>
<th>Time In</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($recentAttendance)){ ?>

<tr>

<td><?= $row['student_id']; ?></td>

<td><?= $row['fullname']; ?></td>

<td><?= $row['class_name']; ?></td>

<td><?= date("F d, Y",strtotime($row['attendance_date'])); ?></td>

<td><?= date("h:i A",strtotime($row['time_in'])); ?></td>

</tr>

<?php } ?>

</tbody>

</table>

<hr>

<h2>📊 Weekly Attendance</h2>

<div class="chart-container">

<?php

$max = 1;

foreach($week as $d){

    if($d['total'] > $max){

        $max = $d['total'];

    }

}

foreach($week as $d){

    $height = ($d['total'] / $max) * 220;
?>

<div class="bar-box">

<div
class="bar"
style="height:<?= $height ?>px;">

<span><?= $d['total']; ?></span>

</div>

<p><?= $d['day']; ?></p>

</div>

<?php } ?>

</div>

<hr>

<h2>📅 Today's Class Schedule</h2>

<div class="schedule-grid">

<?php

if(mysqli_num_rows($todaySchedule) > 0){

while($row = mysqli_fetch_assoc($todaySchedule)){

    if($currentTime < $row['start_time']){

        $status = "Upcoming";
        $color = "upcoming";

    }elseif($currentTime > $row['end_time']){

        $status = "Finished";
        $color = "finished";

    }else{

        $status = "Ongoing";
        $color = "ongoing";

    }

?>

<div class="schedule-card">

<h3><?= $row['class_name']; ?></h3>

<p><strong>Section:</strong> <?= $row['section_name']; ?></p>

<p><strong>Subject:</strong> <?= $row['subject']; ?></p>

<p><strong>Instructor:</strong> <?= $row['instructor']; ?></p>

<p>

<strong>Time:</strong>

<?= date("h:i A",strtotime($row['start_time'])); ?>

-

<?= date("h:i A",strtotime($row['end_time'])); ?>

</p>

<span class="schedule-status <?= $color; ?>">

<?= $status; ?>

</span>

</div>

<?php

}

}else{

?>

<div class="no-class">

<h3>No classes scheduled today.</h3>

</div>

<?php

}

?>

</div>
</div>



</body>
</html>