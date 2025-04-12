<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Information</title>
    <meta name="keywords" content="Student Information Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Student Info Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../Styles/studentinfo.css">
</head>
<body>
    <?php  include "../Classes/sidebar.php" ?>

    <div class="personal-info-container">
  <h1>Your profile info in Student Information Management</h1>
  <p class="description">
    Personal info and options to manage it. Info about you and your preferences
  </p>
  <div class="border">
    <h2>Student Basic Information</h2>
    <div class="info-grid">
    <div class="info-card">
            <div class="label">Name</div>
            <div class="value">
                Juan Dela Cruz
                <a href="#" class="edit-link">Edit</a>
            </div>
        </div>
        <div class="info-card">
            <div class="label">Student Number</div>
            <div class="value">
                202311233
                <a href="#" class="edit-link">Edit</a>
            </div>
        </div>
        <div class="info-card">
            <div class="label">Section</div>
            <div class="value">
                BSCS 2-3
                <a href="#" class="edit-link">Edit</a>
            </div>
        </div>
        <div class="info-card">
            <div class="label">Age</div>
            <div class="value">
                20
                <a href="#" class="edit-link">Edit</a>
            </div>
        </div>
    </div>
    <h2>Account Information</h2>
    <div class="info-grid">
    <div class="info-card">
            <div class="label">Email</div>
            <div class="value">
                k********gmail.com
                <a href="#" class="edit-link">Edit</a>
            </div>
        </div>
        <div class="info-card">
            <div class="label">Password</div>
            <div class="value">
                ********
                <a href="#" class="edit-link">Edit</a>
            </div>
        </div>
    </div>
    <!-- Add more info-cards as needed -->
  </div>
</div>
</body>
</html>