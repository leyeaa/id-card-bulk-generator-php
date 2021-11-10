<?php 
session_start();
include("db_connect.php");
if(isset($_COOKIE['adminid']) && $_COOKIE['adminemail'] && isset($_REQUEST['id'])){
	
	$userid=$_COOKIE['adminid'];
	$useremail=$_COOKIE['adminemail'];
	$id=$_REQUEST['id'];

$sqluser ="SELECT * FROM users_staff WHERE id='$id'";

$retrieved = mysqli_query($db,$sqluser);
    while($found = mysqli_fetch_array($retrieved))
	     {
              $Firstname = strtoupper(trim($found['Firstname']));
		      $Surname= strtoupper($found['Surname']);
			  $Position = $found['Position'];
			  $Department= $found['Department'];
			  $Mtitle= $found['Mtitle'];
			  $RecID= $found['Staffid'];
			  $Picname= $found['Picname'];
			  $pos1=stripos($Firstname," ");
			  $onames=explode(" ",$Firstname);
			  $mname="";
			  if(count($onames)>1){
				  for($i=1;$i<sizeof($onames);$i++){
					 $mname.=" ".substr($onames[$i],0,1).".";
				  }
				  $oname=$onames[0].$mname;
			  }else{
				  $oname=$Firstname;
			  }
			/*
			  if($pos1===false){
				  $oname=$Firstname;
			  }else{
				  $mname=trim(substr($Firstname,$pos1));
				  $mname=substr($mname,0,1);
				  $oname=substr($Firstname,0,$pos1)." ".$mname.".";
			  }*/
   		/*
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
			*/
}		 $dept1=$Department;
		 
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
	<link rel="stylesheet" type="text/css" href="stylefont.css"/>
        <link rel="shortcut icon" href="favicon.ico" />
        <script src="include/jquery-1.7.2.min.js"></script>
<style type="text/css">
	body{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:30px;
		color: #333333;
	
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
	  /* ... the rest of the rules ... Cambria, Hoefler Text, Liberation Serif, Times, Times New Roman, serif; */
	}
	#waterMark{
	position:absolute;
	z-index:-10;
	top:0%;
	left:0%;
	width: 1015px;
	height: 646px;
	
	
}
	.staffname{
		font-family: Impact, Haettenschweiler, "Franklin Gothic Bold", "Arial Black", "sans-serif";
		font-size: 54px;
	}
	.dept{
		font-family: "AR JULIAN";
		font-size: 38px;
		font-weight:bold;
	}
	.position{
		font-family:'AR ESSENCE Regular';
		font-weight:normal;
		font-size:44px;
		font-weight:bold;
	}

</style>

</head>

<body bgcolor="#ffffff">
<table width="100%" style="width: 1015px; height: 646px; page-break-after:always;" background="images/staff_front.jpg">
	<?php
			echo "<tr>";
				echo '<td align="left" valign="top" width="100%" style="padding-top:205px; padding-left: 45px;">
				<div style="line-height:1.6em;">';
					echo '<table align="center" width="100%">';
						echo '<tr><td align="left" colspan="3" style="padding-left:470px; font-family:Arial; font-size: 28px;"><b>'.$RecID.'</b></td></tr>
							<tr>
								<td align="center">
								<span class="staffname">'.strtoupper($oname).' '.strtoupper($Surname).'<span><br />
								<span class="position">'.strtoupper($Position).'<span><br />
								<span class="dept">'.ucwords(strtolower($dept1)).'<span>
								</td> 
								<td align="left" style="width: 241px; padding-top: 20px;">
									<div style="border: 4px solid #000000; width:239px; height:269px;">
										<img src="images/'.$Picname.'" width="239" height="269" />
									</div>
								</td>
								<td align="left" style="padding-right:10px;">';
									echo "<center><img alt='testing' src='barcode2.php?codetype=Code39&size=25&text=".$RecID."&print=true'/></center>";
								echo '
								</td>
							</tr>
							';
							
					echo '</table></div>';
				echo '</td>';
			echo '</tr>';
			
?>
</table>

<table width="100%" style="width: 1015px; height: 646px; page-break-after:always;" background="images/staff_back.jpg">
	<tr><td style="padding-top:520px; font-size:16px; padding-left: 40px; font-weight: bold;" align="left">
		<?php
			echo "Issued Date: ".date('d/m/Y');
		?>
		</td>
	</tr>
</table>
</body>
</html>
