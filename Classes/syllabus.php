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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/4aa19b73e3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../Styles/syllabus.css">
    <style type="text/css">
    h2{
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
    <?php  include "../Classes/sidebar.php" ?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-left: 20%!important padding: right 7% !important;">
        <hr>
        <div class="table-responsive">
          <!-- First table for claimed users -->
          <h2>Welcome to
            Syllabus
          </h2>
          <table class='table table-striped table-sm' style="
            background-color: rgba(255, 255, 255, 0.6) !important;
            border: 1px solid #dadce0 !important;
            border-radius: 8px!important;
           ">
            <thead>
            <tr>
                <th scope='col'>Subject</th>
                <th scope='col'>Instructor</th>
                <th scope='col'>Day</th>
                <th scope='col'>Time In</th>
                <th scope='col'>Time out</th>
                <th scope='col'>Modify</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>
                  <?php
                    echo "<a class='btn btn-outline-warning btn-sm' style='color: #1b651b;'>Update</a>
                          <a class='btn btn-outline-warning btn-sm' style='color: #1b651b;'>Delete</a>";
                  ?>
                  </td>
              </tr>
            </tbody>
          </table>
          <button type="submit" class="submitBtn">Add Syllabus</button>
</body>
</html>