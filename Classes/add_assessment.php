<?php
session_start();
include "../Classes/connection.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

try {
    // Get only the subjects linked to the current student
    $stmt = $con->prepare("
        SELECT s.subject_id, s.subject_name
        FROM subject s
        INNER JOIN studentsubject ss ON s.subject_id = ss.subject_id
        WHERE ss.student_id = ?
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $subjectResult = $stmt->get_result();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $subject_id = $_POST['subject'];
        $type = $_POST['type'];
        $assessment_name = $_POST['assessment_name'];
        $score = $_POST['score'];

        // Double check subject is really linked to the student
        $checkSubject = $con->prepare("SELECT 1 FROM studentsubject WHERE student_id = ? AND subject_id = ?");
        $checkSubject->bind_param("ii", $student_id, $subject_id);
        $checkSubject->execute();

        if ($checkSubject->get_result()->num_rows == 0) {
            echo "<script>alert('Unauthorized subject selection.'); window.location.href='add_assessment.php';</script>";
            exit();
        }
        $checkSubject->close();

        // Insert into assessment
        $stmt1 = $con->prepare("INSERT INTO assessment (assessment_name, student_id, subject_id, type) VALUES (?, ?, ?, ?)");
        $stmt1->bind_param("ssis", $assessment_name, $student_id, $subject_id, $type);
        $stmt1->execute();
        $assessment_id = $stmt1->insert_id;
        $stmt1->close();

        // Insert into results
        $stmt2 = $con->prepare("INSERT INTO results (assessment_id, student_id, score) VALUES (?, ?, ?)");
        $stmt2->bind_param("iid", $assessment_id, $student_id, $score);
        $stmt2->execute();
        $stmt2->close();

        echo "<script>alert('Assessment successfully added!'); window.location.href='assessment.php';</script>";
        exit();
    }

} catch (Exception $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='add_assessment.php';</script>";
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
    <link rel="stylesheet" href="../Styles/add_update_assessment.css">
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
                        <option value="<?php echo $row['subject_id']; ?>"><?php echo htmlspecialchars($row['subject_name']); ?></option>
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