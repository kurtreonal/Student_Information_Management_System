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

// Fetch all subjects for the dropdown
$subjectQuery = "SELECT subject_id, subject_name FROM subject";
$subjectResult = mysqli_query($con, $subjectQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_SESSION['student_id'];
    $subject_id = $_POST['subject'];
    $type = $_POST['type'];
    $assessment_name = $_POST['assessment_name'];
    $score = $_POST['score'];

    // Insert into assessment table
    $insertAssessment = "INSERT INTO assessment (assessment_name, subject_id, type) VALUES (?, ?, ?)";
    $stmt1 = $con->prepare($insertAssessment);
    $stmt1->bind_param("sis", $assessment_name, $subject_id, $type);
    $stmt1->execute();
    $assessment_id = $stmt1->insert_id;
    $stmt1->close();

    // Insert into results table
    $insertResult = "INSERT INTO results (assessment_id, student_id, score) VALUES (?, ?, ?)";
    $stmt2 = $con->prepare($insertResult);
    $stmt2->bind_param("iid", $assessment_id, $student_id, $score);
    $stmt2->execute();
    $stmt2->close();

    // Redirect after success
    header("Location: assessment.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Assessment</title>
    <meta name="keywords" content="Student Information Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Student Info Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../Styles/add_assessment.css">
</head>
<body>
    <?php include "../Classes/sidebar.php"; ?>
    <h2 class="move">Welcome Student</h2>

    <div class="form-container">
        <h2>Add Assessment</h2>
        <form method="POST" action="add_assessment.php">
            <div class="form-group">
                <label for="subject">Subject</label>
                <select id="subject" name="subject" required>
                    <option value="">Select subject</option>
                    <?php while ($row = mysqli_fetch_assoc($subjectResult)): ?>
                        <option value="<?php echo $row['subject_id']; ?>"><?php echo $row['subject_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="type">Assessment Type</label>
                <select id="type" name="type" required>
                    <option value="">Select type</option>
                    <option value="Quiz">Quiz</option>
                    <option value="Activity">Activity</option>
                    <option value="Exam">Exam</option>
                </select>
            </div>

            <div class="form-group">
                <label for="assessment_name">Assessment Name</label>
                <input type="text" id="assessment_name" name="assessment_name" placeholder="e.g., Midterm Quiz 1" required>
            </div>

            <div class="form-group">
                <label for="score">Score</label>
                <input type="number" id="score" name="score" placeholder="e.g., 95" min="0" max="100" step="0.01" required>
            </div>

            <button type="submit" class="submitBtn">Submit</button>
        </form>
    </div>
</body>
</html>