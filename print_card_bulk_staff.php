<?php 
session_start();
include("db_connect.php");
if(isset($_COOKIE['adminid']) && $_COOKIE['adminemail'] && isset($_REQUEST['startpoint']) && isset($_REQUEST['endpoint']) && isset($_REQUEST['dept'])){
	
	$userid=$_COOKIE['adminid'];
	$useremail=$_COOKIE['adminemail'];
	/*if($_REQUEST['startpoint']==1){
		$start=0;
	}else{
		
	}*/
	$start=$_REQUEST['startpoint']-1;
	$endpoint=$_REQUEST['endpoint'];
	$dept=$_REQUEST['dept'];
}else{
	 header('location:index.php');
      exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>STAFF BULK CARD PRINTING: <?php echo $dept; ?></title>
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
<?php
	if($endpoint=="ALL"){
		$sqluser ="SELECT * FROM users_staff WHERE Department='$dept' ORDER BY id";
	}else{
		$sqluser ="SELECT * FROM users_staff WHERE Department='$dept' ORDER BY id limit $start,$endpoint";
	}
	$retrieved = mysqli_query($db,$sqluser);
	$no=mysqli_num_rows($retrieved);
	if($no>=1){
    	while($found = mysqli_fetch_array($retrieved)){
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
			  $dept1=$Department;
		echo '<table width="100%" style="width: 1015px; height: 646px; page-break-after:always;" background="images/staff_front.jpg">';
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
			echo '</table>';
			echo '<table width="100%" style="width: 1015px; height: 646px; page-break-after:always;" background="images/staff_back.jpg">
			<tr><td style="padding-top:520px; font-size:16px; padding-left: 40px; font-weight: bold;" align="left">';
			echo "Issued Date: ".date('d/m/Y');
		echo '</td>
			</tr>
		</table>';
		}
	}else{
		header("location: admin2.php?message=Record not found for the given parameters");
	}
?>
</body>
</html>
