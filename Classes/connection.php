<?php

$host = "localhost";
$user = "root";
$pass = "";
$student_info_db = "student_info_db";

$con = mysqli_connect($host, $user, $pass, $student_info_db);

if(mysqli_connect_errno()){
        echo "Failed to connect to the server: MYSQL".mysqli_connect_error();
}