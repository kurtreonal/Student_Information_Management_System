<?php
session_start();
include "../Classes/connection.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student info
$query = "SELECT s.*, sec.section_name FROM student s JOIN section sec ON s.section_id = sec.section_id WHERE s.student_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // If password is provided, update it too
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $updateQuery = "UPDATE student SET first_name=?, middle_name=?, last_name=?, age=?, email=?, password=? WHERE student_id=?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $age, $email, $hashed_password, $student_id);
    } else {
        // If password not provided, exclude from update
        $updateQuery = "UPDATE student SET first_name=?, middle_name=?, last_name=?, age=?, email=? WHERE student_id=?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("sssssi", $first_name, $middle_name, $last_name, $age, $email, $student_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Information updated successfully!'); window.location.href='studentinfo.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating information.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Student Information</title>
    <meta name="keywords" content="Student Information Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Student Info Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Styles/update_studentinfo.css">
    <title>Update Information</title>
</head>
<body>
    <?php include "../Classes/sidebar.php" ?>
    <div class="container mt-5" style="max-width: 900px; margin-left: auto; margin-right: auto;">
    <div class="page-title">Edit Your Personal Info</div>
    <div class="description">Update your personal info and your preferences</div>
    <form method="POST">
        <div class="card">
            <h5>Basic Information</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input type="text" class="form-control" name="middle_name" value="<?= htmlspecialchars($student['middle_name']) ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="student_number" class="form-label">Student Number</label>
                    <input type="text" class="form-control" name="student_number" value="<?= htmlspecialchars($student['student_number']) ?>" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" name="age" value="<?= htmlspecialchars($student['age']) ?>" min="0" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="section" class="form-label">Section</label>
                    <input type="text" class="form-control" name="section" value="<?= htmlspecialchars($student['section_name']) ?>" readonly>
                </div>
            </div>
        </div>

        <div class="card">
            <h5>Account Information</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" name="password" placeholder="••••••••">
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-save">Save Changes</button>
    </form>
    <div class="hide">HIDDEN</div>
</div>
</body>
</html>