<?php

$server_name = "localhost";
$user_name  = "root";
$password = "";
$database = "web_learners";
$con = mysqli_connect($server_name,$user_name,$password,$database);
if($con){
    // echo"connection successful <br>";
}
else 
{
    echo "<span class='alert alert-danger'>connection failed</span> <br>";
}
?>