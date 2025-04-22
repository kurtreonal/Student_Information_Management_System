<?php
session_start();
include "../Classes/connection.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['subject_id'])) {
    header("Location: syllabus.php");
    exit();
}

$subject_id = $_GET['subject_id'];
// Make sure this subject belongs to this student
$checkOwnership = $con->prepare("
    SELECT 1 FROM studentsubject
    WHERE student_id = ? AND subject_id = ?
");
$checkOwnership->bind_param("ii", $_SESSION['student_id'], $subject_id);
$checkOwnership->execute();
if ($checkOwnership->get_result()->num_rows == 0) {
    header("Location: syllabus.php?unauthorized=1");
    exit();
}
$checkOwnership->close();

// Fetch existing subject and schedule data
$query = "
    SELECT s.subject_id, s.subject_name, s.instructor, sch.day_of_week, sch.time_in, sch.time_out, sch.schedule_id
    FROM schedulesubject ss
    JOIN subject s ON ss.subject_id = s.subject_id
    JOIN schedule sch ON ss.schedule_id = sch.schedule_id
    WHERE s.subject_id = ? LIMIT 1
";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    header("Location: syllabus.php");
    exit();
}

// Handle form update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_name = $_POST['subject_name'];
    $instructor = $_POST['instructor'];
    $day = $_POST['day_of_week'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $schedule_id = $_POST['schedule_id'];

    // Update subject
    $updateSubject = "UPDATE subject SET subject_name = ?, instructor = ? WHERE subject_id = ?";
    $stmt1 = $con->prepare($updateSubject);
    $stmt1->bind_param("ssi", $subject_name, $instructor, $subject_id);
    $stmt1->execute();
    $stmt1->close();

    // Update schedule
    $updateSchedule = "UPDATE schedule SET day_of_week = ?, time_in = ?, time_out = ? WHERE schedule_id = ?";
    $stmt2 = $con->prepare($updateSchedule);
    $stmt2->bind_param("sssi", $day, $time_in, $time_out, $schedule_id);
    $stmt2->execute();
    $stmt2->close();

    header("Location: syllabus.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Syllabus</title>
    <meta charset="UTF-8">
    <meta name="description" content="Update Syllabus">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../Styles/add_update_syllabus.css">
</head>
<body>
<?php include "../Classes/sidebar.php"; ?>
<h2 class="move">Update Syllabus</h2>

<div class="form-container">
    <form method="POST">
        <input type="hidden" name="schedule_id" value="<?= $data['schedule_id'] ?>">

        <div class="form-group">
            <label for="subject_name">Subject</label>
            <input type="text" id="subject_name" name="subject_name" value="<?= htmlspecialchars($data['subject_name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="instructor">Instructor</label>
            <input type="text" id="instructor" name="instructor" value="<?= htmlspecialchars($data['instructor']) ?>" required>
        </div>

        <div class="form-group">
            <label for="day_of_week">Day</label>
            <select id="day_of_week" name="day_of_week" required>
                <?php
                $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                foreach ($days as $day) {
                    $selected = ($day == $data['day_of_week']) ? "selected" : "";
                    echo "<option value='$day' $selected>$day</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="time_in">Time In</label>
            <input type="time" id="time_in" name="time_in" value="<?= $data['time_in'] ?>" required>
        </div>

        <div class="form-group">
            <label for="time_out">Time Out</label>
            <input type="time" id="time_out" name="time_out" value="<?= $data['time_out'] ?>" required>
        </div>

        <button type="submit" class="submitBtn">Save Changes</button>
    </form>
</div>
</body>
</html>
