<?php 
session_start();
include("db_connect.php");
if(isset($_COOKIE['adminid']) && $_COOKIE['adminemail'] && isset($_REQUEST['id'])){
	
	$userid=$_COOKIE['adminid'];
	$useremail=$_COOKIE['adminemail'];
	$id=$_REQUEST['id'];

$sqluser ="SELECT * FROM users WHERE id='$id'";

$retrieved = mysqli_query($db,$sqluser);
    while($found = mysqli_fetch_array($retrieved))
	     {
              $Firstname = strtoupper(trim($found['Firstname']));
		      $Surname= strtoupper($found['Surname']);
			  $Faculty = $found['Faculty'];
			  $Department= $found['Department'];
			  $Level= $found['Level'];
			  $Gender= $found['Gender'];
			  $RecID= $found['Staffid'];
			  $Picname= $found['Picname'];
			  $pos1=stripos($Firstname," ");
			  if($pos1===false){
				  $oname=$Firstname;
			  }else{
				  $mname=trim(substr($Firstname,$pos1));
				  $mname=substr($mname,0,1);
				  $oname=substr($Firstname,0,$pos1)." ".$mname.".";
			  }
   
  	     switch($Department){
				case "Biological Sciences(Animal and Environmental)":
				$dept1="Biological Sciences(AEB)";
				break;
				case "Biological Sciences(Microbiology)":
				$dept1="Biological Sciences(MCB)";
				break;
				case "Biological Sciences(Plant Biology and Biotechnology)":
				$dept1="Biological Sciences(PBT)";
				break;
				default:
				$dept1=$Department;
				break;
			}
}		 
		 
}else{
	 header('location:index.php');
      exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ID CARD: <?php echo $RecID; ?></title>
<link type="text/css" rel="stylesheet" href="include/style.css" />
        <link rel="shortcut icon" href="favicon.ico" />
        <script src="include/jquery-1.7.2.min.js"></script>
<style type="text/css">
	body{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:30px;
	
	}
	@page {
	  size: 85.852mm 54.61; // set appropriately
	  margin: 0;
	}
	@media print {
	  html, body {
		width: 85.852mm; // set appropriately
		height: 54.61mm; // set appropriately
	  }
	  /* ... the rest of the rules ... */
	}
	#waterMark{
	position:absolute;
	z-index:-10;
	top:0%;
	left:0%;
	width: 1015px;
	height: 646px;
	
	
}

</style>

</head>

<body bgcolor="#ffffff">
<table width="100%" style="width: 1015px; height: 646px; page-break-after:always;" background="images/student_front.jpg">
	<?php
			echo "<tr>";
				echo '<td align="left" valign="top" width="72%" style="padding-top:235px; padding-left: 45px;"> <div style="line-height:1.8em;">';
					echo '<table align="center" width="100%">';
						echo '<tr>
								<td align="left">MATRIC NO.:</td>
								<td align="left" style="color:#F00;"><b>'.$RecID.'</b></td> 
							</tr>
							<tr>
								<td align="left">NAME:</td>
								<td align="left"><b>'.$oname.' '.$Surname.'</b></td> 
							</tr>
							<tr>
								<td align="left">FACULTY:</td>
								<td align="left"><b>'.$Faculty.'</b></td> 
							</tr>
							<tr>
								<td align="left">DEPARTMENT:</td>
								<td align="left"><b>'.$dept1.'</b></td> 
							</tr>
							<tr>
								<td align="left">GENDER:</td>
								<td align="left"><b>'.$Gender.'</b></td> 
							</tr>';
							/*
							echo '<tr>
								<td align="right" width="2%"><b>5</b></td>
								<td align="left" width="24%">&nbsp;<b>Address.:</b></td>
								<td align="left" colspan="4" width="74%" style="border-bottom:#000000 dotted 1px;">'.$rec['address'].'</td> 
							</tr>';
							*/	
					echo '</table></div>';
				echo '</td>';
				echo '<td valign="top" align="center" style="padding-top:285px;">
						<div style="border: 3px solid #000000; width:239px; height:269px;">
						<img width="239" height="269" src="images/'.$Picname.'" />
						</div>
					</td>';
			echo '</tr>';
			
?>
</table>

<table width="100%" style="width: 1015px; height: 646px; page-break-after:always;" background="images/student_back.jpg">
	<tr><td style="padding-top:520px;" align="center">
		<?php
				echo "<center><img alt='testing' src='barcode.php?codetype=Code39&size=50&text=".$RecID."&print=true'/></center>";
		?>
		</td>
	</tr>
</table>
</body>
</html>
