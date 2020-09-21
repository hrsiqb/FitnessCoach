<?php
    session_start();
    include "connection.php";
    $from_user_id = $_SESSION['userID'];
    $groupMsg = $_POST['group_msg'];
    $query = "INSERT INTO chat_msg (to_user_id, from_user_id, chat_msg) VALUES(0,'$from_user_id','$groupMsg')";
    $result = mysqli_query($con, $query);
    
    if($result)
        echo "<span class='text-success' style='float:right'>You: " . $groupMsg . "</span><br>";

?>