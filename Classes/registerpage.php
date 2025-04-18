<?php
session_start();

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

include "../Classes/connection.php";

// Fetch section ID
$section_query = "SELECT section_id, section_name FROM section";
$section_result = $con->query($section_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $first_name     = $_POST['first_name'];
    $middle_name    = $_POST['middle_name'];
    $last_name      = $_POST['last_name'];
    $student_number = $_POST['student_number'];
    $section_name   = $_POST['section'];
    $age            = $_POST['age'];
    $email          = $_POST['email'];
    $password       = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Step 1: Check or insert section
    $stmt = $con->prepare("SELECT section_id FROM section WHERE section_name = ?");
    $stmt->bind_param("s", $section_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $section_id = $row['section_id'];
    } else {
        // Insert new section
        $stmt = $con->prepare("INSERT INTO section (section_name) VALUES (?)");
        $stmt->bind_param("s", $section_name);
        $stmt->execute();
        $section_id = $stmt->insert_id;
    }

    // Step 2: Insert student record
    $stmt = $con->prepare("INSERT INTO student (first_name, middle_name, last_name, student_number, section_id, age, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiiss", $first_name, $middle_name, $last_name, $student_number, $section_id, $age, $email, $password);

    if ($stmt->execute()) {
        echo "✅ Registration successful!";
        header("Location: login.php"); // Redirect to login page after successful registration
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Registration Form</title>
  <link rel="stylesheet" href="../Styles/registerpage.css">
</head>
<body>
    <a href="../Classes/landingpage.php" class="nav-logo"><img class="nav-logo" src="../Assets/cvsulogo.png" alt="Tohsaka Cute Photo" height="45px" width="35%"></a>
  <div class="container">
    <!-- Title section -->
    <div class="title">Registration</div>
    <div class="content">
      <!-- Registration form -->
      <form action="../Classes/registerpage.php" method="POST">
        <div class="user-details">
          <!-- Input for Full Name -->
          <div class="input-box">
            <span class="details">First Name</span>
            <input type="text" name="first_name" placeholder="Enter your firstname" required>
          </div>
          <!-- Input for Username -->
          <div class="input-box">
            <span class="details">Middle Name</span>
            <input type="text" name="middle_name" placeholder="Enter your middlename" required>
          </div>
          <!-- Input for Last Name -->
          <div class="input-box">
            <span class="details">Last Name</span>
            <input type="text" name="last_name" placeholder="Enter your lastname" required>
          </div>
          <!-- Input for Student Number -->
          <div class="input-box">
            <span class="details">Student Number</span>
            <input type="number" name="student_number" placeholder="Enter your student number" required>
          </div>
          <!-- Input for Section -->
          <div class="input-box">
            <span class="details">Section</span>
            <input type="text" name="section" placeholder="Enter your section" required>
          </div>
          <!-- Input for Age -->
          <div class="input-box">
            <span class="details">Age</span>
            <input type="number" name="age" placeholder="Enter your age" required>
          </div>
          <!-- Input for Email -->
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="email" placeholder="Enter your email" required>
          </div>
          <!-- Input for Password -->
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="password" placeholder="Enter your password" required>
          </div>
        </div>

        <!-- Submit button -->
        <div class="button">
          <input type="submit" value="Register">
        </div>
      </form>
    </div>
  </div>
</body>
</html>