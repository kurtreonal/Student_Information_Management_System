<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

include "../Classes/connection.php";

// Get the assessment ID from query string
if (!isset($_GET['id'])) {
    header("Location: assessment.php");
    exit();
}

$assessment_id = $_GET['id'];
$student_id = $_SESSION['student_id'];

// Fetch all subjects
$subjectQuery = "SELECT subject_id, subject_name FROM subject";
$subjectResult = mysqli_query($con, $subjectQuery);

// Fetch assessment and result data
$fetchQuery = "
    SELECT a.assessment_name, a.subject_id, a.type, r.score
    FROM assessment a
    JOIN results r ON a.assessment_id = r.assessment_id
    WHERE a.assessment_id = ? AND r.student_id = ?
";
$stmt = $con->prepare($fetchQuery);
$stmt->bind_param("ii", $assessment_id, $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Assessment not found or access denied.";
    exit();
}

$data = $result->fetch_assoc();

// Handle update submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST['subject'];
    $type = $_POST['type'];
    $assessment_name = $_POST['assessment_name'];
    $score = $_POST['score'];

    // Update assessment
    $updateAssessment = "UPDATE assessment SET assessment_name = ?, subject_id = ?, type = ? WHERE assessment_id = ?";
    $stmt1 = $con->prepare($updateAssessment);
    $stmt1->bind_param("sisi", $assessment_name, $subject_id, $type, $assessment_id);
    $stmt1->execute();
    $stmt1->close();

    // Update result
    $updateResult = "UPDATE results SET score = ? WHERE assessment_id = ? AND student_id = ?";
    $stmt2 = $con->prepare($updateResult);
    $stmt2->bind_param("dii", $score, $assessment_id, $student_id);
    $stmt2->execute();
    $stmt2->close();

    header("Location: assessment.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Assessment</title>
    <meta name="keywords" content="Student Information Page">
    <meta name="description" content="Update your assessment.">
    <meta name="author" content="Student Info Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../Styles/add_update_assessment.css">
</head>
<body>
    <?php include "../Classes/sidebar.php"; ?>
    <h2 class="move">Update Assessment</h2>

    <div class="form-container">
        <h2>Update Assessment</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="subject">Subject</label>
                <select id="subject" name="subject" required>
                    <option value="">Select subject</option>
                    <?php while ($row = mysqli_fetch_assoc($subjectResult)): ?>
                        <option value="<?php echo $row['subject_id']; ?>"
                            <?php echo $row['subject_id'] == $data['subject_id'] ? 'selected' : ''; ?>>
                            <?php echo $row['subject_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="type">Assessment Type</label>
                <select id="type" name="type" required>
                    <option value="">Select type</option>
                    <option value="Quiz" <?php echo $data['type'] == 'Quiz' ? 'selected' : ''; ?>>Quiz</option>
                    <option value="Activity" <?php echo $data['type'] == 'Activity' ? 'selected' : ''; ?>>Activity</option>
                    <option value="Exam" <?php echo $data['type'] == 'Exam' ? 'selected' : ''; ?>>Exam</option>
                </select>
            </div>

            <div class="form-group">
                <label for="assessment_name">Assessment Name</label>
                <input type="text" id="assessment_name" name="assessment_name" value="<?php echo htmlspecialchars($data['assessment_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="score">Score</label>
                <input type="number" id="score" name="score" value="<?php echo htmlspecialchars($data['score']); ?>" min="0" max="100" step="0.01" required>
            </div>

            <button type="submit" class="submitBtn">Update</button>
        </form>
    </div>
</body>
</html>