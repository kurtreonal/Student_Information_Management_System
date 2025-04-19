<?php
session_start();
include "../Classes/connection.php";

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$field = $_GET['field'] ?? '';
$allowed_fields = ['first_name', 'middle_name', 'last_name', 'student_number', 'age', 'email', 'password', 'section_id'];

// Redirect if invalid field
if (!in_array($field, $allowed_fields)) {
    header("Location: studentinfo.php");
    exit();
}

// If form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_value = $_POST['new_value'];

    // Special case for password: hash before storing
    if ($field == 'password') {
        $new_value = password_hash($new_value, PASSWORD_DEFAULT);
    }

    // Prepare and update
    $stmt = $con->prepare("UPDATE student SET $field = ? WHERE student_id = ?");
    $stmt->bind_param("si", $new_value, $student_id);

    if ($stmt->execute()) {
        header("Location: studentinfo.php?updated=$field");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update <?= htmlspecialchars($field) ?></title>
</head>
<body>
    <h2>Update <?= ucfirst(str_replace("_", " ", $field)) ?></h2>
    <form method="POST">
        <input type="<?= $field == 'password' ? 'password' : 'text' ?>" name="new_value" required>
        <button type="submit">Update</button>
    </form>
    <a href="studentinfo.php">Cancel</a>
</body>
</html>