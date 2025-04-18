<?php
session_start();
include "../Classes/connection.php";

// Get student info from DB
$student_id = $_SESSION['student_id'];
$student_query = "SELECT * FROM student WHERE student_id = ?";
$stmt = $con->prepare($student_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result_student = $stmt->get_result();
$student = $result_student->fetch_assoc();
$stmt->close();

// Check if student exists in DB
if (!$student) {
  echo "<p>Student not found. Please Login First</p>
  <a href='../Classes/login.php' class='btn btn-outline-warning btn-sm' style='color: #1b651b; margin-right: 10px;'>Login Here</a>

  <p>New Student?</p>
  <a href='../Classes/registerpage.php' class='btn btn-outline-warning btn-sm' style='color: #1b651b;'>Register Here</a>";
  exit();
}

// Fetch all assessments for the student
$assessment_query = "
  SELECT
      a.assessment_id,
      sub.subject_name,
      a.type AS assessment_type,
      a.assessment_name,
      r.score AS assessment_score
  FROM assessment a
  JOIN subject sub ON a.subject_id = sub.subject_id
  LEFT JOIN results r ON a.assessment_id = r.assessment_id AND r.student_id = ?
  WHERE r.student_id = ? OR r.student_id IS NULL
";

$stmt = $con->prepare($assessment_query);
$stmt->bind_param("ii", $student_id, $student_id);
$stmt->execute();
$assessment_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment</title>
    <meta name="keywords" content="Assessment Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Assessment Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/4aa19b73e3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../Styles/assessment.css">
    <style type="text/css">
    h2 {
      display: inline;
      margin-right: 78%;
    }
    .submitBtn {
      padding: 10px 18px;
      font-size: 15px;
      background-color: rgba(255, 255, 255, 0.4);
      color: #1b651b;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .submitBtn:hover {
      background-color: rgb(255, 193, 7);
    }
    </style>
</head>
<body>
<?php include "../Classes/sidebar.php"; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-left: 20%!important; padding-right: 7% !important;">
        <hr>
        <div class="table-responsive">
          <!-- First table for claimed users -->
          <h2>Welcome to Assessment</h2>
          <table class='table table-striped table-sm' style="background-color: rgba(255, 255, 255, 0.6) !important; border: 1px solid #dadce0 !important; border-radius: 8px!important;">
            <thead>
                <tr>
                    <th scope='col'>Subject</th>
                    <th scope='col'>Type</th>
                    <th scope='col'>Name</th>
                    <th scope='col'>Score</th>
                    <th scope='col'>Modify</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $assessment_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['assessment_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['assessment_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['assessment_score'] ?? 'Not yet graded'); ?></td>
                    <td>
                        <a href="update_assessment.php?id=<?php echo $row['assessment_id']; ?>" class='btn btn-outline-warning btn-sm' style='color: #1b651b;'>Update</a>
                        <a href="delete_assessment.php?assessment_id=<?php echo $row['assessment_id']; ?>"
                          class="btn btn-outline-warning btn-sm"
                          style="color: #1b651b;"
                          onclick="return confirm('Are you sure you want to delete this assessment?');">Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <a href="add_assessment.php" class="submitBtn">Add Assessment</a>
    </main>
</body>
</html>
