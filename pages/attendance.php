<?php
include "../includes/config.php";

// Save Attendance
if (isset($_POST['save'])) {

    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $status = "Present";
    $class_id = $_POST['class_id'];

    // Check if the student exists
    $checkStudent = mysqli_query($conn, "SELECT * FROM students WHERE student_id='$student_id'");

    if (mysqli_num_rows($checkStudent) > 0) {

        // Prevent duplicate attendance for the same day
        $checkAttendance = mysqli_query($conn,
            "SELECT * FROM attendance
             WHERE student_id='$student_id' 
             AND attendance_date='$date'");

        if (mysqli_num_rows($checkAttendance) == 0) {

            $sql = "INSERT INTO attendance
(student_id, class_id, attendance_date, time_in, status)
VALUES
('$student_id','$class_id','$date','$time','$status')";

if(mysqli_query($conn, $sql)){
    echo "<script>alert('Attendance Recorded Successfully!');</script>";
}else{
    die(mysqli_error($conn));
}
        } else {

            echo "<script>alert('This student has already timed in today.');</script>";

        }

    } else {

        echo "<script>alert('Student ID not found!');</script>";

    }
}

$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM attendance
WHERE attendance_date = CURDATE()
"));
$selectedClass = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
?>



<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Attendance</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/attendance.css">

</head>

<body>

<div class="sidebar">

    <h2>AMS</h2>

    <a href="../OJT Projects.php">🏠 Dashboard</a>
    <a href="students.php">👨‍🎓 Students</a>
    <a href="classes.php">🎓 Classes</a>
    <a href="attendance.php" class="active">📝 Attendance</a>
    <a href="reports.php">📊 Reports</a>
    <a href="login/logout.php">⏻ Logout</a>

</div>

<div class="main-content">

<div class="container">

<h1>Attendance</h1>


<?php

$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;

// Total students in this class
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM students
WHERE section_id = (
    SELECT section_id
    FROM classes
    WHERE id='$class_id'
)
"));

// Present today
$present = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM attendance
WHERE attendance_date = CURDATE()
AND class_id='$class_id'
"));

// Absent today
$absent = $totalStudents['total'] - $present['total'];

$filter = isset($_GET['filter']) ? $_GET['filter'] : "all";
?>


<?php
if($selectedClass == 0){
?>
<?php

$result = mysqli_query($conn,"
SELECT
classes.*,
sections.section_name
FROM classes
LEFT JOIN sections
ON classes.section_id = sections.id
ORDER BY classes.class_name
");

?>
<div class="attendance-grid">

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<div class="attendance-card">

    <h3><?= $row['class_name']; ?></h3>

    <p><strong>Section:</strong> <?= $row['section_name']; ?></p>

    <p><strong>Subject:</strong> <?= $row['subject']; ?></p>

    <p><strong>Day:</strong> <?= $row['day_of_week']; ?></p>

    <p>
        <strong>Time:</strong>
        <?= date("h:i A", strtotime($row['start_time'])) ?>
        -
        <?= date("h:i A", strtotime($row['end_time'])) ?>
    </p>

    <a href="attendance.php?class_id=<?= $row['id']; ?>" class="btn-attendance">
        View Attendance
    </a>

</div>

<?php } ?>

</div>
<?php
}else{
?>
<div class="attendance-summary">

<a href="attendance.php?class_id=<?= $class_id ?>&filter=present" class="summary-card present">

<h2><?= $present['total']; ?></h2>

<p>Present</p>

</a>

<a href="attendance.php?class_id=<?= $class_id ?>&filter=absent" class="summary-card absent">

<h2><?= $absent; ?></h2>

<p>Absent</p>

</a>

<a href="attendance.php?class_id=<?= $class_id ?>&filter=all" class="summary-card total">

<h2><?= $totalStudents['total']; ?></h2>

<p>Total Students</p>

</a>

</div>

</div>

<hr>

<h2>Attendance Today</h2>
<div class="search-box">
<input
type="text"
id="searchAttendance"
placeholder="🔍 Search Student..."
style="margin-bottom:20px;width:100%;">

<table id="attendanceTable" class="attendance-table">

<thead>

<tr>

<th>Student ID</th>
<th>Name</th>
<th>Date</th>
<th>Time In</th>
<th>Status</th>
<th>Class</th>
<th>Subject</th>

</tr>

</thead>

<?php
$classFilter = "";

if(isset($_GET['class_id']) && $_GET['class_id']!=""){

    $class_id = intval($_GET['class_id']);

    $classFilter = "AND attendance.class_id=$class_id";

}

if($filter=="present"){

$sql = mysqli_query($conn,"
SELECT
attendance.*,
students.fullname,
classes.class_name,
classes.subject
FROM attendance
INNER JOIN students
ON attendance.student_id=students.student_id
INNER JOIN classes
ON attendance.class_id=classes.id
WHERE attendance.attendance_date=CURDATE()
AND attendance.class_id='$class_id'
ORDER BY attendance.time_in DESC
");

}elseif($filter=="absent"){

$sql = mysqli_query($conn,"
SELECT
students.student_id,
students.fullname
FROM students
WHERE students.section_id=(
SELECT section_id
FROM classes
WHERE id='$class_id'
)
AND students.student_id NOT IN(
SELECT student_id
FROM attendance
WHERE attendance_date=CURDATE()
AND class_id='$class_id'
)
");

}else{

$sql = mysqli_query($conn,"
SELECT
students.student_id,
students.fullname,
attendance.attendance_date,
attendance.time_in,
attendance.status,
classes.class_name,
classes.subject

FROM students

INNER JOIN classes
ON students.section_id = classes.section_id

LEFT JOIN attendance
ON students.student_id = attendance.student_id
AND attendance.class_id = classes.id
AND attendance.attendance_date = CURDATE()

WHERE classes.id='$class_id'

ORDER BY attendance.time_in DESC
");

}
?>

<tbody>

<?php while($row = mysqli_fetch_assoc($sql)){ ?>

<tr>

    <td><?= $row['student_id']; ?></td>

    <td><?= $row['fullname']; ?></td>

    <?php if($filter == "absent"){ ?>

        <td>-</td>

        <td>-</td>

        <td>
            <span class="status-absent">
                Absent
            </span>
        </td>

        <td>-</td>

        <td>-</td>

    <?php }else{ ?>

        <td>
<?php
if($row['attendance_date']){
    echo date("F d, Y", strtotime($row['attendance_date']));
}else{
    echo "-";
}
?>
</td>

<td>
<?php
if($row['time_in']){
    echo date("h:i A", strtotime($row['time_in']));
}else{
    echo "-";
}
?>
</td>

<td>

<?php if($row['status']){ ?>

<span class="status-present">
    <?= $row['status']; ?>
</span>

<?php }else{ ?>

<span class="status-absent">
    Absent
</span>

<?php } ?>

</td>

        <td><?= $row['class_name']; ?></td>

        <td><?= $row['subject']; ?></td>

    <?php } ?>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>
</div>

<script>
const search = document.getElementById("searchAttendance");

if(search){

    search.addEventListener("keyup", function(){

        let value = this.value.toLowerCase();

        let rows = document.querySelectorAll("#attendanceTable tbody tr");

        rows.forEach(function(row){

            row.style.display =
                row.innerText.toLowerCase().includes(value)
                ? ""
                : "none";

        });

    });

}
</script>

<?php
}
?>

</body>

</html>