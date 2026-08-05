<?php
include "../includes/config.php";

$currentTime = date("H:i:s");
$currentDate = date("Y-m-d");

$sql = "
SELECT
c.class_id,
c.class_name,
c.teacher_name,
c.start_time,
c.end_time,

COUNT(s.student_id) AS total_students,

SUM(
CASE
WHEN a.status='Present'
AND a.attendance_date='$currentDate'
THEN 1
ELSE 0
END
) AS present_students

FROM class_table c

LEFT JOIN student_table s
ON c.class_id=s.class_id

LEFT JOIN attendance_table a
ON s.student_id=a.student_id

WHERE c.end_time <= '$currentTime'

GROUP BY c.class_id
";

$result=mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html>

<head>

<title>Attendance Report</title>

<style>

table{

width:100%;
border-collapse:collapse;

}

table th,table td{

border:1px solid black;
padding:10px;
text-align:center;

}

a{

text-decoration:none;
padding:5px 10px;
background:green;
color:white;

}

</style>

</head>

<body>

<h2>Attendance Report</h2>

<table>

<tr>

<th>Class</th>
<th>Teacher</th>
<th>Total</th>
<th>Present</th>
<th>Absent</th>
<th>Action</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

$absent=$row['total_students']-$row['present_students'];

?>

<tr>

<td><?php echo $row['class_name']; ?></td>

<td><?php echo $row['teacher_name']; ?></td>

<td><?php echo $row['total_students']; ?></td>

<td><?php echo $row['present_students']; ?></td>

<td><?php echo $absent; ?></td>

<td>

<a href="view_report.php?class_id=<?php echo $row['class_id']; ?>">
View
</a>

</td>

</tr>

<?php
}
?>

</table>

</body>

</html>