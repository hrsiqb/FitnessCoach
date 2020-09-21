<br><br><br>

<?php

session_start();

if(isset($_SESSION['userEmail']))
    header("Location: Dashboard.php");
else
    {
include 'connection.php';

if(isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['country'])
                         && isset($_POST['pswd']) && isset($_FILES['file']['name']))
{
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$country = $_POST['country'];
$pswd = $_POST['pswd'];
$pswd_re_enter = $_POST['pswd-re-enter'];
$imagename = $_FILES['file']['name'];

if(empty($name))
        $nameerror = "*enter name";
 else
    {
        if(!preg_match("/^[a-zA-Z\s]*$/",$name))
            $nameerror =  "*invalid name";
    }   

if(empty($email))
    $emailerror = "*enter email";
else
{
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        $emailerror = "*invalid email";
}

if(empty($phone))
    $phoneerror = "*enter phone";
 else
 {
     if(!is_numeric($phone))
        $phoneerror = "*invalid phone";
 }

if(empty($country))
    $countryerror =  "*select country";

if(empty($imagename))
    $imageerror =  "*select Image";

$passwordLength = strlen($pswd);
if(empty($pswd))
    $pswderror = "*enter password";
 else
 {
    if(!preg_match("/[a-z]/",$pswd) || !preg_match("/[A-Z]/",$pswd) || (!preg_match("/[^\w]/",$pswd)) || !($passwordLength >= 6))
        $pswderror =  "*password must be atleast 6 characters long and contain lower case,upper case & special character";
 }

if(empty($pswd_re_enter))
        $repswderror = "*re enter password";
else
 {
    if(!preg_match("/[a-z]/",$pswd) || !preg_match("/[A-Z]/",$pswd) || (!preg_match("/[^\w]/",$pswd)) || !($passwordLength >= 6))
        $pswderror =  "*password must be atleast 6 characters long and contain lower case,upper case & special character";

    if($pswd != $pswd_re_enter)
        $repswderror =  "*Password does not match";

    
 }
    if(!(isset($nameerror) || isset($emailerror) || isset($phoneerror) || isset($countryerror) || isset($pswderror) || isset($repswderror) || isset($imageerror)))        
    {
        $code = md5(rand(0,1000));
        $image_location = "Pictures/" . $imagename;
        $result1 = move_uploaded_file($_FILES['file']['tmp_name'], $image_location);
        if(!$result1)
            $imageerror = "image upload failed";
        $extension = pathinfo($image_location, PATHINFO_EXTENSION);
        $size = $_FILES['file']['size'];
        if($size > 1000000000)
            $imageerror = "*size is too large <br>";
        if(!($extension == "JPG" || $extension == "PNG" || $extension == "GIF" || $extension == "jpg" || $extension == "png" || $extension == "gif"))
            $imageerror = "*file type is not correct <br>";
        
        if(!(isset($imageerror)))
        {
            $query = "SELECT * FROM user WHERE Email = '$email'";
            $result1 = mysqli_query($con, $query);
            if($result1->num_rows)
            {   
                $emailerror = "*email already exists";
            }
            else 
            {   
                $result =  mysqli_query($con, "INSERT INTO user (Name, Email, Phone, Country, Image, Password, Code) VALUES ('$name','$email','$phone','$country','$imagename','$pswd','$code')");
            }      
        }
    }
    if(!isset($result))
        $recordErr = "Error inserting record <br>";
}
}
?> 

<!DOCTYPE html>
<html>
<head>    
    <title></title>
    <link href="Bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <script src="Bootstrap/js/jquery-3.1.1.min.js"></script>
    <!--Bootstrap js link-->
    <script src="js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <style type="text/css">
        h1 {
            color: #0e59a5;
            text-align: center;
        }

        label {
            color: #0e59a5;
        }
    </style>
</head>
<body>
    <!--========================================Navigation Bar=============================================-->
      
    <!--fixed-top class is used to fix the navigation bar to the top while scrolling(it is necessay to use margin-top(in the container on line 52) with this class)-->
    <nav class="navbar navbar-expand-md bg-primary navbar-dark  fixed-top">
        <a href="#" class="navbar-brand">CONTACT BOOK</a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navcollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navcollapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="#" class="nav-link">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Contact us</a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item">Via Email</a>
                        <a href="#" class="dropdown-item">Via Facebook</a>
                        <a href="#" class="dropdown-item">Via Whatsapp</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">About</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="Login.php" class="nav-link">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <!--========================================Login Form=============================================-->
    
    <?php 
    
if(isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['country'])
&& isset($_POST['pswd']) && isset($_FILES['file']['name']))
{
        if(isset($recordErr))
            {echo "<span class='alert alert-danger' style='margin:1px 100px 1px 100px;'>"; echo $recordErr; echo "</span>";}
        else
            echo "<span class='alert alert-success' style='margin:1px 100px 1px 100px;'>Record Inserted Successfully</span>";
}
        ?>
    <form class="container" style="margin-top:3vw"action=""method="POST" enctype="multipart/form-data" >
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <h1>Register</h1>
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" placeholder="enter name" class="form-control" />
                        <?php if(isset($nameerror))
                                {echo "<span style='color:red'>";echo $nameerror; echo "</span>";}?>
                    </div>

                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" placeholder="enter email" class="form-control" />
                        <?php if(isset($emailerror))
                                {echo "<span style='color:red'>";echo $emailerror; echo "</span>";}?>
                    </div>

                    <div class="form-group">
                        <label>Phone:</label>
                        <input type="text" name="phone" placeholder="enter Phone" class="form-control" />
                        <?php if(isset($phoneerror))
                                {echo "<span style='color:red'>";echo $phoneerror; echo "</span>";}?>
                    </div>

                    <div class="form-group">
                        <label>Select Country:</label>
                        <select class="form-control" name="country">
                            <option value="Pakistan">Pakistan</option>
                            <option value="India">India</option>
                            <option value="England">England</option>
                            <option value="USA">USA</option>
                        <?php if(isset($countryerror))
                                {echo "<span style='color:red'>";echo $countryerror; echo "</span>";}?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="file" name="file" />
                        <?php if(isset($imageerror))
                                {echo "<span style='color:red'>";echo $imageerror; echo "</span>";}?>
                    </div>

                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="pswd" placeholder="enter Password" class="form-control" />
                        <?php if(isset($pswderror))
                                {echo "<span style='color:red'>";echo $pswderror; echo "</span>";}?>
                    </div>

                    <div class="form-group">
                        <label>Re-Enter Password:</label>
                        <input type="password" name="pswd-re-enter" placeholder="enter Password" class="form-control" />
                        <?php if(isset($repswderror))
                                {echo "<span style='color:red'>";echo $repswderror; echo "</span>";}?>
                    </div>
                    <button class="btn btn-primary">Submit</button>
                    <button class="btn btn-basic">Cancel</button>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </form>

</body>
</html>