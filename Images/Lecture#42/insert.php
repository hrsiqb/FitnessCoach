<?php

include 'connection.php';

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['country'])){
    $namebool = true;
    $emailbool = true;
    $phonebool = true;

    $nameError = "";
    $emailError = "";
    $phoneError = "";

    if(empty($_POST['name'])){
        $nameError = "*enter name";
        $namebool=false;
    }
    else{
        if(!preg_match("/^[a-zA-Z\s]*$/",$_POST['name'])){
               
            $nameError = "*enter alphabets only";
                $namebool=false;
            }
        else{
            $namebool=true;
        }
    }
    if(empty($_POST['email'])){
        
        $emailError = "*enter email";
        $emailbool=false;
    }
    else{
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                
            $emailError = "*invalid email";
                $emailbool=false;
            }
        else{
            $emailbool=true;
        }
    }
    if(empty($_POST['phone'])){
        
        $phoneError = "*enter phone";
        $phonebool=false;
    }
    else{
        if(!is_numeric($_POST['phone'])){
                
            $phoneError = "*enter numbers only";
                $phonebool=false;
            }
        else{
            $phonebool=true;
        }
    }
    $status = "";
    if($namebool == true && $emailbool == true && $phonebool == true){
        
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $country = $_POST['country'];

        
        if(isset($_POST["status"])){
            $id = $_POST["id"];
            $query = "UPDATE contacts SET Name = '$name', Email = '$email', Phone = '$phone', Country = '$country' WHERE ID=" .$id;
            $result = mysqli_query($con, $query);
            $status = "updated";
            $array = array('id' => $id, 'name' => $name, 'email' => $email, 'phone' => $phone, 'country' => $country,'status' => $status);
            echo json_encode($array);
        }
        else{
            $query = "INSERT INTO contacts (Name, Email, Phone, Country) VALUES ('$name','$email','$phone','$country')";
            $result = mysqli_query($con, $query);
            $id = mysqli_insert_id($con);
            $status = "submitted";
            $array = array('id' => $id, 'name' => $name, 'email' => $email, 'phone' => $phone, 'country' => $country,'status' => $status);
            echo json_encode($array);
        }
    }
    else{
        $status = "error";
        $array = array('nameErr' => $nameError,'emailErr' => $emailError,'phoneErr' => $phoneError,'status' => $status);
        echo json_encode($array);
    }
}

?>