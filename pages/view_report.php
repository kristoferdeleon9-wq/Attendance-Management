<?php
include "../includes/config.php";

$class_id=$_GET['class_id'];

$currentDate=date("Y-m-d");

$sql="

SELECT

s.student_no,
s.fullname,

IF(a.status='Present','Present','Absent') AS attendance_status

FROM student_table s

LEFT JOIN attendance_table a

ON s.student_id=a.student_id

AND a.attendance_date='$currentDate'

WHERE s.class_id='$class_id'

ORDER BY s.fullname

";

$result=mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html>

<head>

<title>Attendance Details</title>

<style>

table{

width:80%;
margin:auto;
border-collapse:collapse;

}

th,td{

border:1px solid black;
padding:10px;
text-align:center;

}

.present{

color:green;
font-weight:bold;

}

.absent{

color:red;
font-weight:bold;

}

</style>

</head>

<body>

<h2 align="center">Attendance Details</h2>

<table>

<tr>

<th>Student No</th>
<th>Name</th>
<th>Status</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['student_no']; ?></td>

<td><?php echo $row['fullname']; ?></td>

<td class="<?php echo strtolower($row['attendance_status']); ?>">

<?php echo $row['attendance_status']; ?>

</td>

</tr>

<?php

}

?>

</table>

</body>

</html>