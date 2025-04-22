<?php
session_start();
include "../Classes/connection.php";

// If student is not logged in, redirect to login
if (!isset($_SESSION['student_id'])) {
    header("Location: ../Classes/login.php");
    exit();
}

    $student_id = $_SESSION['student_id'];

    // Check if student exists in the database
    $student_query = "SELECT * FROM student WHERE student_id = ?";
    $stmt = $con->prepare($student_query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result_student = $stmt->get_result();
    $student = $result_student->fetch_assoc();
    $stmt->close();

// If student is not found (deleted or invalid), redirect to login
if (!$student) {
    session_destroy(); // Clear invalid session
    header("Location: ../Classes/login.php");
    exit();
}
?>
<?php

    $query = "
        SELECT
            s.subject_id,
            s.subject_name,
            s.instructor,
            sch.day_of_week,
            sch.time_in,
            sch.time_out
        FROM schedulesubject ss
        JOIN subject s ON ss.subject_id = s.subject_id
        JOIN schedule sch ON ss.schedule_id = sch.schedule_id
        WHERE s.is_deleted = 0
        AND sch.is_deleted = 0
        AND s.student_id = ?
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
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
    <?php $mode = "syllabus";
    include "../Classes/search.php";?>
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
            <?php while ($row = mysqli_fetch_assoc($search_result)): ?>
                <?php $subject_id = $row['subject_id']; ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['subject_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['instructor']); ?></td>
                    <td><?php echo htmlspecialchars($row['day_of_week']); ?></td>
                    <td><?php echo date("h:i A", strtotime($row['time_in'])); ?></td>
                    <td><?php echo date("h:i A", strtotime($row['time_out'])); ?></td>
                    <td>
                        <a href='update_syllabus.php?subject_id=<?= $subject_id ?>' class='btn btn-outline-warning btn-sm' style='color: #1b651b;'>Update</a>
                        <a href='delete_syllabus.php?subject_id=<?= $subject_id ?>'
                        class='btn btn-outline-danger btn-sm'
                        onclick="return confirm('Are you sure you want to delete this syllabus?');">
                        Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <a href="add_syllabus.php" class="submitBtn">Add Syllabus</a>
</main>

<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(filter) ? '' : 'none';
    });
});
</script>
</body>
</html>