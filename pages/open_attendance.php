<?php

include "../includes/config.php";

$class_id = intval($_GET['class_id']);

$class_query = mysqli_query($conn,"
    SELECT start_time, end_time
    FROM classes
    WHERE id='$class_id'
");

$class = mysqli_fetch_assoc($class_query);

$current_time = date("H:i:s");

if($current_time < $class['start_time']){

    die("Attendance has not started yet.");

}

if($current_time > $class['end_time']){

    die("Attendance time has ended.");

}

$date = date("Y-m-d");

$check = mysqli_query($conn,"
SELECT *
FROM attendance_sessions
WHERE class_id='$class_id'
AND session_date='$date'
");

if(mysqli_num_rows($check)==0){

    mysqli_query($conn,"
    INSERT INTO attendance_sessions
    (class_id,session_date,status)
    VALUES
    ('$class_id','$date','OPEN')
    ");

}else{

    mysqli_query($conn,"
    UPDATE attendance_sessions
    SET status='OPEN',
        closed_at=NULL
    WHERE class_id='$class_id'
    AND session_date='$date'
    ");

}

header("Location: scanner.php?class_id=".$class_id);

?>