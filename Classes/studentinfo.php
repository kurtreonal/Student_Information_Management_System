<?php
session_start();
include "../Classes/connection.php";

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student info with section name
$query = "
    SELECT s.*, sec.section_name
    FROM student s
    JOIN section sec ON s.section_id = sec.section_id
    WHERE s.student_id = ?
";

$stmt = $con->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "<p>Student not found.</p>";
    exit();
}

// Mask email for display
function maskEmail($email) {
    $parts = explode('@', $email);
    $maskedUser = substr($parts[0], 0, 1) . str_repeat('*', strlen($parts[0]) - 1);
    return $maskedUser . '@' . $parts[1];
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
    <link rel="stylesheet" href="../Styles/studentinfo.css">
</head>
<body>

<?php include "../Classes/sidebar.php" ?>

<div class="personal-info-container">
    <h1>Your profile info in Student Information Management</h1>
    <p class="description">Personal info and options to manage it. Info about you and your preferences</p>
    <div class="border">
        <h2>Student Basic Information</h2>
        <div class="info-grid">
            <div class="info-card">
                <div class="label">Name</div>
                <div class="value">
                    <?= htmlspecialchars($student['first_name'] . ' ' . $student['middle_name'] . ' ' . $student['last_name']) ?>
                    <a href="#" class="edit-link">Edit</a>
                </div>
            </div>
            <div class="info-card">
                <div class="label">Student Number</div>
                <div class="value">
                    <?= htmlspecialchars($student['student_number']) ?>
                    <a href="#" class="edit-link">Edit</a>
                </div>
            </div>
            <div class="info-card">
                <div class="label">Section</div>
                <div class="value">
                    <?= htmlspecialchars($student['section_name']) ?>
                    <a href="#" class="edit-link">Edit</a>
                </div>
            </div>
            <div class="info-card">
                <div class="label">Age</div>
                <div class="value">
                    <?= htmlspecialchars($student['age']) ?>
                    <a href="#" class="edit-link">Edit</a>
                </div>
            </div>
        </div>

        <h2>Account Information</h2>
        <div class="info-grid">
            <div class="info-card">
                <div class="label">Email</div>
                <div class="value">
                    <?= htmlspecialchars(maskEmail($student['email'])) ?>
                    <a href="#" class="edit-link">Edit</a>
                </div>
            </div>
            <div class="info-card">
                <div class="label">Password</div>
                <div class="value">
                    ********
                    <a href="#" class="edit-link">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>