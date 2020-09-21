<?php

    session_start();
    if(isset($_SESSION['userEmail'])){
        include 'connection.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="Bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <!-- <script src="Bootstrap/js/jquery-3.5.1.min.js"></script>
    <script src="Bootstrap/js/jquery-3.5.1.slim.min.js"></script> -->
    <script src="Bootstrap/js/jquery-3.5.1.js"></script>
    <!--Bootstrap js link-->
    <script src="Bootstrap/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
    <link href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
    
    <style type="text/css">
        @media only screen and (max-width: 992px) 
        {
            .editBtn,.detailsBtn
            {
                margin-bottom: 4px;
            }
        }
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none;  
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
        label {
            color: #0e59a5;
        }
    </style>
</head>
<body>
<div id="ln"></div>
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
                <li class="nav-item">
                    <a href="chat_bot.php" class="nav-link">Chat Bot</a>
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
                    <a href="Profile.php" class="nav-link"><img style="width: 30px; height:30px;border-radius: 50%;"src="Pictures/<?php echo $row["Image"];?>" alt="<?php echo $row["Image"];?>"> &nbsp;<?php echo $_SESSION['userEmail'] ?></a>
                </li>
                <li class="nav-item">
                    <a href="Logout.php" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <br><br><br><br><br>
    <div class="container">
        <button class="btn btn-primary" id="groupChat" style="float: right">Group Chat</button>
        <table class="table" id="tableRecord">
            <thead>
                <tr>
                    <td scope="col">Name</td>
                    <td scope="col">Status</td>
                    <td scope="col">Start Chat</td>
                </tr>
            </thead>
                <tbody id="table">

                </tbody>
        </table>
    </div>
    <div id="chatBot"></div>

    <div id="groupDialog" title="Chating in Group">
        <!-- <div class="typ" id="typ"></div>'; -->
        <div id="groupHist" style="height: 300px; border:1px solid #ccc; overflow-y: scroll; margin-bottom: 24px; padding:16px;"></div>
        <div class="form-group">
            <textarea id="group_message" class="form-control"></textarea>
        </div>
        <div class="form-group" align="right">
            <button type="button" class="btn btn-primary" id="sendGroupMsg">Send</button>
        </div>
    </div>


</body>
</html>
<?php
    }
?>

<script>
    var tableData = document.getElementById('table');
    $toUserName = "";
    $(document).ready(function(){
        $interval = setInterval(() => {  
            fetch_users();
            chat_msg();
            fetch_group_chat();
            typing("no");
        }, 2000);
        $uId = 0;
        function fetch_users(){
            $.ajax({
                url : "fetch_user.php",
                method : 'POST',
                success : function(data){
                    tableData.innerHTML = data;
                }
            })
        };
    });
    function openDialog(toUserId, toUserName){
        $uId++;
        var dialog = '<div id="userDialog' + toUserId + '" class="userDialog" title="Chating With ' + toUserName + '">';
        dialog += '<div class="typ" id="typ"></div>';
        dialog += '<div class="chatHist" id="chatHist" style="height: 300px; border:1px solid #ccc; overflow-y: scroll; margin-bottom: 24px; padding:16px;" data-touserid="' + toUserId + '"></div>';
        dialog += '<div class="form-group">';
        dialog += '<textarea id="chat_message_' + toUserId + '_' + $uId + '" onkeyup="typing(`yes`)" class="form-control"></textarea></div>';
        dialog += '<div class="form-group" align="right">';
        dialog += '<button type="button" class="btn btn-primary send_msg" id="' + toUserId + '" name="' + toUserName + '">Send</button></div></div>';
        $("#chatBot").html(dialog);
    }
    $(document).on('click', '.sendMsg', function(){
        $toUserId = $(this).data("id");
        $toUserName = $(this).data("tousername");
        $("#userDialog" + $toUserId).dialog({
            autoOpen: false,
            width: 400
        })
        openDialog($toUserId, $toUserName);
        $("#userDialog" + $toUserId).dialog(open);
        
        $chat_msg = "";
        $.ajax({
            url : "insert_msg.php",
            method : "POST",
            data : {
                id: $toUserId,
                chat_msg: $chat_msg,
                toUserName: $toUserName
            },
            success : function(data){
                $(".chatHist").html(data);
            }
        })
    })
    
    $(document).on('click', '.send_msg', function(){
        $id = $(this).attr('id');
        $toUserName = $(this).attr('name');
        $chat_msg = $("#chat_message_" + $id + "_" + $uId).val();
        $.ajax({
            url : "insert_msg.php",
            method : "POST",
            data : {
                id: $id,
                chat_msg: $chat_msg,
                toUserName: $toUserName
            },
            success : function(data){
                $('#chat_message_' + $id + '_' + $uId).val('');
                $(".chatHist").html(data);
            }
        })
    })
    function fetch_chat_msg(touserid){
        $.ajax({
            url: "fetch_chat_msg.php",
            method: "POST",
            data: {
                touserid: touserid,
                toUserName: $toUserName
            },
            success: function(data){
                array = $.parseJSON(data);
                $(".chatHist").html(array.hist);
                if(array.typ === "yes")
                    $("#typ").html("typing...");
                else
                    $("#typ").html("");
            }
        })
    }
    function chat_msg(){
        $(".chatHist").each(function(){
            $toUserId = $(this).data("touserid")
            fetch_chat_msg($toUserId);
         })
    }
    function typing($status){
        $.ajax({
        url: "is_type_status.php",
        method: "POST",
        data:{
            status: $status
        },
        success:function(data){
            // if($status === "yes"){  
            //     $("#typ").html(data)
            // }
        }
        })
    }
//     $('#userDialog' + $toUserId).live("dialoghide", function(){
//    //code to run on dialog close
//    alert('closed');
// });
function fetch_group_chat(){
    $.ajax({
        url: "fetch_group_chat_msg.php",
        method: "POST",
        success: function(data){
            $("#groupHist").html(data);
        }
    })
}
$("#groupDialog").dialog({
    autoOpen: false,
    width: 400
})
$("#groupChat").click(function(){
    $("#groupDialog").dialog("open");
    fetch_group_chat();
})
$("#sendGroupMsg").click(function(){
    var $msg = $("#group_message").val();
    $.ajax({
        url: "insert_group_message.php",
        method: "POST",
        data:{
            group_msg: $msg
        },
        success: function(data){
            $('#group_message').val('');
            fetch_group_chat();
        }
    })
})
</script>