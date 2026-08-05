<?php
include "../includes/config.php";
include "../phpqrcode/qrlib.php";

$message = "";
$messageType = "";

    $selectedSection = isset($_GET['section_id']) ? (int)$_GET['section_id'] : 0;

    if(isset($_POST['save_section'])){

    $section_name = mysqli_real_escape_string($conn, $_POST['section_name']);

    mysqli_query($conn,"
        INSERT INTO sections(section_name)
        VALUES('$section_name')
    ");

    header("Location: students.php");
    exit;
}

if(isset($_POST['save'])){

    $student_id = $_POST['student_id'];
    $fullname   = $_POST['fullname'];
    $course = $_POST['course'];
    $photo = $_FILES['photo']['name'];

$tmp = $_FILES['photo']['tmp_name'];

$photoName = time() . "_" . $photo;

move_uploaded_file($tmp, "../uploads/" . $photoName);
    $section_id = $_POST['section_id'];

    $qrText = $student_id;

    $fileName = "../qrcodes/".$student_id.".png";

    QRcode::png($qrText, $fileName, QR_ECLEVEL_L, 8);

    $sql = "INSERT INTO students
(student_id, fullname, course, section_id, qr_code, photo)

VALUES

('$student_id',
'$fullname',
'$course',
'$section_id',
'$fileName',
'$photoName')";

    if(mysqli_query($conn,$sql)){
        echo "<script>alert('Student Added Successfully!');</script>";
    }else{
        echo mysqli_error($conn);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Students</title>

<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/students.css">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="sidebar">

    <h2>AMS</h2>

    <a href="../OJT Projects.php">🏠 Dashboard</a>
    <a href="students.php" class="active">👨‍🎓 Students</a>
    <a href="classes.php">🎓 Classes</a>   
    <a href="attendance.php">📝 Attendance</a>
    <a href="reports.php">📊 Reports</a>
    <a href="#">⚙️ Settings</a>

</div>

<div class="main-content">

<div class="container">

<h1>Students</h1>

<?php
if($selectedSection == 0){

    echo "<h2>Select a Section</h2>";

    $sections = mysqli_query($conn,"SELECT * FROM sections ORDER BY section_name");
?>

<button type="button" class="btn-add" onclick="showSectionForm()">
    + Add Section
</button>

<form method="POST" id="sectionForm" style="display:none; margin:20px 0;">

    <label>Section Name</label>

    <input type="text"
           name="section_name"
           placeholder="Example: BSIT 4A"
           required>

    <button type="submit" name="save_section">
        Save Section
    </button>

    <button type="button" onclick="hideSectionForm()">
        Cancel
    </button>

</form>

<div class="section-container">

<?php
while($sec = mysqli_fetch_assoc($sections)){
?>

    <div class="section-card">

        <div class="section-icon">📚</div>

        <h3><?= $sec['section_name']; ?></h3>

        <a href="students.php?section_id=<?= $sec['id']; ?>">
            Open Section
        </a>

    </div>

<?php
}
?>

</div>

<?php
}else{
?>

    <a href="students.php" class="btn-back">← Back to Sections</a>

<?php
$section = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT section_name
FROM sections
WHERE id='$selectedSection'
"));
?>

<h2><?= $section['section_name']; ?></h2>

<button type="button" class="btn-add" onclick="showForm()">
    + Add Student
</button>

<form method="POST"
      enctype="multipart/form-data"
      id="studentForm"
      style="display:none;">

<label>Student ID</label>
<input type="text" name="student_id" required>

<label>Full Name</label>
<input type="text" name="fullname" required>

<label>Course</label>
<input type="text" name="course" required>
<label>Student Photo</label>

<input
type="file"
name="photo"
accept="image/*"
required>

<input type="hidden" name="section_id" value="<?= $selectedSection; ?>">

<button type="button" class="btn-cancel" onclick="hideForm()">
    Cancel
</button>
<button type="submit" name="save">
Save Student
</button>

</form>

<hr>

<h2>Student List</h2>

<div class="student-grid">

<?php

$result = mysqli_query($conn,"
SELECT students.*, sections.section_name
FROM students
LEFT JOIN sections
ON students.section_id = sections.id
WHERE students.section_id='$selectedSection'
");

while($row = mysqli_fetch_assoc($result)){
?>

<div class="student-card">

    <div class="student-header">
        <h2>Attendance Management System</h2>
        <span>Student Identification Card</span>
    </div>

    <div class="student-body">

        <div class="student-photo">
            <img src="../uploads/<?= $row['photo']; ?>" class="student-img">
        </div>

        <div class="student-details">

            <h3><?= strtoupper($row['fullname']); ?></h3>

            <span class="student-id">
                <?= $row['student_id']; ?>
            </span>

            <table class="student-info-table">

                <tr>
                    <td><strong>Course</strong></td>
                    <td><?= $row['course']; ?></td>
                </tr>

                <tr>
                    <td><strong>Section</strong></td>
                    <td><?= $row['section_name']; ?></td>
                </tr>

            </table>

        </div>

        <div class="student-qr">

    <a href="<?= $row['qr_code']; ?>" target="_blank">
        <img src="<?= $row['qr_code']; ?>" class="qr-img" alt="QR Code">
    </a>

    <p class="scan-text">
        Scan for Attendance
    </p>

</div>

    </div>

    <div class="student-footer">

        <a href="edit_student.php?id=<?= $row['id']; ?>" class="btn-edit">
            ✏ Edit
        </a>

        <a href="delete_student.php?id=<?= $row['id']; ?>"
           class="btn-delete"
           onclick="return confirm('Delete this student?')">
            🗑 Delete
        </a>

    </div>

</div>

<?php } ?>

</div>

</div>

</div>

<?php
}
?>
<script>
function showForm(){
    document.getElementById("studentForm").style.display = "block";
}

function hideForm(){
    document.getElementById("studentForm").style.display = "none";
}

function showSectionForm(){
    document.getElementById("sectionForm").style.display = "block";
}

function hideSectionForm(){
    document.getElementById("sectionForm").style.display = "none";
}
</script>


</body>
</html>