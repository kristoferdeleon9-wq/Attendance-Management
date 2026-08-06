<?php
include "../includes/config.php";

$currentDate = date("Y-m-d");
$result = mysqli_query($conn,"
SELECT
    c.id,
    c.class_name,
    c.subject,
    c.instructor,

    COUNT(DISTINCT s.student_id) AS total_students,

    COUNT(
        DISTINCT CASE
            WHEN a.status='Present'
            AND a.attendance_date = CURDATE()
            THEN a.student_id
        END
    ) AS present_students

FROM classes c

LEFT JOIN students s
ON c.section_id = s.section_id

LEFT JOIN attendance a
ON a.student_id = s.student_id
AND a.class_id = c.id
AND a.attendance_date = CURDATE()

GROUP BY
c.id,
c.class_name,
c.subject,
c.instructor

ORDER BY c.class_name ASC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Attendance Reports</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/attendance.css">
<link rel="stylesheet" href="../css/reports.css">

</head>

<body>

<!-- =========================
SIDEBAR
========================= -->

<div class="sidebar">

<h2>AMS</h2>

<a href="../OJT Projects.php">🏠 Dashboard</a>
<a href="students.php">👨‍🎓 Students</a>
<a href="classes.php">🎓 Classes</a>
<a href="attendance.php">📝 Attendance</a>
<a href="reports.php" class="active">📊 Reports</a>
<a href="#">⚙️ Settings</a>

</div>

<!-- =========================
MAIN CONTENT
========================= -->

<div class="main-content">

<div class="container">

<h1>Attendance Reports</h1>

<p class="page-description">
View attendance statistics for every class.
</p>

<!-- SEARCH -->

<div class="search-box">

<input
type="text"
id="searchReport"
placeholder="🔍 Search Class or Teacher...">

</div>

<!-- TABLE -->

<table
class="attendance-table"
id="reportTable">

<thead>

<tr>

<th>Class</th>
<th>Subject</th>
<th>Instructor</th>
<th>Total</th>
<th>Present</th>
<th>Absent</th>
<th>Attendance %</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($result)){

$absent=$row['total_students']-$row['present_students'];

if($row['total_students']>0){

$percent=round(
($row['present_students']/$row['total_students'])*100
);

}else{

$percent=0;

}

?>

<tr>

<td><?= $row['class_name']; ?></td>

<td><?= $row['subject']; ?></td>

<td><?= $row['instructor']; ?></td>

<td><?= $row['total_students']; ?></td>

<td>

<span class="status-present">

<?= $row['present_students']; ?>

</span>

</td>

<td>

<span class="status-absent">

<?= $absent; ?>

</span>

</td>

<td>

<strong><?= $percent; ?>%</strong>

</td>

<td>

<a
class="btn-attendance"
href="view_report.php?class_id=<?= $row['id']; ?>">
View Report
</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<script>

const search=document.getElementById("searchReport");

search.addEventListener("keyup",function(){

let value=this.value.toLowerCase();

let rows=document.querySelectorAll("#reportTable tbody tr");

rows.forEach(function(row){

row.style.display=

row.innerText.toLowerCase().includes(value)

? ""

: "none";

});

});

</script>

</body>

</html>