<?php
include "../includes/config.php";
?>
<?php

if(!isset($_GET['class_id']))
{
    header("Location: classes.php");
    exit();
}

$class_id = intval($_GET['class_id']);

?>

<?php

$date = date("Y-m-d");

$session = mysqli_query($conn,"
SELECT *
FROM attendance_sessions
WHERE class_id='$class_id'
AND session_date='$date'
AND status='OPEN'
");

if(mysqli_num_rows($session)==0){

    die("Attendance is closed.");

}

?>

<?php

$sql = mysqli_query($conn,"
SELECT *
FROM classes
WHERE id='$class_id'
");

$class = mysqli_fetch_assoc($sql);

if(!$class)
{
    die("Class not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>QR Scanner</title>

<link rel="stylesheet" href="../css/style.css">

<script src="https://unpkg.com/html5-qrcode"></script>

<style>

#reader{
    width:100%;
    max-width:500px;
    margin:20px auto;
}
#message{
    margin-top:20px;
    text-align:center;
    font-size:20px;
    font-weight:bold;
}

.success{
    color:green;
}

.error{
    color:red;
}

.student-card{
    margin-top:25px;
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
}

.student-profile{
    display:flex;
    gap:20px;
}

.student-profile img{
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:50%;
}


@media(max-width:768px){

.student-profile{
    flex-direction:column;
    align-items:center;
    text-align:center;
}

.student-info{
    width:100%;
}

}

</style>

</head>

<body>

<div class="sidebar">

    <h2>AMS</h2>

    <a href="../OJT Projects.php">🏠 Dashboard</a>
    <a href="students.php">👨‍🎓 Students</a>
    <a href="classes.php">🎓 Classes</a>
    <a href="attendance.php">📝 Attendance</a>
    <a href="scanner.php" class="active">📷 QR Scanner</a>
    <a href="reports.php">📊 Reports</a>
    <a href="#">⚙️ Settings</a>
</div>

<div class="main-content">

<a href="classes.php" class="btn-back">
← Back to Classes
</a>

<div class="container">

<h1>QR Attendance Scanner</h1>

<div class="scanner-header">

    <div class="info-box">
        <h3>Today's Date</h3>
        <p id="todayDate"></p>
    </div>

    <div class="info-box">
        <h3>Current Time</h3>
        <p id="clock"></p>
    </div>

</div>

<div class="info-box" style="margin-bottom:20px;">

    <h3>
        <?= $class['class_name']; ?>
    </h3>

    <p>

        <?= $class['subject']; ?>

        <br>

        <?= $class['day_of_week']; ?>

        <br>

        <?= date("h:i A",strtotime($class['start_time'])); ?>

        -

        <?= date("h:i A",strtotime($class['end_time'])); ?>

    </p>

</div>

<div id="reader"></div>

<div id="message"></div>

<div id="student-card" class="student-card" style="display:none;">

    <h2>✅ Attendance Recorded</h2>

    <div class="student-profile">

        <img id="student-photo" style="display:none;">

        <div class="student-info">

            <p><strong>Student ID:</strong> <span id="sid"></span></p>

            <p><strong>Name:</strong> <span id="sname"></span></p>

            <p><strong>Course:</strong> <span id="scourse"></span></p>

            <p><strong>Section:</strong> <span id="ssection"></span></p>

            <p><strong>Subject:</strong> <span id="ssubject"></span></p>

            <p><strong>Date:</strong> <span id="sdate"></span></p>

            <p><strong>Time:</strong> <span id="stime"></span></p>

            <p>
                <strong>Status:</strong>

                <span id="sstatus" class="status-badge"></span>

            </p>

        </div>

    </div>

</div>

</div>

</div>

<script>

const message = document.getElementById("message");

const html5QrCode = new Html5Qrcode("reader");

let isScanning = false;

let lastScan = "";
let lastScanTime = 0;

function startScanner(){

let qrSize = Math.min(window.innerWidth * 0.7, 250);

    html5QrCode.start(

        {
            facingMode:"environment"
        },

        {
            fps:10,
            qrbox:qrSize
        },

        onScanSuccess,

        onScanFailure

    );

}

function onScanFailure(error){

    if(message.innerHTML === ""){
        message.className = "";
        message.innerHTML = "📷 Looking for QR Code...";
    }

}

function onScanSuccess(decodedText){

console.log("QR Detected:", decodedText);

const now = Date.now();

if(decodedText === lastScan && now - lastScanTime < 5000){
    return;
}

lastScan = decodedText;
lastScanTime = now;

    if(isScanning){
        return;
    }

    isScanning = true;

    fetch("save_attendance.php",{

        method:"POST",

        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },

        body:
        "student_id=" + encodeURIComponent(decodedText) +
        "&class_id=<?= $class_id ?>"

    })

.then(response => response.json())

.then(data => {

    if(data.success){

        document.getElementById("student-card").style.display="block";

        document.getElementById("sid").innerHTML=data.student_id; 
        document.getElementById("sname").innerHTML=data.fullname; 
        document.getElementById("scourse").innerHTML=data.course; 
        document.getElementById("ssection").innerHTML = data.section;

document.getElementById("ssubject").innerHTML = data.subject;   

if(data.photo){

    document.getElementById("student-photo").src = "../uploads/" + data.photo;
    document.getElementById("student-photo").style.display = "block";

}else{

    document.getElementById("student-photo").style.display = "none";

}
        document.getElementById("sdate").innerHTML=data.date;
        document.getElementById("stime").innerHTML=data.time;
        document.getElementById("sstatus").innerHTML=data.status;

    }else{

    message.className = "error";
    message.innerHTML = data.message;


    }

    isScanning = false; 


});


}

function updateClock(){

    const now = new Date();

    document.getElementById("clock").innerHTML =
        now.toLocaleTimeString();

    document.getElementById("todayDate").innerHTML =
        now.toLocaleDateString(undefined,{
            weekday:'long',
            year:'numeric',
            month:'long',
            day:'numeric'
        });

}

setInterval(updateClock,1000);

updateClock();

startScanner();

</script>

</body>

</html>