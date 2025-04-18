<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

include "../Classes/connection.php";

// Redirect if user is not logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject_name'];
    $instructor = $_POST['instructor'];
    $day = $_POST['day_of_week']; // Make sure this matches form
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];

    // Insert into subject table
    $insertSubject = "INSERT INTO subject (subject_name, instructor) VALUES (?, ?)";
    $stmt1 = $con->prepare($insertSubject);
    $stmt1->bind_param("ss", $subject, $instructor);
    $stmt1->execute();
    $subject_id = $stmt1->insert_id;
    $stmt1->close();

    // Insert into schedule table
    $insertSchedule = "INSERT INTO schedule (day_of_week, time_in, time_out) VALUES (?, ?, ?)";
    $stmt2 = $con->prepare($insertSchedule);
    $stmt2->bind_param("sss", $day, $time_in, $time_out);
    $stmt2->execute();
    $schedule_id = $stmt2->insert_id;
    $stmt2->close();

    // Link schedule and subject
    $insertLink = "INSERT INTO schedulesubject (schedule_id, subject_id) VALUES (?, ?)";
    $stmt3 = $con->prepare($insertLink);
    $stmt3->bind_param("ii", $schedule_id, $subject_id);
    $stmt3->execute();
    $stmt3->close();


    $student_id = $_SESSION['student_id'];

    // Link subject to student
    $stmt4 = $con->prepare("INSERT INTO studentsubject (student_id, subject_id) VALUES (?, ?)");
    $stmt4->bind_param("ii", $student_id, $subject_id);
    $stmt4->execute();
    $stmt4->close();

    // Link schedule to student
    $stmt5 = $con->prepare("INSERT INTO studentschedule (student_id, schedule_id) VALUES (?, ?)");
    $stmt5->bind_param("ii", $student_id, $schedule_id);
    $stmt5->execute();
    $stmt5->close();
    // Redirect after success
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
    <link rel="stylesheet" href="../Styles/add_syllabus.css">
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