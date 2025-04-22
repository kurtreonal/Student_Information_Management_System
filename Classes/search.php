<?php
if (!isset($con) || !isset($_SESSION['student_id'])) {
    exit("Missing connection or session.");
}

$student_id = $_SESSION['student_id'];
$searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : null;

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

if ($searchTerm) {
    $query .= " AND (
        s.subject_name LIKE ?
        OR s.instructor LIKE ?
        OR sch.day_of_week LIKE ?
    )";
}

$stmt = $con->prepare($query);

if ($searchTerm) {
    $stmt->bind_param("isss", $student_id, $searchTerm, $searchTerm, $searchTerm);
} else {
    $stmt->bind_param("i", $student_id);
}

$stmt->execute();
$search_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/search.css">
    <title>Document</title>
</head>
<body>
        <div class="search-bar-container mb-3">
            <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search syllabus...">
        </div>
</body>
</html>