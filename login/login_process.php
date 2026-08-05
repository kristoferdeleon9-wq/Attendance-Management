<?php
session_start();

include "../includes/config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if($user['role'] == "admin"){
            header("Location: ../OJT Projects.php");
        }
        elseif($user['role'] == "teacher"){
            header("Location: teacher/dashboard.php");
        }
        else{
            header("Location: student/dashboard.php");
        }

        exit();

    }else{

        header("Location: login.php?error=1");
        exit();

    }

}else{

    header("Location: login.php");
    exit();

}
?>