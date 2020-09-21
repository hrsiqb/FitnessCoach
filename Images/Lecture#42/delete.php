<?php
include 'connection.php';
if(isset($_POST["id"])){
    $id = $_POST["id"];
    $query = "DELETE FROM contacts WHERE ID=" . $id;
    $result = mysqli_query($con, $query);
    if($result){
        echo "deleted";
    }
    else{
        echo "error deleting";
    }
}
?>