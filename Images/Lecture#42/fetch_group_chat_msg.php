<?php

    session_start();
    include "connection.php";

    $from_user_id = $_SESSION['userID'];
    $query = "SELECT * FROM chat_msg WHERE to_user_id = 0 ORDER BY timestamp ASC";
    $result = mysqli_query($con, $query);

    while($row = mysqli_fetch_assoc($result)){
        
        $userid = $row['from_user_id'];
        
        $query1 = "SELECT * FROM user WHERE ID = $userid";
        $result1 = mysqli_query($con, $query1);
        $row1 = mysqli_fetch_assoc($result1);

        if($row['from_user_id'] == $from_user_id && $row['to_user_id'] == 0)
            echo "<span class='text-success' style='float:right'>You: " . $row['chat_msg'] . "</span><br>";
        else if($row['to_user_id'] == 0)
            echo "<span class='text-danger'>" . $row1['Name'] . ': ' . $row['chat_msg'] . "</span><br>";
    }

?>