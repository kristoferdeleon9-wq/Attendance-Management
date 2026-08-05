<?php
session_start();

if(isset($_SESSION['role'])){
    if($_SESSION['role'] == "admin"){
        header("Location: admin/dashboard.php");
    }elseif($_SESSION['role'] == "teacher"){
        header("Location: teacher/dashboard.php");
    }else{
        header("Location: student/dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Attendance Management System</title>

<link rel="stylesheet" href="../css/style.css">

</head>
<body>

<div class="login-container">

    <div class="login-box">

        <h2>Attendance Management System</h2>
        <p>Please login to continue</p>

        <?php
        if(isset($_GET['error'])){
            echo "<div class='error'>Invalid username or password.</div>";
        }
        ?>

        <form action="login_process.php" method="POST">

            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Login</button>

        </form>

    </div>

</div>

</body>
</html>