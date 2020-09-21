<?php
    session_start();
    include 'connection.php';

    $status = $_POST['status'];
    $loginId = $_SESSION['login_details_id'];
    
    $query = "UPDATE login_details SET is_type = '$status' WHERE user_details_id = $loginId";
    $result = mysqli_query($con, $query);

    if($result){
        // echo "typing...";
    }
?>