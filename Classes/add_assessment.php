<!DOCTYPE html>
<html lang="en">
<head>
<title>Add Assessment</title>
    <meta name="keywords" content="Student Information Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Student Info Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../Styles/add_assessment.css">
</head>
<body>
    <?php include "../Classes/sidebar.php"; ?>
    <h2 class="move">Welcome
            Student
      </h2>
  <div class="form-container">
    <h2>Add Assessment</h2>
    <form>
      <div class="form-group">
        <label for="subject">Subject</label>
        <select id="subject" name="subject">
          <option value="">Select subject</option>
          <option value="Math">Math</option>
          <option value="Science">Science</option>
          <option value="English">English</option>
        </select>
      </div>

      <div class="form-group">
        <label for="type">Assessment Type</label>
        <select id="type" name="type">
          <option value="">Select type</option>
          <option value="Quiz">Quiz</option>
          <option value="Assignment">Assignment</option>
          <option value="Project">Project</option>
          <option value="Exam">Exam</option>
        </select>
      </div>

      <div class="form-group">
        <label for="name">Assessment Name</label>
        <input type="text" id="name" name="name" placeholder="e.g., Midterm Quiz 1">
      </div>

      <div class="form-group">
        <label for="score">Score</label>
        <input type="number" id="score" name="score" placeholder="e.g., 95" min="0" max="100">
      </div>

      <button type="submit" class="submitBtn">Submit</button>
    </form>
  </div>

</body>
</html>