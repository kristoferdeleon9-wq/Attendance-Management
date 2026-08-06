<?php
include "../includes/config.php";

$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;

if($class_id == 0){
    die("Invalid Class");
}

$date = date("Y-m-d");

// Class Information
$class = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT
classes.*,
sections.section_name
FROM classes
LEFT JOIN sections
ON classes.section_id=sections.id
WHERE classes.id='$class_id'
"));

// Total Students
$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM students
WHERE section_id='{$class['section_id']}'
"));

// Present
$present = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM attendance
WHERE class_id='$class_id'
AND attendance_date=CURDATE()
"));

// Absent
$absent = $total['total']-$present['total'];


// Student List
$list = mysqli_query($conn,"
SELECT

students.student_id,
students.fullname,

attendance.status,
attendance.time_in,
attendance.attendance_date

FROM students

LEFT JOIN attendance

ON students.student_id=attendance.student_id
AND attendance.class_id='$class_id'
AND attendance.attendance_date=CURDATE()

WHERE students.section_id='{$class['section_id']}'

ORDER BY students.fullname
");

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>View Report</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/attendance.css">
<link rel="stylesheet" href="../css/report.css">

</head>

<body>

<div class="sidebar">

<h2>AMS</h2>

<a href="../OJT Projects.php">🏠 Dashboard</a>
<a href="students.php">👨‍🎓 Students</a>
<a href="classes.php">🎓 Classes</a>
<a href="attendance.php">📝 Attendance</a>
<a href="report.php" class="active">📊 Reports</a>

</div>

<div class="main-content">

<div class="container">

<h1><?= $class['class_name']; ?></h1>

<p>

<strong>Section:</strong>
<?= $class['section_name']; ?>

<br>

<strong>Subject:</strong>
<?= $class['subject']; ?>

<br>

<strong>Teacher:</strong>
<?= $class['teacher_name']; ?>

</p>

<hr>

<div class="attendance-summary">

<div class="summary-card total">

<h2><?= $total['total']; ?></h2>

<p>Total Students</p>

</div>

<div class="summary-card present">

<h2><?= $present['total']; ?></h2>

<p>Present</p>

</div>

<div class="summary-card absent">

<h2><?= $absent; ?></h2>

<p>Absent</p>

</div>

</div>

<div class="search-box">

<input
type="text"
id="searchStudent"
placeholder="Search Student">

</div>

<table
class="attendance-table"
id="studentTable">

<thead>

<tr>

<th>Student ID</th>

<th>Name</th>

<th>Status</th>

<th>Time In</th>

<th>Date</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($list)){

?>

<tr>

<td><?= $row['student_id']; ?></td>

<td><?= $row['fullname']; ?></td>

<td>

<?php

if($row['status']){

?>

<span class="status-present">

Present

</span>

<?php

}else{

?>

<span class="status-absent">

Absent

</span>

<?php

}

?>

</td>

<td>

<?php

if($row['time_in']){

echo date("h:i A",strtotime($row['time_in']));

}else{

echo "-";

}

?>

</td>

<td>

<?php

if($row['attendance_date']){

echo date("F d, Y",strtotime($row['attendance_date']));

}else{

echo "-";

}

?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<br>

<a
href="report.php"
class="btn-attendance">

⬅ Back to Reports

</a>

</div>

</div>

<script>

document.getElementById("searchStudent").addEventListener("keyup",function(){

let value=this.value.toLowerCase();

let rows=document.querySelectorAll("#studentTable tbody tr");

rows.forEach(function(row){

row.style.display=row.innerText.toLowerCase().includes(value)

? ""

: "none";

});

});

</script>

</body>

</html>