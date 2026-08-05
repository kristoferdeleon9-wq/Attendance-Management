<?php
include "../includes/config.php";
date_default_timezone_set('Asia/Manila');

header('Content-Type: application/json');

if(isset($_POST['student_id']) && isset($_POST['class_id'])){

    $student_id = mysqli_real_escape_string($conn,$_POST['student_id']);
    $class_id   = mysqli_real_escape_string($conn,$_POST['class_id']);

    $date = date("Y-m-d");
    $time = date("H:i:s");

    $session = mysqli_query($conn,"
SELECT *
FROM attendance_sessions
WHERE class_id='$class_id'
AND session_date='$date'
AND status='OPEN'
");

if(mysqli_num_rows($session) == 0){

    echo json_encode([
        "success"=>false,
        "message"=>"Attendance session is closed."
    ]);

    exit;
}

    $studentQuery = mysqli_query($conn,"
SELECT
    students.*,
    sections.section_name
FROM students
LEFT JOIN sections
ON students.section_id = sections.id
WHERE students.student_id='$student_id'
");

    if(mysqli_num_rows($studentQuery)==0){
        echo json_encode([
            "success"=>false,
            "message"=>"Student not found."
        ]);
        exit;
    }

    $student = mysqli_fetch_assoc($studentQuery);

    // Get class
    $classQuery = mysqli_query($conn,"
        SELECT * FROM classes
        WHERE id='$class_id'
    ");

    if(mysqli_num_rows($classQuery)==0){
        echo json_encode([
            "success"=>false,
            "message"=>"Class not found."
        ]);
        exit;
    }

    $class = mysqli_fetch_assoc($classQuery);

    if($student['section_id'] != $class['section_id']){

    echo json_encode([
        "success" => false,
        "warning" => true,
        "message" => "⚠️ WARNING!\n\nThis student does not belong to this class or section.\nAttendance was NOT recorded."
    ]);

    exit;
}

    // Check duplicate attendance
    $check = mysqli_query($conn,"
        SELECT *
        FROM attendance
        WHERE student_id='$student_id'
        AND class_id='$class_id'
        AND attendance_date='$date'
    ");

    if(mysqli_num_rows($check)>0){

    echo json_encode([
        "success" => false,
        "already_scanned" => true,
        "message" => "⚠️ Attendance Already Recorded!\n\nThis student has already scanned their QR code today."
    ]);

    exit;
}
    $status = "Present";    

    // Save attendance
    mysqli_query($conn,"
        INSERT INTO attendance
        (student_id,class_id,attendance_date,time_in,status)
        VALUES
        ('$student_id','$class_id','$date','$time','$status')
    ");

    echo json_encode([

    "success"=>true,

    "student_id"=>$student['student_id'],

    "fullname"=>$student['fullname'],

    "course"=>$student['course'],

    "section"=>$student['section_name'],

    "subject"=>$class['subject'],

    "photo"=>$student['photo'],

    "date"=>$date,

    "time"=>date("h:i A",strtotime($time)),

    "status"=>"Present"

]);

}else{

    echo json_encode([
        "success"=>false,
        "message"=>"Invalid request."
    ]);
}