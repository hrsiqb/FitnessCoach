<br>

<?php

session_start();
if(isset($_SESSION['userEmail']))
{
    include 'connection.php';

    if(isset($_GET['pageLimit'])){
        $pageLimit = $_GET['pageLimit'];
        if(!$pageLimit)
            $pageLimit = 1;
    }
    else
        $pageLimit = 5;
    
    if(isset($_GET['search'])){
        $search = $_GET['search'];
        $query = "SELECT * FROM contacts WHERE (Name = '$search' || Email = '$search' || Phone = '$search' || Country = '$search')";
    }
    else{
        $query = "SELECT * FROM contacts";
    }
    
    $result = mysqli_query($con, $query);
    $counts = mysqli_num_rows($result);
    $totalPages = ceil($counts/$pageLimit);

    if(isset($_GET['page']))
        $page = $_GET['page'];
    else{
        $page = 1;
    }
    $startingPage = ($page - 1) * $pageLimit;
    if(isset($_GET['search'])){
        $search = $_GET['search' ];
        $query = "SELECT * FROM contacts WHERE (Name = '$search' || Email = '$search' || Phone = '$search' || Country = '$search') LIMIT " . $startingPage . ',' . $pageLimit;
    }
    else{
        $query = "SELECT * FROM contacts LIMIT " . $startingPage . ',' . $pageLimit;
    }
    $result = mysqli_query($con, $query);
?>


<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="Bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <script src="Bootstrap/js/jquery-3.5.1.min.js"></script>
    <script src="Bootstrap/js/jquery-3.5.1.slim.min.js"></script>
    <script src="Bootstrap/js/jquery-3.5.1.js"></script>
    <!--Bootstrap js link-->
    <script src="Bootstrap/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

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
    <br><br><br>

    <!--======================================== Dashboard =============================================-->
<span class="alert" id="successMsg" role="alert"></span>
<div class="container">
    <div class="d-flex justify-content-between">
        <button style="text-align: left;"type="button" class="btn btn-primary" data-toggle="modal" data-target="#mymodal">Add Record</button>
        <form class="form-inline" action="Dashboard.php" method="GET" style="margin-bottom: 0px;">
			<input type="text" name="search" id="search" placeholder="Search" class="form-control mr-2">
			<button class="btn btn-success">Search</button>
		</form>
        <button style="text-align: right;" type="button" class="btn btn-primary" onclick="window.open('Export.php','_blank')">Export Contact</button>
    </div>
    <br><br>
        <div style="overflow-x:auto">
            <table  style="text-align:center" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Country</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(mysqli_num_rows($result) > 0){

                            while($row = mysqli_fetch_assoc($result)){
                    ?>
                                    <tr>
                                        <td style='vertical-align: middle;' id="id"><?php echo $row["ID"] ?></td>
                                        <td style='vertical-align: middle;' id="nameUp<?php echo $row["ID"]?>"><?php echo $row["Name"] ?></td>
                                        <td style='vertical-align: middle;' id="emailUp<?php echo $row["ID"]?>"><?php echo $row["Email"] ?></td>
                                        <td style='vertical-align: middle;' id="phoneUp<?php echo $row["ID"]?>"><?php echo $row["Phone"] ?></td>
                                        <td style='vertical-align: middle;' id="countryUp<?php echo $row["ID"]?>"><?php echo $row["Country"] ?></td>
                                        <td>
                                            <button type="button" data-toggle="modal" data-target="#editModal" value="<?php echo $row["ID"]?>" id="editBtn" class="btn btn-primary editBtn">Edit</button>
                                            <button type="button" data-toggle="modal" data-target="#detailsModal" value="<?php echo $row["ID"]?>" id="detailsBtn" class="btn btn-primary detailsBtn">Detail</button>
                                            <button type="button" class="btn btn-danger deleteBtn" value="<?php echo $row["ID"]?>" id="deleteBtn">Delete</button>
                                        </td>
                                    </tr>
                    <?php
                            }
                        }
                        else
                            echo "<center><h1 class='alert alert-danger'>No record found</h1></center>";
                    ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between">
                <ul class="pagination">
                    <?php
                    if(isset($search)){
                        if($page > 1)
                            echo "<li class='page-item'><a class='page-link' href='Dashboard.php?page=" . ($page-1) . "&search=" . $search . "&pageLimit=" . $pageLimit . "'>Prev</a></li>";
            
                        for($i = 1; $i <= $totalPages; $i++){
                            if($i == $page)
                                echo "<li class='page-item active'><a class='page-link' href='Dashboard.php?page=" . $i . "&search=" . $search . "&pageLimit=" . $pageLimit . "'>" . $i . "</a></li>";    
                            else
                                echo "<li class='page-item'><a class='page-link' href='Dashboard.php?page=" . $i . "&search=" . $search . "&pageLimit=" . $pageLimit . "'>" . $i . "</a></li>";
                        }                                     
                        if($page < $totalPages)
                            echo "<li class='page-item'><a class='page-link' href='Dashboard.php?page=" . ($page+1) . "&search=" . $search . "&pageLimit=" . $pageLimit . "'>Next</a></li>";
                        }
                    else{
                        if($page > 1)
                            echo "<li class='page-item'><a class='page-link' href='Dashboard.php?page=" . ($page-1) . "&pageLimit=" . $pageLimit . "'>Prev</a></li>";
            
                        for($i = 1; $i <= $totalPages; $i++){
                            if($i == $page)
                                echo "<li class='page-item active'><a class='page-link' href='Dashboard.php?page=" . $i . "&pageLimit=" . $pageLimit . "'>" . $i . "</a></li>";    
                            else
                                echo "<li class='page-item'><a class='page-link' href='Dashboard.php?page=" . $i . "&pageLimit=" . $pageLimit . "'>" . $i . "</a></li>";
                        }                                     
                        if($page < $totalPages)
                            echo "<li class='page-item'><a class='page-link' href='Dashboard.php?page=" . ($page+1) . "&pageLimit=" . $pageLimit . "'>Next</a></li>";
                        }    
                    ?>
                </ul>
                <?php
                if(isset($search)){
                ?>
                    <form class="form-inline" action="Dashboard.php" method="GET" style="margin-bottom: 0px; text-align: left;">
                        <label><b style="margin-right: 15px">Records per page:</b></label>
                        <input type="hidden" name="search" id="search" value="<?php echo $search ?>">
                        <input type="text" name="pageLimit" size="1%" id="pageLimit" value="<?php echo $pageLimit ?>" class="form-control mr-2">
                        <button class="btn btn-success">Go</button>
                    </form>
                <?php
                }
                else{
                ?>
                    <form class="form-inline" action="Dashboard.php" method="GET" style="margin-bottom: 0px; text-align: left;">
                        <label><b style="margin-right: 15px">Records per page:</b></label>
                        <input type="text" name="pageLimit" size="1%" id="pageLimit" value="<?php echo $pageLimit ?>" class="form-control mr-2">
                        <button class="btn btn-success">Go</button>
                    </form>
                <?php
                }
                ?>
            </div>
        </div>
    <!-- =================================================ADD RECORD MODAL============================================================= -->
	<div class="modal fade" id="mymodal">
		<div class="modal-dialog modal-md modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Add new Record</h4>
				</div>
			    <div class="modal-body">
                 <form class="container" id="insertForm" style="margin-top: 1vw" method="post" action="">
					<div class="form-group">
  	                    <label>Name</label>
                        <input type="text" name="name" id="name" placeholder="enter name" class="form-control" />
                        <span style="color:red" id="nameErr"></span>
			        </div>
  			        <div class="form-group">
    				    <label>Email</label>
			            <input type="email" name="email" id="email" placeholder="enter email" class="form-control" />
                        <span style="color:red" id="emailErr"></span>
				    </div>
				    <div class="form-group">
   				        <label>Phone</label>
 				        <input type="text" name="phone" id="phone" placeholder="enter Phone" class="form-control" />
                         <span style="color:red" id="phoneErr"></span>
   				    </div>
 				    <div class="form-group">
    			        <label>Select Country</label>
      			        <select class="form-control" id="country" name="country">
   			                <option value="Pakistan">Pakistan</option>
  			                <option value="India">India</option>
       			            <option value="England">England</option>
      			            <option value="USA">USA</option>
      			        </select>
      			    </div>
    			    <button class="btn btn-primary" type="button" id="submitBtn">Submit</button>
      			    <button class="btn btn-basic" id="insertCancelBtn" data-dismiss="modal">Cancel</button>
                  </form>
  				</div>
			</div>
		</div>
	</div>

    <!-- =================================================EDIT MODAL============================================================= -->

	<div class="modal fade" id="editmodal">
		<div class="modal-dialog modal-md modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Edit Record</h4>
				</div>
			    <div class="modal-body">
                 <form class="container" id="editForm" style="margin-top: 1vw" method="post" action="">
					<input type="hidden" id="recordID" >
                    <div class="form-group">
  	                    <label>Name</label>
                        <input type="text" name="name" id="nameEdit" placeholder="enter name" class="form-control" />
                        <span style="color:red" id="nameErrEdit"></span>
			        </div>
  			        <div class="form-group">
    				    <label>Email</label>
			            <input type="email" name="email" id="emailEdit" placeholder="enter email" class="form-control" />
                        <span style="color:red" id="emailErrEdit"></span>
				    </div>
				    <div class="form-group">
   				        <label>Phone</label>
 				        <input type="text" name="phone" id="phoneEdit" placeholder="enter Phone" class="form-control" />
                         <span style="color:red" id="phoneErrEdit"></span>
   				    </div>
 				    <div class="form-group">
    			        <label>Select Country</label>
      			        <select class="form-control" id="countryEdit" name="country">
   			                <option value="Pakistan">Pakistan</option>
  			                <option value="India">India</option>
       			            <option value="England">England</option>
      			            <option value="USA">USA</option>
      			        </select>
      			    </div>
    			    <button class="btn btn-primary updateBtn" type="button" id="updateBtn">Update</button>
      			    <button class="btn btn-basic" id="editCancelBtn" data-dismiss="modal">Cancel</button>
                  </form>
  				</div>
			</div>
		</div>
	</div>

    <!-- =================================================DETAILS MODAL============================================================= -->

	<div class="modal fade" id="detailsModal">
		<div class="modal-dialog modal-md modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Record Details</h4>
				</div>
			    <div class="modal-body container">
  	                    <label>Record ID:</label>
                          <h6 id="idDetail"></h6>
  	                    <label>Name:</label>
                          <h6 id="nameDetail"></h6>
    				    <label>Email:</label>
                          <h6 id="emailDetail"></h6>
   				        <label>Phone:</label>
                          <h6 id="phoneDetail"></h6>
    			        <label>Country:</label>
                          <h6 id="countryDetail"></h6>
      			        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
			</div>
		</div>
	</div>
</div>

</body>
</html>

<?php

    }
    else
        header("Location: Login.php")

?>

<script type="text/javascript">
    $("#insertCancelBtn").click(function(){
        $("#insertForm")[0].reset();
        $("#nameErr").html("");
        $("#emailErr").html("");
        $("#phoneErr").html("");
    });
    $("#editCancelBtn").click(function(){
        $("#nameErrEdit").html("");
        $("#emailErrEdit").html("");
        $("#phoneErrEdit").html("");
    });
    $("#submitBtn").click(function(){
        $.ajax({
            url: "insert.php",
            method: "POST",
            data: $("#insertForm").serialize(),
            success: function(data){
                var array = $.parseJSON(data);
                if(array.status == "submitted"){
                    $("#insertForm")[0].reset();
                    $("#mymodal").modal('hide');
                    $("#nameErr").html("");
                    $("#emailErr").html("");
                    $("#phoneErr").html("");

                    setTimeout(function() {
                    $('#successMsg').fadeIn();
                    }); // <-- time in milliseconds

                    $("#successMsg").html("Record submitted successfully");
                    $("#successMsg").attr("class", "alert alert-success");
                    $("#ln").html("<br>");
                    $("#ln").insertAfter("#successMsg");

                    setTimeout(function() {
                    $('#successMsg').fadeOut('slow');
                    }, 3000); // <-- time in milliseconds

                    $("table tbody").append("<tr><td>"+ array.id +"</td><td id='nameUp" + array.id + "'>"+ array.name +"</td><td id='emailUp" + array.id + "'>"+ array.email +"</td><td id='phoneUp" + array.id + "'>"+ array.phone +"</td><td id='countryUp" + array.id + "'>"+ array.country +"</td><td>"
                     +"<button type='button' data-toggle='modal' data-target='#editModal' class='btn btn-primary editBtn mr-1' value="+ array.id +">Edit</button><button type='button' data-toggle='modal' data-target='#detailsModal' class='btn btn-primary detailsBtn mr-1' value="+ array.id +" id='detailsBtn'>Detail</button><button type='button' class='btn btn-danger deleteBtn' value="+ array.id +" id='deleteBtn'>Delete</button>"+ "</td></tr>");
                }
                else{
                    $("#nameErr").html(array.nameErr);
                    $("#emailErr").html(array.emailErr);
                    $("#phoneErr").html(array.phoneErr);
                    $("#successMsg").attr("class", "alert");
                    $("#successMsg").html("");
                }
            }
        });
    });
    $(document).on("click", "button.editBtn", function(){
        id = $(this).val();
        $.ajax({
            url: "update.php",
            method: "POST",
            data: {id
            },
            success: function(data){
                array = $.parseJSON(data);
                $("#nameEdit").val(array.name);
                $("#emailEdit").val(array.email);
                $("#phoneEdit").val(array.phone);
                $("#countryEdit").val(array.country);
                $("#recordID").val(array.id);
            }
        });
    });
    $(document).on("click","button.detailsBtn", function(){
        id = $(this).val();
        $.ajax({
            url: "update.php",
            method: "POST",
            data: {id
            },
            success: function(data){
                array = $.parseJSON(data);
                $("#idDetail").html(array.id);
                $("#nameDetail").html(array.name);
                $("#emailDetail").html(array.email);
                $("#phoneDetail").html(array.phone);
                $("#countryDetail").html(array.country);
            }
        });
    });
    $(document).on("click", "button.updateBtn", function(){
       name = $("#nameEdit").val();
       email = $("#emailEdit").val();
       phone = $("#phoneEdit").val();
       country = $("#countryEdit").val();
       status = "updateRecord";
       id = $("#recordID").val();
       $.ajax({
           url: "insert.php",
           method: "POST",
           data: {
               id: id,
               name: name,
               email: email,
               phone: phone,
               country: country,
               status: status
           },
           success: function(data){
                var array = $.parseJSON(data);
                if(array.status == "updated"){
                    $("#editForm")[0].reset();
                    $("#editmodal").modal('hide');
                    $("#nameErrEdit").html("");
                    $("#emailErrEdit").html("");
                    $("#phoneErrEdit").html("");

                    setTimeout(function() {
                    $('#successMsg').fadeIn('fast');
                    }, 1000); // <-- time in milliseconds
                    
                    $("#successMsg").html("Record updated successfully");
                    $("#successMsg").attr("class", "alert alert-success");
                    $("#ln").html("<br>");
                    $("#ln").insertAfter("#successMsg");

                    setTimeout(function() {
                    $('#successMsg').fadeOut('slow');
                    }, 3000); // <-- time in milliseconds

                    $("#nameUp" + array.id).html(array.name);
                    $("#emailUp" + array.id).html(array.email);
                    $("#phoneUp" + array.id).html(array.phone);
                    $("#countryUp" + array.id).html(array.country);
                }
                else{
                    $("#nameErrEdit").html(array.nameErr);
                    $("#emailErrEdit").html(array.emailErr);
                    $("#phoneErrEdit").html(array.phoneErr);
                    $("#successMsg").attr("class", "alert");
                    $("#successMsg").html("");
                }
           }
       });

    });

    $(document).on("click", "button.deleteBtn", function(){
        id = $(this).val();
        recordTr = $(this).closest('tr');
        $.ajax({
            url: "delete.php",
            method: "POST",
            data: {
                id: id
            },
            success: function(data){
                if(data == "deleted"){
                    recordTr.fadeOut(1000, function(){
                        $(this).remove();
                    })
                    setTimeout(function() {
                    $('#successMsg').fadeIn();
                    }); // <-- time in milliseconds

                    $("#successMsg").html("Record Deleted Successfully");
                    $("#successMsg").attr("class", "alert alert-success");
                    $("#ln").html("<br>");
                    $("#ln").insertAfter("#successMsg");

                    setTimeout(function() {
                    $('#successMsg').fadeOut('slow');
                    }, 3000); // <-- time in milliseconds
                }
                else{
                    setTimeout(function() {
                    $('#successMsg').fadeIn();
                    }); // <-- time in milliseconds

                    $("#successMsg").html("Error Deleting Record");
                    $("#successMsg").attr("class", "alert alert-danger");
                    $("#ln").html("<br>");
                    $("#ln").insertAfter("#successMsg");

                    setTimeout(function() {
                    $('#successMsg').fadeOut('slow');
                    }, 3000); // <-- time in milliseconds
                }
            }
        })

    })
</script>