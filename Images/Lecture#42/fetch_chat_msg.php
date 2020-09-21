<?php
    session_start();
    include 'connection.php';
    $fromUserId = $_SESSION['userID'];
    $toUserId = $_POST['touserid'];
    $toUserName = $_POST['toUserName'];
    $array = array('hist' => '','typ' => '');

    $query = "SELECT * FROM chat_msg WHERE from_user_id = $fromUserId && to_user_id = $toUserId || from_user_id = $toUserId && to_user_id = $fromUserId ORDER BY timestamp ASC";
    $result = mysqli_query($con, $query);
    if($result){
        while($rows = mysqli_fetch_assoc($result)){
            if(($fromUserId == $rows['from_user_id']) && ($toUserId == $rows['to_user_id'])){
                $array['hist'] .= "<span class='text-success' style='float:right'>You: " . $rows['chat_msg'] . "</span><br>";
            }
            else{
                $array['hist'] .= "<span class='text-danger'>" . $toUserName . ": " . $rows['chat_msg'] . "</span><br>";
            }
        }
    }
    else 
        echo "Failed";

    $query1 = "UPDATE chat_msg SET status = 0 WHERE from_user_id = $toUserId && from_user_id = $toUserId && status = 1";
    $result1 = mysqli_query($con, $query1);
    
    $query2 = "SELECT * FROM login_details WHERE user_id = '$toUserId' ORDER BY last_activity DESC LIMIT 1";
    $result2 = mysqli_query($con, $query2);
    $row = mysqli_fetch_assoc($result2);
    $array['typ'] = $row['is_type'];
    echo json_encode($array);
?>