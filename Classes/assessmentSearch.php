<?php
// Ensure session and DB connection are set
if (!isset($con) || !isset($_SESSION['student_id'])) {
    exit("Missing connection or session.");
}

$student_id = $_SESSION['student_id'];
$searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : null;

// Query to get assessments with subject name and score
$query = "
    SELECT
        a.assessment_id,
        a.assessment_name,
        a.type,
        sub.subject_name,
        r.score
    FROM assessment a
    JOIN subject sub ON a.subject_id = sub.subject_id
    LEFT JOIN results r ON a.assessment_id = r.assessment_id AND r.student_id = ?
    WHERE a.student_id = ? AND a.is_deleted = 0
";

if ($searchTerm) {
    $query .= " AND (
        a.assessment_name LIKE ?
        OR a.type LIKE ?
        OR sub.subject_name LIKE ?
        OR r.score LIKE ?
    )";
}

$stmt = $con->prepare($query);

if ($searchTerm) {
    $stmt->bind_param("iissss", $student_id, $student_id, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
} else {
    $stmt->bind_param("ii", $student_id, $student_id);
}

$stmt->execute();
$search_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/search.css">
</head>
<body>
        <div class="search-bar-container mb-3">
            <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search assessment...">
        </div>
</body>
</html>