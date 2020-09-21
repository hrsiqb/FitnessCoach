<?php
    session_start();
    include 'connection.php';
    $toUserId = $_POST['id'];
    $msg = $_POST['chat_msg'];
    $toUserName = $_POST['toUserName'];

    $fromUserId = $_SESSION['userID'];
    
    $query1 = "SELECT * FROM chat_msg WHERE from_user_id = $fromUserId && to_user_id = $toUserId || from_user_id = $toUserId && to_user_id = $fromUserId ORDER BY timestamp ASC";
    $result1 = mysqli_query($con, $query1);
    if($result1){
        while($rows = mysqli_fetch_assoc($result1)){
            if(($fromUserId == $rows['from_user_id']) && ($toUserId == $rows['to_user_id'])){
                echo "<span class='text-success' style='float:right'>You: " . $rows['chat_msg'] . "</span><br>";}
            else{
                echo "<span class='text-danger'>" . $toUserName . ": " . $rows['chat_msg'] . "</span><br>";}
        }
    }
    
    if(!empty($msg)){
        $query = "INSERT INTO chat_msg (to_user_id,from_user_id,chat_msg,status) VALUES ('$toUserId','$fromUserId','$msg',1)";
        $result = mysqli_query($con, $query);

        if($result)
            echo "<span class='text-success' style='float:right'>You: " . $msg . "</span><br>";
    }
?>