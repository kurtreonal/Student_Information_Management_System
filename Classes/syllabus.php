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
?>
<?php

// Fetch all subject, instructor, day, time_in, time_out
$query = "
    SELECT
        s.subject_name,
        s.instructor,
        sch.day_of_week,
        sch.time_in,
        sch.time_out
    FROM schedulesubject ss
    JOIN subject s ON ss.subject_id = s.subject_id
    JOIN schedule sch ON ss.schedule_id = sch.schedule_id
";

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Syllabus</title>
    <meta name="keywords" content="Syllabus Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Syllabus Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4aa19b73e3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../Styles/syllabus.css">
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
        <h2>Welcome to Syllabus</h2>
        <table class='table table-striped table-sm' style="background-color: rgba(255, 255, 255, 0.6); border: 1px solid #dadce0; border-radius: 8px;">
            <thead>
                <tr>
                    <th scope='col'>Subject</th>
                    <th scope='col'>Instructor</th>
                    <th scope='col'>Day</th>
                    <th scope='col'>Time In</th>
                    <th scope='col'>Time Out</th>
                    <th scope='col'>Modify</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['instructor']); ?></td>
                    <td><?php echo htmlspecialchars($row['day_of_week']); ?></td>
                    <td><?php echo date("h:i A", strtotime($row['time_in'])); ?></td>
                    <td><?php echo date("h:i A", strtotime($row['time_out'])); ?></td>
                    <td>
                        <a class='btn btn-outline-warning btn-sm' style='color: #1b651b;'>Update</a>
                        <a class='btn btn-outline-warning btn-sm' style='color: #1b651b;'>Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="add_syllabus.php" class="submitBtn">Add Syllabus</a>
</main>
</body>
</html>