<?php

date_default_timezone_set('Asia/Manila');

$host = "sql110.infinityfree.com";
$user = "if0_42589583";
$password = "A09566080042";
$database = "if0_42589583_attendance_management";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>