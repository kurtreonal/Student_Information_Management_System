<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}
include "../Classes/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id'];
    $subject_name = $_POST['subject_name'];
    $instructor = $_POST['instructor'];
    $day = $_POST['day_of_week'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];

    // Check if subject exists for this student
    $stmt = $con->prepare("SELECT s.subject_id FROM subject s
        JOIN studentsubject ss ON s.subject_id = ss.subject_id
        WHERE s.subject_name = ? AND s.instructor = ? AND ss.student_id = ?");
    $stmt->bind_param("ssi", $subject_name, $instructor, $student_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($subject_id);
        $stmt->fetch();
    } else {
        // Insert subject
        $stmtInsert = $con->prepare("INSERT INTO subject (subject_name, instructor, student_id) VALUES (?, ?, ?)");
        $stmtInsert->bind_param("ssi", $subject_name, $instructor, $student_id);
        $stmtInsert->execute();
        $subject_id = $stmtInsert->insert_id;
        $stmtInsert->close();
    }
    $stmt->close();

    // Link subject to student if not yet linked
    $stmt = $con->prepare("SELECT * FROM studentsubject WHERE student_id = ? AND subject_id = ?");
    $stmt->bind_param("ii", $student_id, $subject_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows == 0) {
        $linkSub = $con->prepare("INSERT INTO studentsubject (student_id, subject_id) VALUES (?, ?)");
        $linkSub->bind_param("ii", $student_id, $subject_id);
        $linkSub->execute();
        $linkSub->close();
    }
    $stmt->close();

    // Check if schedule already exists
    $stmt = $con->prepare("SELECT schedule_id FROM schedule WHERE day_of_week = ? AND time_in = ? AND time_out = ?");
    $stmt->bind_param("sss", $day, $time_in, $time_out);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($schedule_id);
        $stmt->fetch();
    } else {
        // Insert schedule
        $stmtInsert = $con->prepare("INSERT INTO schedule (day_of_week, time_in, time_out) VALUES (?, ?, ?)");
        $stmtInsert->bind_param("sss", $day, $time_in, $time_out);
        $stmtInsert->execute();
        $schedule_id = $stmtInsert->insert_id;
        $stmtInsert->close();
    }
    $stmt->close();

    // Link schedule to subject
    $checkLink = $con->prepare("SELECT * FROM schedulesubject WHERE subject_id = ? AND schedule_id = ?");
    $checkLink->bind_param("ii", $subject_id, $schedule_id);
    $checkLink->execute();
    if ($checkLink->get_result()->num_rows == 0) {
        $stmt = $con->prepare("INSERT INTO schedulesubject (schedule_id, subject_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $schedule_id, $subject_id);
        $stmt->execute();
        $stmt->close();
    }
    $checkLink->close();

    // Link schedule to student
    $checkStudSched = $con->prepare("SELECT * FROM studentschedule WHERE student_id = ? AND schedule_id = ?");
    $checkStudSched->bind_param("ii", $student_id, $schedule_id);
    $checkStudSched->execute();
    if ($checkStudSched->get_result()->num_rows == 0) {
        $stmt = $con->prepare("INSERT INTO studentschedule (student_id, schedule_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $student_id, $schedule_id);
        $stmt->execute();
        $stmt->close();
    }
    $checkStudSched->close();

    header("Location: syllabus.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Syllabus</title>
    <meta name="keywords" content="Student Information Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Student Info Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../Styles/add_update_syllabus.css">
</head>
<body>
<?php include "../Classes/sidebar.php"; ?>

<h2 class="move">Welcome Student</h2>

<div class="form-container">
    <h2>Add Syllabus</h2>
    <form method="POST" action="add_syllabus.php">
        <div class="form-group">
            <label for="subject_name">Subject</label>
            <input type="text" id="subject_name" name="subject_name" placeholder="e.g., COSC 70" required>
        </div>

        <div class="form-group">
            <label for="instructor">Instructor</label>
            <input type="text" id="instructor" name="instructor" placeholder="e.g., Ms. Lorem" required>
        </div>

        <div class="form-group">
            <label for="day_of_week">Day</label>
            <select id="day_of_week" name="day_of_week" required>
                <option value="" disabled selected>Choose a day</option>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
        </div>

        <div class="form-group">
            <label for="time_in">Time In</label>
            <input type="time" id="time_in" name="time_in" required>
        </div>

        <div class="form-group">
            <label for="time_out">Time Out</label>
            <input type="time" id="time_out" name="time_out" required>
        </div>

        <button type="submit" class="submitBtn">Submit</button>
    </form>
</div>

</body>
</html>