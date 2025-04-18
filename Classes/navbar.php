<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Landing Page</title>
    <meta name="keywords" content="Student Information Landing Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Landing Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../Styles/navbar.css">
</head>
<body>
    <header class="nav">
        <a href="../Classes/landingpage.php" class="nav-logo"><img class="nav-logo" src="../Assets/cvsulogo.png" alt="Tohsaka Cute Photo" height="45px" width="35%"></a>
        </div>
            <nav class="navigation" style="margin-top: 45px;margin-right: 3%;">
                    <a href="../Classes/landingpage.php">Home</a>
                    <a href="../Classes/studentinfo.php">Student</a>
                    <a href="..//Classes/syllabus.php">Syllabus</a>
                    <a href="../Classes/assessment.php">Assessment</a>
                    <a href="../Classes/about.php">About</a>
            </nav>
        </div>
        <?php if (isset($_SESSION['student_id'])): ?>
            <a href="../Classes/logout.php" class="login-logo" title="Logout">
                <img src="../Assets/user-logo.png" alt="Logout" height="45px" width="45px" style="border-radius: 100%;">
            </a>
        <?php else: ?>
            <a href="../Classes/login.php" class="login-logo" title="Login">
                <img src="../Assets/user-logo.png" alt="Login" height="45px" width="45px" style="border-radius: 100%;">
            </a>
        <?php endif; ?>
    </header>
</body>
</html>