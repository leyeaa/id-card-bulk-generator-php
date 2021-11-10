<?php
error_reporting(E_ALL);
session_start();
include("db_connect.php");
//if(isset($_REQUEST['ids'])){	           
		//$id=$_REQUEST['ids'];
        $query = "SELECT Staffid,Mtitle,Surname,Firstname,Position,Department,Picname FROM users_staff ORDER BY id DESC LIMIT 0,2";         
        $result = mysqli_query($db,$query) or die('Error, query failed');		 
   
	$fn="STAFF_BULK_RECORD_UPLOAD_SAMPLE".'.csv';
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="'.$fn.'"');
	header('Pragma: no-cache');
	header('Expires: 0');
	$opf=fopen('php://output', 'w');
	fputcsv($opf, array('Staffid', 'Mtitle', 'Surname', 'Firstname', 'Position', 'Department', 'Picname'));
	
	while($rec=mysqli_fetch_array($result)){
		$data=array($rec[0],$rec[1],$rec[2],$rec[3],$rec[4],$rec[5],$rec[6]);
		//print_r($data);
		//exit();
		
		fputcsv($opf, $data);
	}
		exit();      
//}

?>