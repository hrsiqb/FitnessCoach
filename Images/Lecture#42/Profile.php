<br><br><br><br>

<?php

session_start();

if(isset($_SESSION['userEmail'])){
    include 'connection.php';
    $email = $_SESSION['userEmail'];

    $query = "SELECT * FROM user WHERE Email = '$email'";
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_array($result);
    $msg = "";
    $message = "";
    if(isset($_GET['active'])){
        $code = $rows['Code'];
        $msg = "<br><br>Verification email has been sent to $email<br>";
        $message = '
        <p style="border:1px solid black; padding: 5px">
        <b>The email sent to you is:</b><br>
        Thank you for contacting us <br>
        if you want to activate your account<br>
        please <a href="http://localhost/Lecture%2336/Verification.php?code=' . $code . '&email=' . $email . '">Click Here</a>
        </p>';
        $headers = "From: sender\'s email";
        mail($email, "Subject", $message, $headers);
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
        <a href="Dashboard.php" class="navbar-brand">CONTACT BOOK</a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navcollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navcollapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="Dashboard.php" class="nav-link">Home</a>
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
                    <?php
                        $query1 = "SELECT * FROM user WHERE ID=" .$_SESSION['userID'];
                        $result1 = mysqli_query($con, $query1);
                        if(mysqli_num_rows($result1) > 0)
                            $row = mysqli_fetch_assoc($result1);
                    ?>
                    <a href="#" class="nav-link"><img style="width: 30px; height:30px;border-radius: 50%;"src="Pictures/<?php echo $row["Image"];?>" alt="img"> &nbsp;<?php echo $_SESSION['userEmail'] ?></a>
                </li>
                <li class="nav-item">
                    <a href="Logout.php" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <br><br><br><br><br>
    <div class="container">
        <?php
            if($rows['Status'] == 0){
                echo "<span class='alert alert-danger'>Your account is not activated</span>";
                if(!isset($_GET['active']))
                    echo "<br><br>If you want to activate your account please <a href='Profile.php?active=activated'>click here</a>";
                echo $msg;
                echo $message;
            }
            else
                echo "<span class='alert alert-success'>Your account is activated</span>";
        ?>
    </div>
</body>
</html>
