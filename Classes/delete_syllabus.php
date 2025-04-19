<?php
session_start();
include "../Classes/connection.php";
include "../Classes/backup_to_csv.php"; // Make sure this handles backups safely

// Make sure student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Get subject_id from URL
if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];

    // Verify subject belongs to this student
    $check = $con->prepare("SELECT * FROM studentsubject WHERE student_id = ? AND subject_id = ?");
    $check->bind_param("ii", $student_id, $subject_id);
    $check->execute();
    $result = $check->get_result();
    if ($result->num_rows === 0) {
        echo "<script>alert('You do not have permission to delete this syllabus.'); window.location.href='syllabus.php';</script>";
        exit();
    }
    $check->close();

    // Begin transaction
    $con->begin_transaction();

    try {
        // Backup and soft delete related assessments
        $assessments = $con->prepare("SELECT * FROM assessment WHERE subject_id = ? AND is_deleted = 0");
        $assessments->bind_param("i", $subject_id);
        $assessments->execute();
        $result = $assessments->get_result();

        while ($row = $result->fetch_assoc()) {
            $data = [
                $row['assessment_id'],
                $row['subject_id'],
                $row['type'],
                $row['assessment_name'],
                1,
                date("Y-m-d H:i:s")
            ];
            backupToCSV("../Backups/deleted_assessments.csv", ["assessment_id", "subject_id", "type", "assessment_name", "is_deleted", "deleted_at"], $data);
        }
        $assessments->close();

        $soft_delete_assessment = $con->prepare("UPDATE assessment SET is_deleted = 1 WHERE subject_id = ?");
        $soft_delete_assessment->bind_param("i", $subject_id);
        $soft_delete_assessment->execute();
        $soft_delete_assessment->close();

        // Backup subject info
        $sub_stmt = $con->prepare("SELECT * FROM subject WHERE subject_id = ?");
        $sub_stmt->bind_param("i", $subject_id);
        $sub_stmt->execute();
        $sub_res = $sub_stmt->get_result();
        $subject = $sub_res->fetch_assoc();
        $sub_stmt->close();

        if ($subject) {
            $data = [
                $subject['subject_id'],
                $subject['subject_name'],
                $subject['instructor'],
                1,
                date("Y-m-d H:i:s")
            ];
            backupToCSV("../Backups/deleted_syllabus.csv", ["subject_id", "subject_name", "instructor", "is_deleted", "deleted_at"], $data);
        }

        // Step 1: Remove the student-subject association
        $delete_studentsubject = $con->prepare("DELETE FROM studentsubject WHERE subject_id = ?");
        $delete_studentsubject->bind_param("i", $subject_id);
        $delete_studentsubject->execute();
        $delete_studentsubject->close();

        // Step 2: Remove the schedule-subject association
        $delete_schedulesubject = $con->prepare("DELETE FROM schedulesubject WHERE subject_id = ?");
        $delete_schedulesubject->bind_param("i", $subject_id);
        $delete_schedulesubject->execute();
        $delete_schedulesubject->close();

        // Step 3: Remove student-schedule entries (if needed)
        $delete_studentschedule = $con->prepare("DELETE FROM studentschedule WHERE schedule_id IN (SELECT schedule_id FROM schedulesubject WHERE subject_id = ?)");
        $delete_studentschedule->bind_param("i", $subject_id);
        $delete_studentschedule->execute();
        $delete_studentschedule->close();

        // Step 4: Soft delete subject instead of DELETE
        $soft_delete_subject = $con->prepare("UPDATE subject SET is_deleted = 1 WHERE subject_id = ?");
        $soft_delete_subject->bind_param("i", $subject_id);
        $soft_delete_subject->execute();
        $soft_delete_subject->close();

        // Commit transaction
        $con->commit();

        header("Location: syllabus.php?deleted=1");
        exit();
    } catch (Exception $e) {
        $con->rollback();
        echo "<script>alert('Error occurred: " . $e->getMessage() . "'); window.location.href='syllabus.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid subject.'); window.location.href='syllabus.php';</script>";
    exit();
}
?>