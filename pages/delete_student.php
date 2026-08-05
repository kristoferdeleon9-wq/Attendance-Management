<?php

include "../includes/config.php";

if(isset($_GET['id'])){

    $id = intval($_GET['id']);

    // Get QR code filename
    $result = mysqli_query($conn,"SELECT qr_code FROM students WHERE id=$id");

    if($row = mysqli_fetch_assoc($result)){

        if(file_exists($row['qr_code'])){
            unlink($row['qr_code']);
        }

    }

    mysqli_query($conn,"DELETE FROM students WHERE id=$id");

}

header("Location: students.php");
exit;