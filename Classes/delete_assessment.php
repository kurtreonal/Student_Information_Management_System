<?php
session_start();
include "../Classes/connection.php";
include "../Classes/backup_to_csv.php";

if (!isset($_SESSION['student_id'])) {
    echo "<p>Student not found. Please Login First</p>
          <a href='../Classes/login.php' class='btn btn-outline-warning btn-sm' style='color: #1b651b;'>Login Here</a>";
    exit();
}

if (isset($_GET['assessment_id'])) {
    $assessment_id = $_GET['assessment_id'];

    // Backup first before deleting
    $stmt = $con->prepare("SELECT * FROM assessment WHERE assessment_id = ?");
    $stmt->bind_param("i", $assessment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $assessment = $result->fetch_assoc();
    $stmt->close();

    if ($assessment) {
        $filename = "../Backups/deleted_assessments.csv";
        $headers = ["assessment_id", "subject_id", "type", "assessment_name", "is_deleted", "deleted_at"];
        $data = [
            $assessment['assessment_id'],
            $assessment['subject_id'],
            $assessment['type'],
            $assessment['assessment_name'],
            1,
            date("Y-m-d H:i:s")
        ];
        backupToCSV($filename, $headers, $data);
    }

    // Soft delete
    $stmt = $con->prepare("UPDATE assessment SET is_deleted = 1 WHERE assessment_id = ?");
    $stmt->bind_param("i", $assessment_id);
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: assessment.php?deleted=1");
        exit();
    } else {
        echo "<script>alert('Error deleting assessment.'); window.location.href='assessment.php';</script>";
        $stmt->close();
    }
} else {
    echo "<script>alert('No assessment ID provided.'); window.location.href='assessment.php';</script>";
}
?>