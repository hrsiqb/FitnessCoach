<?php

session_start();

if(isset($_SESSION['userEmail'])){
    include 'connection.php';
    $id = $_SESSION['userID'];
    
    $query = "SELECT * FROM user WHERE ID != '" . $_SESSION['userID'] . "'";
    $result = mysqli_query($con, $query);
}
function userLastActivity($id, $con){
    $query1 = "SELECT * FROM login_details WHERE user_id = '$id' ORDER BY last_activity ASC LIMIT 1";
    $result1 = mysqli_query($con, $query1);
    while($row = mysqli_fetch_assoc($result1))
        return $row['last_activity'];
}
function is_typing($id, $con){
    $query3 = "SELECT * FROM login_details WHERE user_id = '$id' ORDER BY last_activity DESC LIMIT 1";
    $result3 = mysqli_query($con, $query3);
    while($row = mysqli_fetch_assoc($result3))
        return $row['is_type'];
}
$record ='';

while($row = mysqli_fetch_assoc($result)){
    $query2 = "SELECT * FROM chat_msg WHERE to_user_id ='" . $id . "' && from_user_id ='" . $row['ID'] . "' && status = 1";
    $result2 = mysqli_query($con, $query2);
    if(mysqli_num_rows($result2))
        $count = "&nbsp&nbsp<span class='badge badge-success'>" . mysqli_num_rows($result2) . "</span>";
    else
        $count = "";
    $currentTimestamp = strtotime(date("Y-m-d H:i:s") . '-5 second');
    $currentTime = date("Y-m-d H:i:s", $currentTimestamp);
    $lastActivity = userLastActivity($row['ID'], $con);
    // echo "current time: " . $currentTime . "<br>";
    // echo "last activity: " . $lastActivity;
    // exit;
    
    if(is_typing($row['ID'], $con) === 'yes')
        $typing = "typing...";
    else
        $typing = "";

    if($lastActivity > $currentTime){
        $status = "<span class='badge badge-success'>online</span>";
    }
    else{
        $status = "<span class='badge badge-danger'>offline</span>";
    }
    
    $record = ' <tr>
                <td>' . $row["Name"] . $count . "&nbsp&nbsp" . $typing . '</td>
                <td>' . $status .'</td>
                <td>
                    <button type="button" class="btn btn-primary sendMsg" data-id="' . $row['ID'] . '" data-tousername="' . $row['Name'] . '">Send Message</button>
                </td>
                </tr> ';
                echo $record;
}
?>