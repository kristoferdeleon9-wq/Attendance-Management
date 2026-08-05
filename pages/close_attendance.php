<?php

include "../includes/config.php";

$class_id = intval($_GET['class_id']);

$date = date("Y-m-d");

mysqli_query($conn,"
UPDATE attendance_sessions
SET
status='CLOSED',
closed_at=NOW()
WHERE class_id='$class_id'
AND session_date='$date'
");

header("Location: classes.php");

?>