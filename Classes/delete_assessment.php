<?php
session_start();
include "../Classes/connection.php";

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    echo "<p>Student not found. Please Login First</p>
          <a href='../Classes/login.php' class='btn btn-outline-warning btn-sm' style='color: #1b651b;'>Login Here</a>";
    exit();
}

if (isset($_GET['assessment_id'])) {
    $assessment_id = $_GET['assessment_id'];

    // Step 1: Delete related results first
    $stmt = $con->prepare("DELETE FROM results WHERE assessment_id = ?");
    $stmt->bind_param("i", $assessment_id);
    $stmt->execute();
    $stmt->close();

    // Step 2: Now delete the assessment
    $stmt = $con->prepare("DELETE FROM assessment WHERE assessment_id = ?");
    $stmt->bind_param("i", $assessment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Assessment and related results deleted successfully');</script>";
        header("Location: assessment.php");
        exit();
    } else {
        echo "<script>alert('Error deleting assessment.');</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('No assessment ID provided.');</script>";
}
?>