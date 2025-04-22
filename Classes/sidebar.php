<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../Styles/sidebar.css">
</head>
<body>
  <div id="mySidebar" class="sidebar">
  <a href="./landingpage.php">
        <img class="nav-logo" src="../Assets/cvsulogo.png" alt="CVSU Logo" style="margin-right: 5%;"> Student Info
      </a>
    <div class="sidebar-header">
      <button class="toggle-btn" onclick="toggleNav()">
          <i class="fas fa-bars"></i>
      </button>
    </div>
    <a href="../Classes/landingpage.php"><i class="fas fa-home" style="color: #FFD43B;"></i><span>Home</span></a>
    <a href="../Classes/studentinfo.php"><i class="fas fa-user" style="color: #FFD43B;"></i><span>Student</span></a>
    <a href="../Classes/syllabus.php"><i class="fa-solid fa-calendar-days" style="color: #FFD43B;"></i> <span>Syllabus</span></a>
    <a href="../Classes/assessment.php"><i class="fa-solid fa-pen" style="color: #FFD43B;"></i><span>Assessment</span></a>
    <a href="../Classes/about.php"><i class="fa-solid fa-mug-saucer" style="color: #FFD43B;"></i><span>About</span></a>
    <a href="../Classes/logout.php"><i class="fa-solid fa-arrow-right-from-bracket" style="color: #FFD43B;"></i><span>Logout</span></a>
  </div>
</body>
<script src="../Javascript/function.js"></script>
</html>