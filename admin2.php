<?php 
session_start();
include("db_connect.php");

if(isset($_COOKIE['adminid'])&&$_COOKIE['adminemail']){
	
	$userid=$_COOKIE['adminid'];
$useremail=$_COOKIE['adminemail'];

$sqluser ="SELECT * FROM Administrator WHERE Password='$userid' && Email='$useremail'";

$retrieved = mysqli_query($db,$sqluser);
    while($found = mysqli_fetch_array($retrieved))
	     {
              $firstname = $found['Firstname'];
		      $Surname= $found['Surname'];
			  $emails = $found['Email'];
			  	   $id= $found['id'];			  
   
  	     
}		 
		 
}else{
	 header('location:index.php');
      exit;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Staff Record List</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Glance Design Dashboard Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

<!-- Bootstrap Core CSS -->
<link href="admin/css/bootstrap.css" rel='stylesheet' type='text/css' />

<!-- Custom CSS -->
<link href="admin/css/style.css" rel='stylesheet' type='text/css' />

<!-- font-awesome icons CSS -->
<link href="admin/css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons CSS-->

<!-- side nav css file -->
<link href='admin/css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
<!-- //side nav css file -->
 
 <!-- js-->
<script src="admin/js/jquery-1.11.1.min.js"></script>
<script src="admin/js/modernizr.custom.js"></script>

<!--webfonts-->
<link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
<!--//webfonts--> 

<!-- chart -->
<script src="admin/js/Chart.js"></script>
<!-- //chart -->

<!-- Metis Menu -->
<script src="admin/js/metisMenu.min.js"></script>
<script src="admin/js/custom.js"></script>
<link href="admin/css/custom.css" rel="stylesheet">
<!--//Metis Menu -->
 <script src="script/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="script/sweetalert.css">
 
 <!-- <script src="jquery.js"></script> -->    
<link href="css/animate.min.css" rel="stylesheet"/>   
      <link rel="stylesheet" href="css/bootstrap-dropdownhover.css">

   
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"/>      

   
   <script src='https://code.jquery.com/jquery-1.12.4.js'></script>
   <script src='https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js'></script>
   <script src='https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js'></script>
   <script src='https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js'></script>
   <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js'></script>
   <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js'></script>
   <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js'></script>
   <script src='https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js'></script>
   <script src='https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js'></script>

      <script>
      
      $(document).ready(function() {
    $('#example').DataTable( {
        
        
    } );
        } );
      
      </script>
<script type="text/javascript"> 
            $(document).on("click", ".open-Delete", function () {
                                  var myValue = $(this).data('id');
                                        swal({
                                         title: "Are you sure?",
                                         text: "You want to remove this record from the database!",
                                         type: "warning",
                                         showCancelButton: true,
                                        cancelButtonColor: "red",
                                        confirmButtonColor: "green",
                                        confirmButtonText: "Yes, remove!",
                                         cancelButtonText: "No, cancel!",
                                        closeOnConfirm: false,
                                        closeOnCancel: false,
                                          buttonsStyling: false
                                        },
                     function(isConfirm){
                                      if (isConfirm) {                                      	
                                                  	var vals=myValue;
                                               $.ajax ({
                                                      type : 'POST',
                                                      url: "upload2.php",
                                                      data: { Valuedel: vals},
                                                      success: function(result) {
                                                      if(result=="ok"){
                                                                    swal({title: "Deleted!", text: "Record has been deleted from the database.", type: "success"},
                                                          function(){ 
                                                                          location.reload();
                                                                          }
                                                                      );                               	                        
                                                                 }

                                                       }
                                                  }); } else {
	                                                           swal("Cancelled", "This user is safe :)", "error");
                                                          }
                                         });
                                       
                                       });
                </script>

<script type="text/javascript">
 $(document).on("click", ".open-Updatepicture", function () {
     var myTitle = $(this).data('id');
     $(".modal-body #bookId").val(myTitle);
     
}); 
 </script>
 	<!-- requried-jsfiles-for owl -->
									<!-- //requried-jsfiles-for owl -->
</head> 
<script type="text/javascript">
 $(document).on("click", ".open-Passwords", function () {
     
       var myT = $(this).data('id');
     var myTitle = $(this).data('ie');
       var myp = $(this).data('if');
       var mym = $(this).data('ig');
       var myn = $(this).data('ih');
       var myk = $(this).data('ij');
       var mykm = $(this).data('ik');
       
       
     $(".modal-title #oldname").val(myTitle);
       $(".modal-body #oldname").val(myTitle);
       $(".modal-body #oldpass").val(mykm);
     $(".modal-body #ss").val(myp);     
     $(".modal-body #bb").val(mym);
     $(".modal-body #cc").val(myn);
     $(".modal-body #dd").val(myk);
      $(".modal-body #staffid").val(myT); 
}); 
 </script>
<?php if(isset($_SESSION['memberadded'])){?>
 <script type="text/javascript"> 
 	          $(document).ready(function(){
 	          	                             swal({title: "Successful!", text: "Record added successfully!!.", type: "success"});
                                  });
              </script>
            
           <?php 
	   session_destroy();		
		    }?>
		    <?php if(isset($_SESSION['memberexist'])){?>
                <script type="text/javascript"> 
            $(document).ready(function(){    	
    				              sweetAlert("Oops...", "There is already a record with those details in the database", "error");     				              
                               });
                </script>
           <?php 
       	   session_destroy();}  
           ?>
            <?php if(isset($_SESSION['emptytextboxes'])){?>
                <script type="text/javascript"> 
            $(document).ready(function(){    	
    				              sweetAlert("Oops...", "You did not fill all the textboxes on the form", "error");     				              
                               });
                </script>
           <?php 
       	   session_destroy();}  
           ?>
           <?php if(isset($_SESSION['tutor'])){?>
                <script type="text/javascript"> 
            $(document).ready(function(){ 
                                    swal({
                                         title: "Record removed successfully",
                                         text: "Do you want to remove another one?",
                                         type: "success",
                                         showCancelButton: true,
                                        confirmButtonColor: "green",
                                        confirmButtonText: "OK!",
                                        closeOnConfirm: true,
                                        closeOnCancel: true,
                                          buttonsStyling: false
                                        },
                     function(isConfirm){
                                      if (isConfirm) {                                      	
                                                         window.location ="administrator.php?id=2";
                                                     } 
                                           else {
                                                        window.location ="administrator.php";
                                                 }
                                         });
                                         
                                                    });
                </script>
           <?php 
       	   session_destroy();}  
           ?>
           <?php if(isset($_SESSION['cat'])){?>
                <script type="text/javascript"> 
            $(document).ready(function(){    	
    				              sweetAlert("Oops...", "This category arleady in the system", "error");     				              
                               });
                </script>
           <?php 
       	   session_destroy();}  
           ?>
           <?php if(isset($_SESSION['category'])){?>
                <script type="text/javascript"> 
            $(document).ready(function(){ 
                                    swal({
                                         title: "Category added successfully",
                                         text: "Do you want to add another one?",
                                         type: "success",
                                         showCancelButton: true,
                                        confirmButtonColor: "green",
                                        confirmButtonText: "OK!",
                                        closeOnConfirm: true,
                                        closeOnCancel: true,
                                          buttonsStyling: false
                                        },
                     function(isConfirm){
                                      if (isConfirm) {                                      	
                                                         window.location ="administrator.php?id=3";
                                                     } 
                                           else {
                                                        window.location ="administrator.php";
                                                 }
                                         });
                                         
                                                    });
       
                    </script>             
           <?php 
       	   session_destroy();}  
           ?>
           <?php if(isset($_SESSION['del'])){?>
                <script type="text/javascript"> 
            $(document).ready(function(){ 
                                    swal({
                                         title: "Category Deleted",
                                         text: "Do you want to delete another one?",
                                         type: "success",
                                         showCancelButton: true,
                                        confirmButtonColor: "green",
                                        confirmButtonText: "OK!",
                                        closeOnConfirm: true,
                                        closeOnCancel: true,
                                          buttonsStyling: false
                                        },
                     function(isConfirm){
                                      if (isConfirm) {                                      	
                                                         window.location ="administrator.php?id=4";
                                                     } 
                                           else {
                                                        window.location ="administrator.php";
                                                 }
                                         });
                                         
                                                    });
       
                </script>
           <?php 
       	   session_destroy();}  
           ?>
 


 
 <?php if(isset($_SESSION['pass'])) {?>
<script type="text/javascript"> 

$(document).ready(function(){  
 		                           swal({title: "Successful!", text: "Record details edited!!.", type: "success"});

                               });
       
                    </script>
      <?php  session_destroy(); }?>
      
      
      <?php   $sqlid ="SELECT * FROM users_staff Order BY id DESC";
			       $ret = mysqli_query($db,$sqlid);				                
                     while($found = mysqli_fetch_array($ret))
	                 {
                       $idsx=$found['id'];
                     }
      
	  
	    

$sqluse ="SELECT * FROM Inorg ORDER BY id DESC ";
$retrieve = mysqli_query($db,$sqluse);
    while($foundk = mysqli_fetch_array($retrieve))
	     {
              $name = $foundk['name'];
		      $website= $foundk['website'];
              $phone= $foundk['Phone'];
              $year= $foundk['year'];
			  $mail= $foundk['email'];
			  $idz= $foundk['id'];
		 }	 

  ?>

 <div id="Staffbulkprinting" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="font-size: 14px; font-family: Times New Roman;color:black;">
      <div class="modal-header" style="background:#222d32">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="font-weight: bold;color: #F0F0F0"><center>
        	 PRINT IDs IN BULK
        	</center></h4>
      </div>

      <div class="modal-body" >       	
      	     <form action="print_card_bulk_staff.php" method="post" target="_blank">
  <div class="input-group" style="margin-bottom:10px">
    <span class="input-group-addon">From</span>
    <input id="text" type="number" class="form-control" value="1" name="startpoint" >
  </div>
  <div class="input-group" style="margin-bottom:10px">
    <span class="input-group-addon">QUANTITY</span>
	 <select class="form-control" name="endpoint">
	   <option>ALL</option>
	   <option>5</option>
	   <option>10</option>
	   <option>20</option>
	   <option>30</option>
	   <option>40</option>
	   <option>50</option>
	   <option>60</option>
	   <option>70</option>
	   <option>80</option>
	   <option>90</option>
	   <option>100</option>
   </select> 
  </div>
  <div class="input-group" style="margin-bottom:10px">
    <span class="input-group-addon">Department</span>
   <select class="form-control" name="dept">
	   <?php
	   	$departments="SELECT distinct(dept) FROM facultiesdept ORDER BY dept";
	   $retrec = mysqli_query($db,$departments);
	   	while($dept=mysqli_fetch_array($retrec)){
			echo '<option value="'.$dept[0].'">'.$dept[0].'</option>';
		}
	   ?>
   </select>
  </div>
<!--
    <div class="input-group" style="margin-bottom:10px">
    <span class="input-group-addon">Position</span>
   <select class="form-control" name="position">
	   <?php
	   	$departments2="SELECT distinct(Position) FROM users_staff";
	   $retrec2 = mysqli_query($db,$departments2);
	   	while($dept2=mysqli_fetch_array($retrec2)){
			echo '<option value="'.$dept2[0].'">'.$dept2[0].'</option>';
		}
	   ?>
   </select>
  </div>
-->
      </div>
      <div class="modal-footer">
      	<input type="submit" class="btn btn-success" value="Submit" id="btns1" name="Change"> &nbsp;
      </div>
      </form> 
      </div>       
  </div>
  </div> 
 


<div id="Passwords" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="font-size: 14px; font-family: Times New Roman;color:black;">
      <div class="modal-header" style="background:#222d32">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="font-family: Times New Roman;color:#F0F0F0;"><center>
                   Edit details of <input style="border: none;background:#222d32" type="text" id="oldname" value="" readonly="readonly" />
	    	
        	</center></h4>
      </div>
      <div class="modal-body" >
        <center>
             
        	<form method="post" action="upload2.php" enctype='multipart/form-data'>        		
            
        	      <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;Firstname:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="mfname" id='oldname'></span></p>
        	    <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;&nbsp; &nbsp;Surname:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="msname" id='ss'></span></p>
        		<p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">Department:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="minstitution"  id='cc'></span></p>
        	     <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Position:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="mrank" id='dd'></span></p>
        	     <p ><span style="font-size: 18px; font-weight: bold;">&nbsp;&nbsp; &nbsp;&nbsp;Staff ID:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="mid" id='oldpass'></span></p>
        		Add profile picture:<input name='filed' type='file' id='filed' >
                <input type="hidden" name="page" id="staffid"/>                                                       	      		
           
        </center>
        
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Reset" id="amendreceipt" name="resetpass"> &nbsp;
        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
      </div>
      </div>
       </form>
  </div>
  </div>
<div id="Updatepicture" class="modal fade" role="dialog">
  <div class="modal-dialog" style="float:right;width:20%">
    <!-- Modal content-->
    <div class="modal-content" style="font-size: 14px; font-family: Times New Roman;color:black;">
      <div class="modal-header" style="background:#222d32">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">        	        	
        	</h4>
      </div>
      <div class="modal-body" >
        <center><p></p>
        	<form method="post" action="upload2.php" enctype='multipart/form-data'>        		
            
        	<p style="margin-bottom:10px;">
        			Select picture<input name='file2' type='file' id='file2' >
           </p>  
           <p>
        	<input name='id' type='hidden' value='' id='bookId'>
        	<input name='category' type='hidden' value='Administrator'>
        	 <input type="hidden" name="page" value="users.php"/>                                                        	      		

           </p>     	      		
	                
        </center>
      </div>
      <div class="modal-footer">
                <input type="submit" class="btn btn-success" value="Change" id="btns1" name="Change"> &nbsp;
                  
      </div>
      </div>
       </form>
  </div>
  </div>
 
 <div id="Useradd" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="font-size: 14px; font-family: Times New Roman;color:black;">
      <div class="modal-header" style="background:#222d32">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="font-weight: bold;color: #F0F0F0"><center>
        	ADD RECORD DETAILS
        	</center></h4>
      </div>

      <div class="modal-body" >       	
      	<center> 
        		<form method="post" action="upload2.php" enctype='multipart/form-data' style="width: 98%;">        		

            	
      	        <p style="margin-bottom:10px;">  
        	      <span style="font-size: 15px; font-weight: bold;"><input type="checkbox" name="prof.">&nbsp;Prof.&nbsp;&nbsp; &nbsp; &nbsp;</span>
        	    <span style="font-size: 15px; font-weight: bold;"><input type="checkbox" name="dr">&nbsp;Dr &nbsp; &nbsp;&nbsp;&nbsp;</span>
        		<span style="font-size: 15px; font-weight: bold;"><input type="checkbox" name="mr">&nbsp;Mr &nbsp; &nbsp; &nbsp;&nbsp;</span>        		
        		<span style="font-size: 15px; font-weight: bold;"><input type="checkbox" name="mrs">&nbsp;Mrs &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span>
        		<span style="font-size: 15px; font-weight: bold;"><input type="checkbox" name="miss">&nbsp;Miss</span>
        		</p>
        		                                                           	      		
                 <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;Firstname:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="mfname"></span></p>
        	    <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;&nbsp; &nbsp;Surname:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="msname"></span></p>
        		<p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">Department:<label style="color: red;font-size:20px;">*</label>
					<select name="minstitution" style="width: 270px;">
					   <?php
						$departments1="SELECT distinct(dept) FROM facultiesdept ORDER BY dept";
					   $retrec1 = mysqli_query($db,$departments1);
						while($dept1=mysqli_fetch_array($retrec1)){
							echo '<option value="'.$dept1[0].'">'.$dept1[0].'</option>';
						}
					   ?>
				   </select>
					</span></p>
        	     <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Position:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="position"></span></p>
        	     <p ><span style="font-size: 18px; font-weight: bold;">&nbsp;&nbsp; &nbsp;&nbsp;Staff ID:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="staffid"></span></p>
        		Add profile picture:<input name='filed' type='file' id='filed' >
                    
        		   <input type="hidden" name="page" value="admin2.php"/>                                                        	      		
         </center>
      </div>
      <div class="modal-footer">
       <input type="submit" class="btn btn-success" value="Submit" id="addmember" name="addmember"> &nbsp;
        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
      </div>
      </div>
       </form>
  </div>
  </div> 
  
  <div id="Initialisation" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="font-size: 14px; font-family: Times New Roman;color:black;">
      <div class="modal-header" style="background:#222d32">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="font-weight: bold;color: #F0F0F0"><center>
        	SYSTEM INFORMATION INITIALISATION
        	</center></h4>
      </div>
      	<form method="post" action="upload2.php" enctype='multipart/form-data'>        		

      <div class="modal-body" >       	
      	<center> 
        		<p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp;&nbsp;Org Name:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="orgname"></span></p>
        	    <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;Phone:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="orgphone"></span></p>
        		<p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;Email:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="orgemail"></span></p>
        	     <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;&nbsp;&nbsp;Website:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="orgwebsite"></span></p>
        	     <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">Active Year:<label style="color: red;font-size:20px;">*</label><input style="width:270px;" type="text" name="orgyear"></span></p>
        	        Attach Organisation Logo:(<h7 style="color:red">Make sure it is a transparent image</h7>)<input name='filed' type='file' id='filed' >
                                   	 <input type="hidden" name="page" value="admin2.php"/>                                                        	      		
         </center>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Finish" id="addmember" name="orginitial"> &nbsp;
        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
      </div>
      </div>
       </form>
  </div>
  </div>
  
 <div id="Initialisation2" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style="font-size: 14px; font-family: Times New Roman;color:black;">
      <div class="modal-header" style="background:#222d32">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="font-weight: bold;color: #F0F0F0"><center>
        	EDIT SYSTEM INFORMATION
        	</center></h4>
      </div>
      	<form method="post" action="upload2.php" enctype='multipart/form-data'>        		

      <div class="modal-body" >       	
      	<center> 
        		<p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp;&nbsp;Org Name:<label style="color: red;font-size:20px;">*</label>
        			<input style="width:270px;" type="text" name="orgname" value="<?php if(isset($name)){echo$name;} ?>"></span></p>
        	    <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;Phone:<label style="color: red;font-size:20px;">*</label>
        	    	<input style="width:270px;" type="text" name="orgphone" value="<?php if(isset($phone)){echo$phone;} ?>"></span></p>
        		<p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;Email:<label style="color: red;font-size:20px;">*</label>
        			<input style="width:270px;" type="text" name="orgemail" value="<?php if(isset($mail)){echo$mail;} ?>"></span></p>
        	     <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">&nbsp; &nbsp;&nbsp;&nbsp;Website:<label style="color: red;font-size:20px;">*</label>
        	     	<input style="width:270px;" type="text" name="orgwebsite" value="<?php if(isset($website)){echo$website;} ?>"></span></p>
        	     <p style="margin-bottom:10px;"><span style="font-size: 18px; font-weight: bold;">Active Year:<label style="color: red;font-size:20px;">*</label>
        	     	<input style="width:270px;" type="text" name="orgyear" value="<?php if(isset($year)){echo$year;} ?>"></span></p>
        	                         	   Attach Organisation Logo:(<h7 style="color:red">Make sure it is a transparent image</h7>)<input name='filed' type='file' id='filed' >
                                          	      	 <input type="hidden" name="page" value="admin2.php"/>                                                        	      		
                          	      	<input type="hidden" name="pageid" value="<?php echo$idz; ?>"/> 	
      	
         </center>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Update" id="addmember" name="orgupdate"> &nbsp;
        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
      </div>
      </div>
       </form>
  </div>
  </div> 


<body class="cbp-spmenu-push">
	<div class="main-content">
	<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
		<!--left-fixed -navigation-->
		<aside class="sidebar-left">
      <nav class="navbar navbar-inverse">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
                           

<?php   
				$sqln ="SELECT * FROM Inorg ";
                     $rgetb = mysqli_query($db,$sqln);
	                   $numb=mysqli_num_rows($rgetb);
                   if($numb!=0){
                            while($foundl = mysqli_fetch_array($rgetb))
	                                     {
                                              $profile= $foundl['pname'];
		                                  }
										echo"<center><img src='media/$profile'  width='70%' height='140px' alt=''></center>";	   
                               }
							else{
														     	
								
										
           ?>
            <h1>
            	<a class="navbar-brand" href="index.html"><span class="fa fa-area-chart">
            		
            	</span>MAIN MENU<span class="dashboard_text"></span>
            	</a>
           </h1>
           <?php } ?> 

          </div>
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="sidebar-menu">
              <li class="header">
              	 <h4>Administrator</h4>
              </li>
              <!--<li class="treeview">
                <a href="admin2.php">
                <i class="fa fa-tv"></i> <span>Control Panel</span>
                </a>
              </li>
				<li class="treeview">
                <a href="#">
                <i class="fa fa-cog"></i>
                <span>Initialisation</span>
                <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a data-toggle='modal' data-id='' href='#Initialisation' class='open-Initial'><i class="fa fa-plus"></i>Add System Info</a></li>
                  <li><a data-toggle='modal' data-id='' href='#Initialisation2' class='open-Initial2'><i class="fa fa-minus"></i>Edit System Info</a></li>
                </ul>
              </li>-->
               <li class="treeview">
                <a href="#">
                <i class="fa fa-user"></i>
                <span>Record Category</span>
                <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                  <li><a href="admin.php"><i class="fa fa-user"></i>Students' Record</a></li>
                  <li><a href="admin2.php"><i class="fa fa-user"></i>Staff Record</a></li>
                </ul>
              </li>
			  <li class="treeview">
              	  <a data-toggle='modal' data-id='' href='#Useradd' class='open-adduser'><i class="fa fa-plus"></i>Add Staff Record</a>
               </li>            
              <li class="treeview">
              	  <a  href="bulk_staff.php" ><i class='fa fa-print'></i>Bulk registration</a>
               </li>
              <li class="treeview">
              	  <a data-toggle='modal' href="#Staffbulkprinting" class="Open-Taxreceipted"><i class='fa fa-print'></i>Bulk printing</a>
               </li>
                          
                </ul>
          </div>
          <!-- /.navbar-collapse -->
      </nav>
    </aside>
	</div>
		<!--left-fixed -navigation-->
		
		<!-- header-starts -->
		<div class="sticky-header header-section">
			<div class="header-left">
				<!--toggle button start-->
				<button id="showLeftPush"><i class="fa fa-bars"></i></button>
				<!--toggle button end-->
				
				<!--notification menu end -->
				<div class="clearfix"> </div>
			</div>
			<div class="header-right">
				
				
				<!--search-box-->
				
				<div class="profile_details" >		
					<ul>
						<li class="dropdown profile_details_drop">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<div class="profile_img">	
									<span class="prfil-img">
										<?php   
										$sql ="SELECT * FROM Profilepictures WHERE ids='$id' && Category='User'";
                                                $rget = mysqli_query($db,$sql);
												$num=mysqli_num_rows($rget);
                                                if($num!=0){
												                   while($found = mysqli_fetch_array($rget))
	                                                                {
                                                                       $profile= $found['name'];
		                                                            }
																	echo"<img src='admin/images/$profile' height='50px' width='50px' alt=''>";	   
												             }
												        else{
												           	echo"<img src='admin/images/profile.png' height='50px' width='50px' alt=''>";	   
														     	
												             }
										
										?>
										 </span> 
									<div class="user-name" >
										<p style="color:#1D809F;"><?php if(isset($Surname))
                                            {echo"<strong>".$firstname." ".$Surname."! </strong>";} ?>
				                         </p>
										<span>Administrator&nbsp;<img src='admin/images/dot.png' height='15px' width='15px' alt=''>
										</span>
									</div>
									<i class="fa fa-angle-down lnr"></i>
									<i class="fa fa-angle-up lnr"></i>
									<div class="clearfix"></div>	
								</div>	
							</a>
							<ul class="dropdown-menu drp-mnu">
								 <!-- <li>
                                  <a data-toggle='modal' data-id='<?php echo$id; ?>' href='#Updatepicture' class='open-Updatepicture'><i class="fa fa-user"></i>Change profile picture</a>
                                 </li> -->
								<li> <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a> </li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="clearfix"> </div>				
			</div>
			<div class="clearfix"> </div>	
		</div>
		<!-- //header-ends -->
		<!-- main content start-->
		<div id="page-wrapper"  >
			<div class="main-page" >
	
		
		
				
				
				
	
			<div class="charts">		
			<div class="mid-content-top charts-grids">
				<div class="middle-content">
						<h4 class="title">Users</h4>
					<!-- start content_slider -->
					<?php
					if(isset($_REQUEST['message']) && $_REQUEST['message']!=""){
						echo '<div class="alert alert-info" style="color:#F00;">
                             &nbsp;'.$_REQUEST['message'].'
                         </div>';
					}
					?>
				<div class="alert alert-info">
                             <i class="fa fa-envelope"></i>&nbsp;This screen displays 50 records use the search box to spool more records
                         </div>
					
					     <table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
            	<th>ID</th>
                <th>NAME</th>
                <th>STAFF ID</th>
                <th>POSITION</th>              
                <th>DEPARTMENT</th>     
                <th>PRINT</th>
                <th>EDIT</th>
                <th>DELETE</th>
            </tr>
        </thead>
        <tbody>
        	 <?php   $sqlmember ="SELECT * FROM users_staff ";
			       $retrieve = mysqli_query($db,$sqlmember);
				                    $count=0;
                     while($found = mysqli_fetch_array($retrieve))
	                 {
                       $title=$found['Mtitle'];$firstname=strtoupper($found['Firstname']);$Surname=strtoupper($found['Surname']);$Position=$found['Position'];
                       $id=$found['id'];$dept=$found['Department'];
			                $count=$count+1;  $get_time=$found['Time']; $time=time(); $pass=$found['Staffid'];
			              $names=$firstname." ".$Surname;
					    	 
			      echo"<tr>    <td>$id</td>                                       
                             <td>$firstname $Surname</td>        	
                             <td>$pass</td>
                             <td>$Position</td>
                             
			                 <td>$dept</td>
			                 <td>
			                   <a  href='print_card_staff.php?id=$id' class='btn  btn-success' title='click to print ID Card'  target='_blank'><span class='glyphicon glyphicon-print' style='color:white;'></span></a>
                              </td>
			                 <td>
			                   <a data-toggle='modal' data-id='$id' data-ie='$firstname'   data-if='$Surname' data-ih='$dept' data-ij='$Position' data-ik='$pass' class='open-Passwords btn  btn-info' title='edit user details' href='#Passwords'><span class='glyphicon glyphicon-edit' style='color:white;'></span></a>
							 
			                 </td>				                 
			                 <td>
			                   <a data-id='$id'  class='open-Delete btn  btn-danger' title='delete user' ><span class='glyphicon glyphicon-trash' style='color:white;'></span></a>
							 
			                 </td>			 
                             </tr>"; 
					 
					 } 
		
		           	?>
            </tbody>
        
    </table>
             <button id="clear-all-button">Clear All Filters</button>
                           
				        </div>
		
				</div>

					<!--//sreen-gallery-cursual---->
			</div>
		 </div>
		</div>
	<!--footer-->
	<div class="footer">
	  <p>© 2021 . All Rights Reserved | Design and developed by UNIMED ICT
	
			</p>		
	</div>
    <!--//footer-->
	</div>
		
	<!-- new added graphs chart js-->
	
    <script src="admin/js/Chart.bundle.js"></script>
    <script src="admin/js/utils.js"></script>
	
		
	<!-- Classie --><!-- for toggle left push menu script -->
		<script src="admin/js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			

			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>
	<!-- //Classie --><!-- //for toggle left push menu script -->
		
	<!--scrolling js-->
	<script src="admin/js/jquery.nicescroll.js"></script>
	<script src="admin/js/scripts.js"></script>
	<!--//scrolling js-->
	
	<!-- side nav js -->
	<script src='admin/js/SidebarNav.min.js' type='text/javascript'></script>
	<script>
      $('.sidebar-menu').SidebarNav()
    </script>
		
	<!-- Bootstrap Core JavaScript -->
   <script src="admin/js/bootstrap.js""> </script>
	<!-- //Bootstrap Core JavaScript -->
	 	<script src="css/bootstrap-dropdownhover.js"></script>
	
</body>
</html>