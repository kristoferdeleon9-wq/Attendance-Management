<?php
include "../includes/config.php";
date_default_timezone_set('Asia/Manila');

// Add Class
if(isset($_POST['save'])){

    $class_name = mysqli_real_escape_string($conn,$_POST['class_name']);
    $subject = mysqli_real_escape_string($conn,$_POST['subject']);
    $instructor = mysqli_real_escape_string($conn,$_POST['instructor']);
    $day = mysqli_real_escape_string($conn,$_POST['day']);
    $class_date = $_POST['class_date'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];
    $section_id = $_POST['section_id'];

    mysqli_query($conn,"
INSERT INTO classes
(class_name, section_id, subject, instructor, day_of_week, class_date, start_time, end_time)

VALUES

('$class_name','$section_id','$subject','$instructor','$day','$class_date','$start','$end')
");
    echo "<script>alert('Class Added Successfully');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Classes</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

<body>

<div class="sidebar">

<h2>AMS</h2>

<a href="../OJT Projects.php">🏠 Dashboard</a>
<a href="students.php">👨‍🎓 Students</a>
<a href="classes.php" class="active">🏫 Classes</a>
<a href="attendance.php">📝 Attendance</a>
<a href="reports.php">📊 Reports</a>
    <a href="#">⚙️ Settings</a>

</div>

<div class="main-content">

<div class="container">

<h1>Class Management</h1>

<button id="showForm" class="btn-add">
➕ Create Class
</button>

<div id="createClassForm" style="display:none;">

<form method="POST">

<label>Class Name</label>
<input type="text" name="class_name" required>
<label>Section</label>

<select name="section_id" required>

    <option value="">Select Section</option>

    <?php
    $sections = mysqli_query($conn,
        "SELECT * FROM sections ORDER BY section_name");

    while($sec = mysqli_fetch_assoc($sections)){
    ?>

    <option value="<?= $sec['id']; ?>">
        <?= $sec['section_name']; ?>
    </option>

    <?php } ?>

</select>

<label>Subject</label>
<input type="text" name="subject" required>

<label>Instructor</label>
<input type="text" name="instructor" required>

</select>

<label>Class Date</label>
<input type="date" name="class_date" required>

<label>Day</label>

<input type="text" name="day" id="day" readonly required>

<label>Start Time</label>

<input type="time" name="start_time" required>

<label>End Time</label>

<input type="time" name="end_time" required>

<button type="submit" name="save">

Save Class

</button>

</form>

</div>

<hr>

<h2>Class List</h2>

<div class="class-grid">

<?php

$result = mysqli_query($conn,"
SELECT
    classes.*,
    sections.section_name
FROM classes
LEFT JOIN sections
ON classes.section_id = sections.id
ORDER BY classes.id DESC
");

while($row = mysqli_fetch_assoc($result))
{

?>

<div class="class-card">

    <div class="class-header">
        <h3><i class="bi bi-book-half"></i> <?= $row['class_name']; ?></h3>
    </div>

    <div class="class-body">

        <p>
            <i class="bi bi-people-fill"></i>
            <strong>Section:</strong>
            <?= $row['section_name']; ?>
        </p>

        <p>
            <i class="bi bi-journal-bookmark-fill"></i>
            <strong>Subject:</strong>
            <?= $row['subject']; ?>
        </p>

        <p>
            <i class="bi bi-person-workspace"></i>
            <strong>Instructor:</strong>
            <?= $row['instructor']; ?>
        </p>

        <p>
    <i class="bi bi-calendar-date"></i>
    <strong>Date:</strong>
    <?= date("F d, Y", strtotime($row['class_date'])); ?>
</p>

        <p>
            <i class="bi bi-calendar-event"></i>
            <strong><?= $row['day_of_week']; ?></strong>
        </p>

        <p>
            <i class="bi bi-clock-fill"></i>

            <?= date("h:i A", strtotime($row['start_time'])); ?>

            -

            <?= date("h:i A", strtotime($row['end_time'])); ?>
        </p>

    </div>

    <div class="card-buttons">

    <?php

$current_date = date("Y-m-d");
$current_time = date("H:i:s");

if ($current_date < $row['class_date']) {

    // Class is scheduled for a future date
?>

<a href="#"
   class="btn-view"
   style="background:#0d6efd; cursor:not-allowed;"
   onclick="return false;">
    📅 Scheduled for <?= date("F d, Y", strtotime($row['class_date'])); ?>
</a>

<?php

} elseif ($current_date > $row['class_date']) {

    // Class date has already passed
?>

<a href="#"
   class="btn-view"
   style="background:#6c757d; cursor:not-allowed;"
   onclick="return false;">
    ✅ Attendance Session Ended
</a>

<?php

} elseif (strtotime($current_time) < strtotime($row['start_time'])) {

    // Today is the class date, but it's too early
?>

<a href="#"
   class="btn-view"
   style="background:#ffc107; color:#000; cursor:not-allowed;"
   onclick="return false;">
    ⏰ Attendance Not Yet Open
</a>

<?php

} elseif (strtotime($current_time) > strtotime($row['end_time'])) {

    // Today's class has already finished
?>

<a href="#"
   class="btn-view"
   style="background:#dc3545; cursor:not-allowed;"
   onclick="return false;">
    ✅ Attendance Session Ended
</a>

<?php

} else {

    // Date and time are correct
?>

<a href="open_attendance.php?class_id=<?= $row['id']; ?>" class="btn-view">
    📷 Start Attendance
</a>

<?php

}

?>

        <a href="attendance.php?class_id=<?= $row['id']; ?>" class="btn-view">
            <i class="bi bi-check2-square"></i>
            Attendance
        </a>

        <a href="edit_class.php?id=<?= $row['id']; ?>" class="btn-edit">
            <i class="bi bi-pencil-square"></i>
            Edit
        </a>

    </div>

</div>

<?php } ?>

</div>

</div>

</div>

<script>

const btn = document.getElementById("showForm");
const form = document.getElementById("createClassForm");

btn.onclick = function(){

    if(form.style.display=="none"){

        form.style.display="block";
        btn.innerHTML="❌ Close";

    }else{

        form.style.display="none";
        btn.innerHTML="➕ Create Class";

    }

}

</script>

<script>
document.querySelector('input[name="class_date"]').addEventListener('change', function(){

    let date = new Date(this.value);

    let days = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
    ];

    document.getElementById("day").value = days[date.getDay()];

});
</script>

</body>

</html>