<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Syllabus</title>
    <meta name="keywords" content="Student Information Page">
    <meta name="description" content="Stop dwindling, Start by managing your time.">
    <meta name="author" content="Student Info Page Developer/Designer (Pascua)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../Assets/cvsulogo.png" type="image/x-icon">
    <link rel="stylesheet" href="../Styles/add_syllabus.css">
</head>
<body>
<?php include "../Classes/sidebar.php"; ?>
    <h2 class="move">Welcome
        Student
      </h2>
  <div class="form-container">
    <h2>Add Syllabus</h2>
    <form>
      <div class="form-group">
        <label for="subject">Subject</label>
        <select id="subject" name="subject">
          <option value="">Select subject</option>
          <option value="Math">Math</option>
          <option value="Science">Science</option>
          <option value="English">English</option>
          <!-- Add more options dynamically as needed -->
        </select>
      </div>
      <div class="form-group">
        <label for="name">Instructor</label>
        <input type="text" id="name" name="name" placeholder="e.g., Ms Lorem">
      </div>

      <div class="form-group">
        <label for="subject">Day</label>
        <select id="subject" name="subject">
        </select>
      </div>

      <div class="form-group">
        <label for="appt">Time In</label>
        <input type="time" id="apptin" name="apptin">
      </div>

      <div class="form-group">
        <label for="appt">Time Out</label>
        <input type="time" id="apptout" name="apptout">
      </div>

      <button type="submit" class="submitBtn">Submit</button>
    </form>
  </div>

</body>
</html>