<?php
    
include 'connection.php';

    if(isset($_POST["id"])){
        $id = $_POST["id"];
        $query = "SELECT * FROM contacts WHERE ID=" . $id;
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $name =  $row["Name"];
        $email =  $row["Email"];
        $phone =  $row["Phone"];
        $country =  $row["Country"];
        $array = array('id' => $id,'name' => $name,'email' => $email,'phone' => $phone,'country' => $country);
        echo json_encode($array);
    }
?>