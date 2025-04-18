<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    // Check if email exists
    $stmt = $con->prepare("SELECT * FROM student WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        // Update password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_stmt = $con->prepare("UPDATE student SET password = ? WHERE email = ?");
        $update_stmt->bind_param("ss", $hashed_password, $email);
        $update_stmt->execute();

        echo "<script>alert('Password successfully updated! You may now login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Email not found. Please check again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Login Page</title>
    <meta name="keywords" content="Student Information Landing Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Landing Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/login.css"> <!-- reuse login styles -->
</head>
<body>
<div class="container">
    <div class="myform">
        <form method="POST" action="">
            <h2>Reset Password</h2>
            <input type="email" name="email" placeholder="Enter your registered email" required>
            <input type="password" name="new_password" placeholder="Enter new password" required>
            <button type="submit">Reset Password</button>
            <a href="login.php" class="btn btn-link">Back to Login</a>
        </form>
    </div>
    <div class="image">
        <img src="../Assets/cvsulogo.png">
    </div>
</div>
</body>
</html>