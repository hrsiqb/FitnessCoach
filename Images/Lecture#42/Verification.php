<?php 
    include 'Connection.php';

    $code = $_GET['code'];
    $email = $_GET['email'];
    
    $query = "SELECT * FROM user WHERE Email = '$email'";
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_array($result);
    $codeS = $rows['Code'];

    if($code == $codeS){
        $query1 = "UPDATE user SET Status=1 WHERE Email = '$email'";
        $result1 = mysqli_query($con, $query1);
        if($result1)
            echo "<span class='alert alert-success'>Account activated</span>";
        else
            echo "<span class='alert alert-danger'>Account activation failed</span>";
    }
    else
        echo "<span class='alert alert-danger'>Account activation failed</span>";
?>