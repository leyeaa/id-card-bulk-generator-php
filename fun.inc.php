<?php

/*

$con = mysqli_connect("localhost","root","","unimedportal");

if (mysqli_connect_errno())

  {

  echo "Failed to connect to MySQL: " . mysqli_connect_error();

  }



*/

$con = mysqli_connect("localhost","unimed5_ict1","*biscuit_@_children!","unimed5_unimedportaldb");

if (mysqli_connect_errno())

  {

  echo "Failed to connect to MySQL: " . mysqli_connect_error();

  }



try {

    $conn = new PDO('mysql:host=localhost;dbname=unimed5_unimedportaldb', "unimed5_ict1", "*biscuit_@_children!");

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

    echo 'ERROR: ' . $e->getMessage();

}



//---------------------------------- #FUNCTION TO CONNECT TO DATABASE.....#--

function connet(){

	$connection=mysql_connect("localhost","unimed5_ict1","*biscuit_@_children!")

	or die("connection failed");

	mysql_select_db("unimed5_unimedportaldb",$connection)

	or die("cannot connect to DataBase");

	/*

	$connection=mysql_connect("localhost","root","")

	or die("connection failed");

	mysql_select_db("unimedportal",$connection)

	or die("cannot connect to DataBase");

	*/

}

//------------------------------------- #FUNCTION TO QUERY DATABASE.....#--------------------------------------

function result($query){

	connet();

	if(!$result=mysql_query($query)){

	   $message=mysql_error();

	return $message;	

}	

else

{

	return $result;

}

}



function getRecs($table,$field,$id){

	connet();

	$checkQuery="SELECT * FROM $table WHERE $field='$id' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}



function getFacs($fac,$dept){

	connet();

	if($dept==0){

		$checkQuery="SELECT * FROM facultiesdept WHERE faculty_id=$fac ORDER BY id DESC LIMIT 0,1";	

	}elseif($dept==""){

		$checkQuery="SELECT * FROM facultiesdept WHERE faculty_id=$fac ORDER BY id DESC LIMIT 0,1";

	}else{

		$checkQuery="SELECT * FROM facultiesdept WHERE faculty_id=$fac AND dept_id=$dept ORDER BY id DESC LIMIT 0,1";

	}

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}



function searchP($fac,$dept){

connet();

	$checkQuery="SELECT * FROM profile WHERE faculty='$fac' AND dept='$dept' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}



function pdspVerify($phone){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE mobile='$phone' AND feetype='UNIMED PRE-DEGREE APPLICATION' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}

function trfVerify($phone){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE mobile='$phone' AND feetype='UNIMED TRANSFER FEE' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}


function payProvider($paytype){
	$paytech=array("UNIMED SCHOOL FEES","POST BASIC NURSING SCHOOL FEES","UNIMED PRE-DEGREE SCHOOL FEE","NURSING AND MIDWIFERY SCHOOL FEE","UNIMED PREDEGREE ACCEPTANCE");
	$etz=array("UNIMED ACCEPTANCE FEE","UNIMED ACCOMMODATION FEE","UNIMED INTERNET ACCESS","UNIMED CHANGE OF COURSE FEE","UNIMED PRE-DEGREE APPLICATION","UNIMED TRANSFER FEE","UNDERGRADUATE AND POSTGRADUATE DIPLOMA ACCEPTANCE","UNDERGRADUATE AND POSTGRADUATE DIPLOMA SCHOOL FEES","CAR-UNIMED CONFERENCE","UNIMED LATE REGISTRATION FEE");
	//,,"UNIMED ACCOMMODATION FEE"
	if(in_array($paytype,$paytech)){
		$provider=1;
	}elseif(in_array($paytype,$etz)){
		$provider=2;
	}
	return $provider;
}

function payControl($paytype){
	$provider=payProvider($paytype);
	if($provider==1){
		$url="paymentupverify.php";
	}elseif($provider==2){
		$url="paymentetznew.php";
	}
	return $url;
}

function ugd_pgd_pay_code($prog){

	switch($prog){

		case "PROFESSIONAL PGD APPLICATION":

			$rec=array('MZ070006','20000');

			break;

		case "POSTGRADUATE DIPLOMA APPLICATION":

			$rec=array('MZ070005','20000');

			break;

		case "B.Sc. CONVERSION APPLICATION":

			$rec=array('MZ070004','15000');

			break;

		case "ADVANCED PROFESSIONAL CERTIFICATE APPLICATION":

			$rec=array('MZ070003','15000');

			break;

		case "FOUNDATION SCIENCE APPLICATION":

			$rec=array('MZ070002','15000');

			break;

		default:

			$rec=array('MZ070005','20000');

			break;

	}

	return $rec;

}

function cu2($code,$dept){
	connect();
	$sql=result("SELECT credit FROM course WHERE courseCode='$code' AND (dept='$dept' || dept='ALL')");
	$no=mysql_num_rows($sql);
	if($no==0){
		$un=0;
	}else{
		list($un)=mysql_fetch_array($sql);
	}
	return $un;
}

function Phyresit(){
	connect();
	$sql=result("SELECT matricno, courses, dept FROM resit");
	$no=0;
	while($ret=mysql_fetch_array($sql)){
		$coses=explode("|",$ret[1]);
		$ca=array();
		$units=array();
		foreach($coses as $cos){
			$ca[]=0;
			$units[]=cu2($cos,$ret[2]);
		}
		$impca=implode("|",$ca);
		$impun=implode("|",$units);
		echo "UPDATE resit SET ca='$impca', exam='$impca', total='$impca', units='$impun' WHERE matricno='$ret[0]'";
		exit();
		//result("UPDATE resit SET ca='$impca', exam='$impca', total='$impca', units='$impun' WHERE matricno='$ret[0]'");
		$no+=1;
	}
	//return $no;
}

function ugd_pgdVerify($phone){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE mobile='$phone' AND feetype='UNDERGRADUATE AND POSTGRADUATE DIPLOMA APPLICATION' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}

function carVerify($phone){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE mobile='$phone' AND feetype='CAR-UNIMED CONFERENCE' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}


function pbnpVerify($email,$session="2020/2021"){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE email='$email' AND feetype='POST BASIC NURSING APPLICATION' AND session='$session' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}



function pbnpAdmit($regno){

	connet();

	$checkResult=result("SELECT * FROM postbasicnus_result WHERE regno='$regno' AND status='ADMITTED' ORDER BY id DESC LIMIT 0,1");

	$rows=mysql_num_rows($checkResult);

	$recs=getRecs("postbasicnus_deg_basicinfo","regno",$regno);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}



function uniqueMember($ema){

connet();

	$em=trim($ema);

	$uQuery="select memberID from members where email='$em'";

	$uResult=result($uQuery);

	while($c_row=mysql_fetch_array($uResult)){

	$content=$c_row[0];

	}

	if($content>0){

		return 1;

	}else{

		return -1;

	}

}



//-------------------------------------- #FUNCTION TO INSERT NEW MESSAGES....#-----------------------------------------------

function resultnew($query){

	$conn=mysqli_connect("localhost","unimed5_ict1","*biscuit_@_children!","unimed5_unimedportaldb");

	//$conn=mysqli_connect("localhost","root","Trailblazer@1","resultsuite");

	if (mysqli_connect_errno()){

  		echo "Failed to connect to MySQL: " . mysqli_connect_error();

  	}

	if(!$result=mysqli_query($conn,$query)){

	   $message=mysqli_error($conn);

	return $message;	

}	

else

{

	return $result;

}

}



function input($values, $table){

	$values[]=date("Y-m-d");

	$n=sizeof($values);//GET THE NUMBER OF PARAMETERS

	$reg_query="INSERT INTO $table Values (id,";

//BUILD UP QUERY DEPENDING ON THE NUMBER OF VALUES SENT....

	for($x=0; $x<$n; $x++){

	 $reg_query .="'".$values[$x]."',";

	} 

	$rev_query=strrev($reg_query);//reverse the query....

	$irev_query=substr($rev_query,1,strlen($reg_query));//strip the last comma....

	$auth_query=strrev($irev_query);//reverse the stripped query...

	$auth_query .=")";//add the closing bracket to the formatted query....

	$reg_result=resultnew($auth_query);

	return $reg_result;

	//return $auth_query

}

function inputtest($values, $table){

	$values[]=date("Y-m-d");

	$n=sizeof($values);//GET THE NUMBER OF PARAMETERS

	$reg_query="INSERT INTO $table Values (id,";

//BUILD UP QUERY DEPENDING ON THE NUMBER OF VALUES SENT....

	for($x=0; $x<$n; $x++){

	 $reg_query .="'".$values[$x]."',";

	} 

	$rev_query=strrev($reg_query);//reverse the query....

	$irev_query=substr($rev_query,1,strlen($reg_query));//strip the last comma....

	$auth_query=strrev($irev_query);//reverse the stripped query...

	$auth_query .=")";//add the closing bracket to the formatted query....

	//$reg_result=resultnew($auth_query);

	//return $reg_result;

	return $auth_query;

}



function input22($values, $table){

	$values[]=date("Y-m-d");

	$n=sizeof($values);//GET THE NUMBER OF PARAMETERS

	$reg_query="INSERT INTO $table Values ('',";

//BUILD UP QUERY DEPENDING ON THE NUMBER OF VALUES SENT....

	for($x=0; $x<$n; $x++){

	 $reg_query .="'".$values[$x]."',";

	}

	$rev_query=strrev($reg_query);//reverse the query....

	$irev_query=substr($rev_query,1,strlen($reg_query));//strip the last comma....

	$auth_query=strrev($irev_query);//reverse the stripped query...

	$auth_query .=")";//add the closing bracket to the formatted query....



	$reg_result=$auth_query;//result($auth_query);

	//$insertId=mysql_insert_id();

	return $reg_result;

	//return $insertId;

	//return $auth_query;

}



function input2($values, $table){

	$values[]=date("Y-m-d");

	$n=sizeof($values);//GET THE NUMBER OF PARAMETERS

	$reg_query="REPLACE INTO $table Values (id,";

//BUILD UP QUERY DEPENDING ON THE NUMBER OF VALUES SENT....

	for($x=0; $x<$n; $x++){

	 $reg_query .="'".$values[$x]."',";

	} 

	$rev_query=strrev($reg_query);//reverse the query....

	$irev_query=substr($rev_query,1,strlen($reg_query));//strip the last comma....

	$auth_query=strrev($irev_query);//reverse the stripped query...

	$auth_query .=")";//add the closing bracket to the formatted query....

	$reg_result=resultnew($auth_query);

	return $reg_result;

	//return $auth_query

}

function input23($values, $table){

	$values[]=date("Y-m-d");

	$n=sizeof($values);//GET THE NUMBER OF PARAMETERS

	$reg_query="REPLACE INTO $table Values (id,";

//BUILD UP QUERY DEPENDING ON THE NUMBER OF VALUES SENT....

	for($x=0; $x<$n; $x++){

	 $reg_query .="'".$values[$x]."',";

	} 

	$rev_query=strrev($reg_query);//reverse the query....

	$irev_query=substr($rev_query,1,strlen($reg_query));//strip the last comma....

	$auth_query=strrev($irev_query);//reverse the stripped query...

	$auth_query .=")";//add the closing bracket to the formatted query....

	//$reg_result=resultnew($auth_query);

	return $auth_query;

	//return $auth_query

}


function imp($rec){

	$ret=implode("|",$rec);

	return $ret;

}



function ex($rec){

	$ret=explode("|",$rec);

	return $ret;

}



function verifyEtranzact($ternimalid,$paycode,$responseurl){

			#$tx = urlencode($_REQUEST['tx']);

			//$url = 'https://www.etranzact.net/WebConnectPlus/query.jsp';

			#$url = 'http://www.etranzact.net/Query/queryPayoutletTransaction.jsp';

			//$url = 'http://demo.etranzact.net/WebConnectPlus/query.jsp';

			$url = 'https://www.etranzact.net/WebConnectPlus/query.jsp';

							

			$nvpString="TERMINAL_ID=$ternimalid".

					   "&CONFIRMATION_NO=$paycode".

					   "&RESPONSE_URL=$responseurl";

			//return $url.$nvpString;

			#define where the data is going to

			$curl = curl_init($url);

			#tell cURL to fail if an error occurs

			curl_setopt($curl, CURLOPT_FAILONERROR, 1); 

			#allow for redirects

			//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

			#curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			#assign the returned data to a variable

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			#set the timeout

			curl_setopt($curl, CURLOPT_TIMEOUT, 60);

			#use POST

			curl_setopt($curl, CURLOPT_POST, 1);

			#set the POST data

			curl_setopt($curl, CURLOPT_POSTFIELDS, $nvpString);

			#execute the transaction

			$response = curl_exec($curl);

			#show errors

			$curerror = curl_error($curl);

			#close the connection

			curl_close($curl);

			

			//return $curerror;

			return $response;	

			

			$pay_params=explode("&",$response);

			//return $pay_params;

			

			if(sizeof($pay_params)==1){

				return "-1";

				//return $pay_params;

			}else{

				#$pay_params=explode("&",$response);

				for($q=0;$q<sizeof($pay_params);$q++){

					$splitted_params[]=explode("=",$pay_params[$q]);

					$arrayKeys[]=$q;

					$splitted_keys[]=$splitted_params[$q][0];

					$splitted_values[]=trim(urldecode(str_replace("</html>","",$splitted_params[$q][1])));			

				}

				$new_params=array_combine($arrayKeys,$splitted_values);



				array_pop($new_params);

				array_pop($new_params);



				return $new_params;	

			}

			#return $new_params;

	}



function upStudentInfo($matric){

	connet();

	$upQuery="UPDATE studentiformation SET date=CURDATE() where matricno='$matric'";

	$upResult=result($upQuery);

}



function getUserIdx(){

	$userid=microtime();

	$use=strtoupper(substr(md5($userid),2,6));

	return $use;

}



function getUserIdxx(){

	$userid=microtime();

	$use="CI-".strtoupper(substr(md5($userid),2,8));

	return $use;

}



function getFacultyID($state){

connet();

	$query="SELECT faculty_id FROM faculties_dept WHERE faculty='$state'";

	$queryResult=result($query);

	list($id)=mysql_fetch_array($queryResult);

	return $id;

}

function getFacultyIDCoc($state){

connet();

	$query="SELECT faculty_id FROM faculties_dept2 WHERE faculty='$state'";

	$queryResult=result($query);

	list($id)=mysql_fetch_array($queryResult);

	return $id;

}



function getFaculty($faculty){

	connet();

		$query="SELECT faculty FROM facultiesdept WHERE faculty_id=$faculty limit 0,1";

		$queryResult=result($query);

		list($id)=mysql_fetch_array($queryResult);

		return $id;

	}



function getDeptName($faculty,$dept){

	connet();

		$query="SELECT dept FROM facultiesdept WHERE faculty_id=$faculty AND dept_id=$dept";

		$queryResult=result($query);

		list($id)=mysql_fetch_array($queryResult);

		return $id;

	}



function calcAge(){

	connet();

	$sql=result("SELECT * FROM studentinformation");

	$age1a=0;

	$age2a=0;

	$age3a=0;

	$age4a=0;

	$age1b=0;

	$age2b=0;

	$age3b=0;

	$age4b=0;

	$date=date('Y-m-d');

	while($rec=mysql_fetch_array($sql)){

		if(substr($date, 5, 2)>=substr($rec[5], 5, 2) && substr($date, 8, 2)>substr($rec[5], 8, 2)){

			$agerec=$date-$rec[5];

		}else{

			$agerec=$date-$rec[5]-1;

		}

		switch($rec[4]){

			case "M":

			if($agerec<=20){

				$age1a+=1;

			}elseif($agerec<=25){

				$age2a+=1;

			}elseif($agerec<=30){

				$age3a+=1;

			}elseif($agerec<=50){

				$age4a+=1;

			}

			break;

				case "F":

			if($agerec<=20){

				$age1b+=1;

			}elseif($agerec<=25){

				$age2b+=1;

			}elseif($agerec<=30){

				$age3b+=1;

			}elseif($agerec<=50){

				$age4b+=1;

			}

			break;

		}

	}

	echo $age1a;

	echo "<br>";

	echo $age2a;

	echo "<br>";

	echo $age3a;

	echo "<br>";

	echo $age4a;

	echo "<br>";

	echo "<br>";

	echo $age1b;

	echo "<br>";

	echo $age2b;

	echo "<br>";

	echo $age3b;

	echo "<br>";

	echo $age4b;

	echo "<br>";

}



function getDept($fin){

	connet();

		$query="SELECT dept, dept_id FROM facultiesdept WHERE faculty_id=$fin";

		$queryResult=result($query);

		while($rec=mysql_fetch_array($queryResult)){

			echo '<a href="departments.php?din='.$rec[1].'&fin='.$fin.'">&rarr;&nbsp;'.$rec[0].'</a>';

		}

		

	}



function getStateID($state){

connet();

	$query="SELECT state_id FROM state2 WHERE state='$state'";

	$queryResult=result($query);

	list($id)=mysql_fetch_array($queryResult);

	return $id;

}



function selectLG($state,$lg1){

	$query=result("SELECT lg FROM state2 WHERE state='$state'");

	while($lg=mysql_fetch_array($query)){

		echo '<option value="'.$lg[0].'"'; if($lg[0]==$lg1){echo 'selected="selected"';}; echo '>'.$lg[0].'</option>';

	}

}



function getUserId($table,$cat){

	connet();

		$lastRec=getRec($table,$cat);  

		if($lastRec==0){

		  $Code=(0+1);

		}else{

			$rec=substr($lastRec[1],7);

		  	$Code=($rec+1); 

		}

		$Code=sprintf("%06d",$Code);

		$use[]=$cat.'/OT/'.$Code;

		$use[]=$cat.'_OT_'.$Code;

		return $use;

}



function accVerify($user,$pass){

connet();

	$user=trim($user);

	$pass=trim(md5($pass));

	$res=result("select * from student_acc where username='$user'");

	$rect=mysql_num_rows($res);

	if($rect !=0){

		$rec=mysql_fetch_array($res);

	}

	if($rect==0){

		return 0;

	}elseif($pass !=$rec[2]){

		return 1;

	}

}



function accLogin($user,$pass){

connet();

	$user=trim($user);

	$pass=trim(md5($pass));

	$loginQuery="select * from student_acc where username='$user' AND password='$pass'";

	$loginResult=result($loginQuery);

	$recordCount=mysql_num_rows($loginResult);

	if($recordCount>=1){

		$c_row=mysql_fetch_array($loginResult);

		return $c_row;

	}else{

		return 0;

	}

}



function accLogin1($user,$pass,$table){

connet();

	$user=trim($user);

	$pass=trim(md5($pass));

	$loginQuery="select * from $table where username='$user' AND password='$pass'";

	$loginResult=result($loginQuery);

	$recordCount=mysql_num_rows($loginResult);

	if($recordCount>=1){

		$c_row=mysql_fetch_array($loginResult);

		return $c_row;

	}else{

		return 0;

	}

}



function gender($sex){

	switch($sex){

		case "F":

			$gen="Female";

			break;

		case "M":

			$gen="Male";

			break;

		default:

			$gen=$sex;

			break;

	}

	return $gen;

}



function userLoginxx($user,$old){

	connet();

	$old=md5($old);

	$checkQuery="SELECT * FROM student_acc WHERE username='$user' AND password='$old'";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	if($rows==1){

		return 1;

	}else{

		return 0;

	}

}



function userLoginxxipnme($user,$old){

	connet();

	$old=md5($old);

	$checkQuery="SELECT * FROM ipnme_acc WHERE username='$user' AND password='$old'";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	if($rows==1){

		return 1;

	}else{

		return 0;

	}

}



function updatePassxx($user,$new){

	connet();

	$new=md5($new);

	$upQuery="UPDATE student_acc SET password='$new' WHERE username='$user'";

	$upResult=result($upQuery);

	return $upResult;

}


function listlevelsal($name,$hid,$val=""){

		echo '<select name="'.$name.'" id="'.$hid.'" style="width:175px; font-size:13px; line-height:2em; height:25px;"><option></option>';

					echo '

						<option value="100" '; if($val=="100"){ echo 'selected="selected"';} echo '>100</option>

						<option value="200" '; if($val=="200"){ echo 'selected="selected"';} echo '>200</option>

						<option value="300" '; if($val=="300"){ echo 'selected="selected"';} echo '>300</option>
						
						<option value="400" '; if($val=="400"){ echo 'selected="selected"';} echo '>400</option>

						';	

		echo '</select>';

}

function updatePassxxipnme($user,$new){

	connet();

	$new=md5($new);

	$upQuery="UPDATE ipnme_acc SET password='$new' WHERE username='$user'";

	$upResult=result($upQuery);

	return $upResult;

}



function userLoginxx1($user,$old){

	connet();

	$old=md5($old);

	$checkQuery="SELECT * FROM unimed_users WHERE username='$user' AND password='$old'";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	if($rows==1){

		return 1;

	}else{

		return 0;

	}

}



function updatePassxx1($user,$new){

	connet();

	$new=md5($new);

	$upQuery="UPDATE unimed_users SET password='$new' WHERE username='$user'";

	$upResult=result($upQuery);

	return $upResult;

}





function recSearch($val){

	connet();

	$checkQuery="SELECT * FROM studentinformation WHERE regno='$val' || matricno='$val'";

	$checkResult=result($checkQuery);

	$s_row=mysql_num_rows($checkResult);

	return $s_row;

}



function recSearch2($val){

	connet();

	$checkQuery="SELECT * FROM studentinformation WHERE regno='$val' || matricno='$val'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	return $returnedRows;

}



function mailSender($to,$subject,$message, $from){

    $headers  = 'MIME-Version: 1.0' . "\r\n";

    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $headers .= $from;

    ###Additional headers

    ###$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";

    //$headers .= 'From: SPGS FUTA <info@example.com>' . "\r\n";

    ###$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";

    ###$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

    ###$from=From: example person <info@example.com>'

    ###Mail it

    //return mail($to, $subject, $message, $headers);

    if(mail($to, $subject, $message, $headers)){

      return 1;

    }else{

      return 0;

    }

}



function hostel($regno,$cat=0,$amt=0){

	connet();

	//$putmerec=getRecs("post_utme_basicinfo","regno",$regno);

	//if($putmerec!=0){

	//	$adm=getRecs("admissiontable","regno",$regno);

	//	$rec=array($adm[3],$adm[9]);

	//}else{

	$admtpdsp=getRecs("pre_deg_basicinfo","regno",$regno);

	if(recSearch($regno)!=0){

		$ret=recSearch2($regno);

		$rec=array(convertGender($ret[5]),$ret[19]);

	}elseif($admtpdsp!=0){

		$rec=array($admtpdsp[4],"PDSP");

	}	

	//}

	$cname=strtolower($rec[0]);

	$cname1=$cname."1";

	if($cat==1){

		result("UPDATE hostel SET $cname1=$cname1+1 WHERE level='$rec[1]'");

		result("UPDATE hostel SET $cname1=$cname1+1 WHERE level='$amt'");

	}elseif($cat==2){

		$sql=result("SELECT * FROM hostel WHERE level='$amt' AND $cname>$cname1");

		$no=mysql_num_rows($sql);

		if($no>=1){

			return 1;

		}else{

			return 0;

		}

	}else{

		$sql=result("SELECT * FROM hostel WHERE level='$rec[1]' AND $cname>$cname1");

		$no=mysql_num_rows($sql);

		if($no>=1){

			return 1;

		}else{

			return 0;

		}

	}

}



function paySchFees($regno){

	$rec=getRecs("freshersverification","regno",$regno);

	if($rec[2]=="YES"){

		return 1;

	}else{

		return 0;

	}

}

function updpScreen($regno){

	$rec=getRecs("freshersverificationupdp","regno",$regno);

	if($rec[2]=="YES"){

		return 1;

	}else{

		return 0;

	}

}


function pdspscreen($regno){

	$rec=getRecs("predegreeverification","regno",$regno);

	if($rec[2]=="YES"){

		return 1;

	}else{

		return 0;

	}

}



function payStudCat($regno){

	$rec=recSearch2($regno);

	if($rec[22]=="NEW"){

		return 1;

	}else{

		return 0;

	}

}



function devLevy($faculty){

	switch($faculty){

		case "Clinical Sciences":

			$amt=100000;

			break;

		case "Dentistry":

			$amt=100000;

			break;

		case "Allied Health Sciences":

			$amt=70000;

			break;	

		case "Nursing Science":

			$amt=70000;

			break;

		case "Basic Medical Sciences":

			$amt=50000;

			break;

		case "Sciences":

			$amt=50000;

			break;

		default:

			$amt=100000;

		break;

	}

	return $amt;

}



function acceptFee($faculty){

	switch($faculty){

		case "Clinical Sciences":

			$amt=100000;

			break;

		case "Dentistry":

			$amt=100000;

			break;

		case "Allied Health Sciences":

			$amt=80000;

			break;	

		case "Nursing Science":

			$amt=80000;

			break;

		case "Basic Medical Sciences":

			$amt=60000;

			break;

		case "Sciences":

			$amt=60000;

			break;

		default:

			$amt=100000;

		break;

	}

	return $amt;

}



function accommFee(){

	$amt=130000;

	return $amt;

}



function intAccess(){

	$amt=10000;

	return $amt;

}



function payverify2($reg){

	connet();

	$ret=result("SELECT * FROM paymentinvoice WHERE transactionid='$reg'");	

	$s_row=mysql_num_rows($ret);

	//return $s_row;

	if($s_row >=1){

		$res=result("update paymentinvoice set feestatus='PAID', status='APPROVED' where transactionid='$reg'");

		return 1;

	}else{

		return 0;

	}

}

function updateInv($matricno,$paytype,$session,$txid){
	$res=result("update paymentinvoice set transactionid='$txid' where matricno='$matricno' and feetype='$paytype' and session='$session'");
}

function payverifyup($reg){

	connet();

	$ret=result("SELECT * FROM paymentinvoice WHERE transactionid='$reg'");	

	$s_row=mysql_num_rows($ret);

	//return $s_row;

	if($s_row>=1){

		result("update paymentinvoice set feestatus='PAID', status='APPROVED' where transactionid='$reg'");

		return 1;

	}else{

		return 0;

	}

}



function payverify($reg,$paytype){

	connet();

	$session=getSession();

	$ret=result("SELECT * FROM paymentinvoice WHERE matricno='$reg' AND feetype='$paytype' AND feestatus='PAID' AND session='$session[1]'");	

	$s_row=mysql_num_rows($ret);

	return $s_row;

}

function lateRegExempt($matricno){

	connet();

	$session=getSession();

	$ret=result("SELECT * FROM latereg WHERE matricno='$matricno' AND session='$session[1]' AND semester='$session[2]'");	
	$s_row=mysql_num_rows($ret);

	return $s_row;
}

function coursePay($regno){

	if(payverify($regno,"UNIMED SCHOOL FEES")>=1 && payverify($regno,"UNIMED DEVELOPMENT LEVY")>=1){

			return 1;

		}else{

			return 0;

	}

}



function paymentconfirm($regno){

	$rec=recSearch2($regno);

	switch($rec[22]){

		case "OLD":

		if(payverify($regno,"UNIMED SCHOOL FEES")>=1 && payverify($regno,"UNIMED INTERNET ACCESS")>=1){

			$status=1;	

		}else{

			$status=0;	

		}

		break;

		case "RPT":

		if(payverify($regno,"UNIMED SCHOOL FEES")>=1 && payverify($regno,"UNIMED INTERNET ACCESS")>=1){

			$status=1;	

		}else{

			$status=0;	

		}

		break;

		case "NEW":

		if(payverify($regno,"UNIMED ACCEPTANCE FEE")>=1){

			$status=1;	

		}else{

			$status=0;

		}

		break;

		default:

		$status=0;

		break;

	}

	return $status;

}

/*

function payverify1($reg,$paytype,$session){

	connet();

	$ret=result("SELECT * FROM paymentinvoice WHERE matricno='$reg' AND feetype='$paytype' AND feestatus='PAID' AND session='$session'");	

	$s_row=mysql_num_rows($ret);

	return $s_row;

}

*/



function payverify1($reg,$paytype,$session="2017/2018"){

	connet();

	$ret=result("SELECT * FROM paymentinvoice WHERE matricno='$reg' AND feetype='$paytype' AND feestatus='PAID' AND session='$session'");	

	$s_row=mysql_num_rows($ret);

	return $s_row;

}

function nusPhysio($dept,$level){
	if($level==500 && ($dept=="Nursing Science" || $dept=="Physiotherapy")){
		return 1;
	}else{
		return 0;
	}
}

function paymentconfirm1($regno){

	$rec=recSearch2($regno);

	if(($rec[18]>=2018 || $rec[19]<=300 || nusPhysio($rec[21],$rec[19])==1) && payverify($regno,"UNIMED SCHOOL FEES")>=1){

		$status=1;

	}elseif($rec[18]<2018 && payverify($regno,"UNIMED SCHOOL FEES")>=1 && payverify($regno,"UNIMED INTERNET ACCESS")>=1){

		$status=1;

	}else{

		$status=0;

	}

	/*

	switch($rec[22]){

		case "OLD":	

		if(payverify($regno,"UNIMED SCHOOL FEES")>=1 && payverify($regno,"UNIMED INTERNET ACCESS")>=1){

			$status=1;	

		}else{

			$status=0;	

		}

		break;

		case "RPT":

		if(payverify($regno,"UNIMED SCHOOL FEES")>=1 && payverify($regno,"UNIMED INTERNET ACCESS")>=1){

			$status=1;	

		}else{

			$status=0;	

		}

		break;

		case "NEW":

		if(payverify($regno,"UNIMED SCHOOL FEES")>=1){

			$status=1;	

		}else{

			$status=0;

		}

		break;

		default:

		$status=0;

		break;

	}

	*/

	return $status;

}



function PayParam($paycat){
	switch($paycat){

		case "UNIMED ACCEPTANCE FEE":

			$param=array("7007139952","AFFb1TJT7QxOLM0S");

			break;

		case "UNIMED ACCOMMODATION FEE":

			$param=array("7007139954","mfsBKcI0z1nIOxs1");

			break;

		case "UNIMED INTERNET ACCESS":

			$param=array("7007139955","jMnAglNILfHzotEh");

			break;

		case "UNIMED PRE-DEGREE APPLICATION":

			$param=array("7007139956","51hYJU5diQMuJkMl");

			break;
		case "UNIMED TRANSFER FEE":

			$param=array("7007139958","I8nnhxgsgxkl05J5");

			break;
		case "UNIMED CHANGE OF COURSE FEE":

			$param=array("7007139959","9d8hra0MSRRPQYaB");

			break;
		case "UNDERGRADUATE AND POSTGRADUATE DIPLOMA ACCEPTANCE":

			$param=array("7007139982","CyjZ9KRUrUADxdEI");

			break;
		case "UNDERGRADUATE AND POSTGRADUATE DIPLOMA SCHOOL FEES":

			$param=array("7007139985","3pXb23Duvik9dLIA");

			break;
		case "UNIMED LATE REGISTRATION FEE":

			$param=array("7007139959","9d8hra0MSRRPQYaB");

			break;
		case "CAR-UNIMED CONFERENCE":

			$param=array("7007140010","42u0p5GsU7qhEm4B");

			break;
			
		default:

			$param=array("","");

		break;

	}

	return $param;

}



function deptcode($dept){
	switch($dept){
		case "Biological Sciences(Animal and Environmental)":
			$code="BIO";
			break;
		case "Biological Sciences(Microbiology)":
			$code="BIO";
			break;
		case "Biological Sciences(Plant Biology and Biotechnology)":
			$code="BIO";
			break;	
		case "Chemistry":
			$code="CHE";
			break;
		case "Mathematics":
			$code="MTH";
			break;
		case "Physics(Electronics Physics)":
			$code="PHY";
			break;
		case "Physics":
			$code="PHY";
			break;
		case "Anatomy":
			$code="ANA";
			break;
		case "Biochemistry":
			$code="BCM";
			break;
		case "Physiology":
			$code="PHS";
			break;
		case "Medicine and Surgery":
			$code="MED";
			break;
		case "Dentistry":
			$code="DEN";
			break;
		case "Nursing Science":
			$code="NUS";
			break;
		case "Physiotherapy":
			$code="PHT";
			break;
		case "Medical Laboratory Science":
			$code="MLS";
			break;
		case "Human Nutrition and Dietetics":
			$code="HND";
			break;
		case "Radiography and Radiation Science":
			$code="RGR";
			break;
		case "Prosthetics and Orthotics":
			$code="POT";
			break;
		case "Community Health Science":
			$code="CHS";
			break;
		case "Environmental Health Science":
			$code="EHS";
			break;
		case "Computer Science":
			$code="CSC";
			break;
		case "Environmental Management and Toxicology":
			$code="EMT";
			break;
		case "Food Science":
			$code="FSC";
			break;
		case "Information Technology":
			$code="IFT";
			break;
		case "Science Laboratory Technology":
			$code="SLT";
			break;
		case "Statistics":
			$code="STA";
			break;
		default:
			$code="";
		break;
	}
	return $code;
}



function genMatric3(){

	connet();

	$courses=array("Biological Sciences(Animal and Environmental)","Biological Sciences(Microbiology)","Biological Sciences(Plant Biology and Biotechnology)","Chemistry","Mathematics","Physics(Electronics Physics)","Physics(Medical Physics)","Anatomy","Biochemistry","Physiology","Medicine and Surgery","Dentistry","Nursing Science","Physiotherapy","Medical Laboratory Science");

	$no=695;

	$tno=1;

	foreach($courses as $v){

		$ckey=deptcode($v);

		$sql=result("select * from matric_table where dept='$v' ORDER BY level, surname, onames");

		while($rec=mysql_fetch_array($sql)){

			$matno=$ckey.'/17/'.sprintf("%04d",$no);

			$age=intval(date('Y', time() - strtotime($rec[8]))) - 1970;

			result("update matric_table set matricno='$matno', age='$age', matno='$no' where regno='$rec[1]'");

			$no+=1;

			$tno+=1;

		}

	}

	return $tno;

}



function genMatric2(){

	connet();

	$courses=array("Biological Sciences(Animal and Environmental)","Biological Sciences(Microbiology)","Biological Sciences(Plant Biology and Biotechnology)","Chemistry","Mathematics","Physics(Electronics Physics)","Physics(Medical Physics)","Anatomy","Biochemistry","Physiology","Medicine and Surgery","Dentistry","Nursing Science","Physiotherapy","Medical Laboratory Science");

	

	//$code=array("BIO","BIO","CHE","","","","","","","","","","");

	//$allcomb=array_combine($code,$courses);

	$no=1207;

	$tno=1;

	foreach($courses as $v){

		$ckey=deptcode($v);

		$sql=result("SELECT regno, surname, onames, state, lg, sex, dob, level, dept FROM studentinformation WHERE ayear=2017 AND dept='$v' AND regno NOT IN (SELECT regno FROM matric_table) AND regno IN (SELECT matricno FROM `paymentinvoice` WHERE `feestatus`='PAID' AND `feetype`='UNIMED SCHOOL FEES' AND session='2017/2018' AND matricno NOT LIKE '%/%') ORDER BY level, surname, onames");

		while($rec=mysql_fetch_array($sql)){

			$matno=$ckey.'/17/'.sprintf("%04d",$no);

			$age=intval(date('Y', time() - strtotime($rec[6]))) - 1970;

			//result("update matric_table set matricno='$matno', age='$age', matno='$no' where regno='$rec[1]'");

			$val=array($rec[0],$matno,$rec[1],$rec[2],$rec[3],$rec[4],$rec[5],$rec[6],$age,$rec[7],$v,$no);

			input($val,"matric_table");

			$no+=1;

			$tno+=1;

		}

	}

	return $tno;

}



function genMatric(){

	connet();

	$courses=array("Biological Sciences(Animal and Environmental)","Biological Sciences(Microbiology)","Biological Sciences(Plant Biology and Biotechnology)","Chemistry","Mathematics","Physics(Electronics Physics)","Anatomy","Biochemistry","Physiology","Medicine and Surgery","Dentistry","Nursing Science","Physiotherapy","Medical Laboratory Science");

	

	//$code=array("BIO","BIO","CHE","","","","","","","","","","");

	//$allcomb=array_combine($code,$courses); AND regno NOT IN (SELECT regno FROM matric_table) 

	$no=2332;

	$tno=0;
	$faculties=array("Sciences","Basic Medical Sciences","Clinical Sciences","Dentistry","Nursing Science","Medical Rehabilitation","Allied Health Sciences","Public Health");
	foreach($faculties as $f){
		$query=result("SELECT dept FROM faculties_dept WHERE faculty='$f' ORDER BY dept");
		while($ret=mysql_fetch_array($query)){
			$ckey=deptcode($ret[0]);
			$sql=result("SELECT regno, UCASE(surname), UCASE(onames), state, lg, sex, dob, level, dept FROM studentinformation WHERE ayear=2020 AND dept='$ret[0]' AND regno IN (SELECT matricno FROM paymentinvoice WHERE feetype='UNIMED SCHOOL FEES' AND session='2020/2021' AND feestatus='PAID') ORDER BY level, surname, onames");

			while($rec=mysql_fetch_array($sql)){

				$matno=$ckey.'/20/'.sprintf("%04d",$no);

				$age=intval(date('Y', time() - strtotime($rec[6]))) - 1970;

				//result("update matric_table set matricno='$matno', age='$age', matno='$no' where regno='$rec[1]'");

				$val=array($rec[0],$matno,$rec[1],$rec[2],$rec[3],$rec[4],$rec[5],$rec[6],$age,$rec[7],$f,$ret[0],$no);

				input($val,"matric_table");

				$no+=1;

				$tno+=1;
			}

		}

	}

	return $tno;

}



function csvCourseReg($cos,$session,$sem){ //populate students that registered for a course for staff portal

	connet();

	$q=result("SELECT distinct(matricno) FROM course_reg WHERE session='$session' AND semester='$sem' AND (course1 like '$cos%' || course2 like '$cos%' || course3 like '$cos%' || course4 like '$cos%' || course5 like '$cos%' || course6 like '$cos%' || course7 like '$cos%' || course8 like '$cos%' || course9 like '$cos%' || course10 like '$cos%' || course11 like '$cos%' || course12 like '$cos%' || course13 like '$cos%' || course14 like '$cos%' || course15 like '$cos%' || course16 like '$cos%' || course17 like '$cos%' || course18 like '$cos%' || course19 like '$cos%' || course20 like '$cos%') AND matricno<>'' AND app='APPROVED' ORDER BY dept, matricno");

	$no=mysql_num_rows($q);

	if($no==0){

		return 0;

	}else{

		$sn=0;

		$cc=1;

		while($rec=mysql_fetch_array($q)){

			//if(isset($ca[$cos]) && isset($ex[$cos])){

			//if(array_key_exists($cos,$ca) && array_key_exists($cos,$ex) && array_key_exists($cos,$tt)){

				$sn+=1;

				$cc+=1;

				$cal='=SUM(C'.$cc.'+D'.$cc.')';

				$totrec[]=array($sn,strtoupper($rec[0]),0,0,$cal);

			//}

			

		}

		return $totrec;

	}

}



function Attendance($cos,$session,$sem){ //populate students that registered for a course for staff portal

	connet();

	$q=result("SELECT distinct(matricno) FROM course_reg WHERE session='$session' AND semester='$sem' AND (course1 like '$cos%' || course2 like '$cos%' || course3 like '$cos%' || course4 like '$cos%' || course5 like '$cos%' || course6 like '$cos%' || course7 like '$cos%' || course8 like '$cos%' || course9 like '$cos%' || course10 like '$cos%' || course11 like '$cos%' || course12 like '$cos%' || course13 like '$cos%' || course14 like '$cos%' || course15 like '$cos%' || course16 like '$cos%' || course17 like '$cos%' || course18 like '$cos%' || course19 like '$cos%' || course20 like '$cos%') AND matricno<>'' AND app='APPROVED' ORDER BY dept, matricno");

	$no=mysql_num_rows($q);

	if($no==0){

		echo '<tr>

		<td colspan="6" width="100%">

		No Registrant for the selected course

		</td>

		<tr>';

	}else{

		$sn=0;

		echo '

		<tr>

		<td colspan="6" width="100%">

		<table width="100%" cellpadding="0" cellspacing="0" border="1" bordercolor="#999" style="font-size: 13px;">

		<tr>

					<td align="left" width="5%" style="padding:5px;"><b>S/N</b></td>

					<td align="left" width="15%" style="padding:5px;"><b>MATRIC NO</b></td>

					<td align="left" width="50%" style="padding:5px;"><b>NAME</b></td> 				 

					<td align="left" width="15%" style="padding:5px;"><b>SIGN IN</b></td>

					<td align="left" width="15%" style="padding:5px;"><b>SIGN OUT</b></td>

				</tr>';

		while($rec=mysql_fetch_array($q)){

				$sn+=1;

			$sdata=recSearch2($rec[0]);

			echo '<tr>

				<td align="left" width="5%" style="padding:5px;">'.$sn.'</td>

				<td align="left" width="15%" style="padding:5px;">'.strtoupper($rec[0]).'</td>

				<td align="left" width="50%" style="padding:5px;">'.strtoupper($sdata[3]).', '.strtoupper($sdata[4]).'</td> 				 

				<td align="left" width="15%" style="padding:5px;">&nbsp;</td>

				<td align="left" width="15%" style="padding:5px;">&nbsp;</td>

			</tr>';

			

		}

		echo '

	</table>

	<td>

	</tr>

	';

	}

}

	

function getRecs2($table,$field1,$val1,$field2,$val2){

	connet();

	$checkQuery="SELECT * FROM $table WHERE $field1='$val1' AND $field2='$val2' limit 0,1";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows==1){

		return $recs;

	}else{

		return 0;

	}

}



function searchRecord($table,$field,$code){

connet();

	$query="SELECT * FROM $table WHERE $field='$code'";

	$queryResult=result($query);

	$s_row=mysql_num_rows($queryResult);

	return $s_row;

}



function retInvoice($regno,$paytype){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE matricno='$regno' AND feetype='$paytype' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	return $returnedRows;

}

function retInvoiceP($regno,$paytype){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE matricno='$regno' AND feetype='$paytype' AND feestatus='PAID' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	return $returnedRows;

}

function retInvoice3($regno,$paytype,$session){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE matricno='$regno' AND feetype='$paytype' AND session='$session' AND feestatus='PAID' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	return $returnedRows;

}



function retInvoiceAcc($transid){

	connet();
	//$checkQuery="SELECT * FROM paymentinvoice WHERE transactionid='$transid' AND status<>'PENDING PAYMENT'";
	$checkQuery="SELECT * FROM paymentinvoice WHERE transactionid='$transid' AND feestatus='UNPAID'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	return $returnedRows;

}

function retInvoice2($transid){

	connet();
	//$checkQuery="SELECT * FROM paymentinvoice WHERE transactionid='$transid' AND status<>'PENDING PAYMENT'";
	$checkQuery="SELECT * FROM paymentinvoice WHERE transactionid='$transid' AND status<>'PENDING PAYMENT'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	return $returnedRows;

}


function facultyPage($fin,$din,$no){

	connet();

	echo '<div id="dog'.$no.'" class="tabcontent" style="text-align:justify; font-weight:normal;"> ';

		$sql1=result("SELECT details FROM faculty_info WHERE header=$no AND faculty=$fin AND dept=$din");

		list($details1)=mysql_fetch_array($sql1);

		if($details1 !=""){ echo stripslashes($details1); }else{ echo " "; }					

     echo '<br /><br />

     </div>';

}



function facultyNews($fin,$din,$no){

	connet();

	//

	echo '<div id="dog'.$no.'" class="tabcontent" style="text-align:justify; font-weight:normal;"> ';

		$sql1=result("SELECT * FROM story WHERE fac=$fin AND dept=$din ORDER BY id DESC");

		while($ret1=mysql_fetch_array($sql1)){

			echo '<div class="quotes3">';

			echo '<span style="color:#8D1516;">'.$ret1[1].'</span>';

			echo '<img src="photospeaks/'.$ret1[4].'" widith="745" height="420" />';

			echo stripslashes($ret1[2]);

			echo '<div class="nnewsm1" style="padding-bottom: 8px;">&rArr;

					<a href="unimedNews.php?nid='.$ret1[0].'" target="_blank">Read more</a>

				</div>';

			echo '</div>';

		}					

		echo '<br /><br />

		</div>';

}



function homeNews($st){

	$sql=result("SELECT id, headline, caption, pix FROM story WHERE cat_req<>'DEPT NEWS' ORDER BY id DESC LIMIT $st,1");

	list($id,$headline,$caption,$pix)=mysql_fetch_array($sql);

	echo '<div class="nquotes" style="font-size: 17px;">

		<img src="photospeaks/'.$pix.'" height="160" width="265" />

		</div>

		<div class="nquotes2">

		<div style="padding:7px 10px; height:220px; text-align: left;">

		<span style="font-size: 20px; color: #333; font-weight: bold;">';

		$head=stripslashes($headline);

		$hlen=strlen($head);

		if($hlen>120){

			echo substr($head, 0, 120)."...";

		}else{

			echo $head;

		}

		echo '</span>';

		if($caption!="" && $hlen<120){

			$cap=stripslashes($caption);

			$clen=strlen($cap);

			$blen=120-$hlen;

			echo "<br />".substr($cap, 0, $blen)."...";

		}

		echo '<br />

			<div class="nnewsm1">&rArr;

			<a href="unimedNews.php?nid='.$id.'" target="_blank">Read more</a>

			</div>

		</div>

		</div>';

}



function homeNews2($st){

	$sql=result("SELECT id, headline, caption, pix FROM story ORDER BY id DESC LIMIT $st,1");

	list($id,$headline,$caption,$pix)=mysql_fetch_array($sql);

	echo '<div class="nquotes3">

		<div style="padding:5px; height:110px; text-align: justify;">

		<img src="photospeaks/'.$pix.'" height="85" width="125" align="left" hspace="3" vspace="3" style="padding-right:5px;" />';

		$head=stripslashes($headline);

		$hlen=strlen($head);

		echo '<a href="unimedNews.php?nid='.$id.'" target="_blank" style="font-size:14px;">';

		if($hlen>85){

			echo substr($head, 0, 85)."...";

		}else{

			echo $head;

		}

		echo '</a>';

		echo '

		</div>

		</div>';

}





function servicesPage($fin,$din,$no,$deptname){

	connet();

	switch($no){

		case 1:

		$title="WELCOME!";

		break;

		case 3:

		$title="OBJECTIVES";

		break;

		case 5:

		$title="STAFF DIRECTORY";

		break;

		case 2:

		$title="NEWS AND EVENTS";

		break;

		default:

			$exsql=result("SELECT * FROM dept_extra_menu WHERE facid=$fin AND deptid=$din AND menuid=$no ORDER BY id DESC LIMIT 0,1");

			$mno=mysql_num_rows($exsql);

			if($mno>=1){

				$mret=mysql_fetch_row($exsql);

				$title=$mret[5];

			}else{

				$title="UNKNOWN";

			}

		break;

	}

	echo '

	<div style="color: #8D1516; font-size: 14px;"><strong>'.$title.'</strong></div>';

	if($no==5){

		$sql1=result("SELECT * FROM employment WHERE category=8 AND assigned_to='$deptname' ORDER BY grade DESC, step DESC, staffid ASC, id ASC");

		if(mysql_num_rows($sql1)>0){

			while($ret1=mysql_fetch_array($sql1)){

				//beginning

				$bio1=getRecs("biodata","staffid",$ret1['staffid']);

				echo '<div class="quotes4" style="color:#8D1516;">

					<table width="100%">

					<tr>';

				echo '<td><div style="background-image:url(images/minpixtemp2.jpg); background-repeat:no-repeat; height: 151px; width: 138px;">

					<img src="staff/profile/'.$bio1['passport'].'" height="128" width="115" align="left" hspace="2" vspace="2" style="padding:10px 5px 5px 10px;"/>

				</div></td><td valign="top" align="left" width="80%">

				<table style="line-height:2em;" width="100%">

				<tr style="background-color:#eee;">

				<td style="color: #005BAA; padding: 0px 5px;" width="25%">NAME:</td>

				<td style="color: #8D1516; padding: 0px 5px;">'.ucfirst(strtolower($bio1['lname'])).' '.ucfirst(strtolower($bio1['fname'])).' '.ucfirst(strtolower($bio1['oname'])).'</td>

				</tr>

				<tr style="background-color:#fff;">

				<td style="color: #005BAA; padding: 0px 5px;">QUALIFICATION:</td>

				<td style="color: #8D1516; padding: 0px 5px;">'.getDeg($bio1['staffid']).'</td>

				</tr>

				<tr style="background-color:#fff;">

				<td style="color: #005BAA; padding: 0px 5px;">DESIGNATION:</td>

				<td style="color: #8D1516; padding: 0px 5px;">'.$ret1['designation'].'</td>

				</tr>

				<tr style="background-color:#eee;">

				<td style="color: #005BAA; padding: 0px 5px;">E-MAIL:</td>

				<td style="color: #8D1516; padding: 0px 5px;">'.$bio1['email'].'</td>

				</tr>

				<tr style="background-color:#fff;">

				<td style="color: #005BAA; padding: 0px 5px;" colspan="2">

					<span class="fmenu">

						<a href="staff/profile.php?sid='.$bio1['id'].'" target="_blank">&rarr;&nbsp;View Details</a>

					</span>

				</td>

				</tr>

				</table>

				</td></tr>

				</table>';	

					echo '

				</div>';

					//end	

			}	

		}

	}elseif($no==2){

		$sql1=result("SELECT * FROM story WHERE fac=$fin AND dept=$din ORDER BY id DESC");

		while($ret1=mysql_fetch_array($sql1)){

			echo '<div class="quotes3" style="color: #666; text-align: justify; padding:10px; height: 245px;">';

			echo '<span style="color:#005BAA; font-size:16px; font-weight:bold;">'.$ret1[1].'</span><br />';

			echo '<img src="photospeaks/'.$ret1[4].'" width="500" height="210" align="left" hspace="5" vspace="5" style="padding-right: 10px; padding-top:5px;" />';

			echo stripslashes($ret1[2]);

			echo '<div class="nnewsm1" style="padding-bottom: 8px;">&rArr;

					<a href="unimedNews.php?nid='.$ret1[0].'" target="_blank" style="font-size:14px;">Read more</a>

				</div>';

			echo '</div>';

		}					

	}else{

		echo ' 

	<div style="line-height: 1.8em; font-size: 13px; text-align: justify;">';

	$sql1=result("SELECT details FROM faculty_info WHERE header=$no AND faculty=$fin AND dept=$din");

		list($details1)=mysql_fetch_array($sql1);

		if($details1 !=""){ echo stripslashes($details1); }else{ echo " "; }					

     echo '<br /><br />';

	echo '</div>';	

	}

}

function getDeg($staffid){

	connet();

	$sql=result("SELECT DISTINCT(degree) FROM education WHERE staffid='$staffid'");

	if(mysql_num_rows($sql)>0){

		$deg=array();

		while($ret=mysql_fetch_array($sql)){

			$deg[]=$ret[0];

		}

		$rec=implode(",",$deg);

	}else{

		$rec="";

	}

	return $rec;

}



function facultyPageStaff($fin,$din,$no,$deptname){

	connet();

	echo '<div id="dog'.$no.'" class="tabcontent" style="text-align:justify; font-weight:normal;"> ';

		//$sql1=result("SELECT details FROM faculty_info WHERE header=$no AND faculty=$fin AND dept=$din");

		//list($details1)=mysql_fetch_array($sql1);

		//if($details1 !=""){ echo stripslashes($details1); }else{ echo " "; }

	$sql=result("SELECT * FROM employment WHERE category<>8 AND designation NOT LIKE '%HOD%' AND assigned_to='$deptname' ORDER BY grade DESC, step DESC");

	$sqlhod=result("SELECT * FROM employment WHERE designation LIKE '%HOD%' AND assigned_to='$deptname' ORDER BY grade DESC, step DESC");

	if(mysql_num_rows($sql)>0 || mysql_num_rows($sqlhod)>0){

		echo '<div class="depthead">FACULTY</div>';

		if(mysql_num_rows($sqlhod)>0){

			while($rethod=mysql_fetch_array($sqlhod)){ //dept staff begin

				//beginning

				$bioh=getRecs("biodata","staffid",$rethod['staffid']);

				echo '<div class="quotes4" style="color:#8D1516;">

					<table width="100%">

					<tr>';

				echo '<td><div style="background-image:url(images/minpixtemp2.jpg); background-repeat:no-repeat; height: 151px; width: 138px;">

					<img src="staff/profile/'.$bioh['passport'].'" height="128" width="115" align="left" hspace="2" vspace="2" style="padding:10px 5px 5px 10px;"/>

				</div></td><td valign="top" align="left" width="80%">

				<table style="line-height:2em;" width="100%">

				<tr style="background-color:#eee;">

				<td style="color: #005BAA; padding: 0px 5px;" width="25%">NAME:</td>

				<td style="color: #8D1516; padding: 0px 5px;">'.ucfirst(strtolower($bioh['lname'])).' '.ucfirst(strtolower($bioh['fname'])).' '.ucfirst(strtolower($bioh['oname'])).'</td>

				</tr>

				<tr style="background-color:#fff;">

				<td style="color: #005BAA; padding: 0px 5px;">QUALIFICATION:</td>

				<td style="color: #8D1516; padding: 0px 5px;">'.getDeg($bioh['staffid']).'</td>

				</tr>

				<tr style="background-color:#fff;">

				<td style="color: #005BAA; padding: 0px 5px;">DESIGNATION:</td>

				<td style="color: #8D1516; padding: 0px 5px;">'.$rethod['designation'].'</td>

				</tr>

				<tr style="background-color:#eee;">

				<td style="color: #005BAA; padding: 0px 5px;">E-MAIL:</td>

				<td style="color: #8D1516; padding: 0px 5px;">'.$bioh['email'].'</td>

				</tr>

				<tr style="background-color:#fff;">

				<td style="color: #005BAA; padding: 0px 5px;" colspan="2">

					<span class="fmenu">

						<a href="staff/profile.php?sid='.$bioh['id'].'" target="_blank">&rarr;&nbsp;View Details</a>

					</span>

				</td>

				</tr>

				</table>

				</td></tr>

				</table>';	

					echo '

				</div>';

					//end	

			} //dept staff end	

		}

		while($ret=mysql_fetch_array($sql)){ //dept staff begin

			//beginning

			$bio=getRecs("biodata","staffid",$ret['staffid']);

			echo '<div class="quotes4" style="color:#8D1516;">

				<table width="100%">

				<tr>';

			echo '<td><div style="background-image:url(images/minpixtemp2.jpg); background-repeat:no-repeat; height: 151px; width: 138px;">

				<img src="staff/profile/'.$bio['passport'].'" height="128" width="115" align="left" hspace="2" vspace="2" style="padding:10px 5px 5px 10px;"/>

			</div></td><td valign="top" align="left" width="80%">

			<table style="line-height:2em;" width="100%">

			<tr style="background-color:#eee;">

			<td style="color: #005BAA; padding: 0px 5px;" width="25%">NAME:</td>

			<td style="color: #8D1516; padding: 0px 5px;">'.ucfirst(strtolower($bio['lname'])).' '.ucfirst(strtolower($bio['fname'])).' '.ucfirst(strtolower($bio['oname'])).'</td>

			</tr>

			<tr style="background-color:#fff;">

			<td style="color: #005BAA; padding: 0px 5px;">QUALIFICATION:</td>

			<td style="color: #8D1516; padding: 0px 5px;">'.getDeg($bio['staffid']).'</td>

			</tr>

			<tr style="background-color:#fff;">

			<td style="color: #005BAA; padding: 0px 5px;">DESIGNATION:</td>

			<td style="color: #8D1516; padding: 0px 5px;">'.$ret['designation'].'</td>

			</tr>

			<tr style="background-color:#eee;">

			<td style="color: #005BAA; padding: 0px 5px;">E-MAIL:</td>

			<td style="color: #8D1516; padding: 0px 5px;">'.$bio['email'].'</td>

			</tr>

			<tr style="background-color:#fff;">

			<td style="color: #005BAA; padding: 0px 5px;" colspan="2">

				<span class="fmenu">

					<a href="staff/profile.php?sid='.$bio['id'].'" target="_blank">&rarr;&nbsp;View Details</a>

				</span>

			</td>

			</tr>

			</table>

			</td></tr>

			</table>';	

				echo '

			</div>';

				//end	

		} //dept staff end	

	}

	

	$sql1=result("SELECT * FROM employment WHERE category=8 AND assigned_to='$deptname' ORDER BY grade DESC, step DESC");

	if(mysql_num_rows($sql1)>0){

		echo '<div class="depthead" style="padding-top: 10px;">STAFF</div>';

		while($ret1=mysql_fetch_array($sql1)){

			//beginning

			$bio1=getRecs("biodata","staffid",$ret1['staffid']);

			echo '<div class="quotes4" style="color:#8D1516;">

				<table width="100%">

				<tr>';

			echo '<td><div style="background-image:url(images/minpixtemp2.jpg); background-repeat:no-repeat; height: 151px; width: 138px;">

				<img src="staff/profile/'.$bio1['passport'].'" height="128" width="115" align="left" hspace="2" vspace="2" style="padding:10px 5px 5px 10px;"/>

			</div></td><td valign="top" align="left" width="80%">

			<table style="line-height:2em;" width="100%">

			<tr style="background-color:#eee;">

			<td style="color: #005BAA; padding: 0px 5px;" width="25%">NAME:</td>

			<td style="color: #8D1516; padding: 0px 5px;">'.$bio1['lname'].', '.$bio1['fname'].' '.$bio1['oname'].'</td>

			</tr>

			<tr style="background-color:#fff;">

			<td style="color: #005BAA; padding: 0px 5px;">QUALIFICATION:</td>

			<td style="color: #8D1516; padding: 0px 5px;">'.getDeg($bio1['staffid']).'</td>

			</tr>

			<tr style="background-color:#fff;">

			<td style="color: #005BAA; padding: 0px 5px;">DESIGNATION:</td>

			<td style="color: #8D1516; padding: 0px 5px;">'.$ret1['designation'].'</td>

			</tr>

			<tr style="background-color:#eee;">

			<td style="color: #005BAA; padding: 0px 5px;">E-MAIL:</td>

			<td style="color: #8D1516; padding: 0px 5px;">'.$bio1['email'].'</td>

			</tr>

			<tr style="background-color:#fff;">

			<td style="color: #005BAA; padding: 0px 5px;" colspan="2">

				<span class="fmenu">

					<a href="staff/profile.php?sid='.$bio1['id'].'" target="_blank">&rarr;&nbsp;View Details</a>

				</span>

			</td>

			</tr>

			</table>

			</td></tr>

			</table>';	

				echo '

			</div>';

				//end	

		}	

	}

    echo ' </div>';

}



function facultyPageStaff1($fin,$din,$no){

	connet();

	echo '<div id="dog'.$no.'" class="tabcontent" style="text-align:justify; font-weight:normal;"> ';

		//$sql1=result("SELECT details FROM faculty_info WHERE header=$no AND faculty=$fin AND dept=$din");

		//list($details1)=mysql_fetch_array($sql1);

		//if($details1 !=""){ echo stripslashes($details1); }else{ echo " "; }

	

	//beginning

echo '<div class="quotes4" style="color:#8D1516;">

	<table width="100%">

	<tr>';

echo '<td><div style="background-image:url(images/minpixtemp2.jpg); background-repeat:no-repeat; height: 151px; width: 138px;">

	<img src="staffprofile/" height="128" width="115" align="left" hspace="2" vspace="2" style="padding:10px 5px 5px 10px;"/>

</div></td><td valign="top" align="left" width="80%">

<table style="line-height:2em;" width="100%">

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;" width="25%">NAME:</td>

<td style="color: #8D1516; padding: 0px 5px;">Chidozie E. Mbada</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">QUALIFICATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">B.Sc, M.Sc, Ph.D</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">DESIGNATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">Senior Lecturer</td>

</tr>

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;">E-MAIL:</td>

<td style="color: #8D1516; padding: 0px 5px;">cmbada@unimed.edu.ng</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;" colspan="2">

	<span class="fmenu">

		<a href="departments.php?din='.$rec[1].'&fin='.$fin.'">&rarr;&nbsp;View Details</a>

	</span>

</td>

</tr>

</table>

</td></tr>

</table>';	

	echo '

</div>';

	//end

	echo '<div class="quotes4" style="color:#8D1516;">

	<table width="100%">

	<tr>';

echo '<td><div style="background-image:url(images/minpixtemp2.jpg); background-repeat:no-repeat; height: 151px; width: 138px;">

	<img src="staffprofile/Prof. Balogun.jpg" height="128" width="115" align="left" hspace="2" vspace="2" style="padding:10px 5px 5px 10px;"/>

</div></td><td valign="top" align="left" width="80%">

<table style="line-height:2em;" width="100%">

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;" width="25%">NAME:</td>

<td style="color: #8D1516; padding: 0px 5px;">Emeritus Prof. J.A. Balogun</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">QUALIFICATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">B.Sc, M.Sc, Ph.D</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">DESIGNATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">Emeritus Professor</td>

</tr>

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;">E-MAIL:</td>

<td style="color: #8D1516; padding: 0px 5px;">jbalogun@csu.edu</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;" colspan="2">

	<span class="fmenu">

		<a href="departments.php?din='.$rec[1].'&fin='.$fin.'">&rarr;&nbsp;View Details</a>

	</span>

</td>

</tr>

</table>

</td></tr>

</table>';	

	echo '

</div>';

	echo '<div class="quotes4" style="color:#8D1516;">

	<table width="100%">

	<tr>';

echo '<td><div style="background-image:url(images/minpixtemp2.jpg); background-repeat:no-repeat; height: 151px; width: 138px;">

	<img src="staffprofile/" height="128" width="115" align="left" hspace="2" vspace="2" style="padding:10px 5px 5px 10px;"/>

</div></td><td valign="top" align="left" width="80%">

<table style="line-height:2em;" width="100%">

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;" width="25%">NAME:</td>

<td style="color: #8D1516; padding: 0px 5px;">Prof. B. O. A. Adegoke</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">QUALIFICATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">B.Sc, M.Sc, Ph.D</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">DESIGNATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">Professor</td>

</tr>

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;">E-MAIL:</td>

<td style="color: #8D1516; padding: 0px 5px;">aadegoke@unimed.edu.ng</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;" colspan="2">

	<span class="fmenu">

		<a href="departments.php?din='.$rec[1].'&fin='.$fin.'">&rarr;&nbsp;View Details</a>

	</span>

</td>

</tr>

</table>

</td></tr>

</table>';	

	echo '

</div>';

		echo '<div class="quotes4" style="color:#8D1516;">

		<table width="100%">

		<tr>';

echo '<td><div style="background-image:url(images/minpixtemp2.jpg); background-repeat:no-repeat; height: 151px; width: 138px;">

	<img src="staffprofile/Mr Adegbemigun.jpg" height="128" width="115" align="left" hspace="2" vspace="2" style="padding:10px 5px 5px 10px;"/>

</div></td><td valign="top" align="left" width="80%">

<table style="line-height:2em;" width="100%">

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;" width="25%">NAME:</td>

<td style="color: #8D1516; padding: 0px 5px;">Oluwafemi D. Adegbemigun</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">QUALIFICATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">B.Sc, M.Sc</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">DESIGNATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">Lecturer II</td>

</tr>

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;">E-MAIL:</td>

<td style="color: #8D1516; padding: 0px 5px;">oadegbemigun@unimed.edu.ng</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;" colspan="2">

	<span class="fmenu">

		<a href="departments.php?din='.$rec[1].'&fin='.$fin.'">&rarr;&nbsp;View Details</a>

	</span>

</td>

</tr>

</table>

</td></tr>

</table>';	

	echo '

</div>';

 echo '<br />';

echo '<div class="quotes4" style="color:#8D1516;">

	<table width="100%">

	<tr>';

echo '<td><div style="background-image:url(images/minpixtemp2.jpg); background-repeat:no-repeat; height: 151px; width: 138px;">

	<img src="staffprofile/Mr Francis.JPG" height="128" width="115" align="left" hspace="2" vspace="2" style="padding:10px 5px 5px 10px;"/>

</div></td><td valign="top" align="left" width="80%">

<table style="line-height:2em;" width="100%">

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;" width="25%">NAME:</td>

<td style="color: #8D1516; padding: 0px 5px;">Francis O. Fasuyi</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">QUALIFICATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">B.Sc, M.Sc</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;">DESIGNATION:</td>

<td style="color: #8D1516; padding: 0px 5px;">Lecturer II</td>

</tr>

<tr style="background-color:#eee;">

<td style="color: #005BAA; padding: 0px 5px;">E-MAIL:</td>

<td style="color: #8D1516; padding: 0px 5px;">ffasuyi@unimed.edu.ng</td>

</tr>

<tr style="background-color:#fff;">

<td style="color: #005BAA; padding: 0px 5px;" colspan="2">

	<span class="fmenu">

		<a href="departments.php?din='.$rec[1].'&fin='.$fin.'">&rarr;&nbsp;View Details</a>

	</span>

</td>

</tr>

</table>

</td></tr>

</table>';	

	echo '

</div>';

    echo ' </div>';

}



function eTranzactrec($regno){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE transactionid='$regno'";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	if($rows>=1){

		$recs=mysql_fetch_array($checkResult);

		return $recs;

	}else{

		return 0;

	}

}



function feeCheck($regno,$feetype,$session){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE matricno='$regno' AND feetype='$feetype' AND session='$session'";

	$checkResult=result($checkQuery);

	$s_row=mysql_num_rows($checkResult);

	return $s_row;

}



function feeCheckb($regno,$feetype,$sess=""){

	connet();

	if($sess==""){

		$session=getSession();

		$ses=$session[1];

	}else{

		$ses=$sess;

	}

	

	$checkQuery="SELECT * FROM paymentinvoice WHERE matricno='$regno' AND feetype='$feetype' AND session='$ses'";

	$checkResult=result($checkQuery);

	$s_row=mysql_num_rows($checkResult);

	return $s_row;

}



function feeCheckbb($regno,$feetype,$session){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE matricno='$regno' AND feetype='$feetype' AND session='$session' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$s_row=mysql_num_rows($checkResult);

	return $s_row;

}



function getInv($matricno,$feetype,$session){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE matricno='$matricno' AND feetype='$feetype' AND session='$session' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}

function getSumPaid($matricno,$feetype,$session){

	connet();

	$checkQuery="SELECT SUM(amount) FROM paymentinvoice WHERE matricno='$matricno' AND feetype='$feetype' AND session='$session' AND feestatus='PAID'";

	$checkResult=result($checkQuery);

	$rows=mysql_num_rows($checkResult);

	$recs=mysql_fetch_array($checkResult);

	if($rows>=1){

		return $recs;

	}else{

		return 0;

	}

}



function feeCheckc($regno,$feetype,$session){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE matricno='$regno' AND feetype='$feetype' AND session='$session' AND feestatus='PAID'";

	$checkResult=result($checkQuery);

	$s_row=mysql_num_rows($checkResult);

	return $s_row;

}



function feeCheckd($regno,$feetype,$session){

	connet();

	$checkQuery="SELECT * FROM paymentinvoice WHERE transactionid='$regno' AND feetype='$feetype' AND session='$session' AND feestatus='PAID'";

	$checkResult=result($checkQuery);

	$s_row=mysql_num_rows($checkResult);

	return $s_row;

}



function payType($pay){

	switch($pay){

	 case "UNIMED ACCEPTANCE FEE":

	 $paycode="ACP";

	 break;

	 case "UNIMED SCHOOL FEES":

	 $paycode="SCH";

	 break;

	 case "UNIMED ACCOMMODATION FEE":

	 $paycode="ACM";

	 break;

	 case "UNIMED DEVELOPMENT LEVY":

	 $paycode="DEV";

	 break;

	 case "UNIMED PRE-DEGREE APPLICATION":

	 $paycode="PAF";

	 break;

	 case "UNIMED PRE-DEGREE SCHOOL FEE":

	 $paycode="PSF";

	 break;

	 break;

	 case "UNIMED POST-UTME FEE":

	 $paycode="PUT";

	 break;

	 case "UNIMED POST-UTME PAST QUESTION":

	 $paycode="PPQ";

	 break;

	 case "UNIMED INTERNET ACCESS":

	 $paycode="INT";

	 break;

	 case "UNIMED PREDEGREE ACCEPTANCE":

	 $paycode="PAP";

	 break;

	 case "UNIMED PREDEGREE ACCOMMODATION":

	 $paycode="PAC";

	 break;

	 case "NURSING AND MIDWIFERY SCHOOL FEE":

	 $paycode="ISF";

	 break;

	 case "POST BASIC NURSING APPLICATION":

	 $paycode="PBN";

	 break;

	 case "POST BASIC NURSING SCHOOL FEES":

	 $paycode="PNS";

	 break;

	 case "POST BASIC NURSING ACCEPTANCE FEE":

	 $paycode="PNA";

	 break;

	 case "UNDERGRADUATE AND POSTGRADUATE DIPLOMA APPLICATION":

	 $paycode="UPD";

	 break;
	 case "UNIMED CHANGE OF COURSE FEE":

	 $paycode="COC";

	 break;
	 case "UNIMED TRANSFER FEE":

	 $paycode="IUT";

	 break;
	 case "UNDERGRADUATE AND POSTGRADUATE DIPLOMA ACCEPTANCE":

	 $paycode="UPA";

	 break;
	 case "UNDERGRADUATE AND POSTGRADUATE DIPLOMA SCHOOL FEES":

	 $paycode="UPS";

	 break;
	 case "CAR-UNIMED CONFERENCE":

	 $paycode="CUC";

	 break;
	 case "UNIMED LATE REGISTRATION FEE":

	 $paycode="LRF";

	 break;

	 default:

	 $paycode="N/A";

	 break;		

	}

	return $paycode;

}



function getTransactionId($regno,$payment){

	connet();

	//$recCheck=recSearch($regno);

	//$putmerec=getRecs("post_utme_basicinfo","regno",$regno);

	//if($payment=="UNIMED PRE-DEGREE APPLICATION" || $recCheck==1 || $putmerec!==0){  ///to check for student validity

		$tranID=payType($payment).sprintf("%010d",mt_rand());	

		if(searchRecord("paymentinvoice","transactionid",$tranID)==0){ //checking for existence of transaction id

			return $tranID;

		}else{

			$tranID=payType($payment).sprintf("%010d",mt_rand());

			return $tranID;

		}	

	//}

}



function postUTMEID(){

	connet();

	//$recCheck=recSearch($regno);  

	//if(($recCheck)==1){  ///to check for student validity

		$tranID="PUT".sprintf("%010d",mt_rand());	

		if(searchRecord("paymentinvoice","transactionid",$tranID)==0){ //checking for existence of transaction id

			return $tranID;

		}else{

			$tranID="PUT".sprintf("%010d",mt_rand());

			return $tranID;

		}	

	//}

}



function postUTMEID2(){

	connet();

	//$recCheck=recSearch($regno);  

	//if(($recCheck)==1){  ///to check for student validity

		$tranID="PPQ".sprintf("%010d",mt_rand());	

		if(searchRecord("paymentinvoice","transactionid",$tranID)==0){ //checking for existence of transaction id

			return $tranID;

		}else{

			$tranID="PUT".sprintf("%010d",mt_rand());

			return $tranID;

		}	

	//}

}



function hmbID(){

	connet();

	//$code=hmbCode($cat);

	$appno="HMB".sprintf("%010d",mt_rand());	

	if(searchRecord("hmb_recruitment","appno",$appno)==0){ //checking for existence of application id

		$rcode=$appno;

	}else{

		$appno="HMB".sprintf("%010d",mt_rand());

		$rcode=$appno;

	}

	return $rcode;

}



function TWSid(){

	connet();

	//$code=hmbCode($cat);

	$appno="TWS".substr(mt_rand(),0,5);	

	if(searchRecord("tworkshop","appno",$appno)==0){ //checking for existence of application id

		$rcode=$appno;

	}else{

		$appno="TWS".substr(mt_rand(),0,5);

		$rcode=$appno;

	}

	return $rcode;

}



function pdspID(){

	connet();

	//$code=hmbCode($cat);

	$appno="PDSP".substr(mt_rand(),0,5);	

	if(searchRecord("pre_deg_basicinfo","regno",$appno)==0){ //checking for existence of application id

		$rcode=$appno;

	}else{

		$appno="PDSP".substr(mt_rand(),0,5);

		$rcode=$appno;

	}

	return $rcode;

}

function carUNIMED(){

	connet();

	//$code=hmbCode($cat);

	$appno="CARUNIMED".substr(mt_rand(),0,5);	

	if(searchRecord("paymentinvoice","matricno",$appno)==0){ //checking for existence of application id

		$rcode=$appno;

	}else{

		$appno="CARUNIMED".substr(mt_rand(),0,5);

		$rcode=$appno;

	}

	return $rcode;

}

function trfID(){

	connet();

	//$code=hmbCode($cat);

	$appno="TRF".substr(mt_rand(),0,5);	

	if(searchRecord("transfer_basicinfo","regno",$appno)==0){ //checking for existence of application id

		$rcode=$appno;

	}else{

		$appno="TRF".substr(mt_rand(),0,5);

		$rcode=$appno;

	}

	return $rcode;

}



function ugd_pgdID(){

	connet();

	//$code=hmbCode($cat);

	$appno="UPGD".substr(mt_rand(),0,5);	

	if(searchRecord("paymentinvoice","matricno",$appno)==0){ //checking for existence of application id

		$rcode=$appno;

	}else{

		$appno="UPGD".substr(mt_rand(),0,5);

		$rcode=$appno;

	}

	return $rcode;

}





function pbnpID(){

	connet();

	//$code=hmbCode($cat);

	$appno="PBNP".substr(mt_rand(),0,5);	

	if(searchRecord("postbasicnus_basicinfo","regno",$appno)==0){ //checking for existence of application id

		$rcode=$appno;

	}else{

		$appno="PBNP".substr(mt_rand(),0,5);

		$rcode=$appno;

	}

	return $rcode;

}





function HMBmenu($pno){

	connet();

	$res=getRecs("hmb_recruitment","phone",$pno);

		echo '<tr style="background-color:#063958; color:#FF0;">	

				<td colspan="4" align="center" style="padding:5px;">

				<span class="smenu">

				';

				echo '&nbsp;<a href="basic_info.php">&raquo;Basic Record</a>&nbsp;|';

				if(searchRecord("hmb_recruitment","phone",$pno)>=1 && $res[1]!=""){

					echo '&nbsp;<a href="screening_slip.php" target="_blank">&raquo;Print Screening/Result Slip</a>&nbsp;|';

				}

				//if($res!=0 && $res[5]=='UTME'){

				//	echo '&nbsp;<a href="post_utme_result.php" target="_blank" style="color:#FF0;">&raquo;Post-UTME Result</a>&nbsp;|';

				//}

				echo '<a href="logout.php?from=hmb">&nbsp;&raquo;Logout</a>

				</span>

                </td>

			</tr>';	

}



function hmbAllocation($appno,$cat){

	connet();

	$sql=result("SELECT * FROM hmb_timetable WHERE cat='$cat' AND tno>ano ORDER BY id LIMIT 0,1");

	$no=mysql_num_rows($sql);

	if($no>=1){

		$ret=mysql_fetch_row($sql);

		result("UPDATE hmb_timetable SET ano=ano+1 WHERE id=$ret[0]");

		$srec=array($appno,$cat,$ret[2],$ret[3]);

		input($srec,"hmb_schedule");

	}

}



function pUTMEGrp($dept){
	switch($dept){
		case "Medicine and Surgery":
		$cat="GROUP 1";
		break;
		case "Dentistry":
		$cat="GROUP 1";
		break;
		case "Anatomy":
		$cat="GROUP 2";
		break;
		case "Biochemistry":
		$cat="GROUP 2";
		break;
		case "Physiology":
		$cat="GROUP 2";
		break;
		case "Nursing Science":
		$cat="GROUP 2";
		break;
		case "Physiotherapy":
		$cat="GROUP 2";
		break;
		case "Medical Laboratory Science":
		$cat="GROUP 2";
		break;
		case "Physics(Electronics Physics)":
		$cat="GROUP 2";
		break;
		case "Chemistry":
		$cat="GROUP 2";
		break;
		case "Biological Sciences(Animal and Environmental)":
		$cat="GROUP 2";
		break;
		case "Biological Sciences(Microbiology)":
		$cat="GROUP 2";
		break;
		case "Biological Sciences(Plant Biology and Biotechnology)":
		$cat="GROUP 2";
		break;
		case "Mathematics":
		$cat="GROUP 2";
		break;
		default:
		$cat="UNKNOWN";
		break;

	}

	return $cat;

}



function pUTMESchedule($appno,$dept){
	connet();
	$arec=getRecs("post_utme_acad_rec","regno",$appno);
	if(($arec[39]!="" || $arec[46]!="") && $arec[38]==0){
		$cat="GROUP 2";
	}else{
		$cat=pUTMEGrp($dept);
	}

	//$sql=result("SELECT * FROM post_utme_timetable WHERE cat='$cat' AND tno>ano ORDER BY id LIMIT 0,1");
	$sql=result("SELECT * FROM post_utme_timetable WHERE tno>ano ORDER BY id LIMIT 0,1");

	$no=mysql_num_rows($sql);

	if($no>=1){

		$ret=mysql_fetch_row($sql);

		result("UPDATE post_utme_timetable SET ano=ano+1 WHERE id=$ret[0]");

		$srec=array($appno,$cat,$ret[2],$ret[3],$dept);

		input($srec,"post_utme_schedule");

	}

}



function staffHotspot($staffid){

	connet();

	$srec=getRecs("unimedhotspotstaff","regno",$staffid);

	if($srec==0){

		$lrec=getRecs("unimedhotspotstaff","regno","");

		result("UPDATE unimedhotspotstaff SET regno='$staffid' WHERE id=$lrec[0]");

	}else{

		$lrec=$srec;

	}

	return $lrec;

}



function getStaffId(){

	connet();

		$staffID=STF.sprintf("%05d",mt_rand());	

		$staffID=substr($staffID,0,10);

		if(searchRecord("profile","staffid",$staffID)==0){ //checking for existence of staffid id

			return $staffID;

		}else{

			return 0;

		}	

}



function getOERId(){

	connet();

		$oerID="OER".sprintf("%05d",mt_rand());	

		$oerID=substr($oerID,0,10);

		if(searchRecord("unimed_oer","oerid",$oerID)==0){ //checking for existence of staffid id

			return $oerID;

		}else{

			$oerID="OER".sprintf("%05d",mt_rand());	

			$oerID=substr($oerID,0,10);

			return $oerID;

		}	

}



function clean($string) {

   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.



   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

}



function paymentbackup($matricno,$fee){

	connet();

	//if(strpos($matricno,"/")!==false){

	if(recSearch($matricno)>=1){

		$rec=recSearch2($matricno);

		$prec=array($rec[11],$rec[19],$rec[22],$rec[20]);

	}elseif(searchRecord("ipnme_basicinfo","regno",$matricno)>=1){

		$irec=getRecs("ipnme_basicinfo","regno",$user);

		$prec=array($irec[8],$irec[15],"OLD",$irec[14]);

	}else{

		$putmerec=getRecs("admissiontable","regno",$matricno);

		$prec=array($putmerec[4],$putmerec[9],"NEW",$putmerec[6]);

	}

	if(is_array($prec) && !empty($prec)){

		if($prec[0]=="ONDO"){ 

			$cit="IND";

		}else{ 

			$cit="NON"; 

		}



		switch($fee){

			case "UNIMED SCHOOL FEES":

				$checkQuery="SELECT amount FROM unimedfee WHERE fee='$fee' AND level='$prec[1]' AND cat='$prec[2]' AND citizenship='$cit' AND faculty='$prec[3]'";

				$checkResult=result($checkQuery);

				list($amtdue)=mysql_fetch_array($checkResult);

			break;

			case "UNIMED ACCEPTANCE FEE":

				$checkQuery="SELECT amount FROM unimedfee WHERE fee='$fee' AND faculty='$prec[3]'";

				$checkResult=result($checkQuery);

				list($amtdue)=mysql_fetch_array($checkResult);

			break;

			case "UNIMED DEVELOPMENT LEVY":

				$amtdue=devLevy($prec[3]);

			break;

			case "UNIMED ACCOMMODATION FEE":

				$amtdue=accommFee();

			break;

			case "UNIMED INTERNET ACCESS":

				$amtdue=intAccess();

			break;

			case "UNIMED PREDEGREE ACCEPTANCE":

				$amtdue=20000;

			break;

			case "UNIMED PREDEGREE ACCOMMODATION":

				$amtdue=100000;

			break;

			case "UNIMED PRE-DEGREE SCHOOL FEE":

				$amtdue=180000;

			break;

			case "NURSING AND MIDWIFERY SCHOOL FEE":

				$checkQuery="SELECT amount FROM unimedfee WHERE fee='$fee' AND level='$prec[1]' AND cat='$prec[2]' AND citizenship='$cit' AND faculty='$prec[3]'";

				$checkResult=result($checkQuery);

				list($amtdue)=mysql_fetch_array($checkResult);

			break;

			default:

				$amtdue=500000;

			break;

		}

	return $amtdue;	

	}

}



function payment($matricno,$fee){

	connet();

	//if(strpos($matricno,"/")!==false){

	if(recSearch($matricno)>=1){

		$rec=recSearch2($matricno);

		$prec=array($rec[11],$rec[19],$rec[22],$rec[20]);

	}elseif(searchRecord("ipnme_basicinfo","regno",$matricno)>=1){

		$irec=getRecs("ipnme_basicinfo","regno",$matricno);

		$prec=array($irec[8],$irec[15],"OLD",$irec[14]);

	}elseif(pbnpAdmit($matricno)!=0){

		$pret=getRecs("postbasicnus_deg_basicinfo","regno",$matricno);

		$prec=array("ONDO","PBNP","NEW","POST BASIC NURSING PROGRAMME");

	}elseif(searchRecord("admissiontableupdp","regno",$matricno)>=1){

		$pret=getRecs("updp_basicinfo","regno",$matricno);

		$prec=array("N/A","UPDP","N/A",$pret[14]);

	}else{

		$putmerec=getRecs("admissiontable","regno",$matricno);

		$prec=array($putmerec[4],$putmerec[9],"NEW",$putmerec[6]);

	}

	if(is_array($prec) && !empty($prec)){

		if($prec[0]=="ONDO"){ 

			$cit="IND";

		}else{ 

			$cit="NON"; 

		}



		switch($fee){

			case "UNIMED SCHOOL FEES":

				$checkQuery="SELECT amount, code FROM unimedfee WHERE fee='$fee' AND level='$prec[1]' AND cat='$prec[2]' AND citizenship='$cit' AND faculty='$prec[3]'";

				$checkResult=result($checkQuery);

				list($amtdue,$code)=mysql_fetch_array($checkResult);

			break;

			case "UNIMED ACCEPTANCE FEE":

				$checkQuery="SELECT amount, code FROM unimedfee WHERE fee='$fee' AND faculty='$prec[3]'";

				$checkResult=result($checkQuery);

				list($amtdue,$code)=mysql_fetch_array($checkResult);

			break;

			case "UNIMED DEVELOPMENT LEVY":

				$amtdue=devLevy($prec[3]);

			break;

			case "UNIMED ACCOMMODATION FEE":

				$checkQuery="SELECT amount, code FROM unimedfee WHERE fee='$fee'";

				$checkResult=result($checkQuery);

				list($amtdue,$code)=mysql_fetch_array($checkResult);

			break;

			case "UNIMED INTERNET ACCESS":

				$checkQuery="SELECT amount, code FROM unimedfee WHERE fee='$fee'";

				$checkResult=result($checkQuery);

				list($amtdue,$code)=mysql_fetch_array($checkResult);

			break;

			case "UNIMED PREDEGREE ACCEPTANCE":

				$amtdue=20000;

				$code="PD010001";

			break;

			case "UNIMED PREDEGREE ACCOMMODATION":

				$amtdue=100000;

			break;

			case "UNIMED PRE-DEGREE SCHOOL FEE":

				$amtdue=180000;

				$code="PD010002";

			break;

			case "NURSING AND MIDWIFERY SCHOOL FEE":

				$checkQuery="SELECT amount, code FROM unimedfee WHERE fee='$fee' AND level='$prec[1]' AND citizenship='$cit' AND faculty='$prec[3]'";

				$checkResult=result($checkQuery);

				list($amtdue,$code)=mysql_fetch_array($checkResult);

			break;
			
			case "UNDERGRADUATE AND POSTGRADUATE DIPLOMA ACCEPTANCE":
				$checkQuery="SELECT amount, code FROM unimedfee WHERE fee='$fee' AND level='$prec[1]' AND citizenship='N/A' AND faculty='$prec[3]'";

				$checkResult=result($checkQuery);

				list($amtdue,$code)=mysql_fetch_array($checkResult);

			break;
			case "UNDERGRADUATE AND POSTGRADUATE DIPLOMA SCHOOL FEES":
				$checkQuery="SELECT amount, code FROM unimedfee WHERE fee='$fee' AND level='$prec[1]' AND citizenship='N/A' AND faculty='$prec[3]'";

				$checkResult=result($checkQuery);

				list($amtdue,$code)=mysql_fetch_array($checkResult);

			break;

			case "POST BASIC NURSING ACCEPTANCE FEE":

				$amtdue=50000;

				$code="MZ054812";

			break;

			case "POST BASIC NURSING SCHOOL FEES":

				$amtdue=300000;

				$code="MA053703";

			break;

			default:

				$amtdue=500000;

				$code="N/A";

			break;

		}

		$val=array($code,$amtdue);

	return $val;	

	}

}



function loopID(){

	connet();

	$retN=0;

	$dat=date("Y-m-d");

	for($x=1;$x<=2000;$x++){

		$studID=getUserIdx();

		$checkQ="SELECT * FROM student_number3 WHERE student_id='$studID'";

		$res=result($checkQ);

		$resNo=mysql_num_rows($res);

		if($resNo==0){

			//input($studID,"student_number");

			$iq="insert into student_number values ('', '$studID', '$dat')";

			$insres=mysql_query($iq) or die(mysql_error());

			$retN +=1;

		}else{

			$retN +=0;

		}

	}

	

}



function sendSMS($pass,$receiver,$send,$mess){

/* Variables with the values to be sent. */

				$owneremail="leye4jesus@yahoo.com";

				$subacct="COEIKERE";

				$subacctpwd=$pass;

				$sendto=$receiver; /* destination number */

				$sender=$send; /* sender id */

				$msg=$mess; /* message to be sent */

				$msgt=0;

				/* create the required URL */

				$url = "http://www.smslive247.com/http/index.aspx?"

				. "cmd=sendquickmsg"

				. "&owneremail=" . UrlEncode($owneremail)

				. "&subacct=" . UrlEncode($subacct)

				. "&subacctpwd=" . UrlEncode($subacctpwd)

				. "&message=" . UrlEncode($msg)

				. "&sender=" . UrlEncode($sender)

				. "&sendto=" . UrlEncode($sendto)

				. "&msgtype=" . UrlEncode($msgt);

				/* call the URL */

				if ($f = @fopen($url, "r"))

				{

				$answer = fgets($f, 255);

				if (substr($answer, 0, 1) == "+")

				{

				echo "SMS to $dnr was successful.";

				}

				else

				{

				echo "occurred: [$answer].";

				}

				}

				else

				{

				echo "Error: URL could not be opened.";

				}	

	

}



function lateAuth($code){

connet();

	$query="SELECT * FROM student_number WHERE student_id='$code'";

	$queryResult=result($query);

	$s_row=mysql_fetch_array($queryResult);

	return $s_row;

}

/*

function getAreaList($state,$name,$id,$title){

connet();

	$query="SELECT * FROM state WHERE state='$state'";

	$queryResult=result($query);

	$recRet=mysql_num_rows($queryResult);

	$area= '<select name="'.$name.'" id="'.$id.'" class="propertySelect" style="width:125px;height:19px;font-size:10px;"><option value="" selected>Select LG</option>';

	if($recRet>0){

		while($recSet=mysql_fetch_array($queryResult)){

		$area.= '<option value="'.$recSet[1].'" title="'.$id.'">'.$recSet[1].'</option>';

		}

	}

	$area.= '</select>';

	echo $area;

}

*/



function getAreaList($state,$name){

connet();

		$query="select * from departments order by id ASC";

	$queryResult=result($query);

	$recRet=mysql_num_rows($queryResult);

	$area= '<select name="'.$name.'" class="propertySelect" style="width:145px;height:19px;font-size:10px;"><option value="" selected>Select</option>';

	if($recRet>0){

		while($recSet=mysql_fetch_array($queryResult)){

		$area.= '<option value="'.$recSet[1].'">'.$recSet[1].'</option>';

		}

	}

	$area.= '</select>';

	echo $area;

}



function validate_password($password,$min_char,$max_char){

	if (preg_match("/^.*(?=.{".$min_char.",".$max_char."})(?=.*\d)(?=.*[a-z]).*$/", $password)) {

		return 1;

	} else {

		return 0;

	}

}



function changePass($user,$old,$pass){

	connet();

	$pass=md5($pass);

	$old=md5($old);

	if(confirmPass($user,$old)==1){

		$updateQuery="UPDATE admin_table SET password='$pass' WHERE username='$user'";

		$updateResult=result($updateQuery);

		return 1;

	}else{

		return 0;

	}

}



function searchRecordx($school,$session){

connet();

	$query="SELECT * FROM matriculation WHERE school='$school' AND session='$session'";

	$queryResult=result($query);

	$s_row=mysql_num_rows($queryResult);

	return $s_row;

}



function getPinIdx(){

	$userid=microtime();

	$use=strtoupper(substr(md5($userid),2,12));

	return $use;

}



function loopPin($num){

	connet();

	$retN=0;

	$dat=date("Y-m-d");

	for($x=1;$x<=$num;$x++){

		$studID=getPinIdx();

		$resNo=searchRecord("accessscratchcardtable","PinNumber",$pinID);

		$resN=searchRecord("accessscratchcardtable","CardSerialNumber",$cardID);

		if($resNo==0 && $resN==0){

			//input($studID,"student_number");

			$iq="insert into pin_table values ('', '$studID', '$dat')";

			$insres=mysql_query($iq) or die(mysql_error());

			$retN +=1;

		}else{

			$retN +=0;

		}

	}

	return $retN;

}



function numGeneratorx($start,$range,$name,$val=""){

	$curYear=date("Y");

	$startYear=$start;

	$endYear=$startYear+$range;

	echo '<select name="'.$name.'" style="width:175px; font-size:13px; line-height:2em; height:25px;"><option value="">--Select--</option>';

	//$format=sprintf(%02d);

	for($i=$startYear;$i<$endYear;$i++){

		switch($i){

			default:

				echo '<option value="'.$i.'"'; if($i==$val){ echo 'selected="selected"'; } echo '>'.sprintf("%3d",$i).'</option>';

				break;

		}

	}

	echo '</select>';

}



function selectDept($faculty,$dept1){

	$query=result("SELECT dept FROM faculties_dept WHERE faculty='$faculty'");

	while($dept=mysql_fetch_array($query)){

		echo '<option value="'.$dept[0].'"'; if($dept[0]==$dept1){echo 'selected="selected"';}; echo '>'.$dept[0].'</option>';

	}

}

function selectDeptCoc($faculty,$dept1){

	$query=result("SELECT dept FROM faculties_dept2 WHERE faculty='$faculty'");

	while($dept=mysql_fetch_array($query)){

		echo '<option value="'.$dept[0].'"'; if($dept[0]==$dept1){echo 'selected="selected"';}; echo '>'.$dept[0].'</option>';

	}

}


function selectDept2($faculty,$dept1){

	$query=result("SELECT dept, dept_id FROM facultiesdept WHERE faculty='$faculty'");

	while($dept=mysql_fetch_array($query)){

		echo '<option value="'.$dept[1].'"'; if($dept[1]==$dept1){echo 'selected="selected"';}; echo '>'.$dept[0].'</option>';

	}

}



function checkUser($user){

connet();

	$user=trim($user);

	$userQuery="select * from unimed_users where username='$user'";

	$userResult=result($userQuery);

	$recordCount=mysql_num_rows($userResult);

	if($recordCount>=1){

		return 1;

	}else{

		return 0;

	}

}





function selectDept22($faculty,$dept1){

	$query=result("SELECT dept, dept_id FROM facultiesdept WHERE faculty_id='$faculty'");

	while($dept=mysql_fetch_array($query)){

		echo '<option value="'.$dept[1].'"'; if($dept[1]==$dept1){echo 'selected="selected"';}; echo '>'.$dept[0].'</option>';

	}

}



function Otherdept($hid,$val){

	connet();

	$sql=result("SELECT dept FROM faculties_dept WHERE faculty='Sciences'");

		echo '<select name="'.$hid.'" id="'.$hid.'" style="width:175px; font-size:13px; line-height:2em; height:25px;">';

			echo '<option>--Select Dept--</option>';

			while($ret=mysql_fetch_array($sql)){

				echo '

				<option value="'.$ret[0].'" '; if($val==$ret[0]){ echo 'selected="selected"';} echo '>'.$ret[0].'</option>';

			}		

		echo '</select>';

}

function Otherdept2($hid,$val){
		echo '<select name="'.$hid.'" id="'.$hid.'" style="width:175px; font-size:13px; line-height:2em; height:25px;">';
			echo '<option>--Select Dept--</option>';

	$availdept=array("Physics","Mathematics","Chemistry","Biological Sciences(Animal and Environmental)","Biological Sciences(Plant Biology and Biotechnology)");
			foreach($availdept as $dept){
				echo '
				<option value="'.$dept.'" '; if($val==$dept){ echo 'selected="selected"';} echo '>'.$dept.'</option>';
			}		
		echo '</select>';
}

function Otherdept3($hid,$val){

	connet();

	$sql=result("SELECT dept FROM faculties_dept WHERE faculty='Sciences'");

		echo '<select name="'.$hid.'" id="'.$hid.'" style="width:175px; font-size:13px; line-height:2em; height:25px;">';

			echo '<option>--Select Dept--</option>';

			while($ret=mysql_fetch_array($sql)){

				echo '

				<option value="'.$ret[0].'" '; if($val==$ret[0]){ echo 'selected="selected"';} echo '>'.$ret[0].'</option>';

			}		

		echo '</select>';

}

function listSubjects($name,$hid="",$val=""){

		echo '<select name="'.$name.'" id="'.$hid.'" style="width:175px; font-size:13px; line-height:2em; height:25px;">

				<option value="">--Select Subject--</option>';

			echo '

				<option '; if($val=="English Language"){ echo 'selected="selected"'; } echo '>English Language</option>

				<option '; if($val=="Mathematics"){ echo 'selected="selected"'; } echo '>Mathematics</option>

				<option '; if($val=="Further Mathematics"){ echo 'selected="selected"'; } echo '>Further Mathematics</option>

				<option '; if($val=="Biology"){ echo 'selected="selected"'; } echo '>Biology</option>

				<option '; if($val=="Chemistry"){ echo 'selected="selected"'; } echo '>Chemistry</option>

				<option '; if($val=="Physics"){ echo 'selected="selected"'; } echo '>Physics</option>

				<option '; if($val=="Agricultural Science"){ echo 'selected="selected"'; } echo '>Agricultural Science</option>

				<option '; if($val=="Economics"){ echo 'selected="selected"'; } echo '>Economics</option>

				<option '; if($val=="Geography"){ echo 'selected="selected"'; } echo '>Geography</option>

				<option '; if($val=="Civic Education"){ echo 'selected="selected"'; } echo '>Civic Education</option>

				<option '; if($val=="Computer Studies"){ echo 'selected="selected"'; } echo '>Computer Studies</option>

				<option '; if($val=="Food and Nutrition"){ echo 'selected="selected"'; } echo '>Food and Nutrition</option>

				<option '; if($val=="Wood Work"){ echo 'selected="selected"'; } echo '>Wood Work</option>

				<option '; if($val=="Technical Drawing"){ echo 'selected="selected"'; } echo '>Technical Drawing</option>

				<option '; if($val=="Tourism"){ echo 'selected="selected"'; } echo '>Tourism</option>

				<option '; if($val=="Igbo Language"){ echo 'selected="selected"'; } echo '>Igbo Language</option>

				<option '; if($val=="Yoruba Language"){ echo 'selected="selected"'; } echo '>Yoruba Language</option>

				<option '; if($val=="Christian Religious Knowledge"){ echo 'selected="selected"'; } echo '>Christian Religious Knowledge</option>

				';		

		echo '</select>';

}



function listSubjectsb($name,$hid="",$val){

		echo '<select name="'.$name.'" style="width:175px; font-size:13px; line-height:2em; height:25px;">

				<option value="">--Select Subject--</option>';

			echo '

				<option value="'.$val.'" selected="selected">'.$val.'</option>';	

		echo '</select>';

}



function listSubjectsjb($hid,$val,$no){

		echo '<select name="'.$hid.'" id="'.$hid.'" style="width:133px; font-size:13px; line-height:2em; height:25px;">';

			echo '<option value="">--Select Subject '.$no.'--</option>

				<option '; if($val=="Mathematics"){ echo 'selected="selected"'; } echo '>Mathematics</option>

				<option '; if($val=="Biology"){ echo 'selected="selected"'; } echo '>Biology</option>

				';		

		echo '</select>';

}



function jambsub($sub){

	switch($sub){

		case "BIO":

		$title="Biology";

		break;

		case "CHE":

		$title="Chemistry";

		break;

		case "PHY":

		$title="Physics";

		break;

		default:

		$title=$sub;

		break;

	}

	

}



function listSubjectsj($hid,$val,$no){

		echo '<select name="'.$hid.'" id="'.$hid.'" style="width:133px; font-size:13px; line-height:2em; height:25px;">';

			echo '<option value="">--Select Subject '.$no.'--</option>

				<option value="'.$val.'" selected="selected">'.$val.'</option>

				';		

		echo '</select>';

}





function listGradesal($name,$hid,$val=""){

		echo '<select name="'.$name.'" id="'.$hid.'" style="width:175px; font-size:13px; line-height:2em; height:25px;"><option></option>';

					echo '

						<option value="First Class" '; if($val=="First Class"){ echo 'selected="selected"';} echo '>First Class</option>

						<option value="Second Class Upper" '; if($val=="Second Class Upper"){ echo 'selected="selected"';} echo '>Second Class Upper</option>

						<option value="Second Class Lower" '; if($val=="Second Class Lower"){ echo 'selected="selected"';} echo '>Second Class Lower</option>

						';	

		echo '</select>';

}



function listGrades($name,$hid,$val=""){

		echo '<select name="'.$name.'" id="'.$hid.'" style="width:175px; font-size:13px; line-height:2em; height:25px;">

						<option value="">--Select Grade--</option>';

						echo '<option value="AR" '; if($val=="AR"){ echo 'selected="selected"'; } echo '>AR</option>

						<option value="A1" '; if($val=="A1"){ echo 'selected="selected"'; } echo '>A1</option>

						<option value="B2" '; if($val=="B2"){ echo 'selected="selected"'; } echo '>B2</option>

						<option value="B3" '; if($val=="B3"){ echo 'selected="selected"'; } echo '>B3</option>

						<option value="C4" '; if($val=="C4"){ echo 'selected="selected"'; } echo '>C4</option>

						<option value="C5" '; if($val=="C5"){ echo 'selected="selected"'; } echo '>C5</option>

						<option value="C6" '; if($val=="C6"){ echo 'selected="selected"'; } echo '>C6</option>

						<option value="D7" '; if($val=="D7"){ echo 'selected="selected"'; } echo '>D7</option>

						<option value="E8" '; if($val=="E8"){ echo 'selected="selected"'; } echo '>E8</option>

						<option value="F9" '; if($val=="F9"){ echo 'selected="selected"'; } echo '>F9</option>';		

		echo '</select>';

}



function subtitle($val){

	$rec=explode("|",$val);

	return $rec[0];

}



function subgrd($val){

	$rec=explode("|",$val);

	return $rec[1];

}



function deptH($hid){

	switch($hid){

		case 1:

		$title="WELCOME";

		break;

		case 2:

		$title="ADMINISTRATION";

		break;

		case 3:

		$title="OBJECTIVES";

		break;

		case 4:

		$title="ACADEMIC PROGRAMMES";

		break;

		case 5:

		$title="STAFF";

		break;

		case 6:

		$title="CONTACT";

		break;

		case 7:

		$title="EXTRA 1";

		break;

		case 8:

		$title="EXTRA 2";

		break;

		default:

		$title="UNKNOWN";

		break;

	}

	return $title;

}



function deptArchives($table,$fin,$din){

connet();	

	$sql=result("SELECT * FROM $table WHERE faculty=$fin AND dept=$din ORDER BY id ASC");

	$sql1=result("SELECT * FROM story WHERE fac=$fin AND dept=$din ORDER BY id DESC LIMIT 0,10");

	$n=mysql_num_rows($sql);

	$n1=mysql_num_rows($sql1);

	if($n>=1){

		$sn=0;

		echo '<table width="100%">

				<tr style="background-color:#fff; color:#f00;">

				<td style="padding:5px; font-style:italic; font-weight:bold; text-align:center;" colspan="4">Page Content Information</td>

			</tr>

				<tr style="background-color:#030; color:#FFF;">

				<td style="padding:2px;"><strong>S/N</strong></td>

			  <td style="padding:2px;"><strong>ITEM</strong></td>

			  <td style="padding:2px;"><strong>ACTION 1</strong></td>

			  <td style="padding:2px;"><strong>ACTION 2</strong></td>

			</tr>';

		while($rec=mysql_fetch_array($sql)){

			$sn +=1;

			echo '<tr style="background-color:#FFFFFF;">

				<td style="padding:2px;">'.$sn.'</td>

			  <td style="padding:2px;">'.deptH($rec[2]).'</td>

			  <td style="padding:2px;">[<a href="faculty_info.php?ein='.$rec[0].'"> &raquo;&nbsp;Edit&nbsp;</a>]</td>

			  <td style="padding:2px;">[<a href="deptArchives2.php?din='.$rec[0].'&&cat=1&&faculty='.$rec[3].'&&dept='.$rec[4].'" onclick="return confirm(\'Are you Sure you want to Delete\');">&raquo;&nbsp;Delete&nbsp;</a>]</td>

			</tr>';

		}

		if($n1>=1){

			while($rec1=mysql_fetch_array($sql1)){

				$sn +=1;

				echo '<tr style="background-color:#FFFFFF;">

					<td style="padding:2px;">'.$sn.'</td>

				  <td style="padding:2px;">NEWS - ';

				if(strlen($rec1[1])>70){

					echo substr($rec1[1], 0, 70)."...";

				}else{

					echo $rec1[1];

				}

				echo '</td>

				  <td style="padding:2px;">[<a href="news_upload.php?ein='.$rec1[0].'"> &raquo;&nbsp;Edit&nbsp;</a>]</td>

				  <td style="padding:2px;">[<a href="deptArchives2.php?din='.$rec1[0].'&&cat=2&&faculty='.$rec1[7].'&&dept='.$rec1[8].'" onclick="return confirm(\'Are you Sure you want to Delete\');">&raquo;&nbsp;Delete&nbsp;</a>]</td>

				</tr>';

			}

		}

		echo '</table>';

	}else{

	echo '<table width="100%">

				<tr style="background-color:#fff; color:#f00;">

				<td style="padding:5px; font-style:italic; font-weight:bold; text-align:center;" colspan="4">No Record Exist for the Selection</td>

			</tr>';

	echo '</table>';

	}

}



function deptNews($table,$fin,$din){

connet();	

	$sql1=result("SELECT * FROM story WHERE cat=$din AND status='NO' ORDER BY id DESC LIMIT 0,10");

	$n1=mysql_num_rows($sql1);

	if($n1>=1){

		$sn=0;

		echo '<table width="100%">

				<tr style="background-color:#fff; color:#f00;">

				<td style="padding:5px; font-style:italic; font-weight:bold; text-align:center;" colspan="4">Page Content Information</td>

			</tr>

				<tr style="background-color:#030; color:#FFF;">

				<td style="padding:2px;"><strong>S/N</strong></td>

			  <td style="padding:2px;"><strong>ITEM</strong></td>

			  <td style="padding:2px;"><strong>ACTION 1</strong></td>

			  <td style="padding:2px;"><strong>ACTION 2</strong></td>

			</tr>';

			while($rec1=mysql_fetch_array($sql1)){

				$sn +=1;

				echo '<tr style="background-color:#FFFFFF;">

					<td style="padding:2px;">'.$sn.'</td>

				  <td style="padding:2px;">NEWS</td>

				  <td style="padding:2px;">[<a href="news_upload.php?ein='.$rec1[0].'"> &raquo;&nbsp;Edit&nbsp;</a>]</td>

				  <td style="padding:2px;">[<a href="deptArchives2.php?din='.$rec1[0].'&&cat=2&&faculty='.$rec1[7].'&&dept='.$rec1[8].'" onclick="return confirm(\'Are you Sure you want to Delete\');">&raquo;&nbsp;Delete&nbsp;</a>]</td>

				</tr>';

			}

		echo '</table>';

	}else{

	echo '<table width="100%">

				<tr style="background-color:#fff; color:#f00;">

				<td style="padding:5px; font-style:italic; font-weight:bold; text-align:center;" colspan="4">No Pending News Request</td>

			</tr>';

	echo '</table>';

	}

}

function numGenerator($start,$range,$name,$val2=""){

	$curYear=date("Y");

	$startYear=$start;

	$endYear=$startYear+$range;

	echo '<select name="'.$name.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">';

	if($val2 !=""){

		echo '<option>'.$val2.'</option>';

	}

	echo '<option value="">--Select--</option>';

	//$format=sprintf(%02d);

	for($i=$startYear;$i<$endYear;$i++){

		switch($i){

			default:

				echo '<option value='.$i.'>'.sprintf("%3d",$i).'</option>';

				break;

		}

	}

	echo '</select>';

}



function numGenerator2($start,$range,$name,$val2=""){

	$curYear=date("Y");

	$startYear=$start;

	$endYear=$startYear+$range;

	echo '<select name="'.$name.'" style="width:70px; font-size:13px; line-height:2em; height:22px;">';

	if($val2 !=""){

		echo '<option>'.$val2.'</option>';

	}

	echo '<option value="">--Age--</option>';

	//$format=sprintf(%02d);

	for($i=$startYear;$i<$endYear;$i++){

		switch($i){

			default:

				echo '<option value='.$i.'>'.sprintf("%3d",$i).'</option>';

				break;

		}

	}

	echo '</select>';

}



class DBController {

	/*private $host = "localhost";

	private $user = "root";

	private $password = "";

	private $database = "unimedportal";

	*/

	private $host = "localhost";

	private $user = "unimed5_ict1";

	private $password = "*biscuit_@_children!";

	private $database = "unimed5_unimedportaldb";

	

	function __construct() {

		$conn = $this->connectDB();

		if(!empty($conn)) {

			$this->selectDB($conn);

		}

	}

	

	function connectDB() {

		$conn = mysql_connect($this->host,$this->user,$this->password);

		return $conn;

	}

	

	function selectDB($conn) {

		mysql_select_db($this->database,$conn);

	}

	

	function runQuery($query) {

		$result = mysql_query($query);

		while($row=mysql_fetch_assoc($result)) {

			$resultset[] = $row;

		}		

		if(!empty($resultset))

			return $resultset;

	}

	

	function numRows($query) {

		$result  = mysql_query($query);

		$rowcount = mysql_num_rows($result);

		return $rowcount;	

	}

	

	function getStateID($state){

		$query="SELECT state FROM state2 WHERE state_id='$state'";

		$queryResult=mysql_query($query);

		list($id)=mysql_fetch_array($queryResult);

		return $id;

	}

	

	function getFacultyID($faculty){

		$query="SELECT faculty FROM faculties_dept WHERE faculty_id='$faculty'";

		$queryResult=mysql_query($query);

		list($id)=mysql_fetch_array($queryResult);

		return $id;

	}

}


class DBController2 {

	/*private $host = "localhost";

	private $user = "root";

	private $password = "";

	private $database = "unimedportal";

	*/

	private $host = "localhost";

	private $user = "unimed5_ict1";

	private $password = "*biscuit_@_children!";

	private $database = "unimed5_unimedportaldb";

	

	function __construct() {

		$conn = $this->connectDB();

		if(!empty($conn)) {

			$this->selectDB($conn);

		}

	}

	

	function connectDB() {

		$conn = mysql_connect($this->host,$this->user,$this->password);

		return $conn;

	}

	

	function selectDB($conn) {

		mysql_select_db($this->database,$conn);

	}

	

	function runQuery($query) {

		$result = mysql_query($query);

		while($row=mysql_fetch_assoc($result)) {

			$resultset[] = $row;

		}		

		if(!empty($resultset))

			return $resultset;

	}

	

	function numRows($query) {

		$result  = mysql_query($query);

		$rowcount = mysql_num_rows($result);

		return $rowcount;	

	}

	

	function getStateID($state){

		$query="SELECT state FROM state2 WHERE state_id='$state'";

		$queryResult=mysql_query($query);

		list($id)=mysql_fetch_array($queryResult);

		return $id;

	}

	

	function getFacultyID($faculty){

		$query="SELECT faculty FROM faculties_dept2 WHERE faculty_id='$faculty'";

		$queryResult=mysql_query($query);

		list($id)=mysql_fetch_array($queryResult);

		return $id;

	}

}


function retReg($card,$pin){

	connet();

	$reQuery="select * from accessscratchcardtable where PinNumber='$pin' and CardSerialNumber=$card";

	$regResult=result($reQuery);

	$regVals=mysql_fetch_array($regResult);

	$pass1=$regVals['RegistrationNumber'];

	return strtoupper($pass1);

}



function retReg2($card,$pin){

	connet();

	$reQuery="select * from hostelcard where PinNumber='$pin' and CardSerialNumber=$card";

	$regResult=result($reQuery);

	$regVals=mysql_fetch_array($regResult);

	$pass1=$regVals['RegistrationNumber'];

	return strtoupper($pass1);

}



function retId($card,$pin){

	connet();

	$idQuery="select * from accessscratchcardtable where PinNumber='$pin' and CardSerialNumber=$card";

	$idResult=result($idQuery);

	$idVals=mysql_fetch_array($idResult);

	$pass2=$idVals['studentId'];

	return $pass2;

}



function AuthCount($card,$pin,$matric){

	connet();

	$idQuery="select * from accessscratchcardtable where PinNumber='$pin' and CardSerialNumber='$card' AND RegistrationNumber='$matric'";

	$idResult=result($idQuery);

	$idVals=mysql_fetch_array($idResult);

	$pass2=$idVals['NumberOfUsage'];

	if($pass2<5){

		return 1;

	}else{

		return 0;

	}

}



function checkMatric($matric){

	connet();

	$idQuery="select * from accessscratchcardtable where RegistrationNumber='$matric'";

	$idResult=result($idQuery);

	$idVals=mysql_num_rows($idResult);

	return $idVals;

}



function authCard($card,$pin){

	connet();

	$cardQuery="select * from accessscratchcardtable where PinNumber='$pin' and CardSerialNumber=$card";

	$cardResult=result($cardQuery);

	$cardVals=mysql_num_rows($cardResult);

	return $cardVals;

}



function retStudentInfo($table,$matric){

	connet();

	$cardQuery="select * from $table where matricno='$matric'";

	$cardResult=result($cardQuery);

	$cardVals=mysql_num_rows($cardResult);

	return $cardVals;

	

}



function retStudentStatus($matric){

	connet();

	$cardQuery="select date from studentiformation where matricno='$matric'";

	$cardResult=result($cardQuery);

	list($status)=mysql_fetch_array($cardResult);

	if($status=='1999-09-09'){

		return 0;	

	}else{

		return 1;

	}

}



function regCheck($card,$pin,$matric){

	connet();

		$mQuery="select * from accessscratchcardtable where PinNumber='$pin' and CardSerialNumber=$card and RegistrationNumber='$matric' and RegistrationNumber is NOT NULL";

		$mResult=result($mQuery);

		$mVals=mysql_num_rows($mResult);

		return $mVals;

}



function admupCardTab($card,$pin,$matric){

	$upQuery="UPDATE admissionstatuscardtable SET RegistrationNumber='$matric', NumberOfUsage=NumberOfUsage+1, PinStatus='USED', DateFirstAccessed=CURDATE() where PinNumber='$pin' and CardSerialNumber=$card";

	$upResult=result($upQuery);

}



function admretReg($card,$pin){

	connet();

	$reQuery="select * from admissionstatuscardtable where PinNumber='$pin' and CardSerialNumber=$card";

	$regResult=result($reQuery);

	$regVals=mysql_fetch_array($regResult);

	$pass1=$regVals['RegistrationNumber'];

	return strtoupper($pass1);

}



function admretId($card,$pin){

	connet();

	$idQuery="select * from admissionstatuscardtable where PinNumber='$pin' and CardSerialNumber=$card";

	$idResult=result($idQuery);

	$idVals=mysql_fetch_array($idResult);

	$pass2=$idVals['studentId'];

	return $pass2;

}



function admauthCard($card,$pin){

	connet();

	$cardQuery="select * from admissionstatuscardtable where PinNumber='$pin' and CardSerialNumber=$card AND (NumberOfUsage<5 OR NumberOfUsage IS NULL)";

	$cardResult=result($cardQuery);

	$cardVals=mysql_num_rows($cardResult);

	return $cardVals;

}



function admregCheck($card,$pin,$matric){

	connet();

		$mQuery="select * from admissionstatuscardtable where PinNumber='$pin' and CardSerialNumber=$card and RegistrationNumber='$matric' and RegistrationNumber is NOT NULL";

		$mResult=result($mQuery);

		$mVals=mysql_num_rows($mResult);

		return $mVals;

}



function aplupCardTab($card,$pin,$matric,$cType){

	$upQuery="UPDATE applicationcardtable SET RegistrationNumber='$matric', NumberOfUsage=NumberOfUsage+1, programme='$cType', PinStatus='USED', DateFirstAccessed=CURDATE() where PinNumber='$pin' and CardSerialNumber=$card";

	$upResult=result($upQuery);

}



function aplretReg($card,$pin){

	connet();

	$reQuery="select * from applicationcardtable where PinNumber='$pin' and CardSerialNumber=$card";

	$regResult=result($reQuery);

	$regVals=mysql_fetch_array($regResult);

	$pass1=$regVals[4];

	return strtoupper($pass1);

	//return "ade";

}



function aplretId($card,$pin){

	connet();

	$idQuery="select * from applicationcardtable where PinNumber='$pin' and CardSerialNumber=$card";

	$idResult=result($idQuery);

	$idVals=mysql_fetch_array($idResult);

	$pass2=$idVals['studentId'];

	return $pass2;

}



function aplauthCard($card,$pin){

	connet();

	$cardQuery="select * from applicationcardtable where PinNumber='$pin' and CardSerialNumber=$card and RegistrationNumber IS NULL  and (NumberOfUsage<5 OR NumberOfUsage IS NULL)";

	$cardResult=result($cardQuery);

	$cardVals=mysql_num_rows($cardResult);

	return $cardVals;

}



function aplregCheck($card,$pin,$matric){

	connet();

		$mQuery="select * from applicationcardtable where PinNumber='$pin' and CardSerialNumber=$card and RegistrationNumber='$matric' and RegistrationNumber is NOT NULL";

		$mResult=result($mQuery);

		$mVals=mysql_num_rows($mResult);

		return $mVals;

}



function transupCardTab($card,$pin,$matric,$cType){

	$upQuery="UPDATE transcriptcardtable SET RegistrationNumber='$matric', NumberOfUsage=NumberOfUsage+1, programme='$cType', PinStatus='USED', DateFirstAccessed=CURDATE() where PinNumber='$pin' and CardSerialNumber=$card";

	$upResult=result($upQuery);

}



function transretReg($card,$pin){

	connet();

	$reQuery="select * from transcriptcardtable where PinNumber='$pin' and CardSerialNumber=$card";

	$regResult=result($reQuery);

	$regVals=mysql_fetch_array($regResult);

	$pass1=$regVals['RegistrationNumber'];

	return strtoupper($pass1);

}



function transretId($card,$pin){

	connet();

	$idQuery="select * from transcriptcardtable where PinNumber='$pin' and CardSerialNumber=$card";

	$idResult=result($idQuery);

	$idVals=mysql_fetch_array($idResult);

	$pass2=$idVals['studentId'];

	return $pass2;

}



function transauthCard($card,$pin,$matric){

	connet();

	$cardQuery="select * from transcriptcardtable where PinNumber='$pin' and CardSerialNumber=$card and (RegistrationNumber IS NULL or RegistrationNumber='$matric')  AND (NumberOfUsage<5 OR NumberOfUsage IS NULL)";

	$cardResult=result($cardQuery);

	$cardVals=mysql_num_rows($cardResult);

	return $cardVals;

}



function transregCheck($card,$pin,$matric){

	connet();

		$mQuery="select * from transcriptcardtable where PinNumber='$pin' and CardSerialNumber=$card and RegistrationNumber='$matric' and RegistrationNumber is NOT NULL";

		$mResult=result($mQuery);

		$mVals=mysql_num_rows($mResult);

		return $mVals;

}



//-------------------------------------------------------------------------------

function checkUpdate($id){

	connet();

	$checkerQuery="SELECT * FROM sr08 where studentId=$id";

	$checkResult=mysql_query($checkerQuery);

	$result=mysql_num_rows($checkResult);

	return $result;

}



function checkUpdate2($id){

	connet();

	$checkerQuery="SELECT * FROM sr04 where studentId=$id";

	$checkResult=mysql_query($checkerQuery);

	$result=mysql_num_rows($checkResult);

	return $result;

}



function newInsert($vals, $id){

	//if(!empty($vals)){

		$vals[]=$id;

		$vals[]=$id;

		return input($vals, "sr04");

		//var_dump($vals);	

	//}

}	



function createForm($dept,$semester,$level){

	connet();

	$formQuery="SELECT * FROM course where dept='$dept' AND semester='$semester' AND level='$level'";

	$formResult=result($formQuery);

	$a=0;

	$p=0;

	echo '<table width="500"><form action="courseForm.php" method="post" id="regForm">';

	echo "<tr>";

				echo '<td align="left" width="20"><b>S/N</b></td>';

				echo '<td align="left" width="40" colspan="2"><b>Course Code</b></td>'; 

				echo '<td align="left" width="80" colspan="2"><b>Course Title</b></td>'; 				 

				echo '<td align="left" width="40" colspan="2"><b>Course Status</b></td>';

				echo '<td align="left" width="100"><b>Course Unit</b></td>';

			echo '</tr>';

			echo '<tr>

			<td colspan="7"><hr /></td>

		</tr>';

	while($resultData=mysql_fetch_array($formResult)){

		$a=$a+1;

			echo "<tr>";

				echo '<td align="left" width="20">['.$a.']</td>'; //serial number

/*start course code rendering here*/

				echo '<td align="left" width="40">'.$resultData[1].'</td>';  //course codes

				echo '<td align="left" width="40">';

				echo '<input type="checkbox" name="course[]" value="'.$resultData[1].'" id="courseChecker'.$a.'"/>'; //check boxes

				echo '<input type="hidden" name="courses[]" value="" id="courseStore'.$a.'"/></td>';  // stores the course values to be sent DB

/*end course code rendering here*/

/////////////////////////////////

/*start course title rendering here*/

				echo '<td align="left" width="240" colspan="2">'.$resultData[2].'</td>';

				echo '<input type="hidden" name="title[]" value="'.$resultData[2].'<" id="title"/></td>';

/*end course title rendering here*/

/*start course status rendering here*/

				echo '<td align="left" width="40" colspan="2">'.$resultData[4].'</td>';

				echo '<input type="hidden" name="title[]" value="'.$resultData[4].'<" id="title"/></td>';

/*end course status rendering here*/

				echo '<td align="left" width="100"><input type="text" name="unit[]" value="'.$resultData[3].'" style="width:40px;"/></td>';

			echo '</tr>';

		}

		$p=$a+1;

		echo '<tr>

			<td colspan="7"><hr /></td>

		</tr>';

		echo '<tr>

			<td colspan="7" align="left"><b>More Courses</b></td>

		</tr>';

		echo '<tr>

			<td colspan="7"><hr /></td>

		</tr>';

		echo "<tr>";

				echo '<td align="left" width="20"><b>S/N</b></td>';

				echo '<td align="left" width="40" colspan="2"><b>Course Code</b></td>';  

				echo '<td align="left" width="80" colspan="2"><b>Course Title</b></td>';  

				echo '<td align="left" width="40" colspan="2"><b>Course Status</b></td>';

				echo '<td align="left" width="100"><b>Course Unit</b></td>';

			echo '</tr>';

		for($ex=$p;$ex<10+$p;$ex++){

			echo "<tr>";

				echo '<td align="left" width="20">['.$ex.']</td>';

				echo '<td align="left" width="80" colspan="2"><input type="text" name="courses[]" value="'.$resultData[1].'" id="courseChecker'.$a.'" style="width:80px;"/></td>';  

				echo '<td align="left" width="40" colspan="2"><input type="text" name="title[]" value="" style="width:160px;"/></td>';

				echo '<td align="left" width="240" colspan="2"><input type="text" name="status[]" value="" style="width:40px;"/></td>';

				echo '<td align="left" width="100"><input type="text" name="unit[]" value="'.$resultData[3].'" style="width:40px;"/></td>';

			echo '</tr>';

		}

			echo '<tr>';

			  echo '

			  		<td align="left" colspan="4">&nbsp;</td>

			  		<td align="left">

						<input type="hidden" name="courseSize" value="'.$a.'" id="courseSize"/>

						<input type="reset" name="reset" value="Clear"/>&nbsp;

						<input type="submit" name="courseForm" value="Submit Form"  onClick="return resetVal()"/>

				 	</td>';

			echo '</tr>';

	echo "</form></table>";

	//echo $courseSize;

}



function createForm2($dept,$semester,$level){

	connet();

	$formQuery="SELECT * FROM course where dept='$dept' AND semester='$semester' AND level='$level'";

	$formResult=result($formQuery);

	$a=0;

	$p=0;

	

	while($resultData=mysql_fetch_array($formResult)){

		$a=$a+1;

			echo "<tr>";

				echo '<td align="left" width="20">['.$a.']</td>'; //serial number

/*start course code rendering here*/

				echo '<td align="left" width="40">'.$resultData[1].'</td>';  //course codes

				echo '<td align="left" width="40">';

				echo '<input type="checkbox" name="course[]" value="'.$resultData[1].'" id="courseChecker'.$a.'"/>'; //check boxes

				echo '<input type="hidden" name="courses[]" value="" id="courseStore'.$a.'"/></td>';  // stores the course values to be sent DB

/*end course code rendering here*/

/////////////////////////////////

/*start course title rendering here*/

				echo '<td align="left" width="240" colspan="2">'.$resultData[2].'</td>';

				echo '<input type="hidden" name="title[]" value="'.$resultData[2].'<" id="title"/></td>';

/*end course title rendering here*/

/*start course status rendering here*/

				echo '<td align="left" width="40" colspan="2">'.$resultData[4].'</td>';

				echo '<input type="hidden" name="title[]" value="'.$resultData[4].'<" id="title"/></td>';

/*end course status rendering here*/

				echo '<td align="left" width="100"><input type="text" name="unit[]" value="'.$resultData[3].'" style="width:40px;"/></td>';

			echo '</tr>';

		}

		echo '<tr>

			<td colspan="8"><hr /></td>

		</tr>';

		

	//echo $courseSize;

}



function createForm3($major,$minor,$semester,$level,$school){

	for($a=1;$a<17;$a++){

		   echo "<tr>";

				echo '<td align="left" width="20">['.$a.']</td>';

				echo '<td align="left" width="20">'.listCodes2($major,$minor,$semester,$level,$school).'</td>';

				echo '<td align="left" width="40"><input type="text" name="title[]" value="" style="width:120px"/></td>';

				echo '<td align="left" width="40">

					<select name="type[]" style="width:40px">

						<option value=""></option>

						<option value="1">major</option>

						<option value="2">Minor</option>

						<option value="3">Education</option>

					</select>

				</td>';

				echo '<td align="left" width="40" >

					<select name="unit[]" style="width:40px">

						<option value=""></option>

						<option value="1">1</option>

						<option value="2">2</option>

						<option value="3">3</option>

						<option value="4">4</option>

						<option value="5">5</option>

						<option value="6">6</option>

						<option value="7">7</option>

						<option value="8">8</option>

						<option value="9">9</option>

						<option value="10">10</option>

					</select>

				</td>';

				echo '<td align="left" width="100">

					<select name="status[]" style="width:40px">

						<option value=""></option>

						<option value="c">Carry Over</option>

						<option value="m">Main Course</option>

					</select>

				</td>';

			echo '</tr>';

	}

		

	//echo $courseSize;

}



function listC($dept,$level){

	connet();

	$formQuery="SELECT * FROM course where dept='$dept' AND level='$level' ORDER BY semester";

	$formQuery;

	$formResult=result($formQuery);

	//echo $formResult;

	$n=0;

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<tr>

			<td width="120" align="left">['.$n.']</td>

			<td width="100" align="left"><input type="text" name="course[]" value="'.$resultData[1].'" size="18" id="course"/></td>

			<td width="230" align="left"><input type="text" name="title[]" value="'.$resultData[2].'" id="title"/></td>

			<td width="50" align="left"><input type="text" name="unit[]" value="'.$resultData[3].'" size="10" id="unit"/></td>

		</tr>';

	}

	//var_dump($setData);

}



function list4Update($dept,$level){

	connet();

	$formQuery="SELECT * FROM course where dept='$dept' AND level='$level' ORDER BY semester";

	$formQuery;

	$formResult=result($formQuery);

	//echo $formResult;

	$n=0;

	while($resultData=mysql_fetch_array($formResult)){

		$n +=1;

		echo'<tr>

			<td width="50" align="left">['.$n.']<input type="hidden" value="'.$resultData[0].'" name="rec_id[]" /></td>

			<td width="50" align="left"><input type="text" name="course[]" value="'.$resultData[1].'" size="18" /></td>

			<td width="255" align="left"><input type="text" name="title[]" value="'.$resultData[2].'" /></td>

			<td width="25" align="left"><input type="text" name="unit[]" value="'.$resultData[3].'" size="10" /></td>

			<td width="120" align="left"><select name="semester[]" id="semester">

					<option value="1ST" 

					';

					?>

					<?php 

						if($resultData[7]=="1ST"){

							echo "selected";

						}

					?>

					<?php

					echo '

					>1ST</option>

					<option value="2ND" 

					';

					?>

					<?php 

						if($resultData[7]=="2ND"){

							echo "selected";

						}

					?>

					<?php

					echo '

					>2ND</option>

				</select></td>

		</tr>';

	}

	//var_dump($setData);

}



function staffUpdate($school,$dept){

	connet();

	$formQuery="SELECT * FROM staff_list WHERE school LIKE '%$school%' AND department LIKE '%$dept%' ORDER BY rank ASC";

	$formResult=result($formQuery);

	//echo $formResult;

	$n=0;

	while($resultData=mysql_fetch_array($formResult)){

		$n +=1;

		echo '<tr style="background-color:#FFFFFF;color:#000000;">

				 <td align="left">['.$n.']<input type="hidden" value="'.$resultData[0].'" name="rec_id[]" /></td>	

				<td align="left"><input type="text" name="ord[]" style="height:16px; width:20px;" value="'.$resultData[1].'" /></td>	

                <td align="left"><input type="text" name="name[]" style="height:16px; width:120px;" value="'.$resultData[2].'" /></td>	

                <td align="left"><input type="text" name="qual[]" style="height:16px; width:80px;" value="'.$resultData[3].'" /></td>	

                <td align="left"><input type="text" name="email[]" style="height:16px; width:70px;" value="'.$resultData[4].'" /></td>	

                <td align="left"><input type="text" name="des[]" style="height:16px; width:60px;" value="'.$resultData[5].'" /></td>	

			</tr>';

	}

	//var_dump($setData);

}



function listGrades22($name,$hid,$val){

		echo '<select name="grades[]" id="'.$hid.'" onchange="return showCustomer(this)" style="width:175px; font-size:13px; line-height:2em; height:25px;"><option></option>';

				echo '<option value="AR" '; if($val=="AR"){ echo 'selected="selected"';} echo '>AR</option>

						<option value="A1" '; if($val=="A1"){ echo 'selected="selected"';} echo '>A1</option>

						<option value="B2" '; if($val=="B2"){ echo 'selected="selected"';} echo '>B2</option>

						<option value="B3" '; if($val=="B3"){ echo 'selected="selected"';} echo '>B3</option>

						<option value="C4" '; if($val=="C4"){ echo 'selected="selected"';} echo '>C4</option>

						<option value="C5" '; if($val=="C5"){ echo 'selected="selected"';} echo '>C5</option>

						<option value="C6" '; if($val=="C6"){ echo 'selected="selected"';} echo '>C6</option>';		

		echo '</select>';

}



function getTitle($code){

	connet();

	$sql=result("SELECT courseTitle FROM course WHERE courseCode='$code'");

	$c_row=mysql_num_rows($sql);

	if($c_row >=1){

		list($title)=mysql_fetch_array($sql);

		return $title;

	}else{

		return "";

	}

}



function listCodes($dept,$semester,$level){

	connet();

	$sql=result("SELECT courseCode FROM course WHERE level=$level AND semester='$semester' AND (dept='$dept' || dept='ALL') ORDER BY courseCode ASC");

	while($rec=mysql_fetch_array($sql)){

		echo'<option>'.$rec[0].'</option>';

	}

}



function listCodesp($dept,$semester,$level){

	connet();

	$sql=result("SELECT courseCode FROM course WHERE level<=$level AND semester='$semester' AND (dept='$dept' || dept='ALL') ORDER BY courseCode ASC");

	while($rec=mysql_fetch_array($sql)){

		echo'<option>'.$rec[0].'</option>';

	}

}



function listCourses($dept,$level,$semester){

	connet();

	$sql=result("SELECT * FROM course WHERE level=$level AND semester='$semester' AND (dept='$dept' || dept='ALL') ORDER BY courseCode ASC");

	$n=0;

	while($res=mysql_fetch_array($sql)){

		$n=$n+1;

		$name='cos'.$n.'[]';

		echo'<tr style="background-color:#3399FF;color:#fff;">

            <td style="padding:5px;"><b>['.$n.']</b></td>

            <td style="padding:5px;">

            <select name="'.$name.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

            	<option>'.$res[1].'</option><option></option>';

				listCodes($dept,$semester,$level);

				echo '</select>

            </td>		

            <td style="padding:5px;"><input type="text" name="'.$name.'" value="'.$res[2].'" style="width:450px; height:18px;" /></td>	

			<td style="padding:5px;">'; numGenerator(1,7,$name,$res[3]); echo '</td>

			<td style="padding:5px;">

        	<select name="'.$name.'" id="type'.$n.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

              <option selected>'.$res[8].'</option>

			  <option>M</option>

			  <option>C</option>

			  <option>E</option>

              <option>CO</option>

			  <option></option>

			</select>

            </td>	

			</tr>';

	}

	

	$rem=20-$n;

	$m=$n;

	$level2=$level-100;

	for($k=1;$k<=$rem;$k++){

		$m=$m+1;

		$name2='cos'.$m.'[]';

		echo'<tr style="background-color:#3399FF;color:#fff;">

            <td style="padding:5px;"><b>['.$m.']</b></td>

            <td style="padding:5px;">

            <select name="'.$name2.'" id="code'.$k.'" onchange="return showCustomer(this)" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

            	<option value=""></option>';

				listCodesp($dept,$semester,$level2);

				echo '</select>

            </td>		

            <td style="padding:5px;"><input type="text" name="'.$name2.'" id="title'.$k.'" style="width:450px; height:18px;" /></td>	

			<td style="padding:5px;">'; numGenerator(1,7,$name2); echo '</td>

			<td style="padding:5px;">

        	<select name="'.$name2.'" id="type'.$k.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

              <option></option>

			  <option>M</option>

              <option>CO</option>

			</select>

            </td>	

			</tr>';

	}

}



function listCoursesrpt($dept,$level,$semester,$matno){

	connet();

	$rpt=result("SELECT oc FROM resulttable WHERE matricno='$matno' ORDER BY id DESC LIMIT 0,1");

	list($oc)=mysql_fetch_array($rpt);

	$exp=explode("|",$oc);

	$n=0;

	foreach($exp as $k){

		$sql=result("SELECT courseTitle,credit,cat FROM course WHERE level=$level AND semester='$semester' AND (dept='$dept' || dept='ALL') AND courseCode='$k' ");

		$c=mysql_num_rows($sql);

		if($c==1){

		list($title,$unit,$cat)=mysql_fetch_array($sql);

		$n=$n+1;

		$name1='cos'.$n.'[]';

		echo'<tr style="background-color:#3399FF;color:#fff;">

            <td style="padding:5px;"><b>['.$n.']</b></td>

            <td style="padding:5px;">

            <select name="'.$name1.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

            	<option>'.$k.'</option><option></option>';

				//listCodes($dept,$semester,$level);

				echo '</select>

            </td>		

            <td style="padding:5px;"><input type="text" name="'.$name1.'" value="'.$title.'" style="width:450px; height:18px;" /></td>	

			<td style="padding:5px;">'; numGenerator(1,7,$name1,$unit); echo '</td>

			<td style="padding:5px;">

        	<select name="'.$name1.'" id="type'.$n.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

              <option selected>'.$cat.'</option>

			  <option>M</option>

			  <option>C</option>

			  <option>E</option>

              <option>CO</option>

			  <option></option>

			</select>

            </td>	

			</tr>';

		}

	}

	$sql1=result("SELECT * FROM course WHERE level=200 AND semester='$semester' AND (dept='$dept' || dept='ALL') ORDER BY courseCode ASC");

	while($res=mysql_fetch_array($sql1)){

		$n=$n+1;

		$name='cos'.$n.'[]';

		echo'<tr style="background-color:#3399FF;color:#fff;">

            <td style="padding:5px;"><b>['.$n.']</b></td>

            <td style="padding:5px;">

            <select name="'.$name.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

            	<option>'.$res[1].'</option><option></option>';

				listCodes($dept,$semester,$level);

				echo '</select>

            </td>		

            <td style="padding:5px;"><input type="text" name="'.$name.'" value="'.$res[2].'" style="width:450px; height:18px;" /></td>	

			<td style="padding:5px;">'; numGenerator(1,7,$name,$res[3]); echo '</td>

			<td style="padding:5px;">

        	<select name="'.$name.'" id="type'.$n.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

              <option selected>'.$res[8].'</option>

			  <option>M</option>

			  <option>C</option>

			  <option>E</option>

              <option>CO</option>

			  <option></option>

			</select>

            </td>	

			</tr>';

	}

	

	$rem=20-$n;

	if($rem>=1){

	$m=$n;

	$level2=$level;

		for($k=1;$k<=$rem;$k++){

			$m=$m+1;

			$name2='cos'.$m.'[]';

			echo'<tr style="background-color:#3399FF;color:#fff;">

				<td style="padding:5px;"><b>['.$m.']</b></td>

				<td style="padding:5px;">

				<select name="'.$name2.'" id="code'.$k.'" onchange="return showCustomer(this)" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

					<option value=""></option>';

					listCodesp($dept,$semester,$level2);

					echo '</select>

				</td>		

				<td style="padding:5px;"><input type="text" name="'.$name2.'" id="title'.$k.'" style="width:450px; height:18px;" /></td>	

				<td style="padding:5px;">'; numGenerator(1,7,$name2); echo '</td>

				<td style="padding:5px;">

				<select name="'.$name2.'" id="type'.$k.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

				  <option></option>

				  <option>M</option>

				  <option>CO</option>

				</select>

				</td>	

				</tr>';

		}

	}

}



function semunit($dept,$level,$semester,$matno){

	connet();

	//current semester courses

	$sql1=result("SELECT * FROM course WHERE semester='$semester' AND level=$level AND (dept='$dept' || dept='ALL') ORDER BY courseCode ASC");

	$totunit=0;

	while($res=mysql_fetch_array($sql1)){

		$totunit += $res[3];

	}

	return $totunit;

}



function listPUnits($level,$semester,$matricno,$sess){

	connet();

	$query=result("SELECT course1, course2, course3, course4, course5, course6, course7, course8, course9, course10, course11, course12, course13, course14, course15, course16, course17, course18, course19, course20 FROM course_reg WHERE matricno='$matricno' AND level=$level AND session='$sess' AND semester<>'$semester' ORDER BY id DESC LIMIT 0,1");

	$no=mysql_num_rows($query);

	$cunit=0;

	if($no>=1){

		$ret=mysql_fetch_row($query);

		foreach($ret as $val){

			if($val != "|||"){

				$split=explode("|",$val);

				$cunit += $split[2];

			}

		}

	}

	return $cunit;

}



function extraUnit($dept,$level,$semester,$matno,$sess,$un){

	$funit=semunit($dept,$level,"FIRST",$matno);

	$sunit=semunit($dept,$level,"SECOND",$matno);

	$cunit=semunit($dept,$level,$semester,$matno);

	$sessu=$funit+$sunit+12;

	$punit=listPUnits($level,$semester,$matricno,$sess);

	//$bal=12-($punit-$funit);

	if($level>=200 && ($dept=="Nursing Science" || $dept=="Physiotherapy" || $dept=="Medical Laboratory Science")){

		if($un<=$cunit && ($dept=="Physiotherapy" || $dept=="Medical Laboratory Science")){

			$ret=1;

		}elseif($dept=="Nursing Science"){

			if($semester=="FIRST" && $un<=($funit+12)){

				$ret=1;

			}elseif($semester=="SECOND" && $un<=($sessu-$punit)){

				$ret=1;

			}else{

				$ret=0;

			}

		}else{

			$ret=0;

		}

	}else{

		$ret=0;

	}

	/*elseif($level==300 && $dept=="Physics(Electronics Physics)"){

		if($semester=="FIRST" && $un<=28){

			$ret=1;

		}elseif($semester=="SECOND" && $un<=26){

			$ret=1;

		}else{

			$ret=0;

		}

	}*/

	return $ret;

}



function listPCourses($level,$semester,$matricno){

	connet();

	$query=result("SELECT course1, course2, course3, course4, course5, course6, course7, course8, course9, course10, course11, course12, course13, course14, course15, course16, course17, course18, course19, course20 FROM course_reg WHERE matricno='$matricno' AND semester='$semester' AND level<$level ORDER BY id DESC");

	$no=mysql_num_rows($query);

	$cos=array();

	if($no>=1){

		while($ret=mysql_fetch_array($query)){

			foreach($ret as $val){

				if($val != "|||"){

					$split=explode("|",$val);

					$cos[]=$split[0];

				}

			}	

		}

	}

	return array_filter($cos);

}



function pdspCourses(){

		$name='cos[]';

		echo'<tr style="background-color:#fff;color:#005BAA;">

            <td style="padding:5px;"><b>1</b></td>

            <td style="padding:5px;">

            <input type="checkbox" name="'.$name.'" value="ENG|English Language" checked />ENG

            </td>		

            <td style="padding:5px;">English Language</td>	

			</tr>';

		echo'<tr style="background-color:#fff;color:#005BAA;">

            <td style="padding:5px;"><b>2</b></td>

            <td style="padding:5px;">

            <input type="checkbox" name="'.$name.'" value="MAT|Mathematics" checked />MAT

            </td>		

            <td style="padding:5px;">Mathematics</td>	

			</tr>';

		echo'<tr style="background-color:#fff;color:#005BAA;">

            <td style="padding:5px;"><b>3</b></td>

            <td style="padding:5px;">

            <input type="checkbox" name="'.$name.'" value="BIO|Biology" checked />BIO

            </td>		

            <td style="padding:5px;">Biology</td>	

			</tr>';

		echo'<tr style="background-color:#fff;color:#005BAA;">

            <td style="padding:5px;"><b>4</b></td>

            <td style="padding:5px;">

            <input type="checkbox" name="'.$name.'" value="CHE|Chemistry" checked />CHE

            </td>		

            <td style="padding:5px;">Chemistry</td>	

			</tr>';

		echo'<tr style="background-color:#fff;color:#005BAA;">

            <td style="padding:5px;"><b>5</b></td>

            <td style="padding:5px;">

            <input type="checkbox" name="'.$name.'" value="PHY|Physics" checked />PHY

            </td>		

            <td style="padding:5px;">Physics</td>	

			</tr>';

}



function pbnpCourses($semester){

	connet();

	$sql1=result("SELECT * FROM postbasicnus_courses WHERE semester='$semester' ORDER BY id ASC");

	while($res=mysql_fetch_array($sql1)){

		$n=$n+1;

		echo'<tr style="background-color:#fff;color:#005BAA;">

            <td style="padding:5px;"><b>['.$n.']</b></td>

            <td style="padding:5px;">';

				echo '<input type="hidden" name="cos[]" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[5].'" />';

				echo '<input type="checkbox" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[5].'" checked disabled="disabled" />'.$res[1];

            echo '</td>		

            <td style="padding:5px;">'.$res[2].'</td>

			<td style="padding:5px;">'.$res[3].'</td>

			<td style="padding:5px;">'.$res[5].'</td>	

			</tr>';

	}

}



function listCoursesNew($dept,$level,$semester,$matno,$utyp=1){

	connet();

	$level2=$level-100; // to get previous session registration

	$pcos=listPCourses($level,$semester,$matno);

	//to get previous carry over courses

	$rpt=result("SELECT oc FROM resulttable WHERE matricno='$matno' ORDER BY id DESC LIMIT 0,1");

	list($oc)=mysql_fetch_array($rpt);

	$n=0;

	$name='cos[]';

	if($oc!=""){

		$exp=explode("|",$oc);

		foreach($exp as $k){

			$sql=result("SELECT courseTitle,credit,cat FROM course WHERE courseCode='$k' AND semester='$semester' AND (dept='$dept' || dept='ALL')");

			$c=mysql_num_rows($sql);

			if($c==1){

				list($title,$unit,$cat)=mysql_fetch_array($sql);

				$n=$n+1;

				echo'<tr style="background-color:#fff;color:#005BAA;">

					<td style="padding:5px;"><b>['.$n.']</b></td>

					<td style="padding:5px;">';

					$exdept=array("Medicine and Surgery","Dentistry");

					if($utyp==2 || (in_array($dept,$exdept) && $level>100)){

						echo '<input type="checkbox" name="'.$name.'" value="'.$k.'|'.$title.'|'.$unit.'|CO"';

						echo ' />';

					}else{

					echo '<input type="hidden" name="'.$name.'" value="'.$k.'|'.$title.'|'.$unit.'|CO" />

					<input type="checkbox" name="'.$name.'" value="'.$k.'|'.$title.'|'.$unit.'|CO" checked disabled="disabled" />';

					}

					echo $k.'

					</td>		

					<td style="padding:5px;">'.$title.'</td>	

					<td style="padding:5px;">'.$unit.'</td>

					<td style="padding:5px;">'.$cat.'

					</td>	

					</tr>';

			}

		}

	}

	$sql1=result("SELECT * FROM course WHERE semester='$semester' AND level<=$level AND (dept='$dept' || dept='ALL') ORDER BY level DESC, courseCode ASC");



	while($res=mysql_fetch_array($sql1)){

		if(in_array($res[1],$pcos)==false){

		$n=$n+1;

		echo'<tr style="background-color:#fff;color:#005BAA;">

            <td style="padding:5px;"><b>['.$n.']</b></td>

            <td style="padding:5px;">';

			if($level==100){

				echo '<input type="hidden" name="'.$name.'" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[8].'" />';

				echo '<input type="checkbox" name="'.$name.'" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[8].'" checked disabled="disabled" />'.$res[1];

			}else{

				echo '<input type="checkbox" name="'.$name.'" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[8].'" />'.$res[1];

			}

            echo '</td>		

            <td style="padding:5px;">'.$res[2].'</td>	

			<td style="padding:5px;">'.$res[3].'</td>

			<td style="padding:5px;">

        	'.$res[8].'

            </td>	

			</tr>';

		}

	}

}



function ipnmeCoursesNew($dept,$level){

	connet();

	//to get previous carry over courses

	$n=0;

	$name='cos[]';

	$sql1=result("SELECT * FROM course_ipnme WHERE level=$level AND dept='$dept' ORDER BY courseCode ASC");



	while($res=mysql_fetch_array($sql1)){

		$n=$n+1;

		echo'<tr style="background-color:#fff;color:#005BAA;">

            <td style="padding:5px;"><b>['.$n.']</b></td>

            <td style="padding:5px;">

            <input type="checkbox" name="'.$name.'" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[8].'|'.$res[6].'" />'.$res[1].'

            </td>		

            <td style="padding:5px;">'.$res[2].'</td>	

			<td style="padding:5px;">'.$res[3].'</td>

			<td style="padding:5px;">

        	'.$res[8].'

            </td>	

			</tr>';

	}

}



function ipnmeCoursesNewUp($dept,$level,$matricno,$session){

	connet();

	//CHECK FOR CURRENT REGISTRATION

	$query=result("SELECT course1, course2, course3, course4, course5, course6, course7, course8, course9, course10, course11, course12, course13, course14, course15, course16, course17, course18, course19, course20 FROM course_reg_ipnme WHERE matricno='$matricno' AND level=$level AND session='$session' ORDER BY id DESC LIMIT 0,1");

	$ret=mysql_fetch_row($query);

	$cos=array();

	foreach($ret as $val){

		if($val != "||||"){

			$split=explode("|",$val);

			$cos[]=$split[0];

		}

	}



	//CHECK FOR CARRY OVER COURSES

	$n=0;

	$name='cos[]';

	$sql1=result("SELECT * FROM course_ipnme WHERE AND level=$level AND dept='$dept' ORDER BY courseCode ASC");

	while($res=mysql_fetch_array($sql1)){

		$n=$n+1;

		echo'<tr style="background-color:#fff;color:#005BAA; font-weight:bold;">

            <td style="padding:5px;"><b>['.$n.']</b></td>

            <td style="padding:5px;">

            <input type="checkbox" name="'.$name.'" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[8].'|'.$res[6].'"';

			if(in_array($res[1],$cos)){ echo "checked"; }

			echo ' />'.$res[1].'

            </td>		

            <td style="padding:5px;">'.$res[2].'</td>	

			<td style="padding:5px;">'.$res[3].'</td>

			<td style="padding:5px;">

        	'.$res[8].'

            </td>	

			</tr>';

	}

}



function listCoursesNewUp($dept,$level,$semester,$matricno,$session,$utyp){

	connet();

	//CHECK FOR CURRENT REGISTRATION

	$query=result("SELECT course1, course2, course3, course4, course5, course6, course7, course8, course9, course10, course11, course12, course13, course14, course15, course16, course17, course18, course19, course20, app, rem FROM course_reg WHERE matricno='$matricno' AND semester='$semester' AND level=$level AND session='$session' ORDER BY id DESC LIMIT 0,1");

	$ret=mysql_fetch_row($query);

	$cos=array();

	$c=0;

	foreach($ret as $val){

		$c+=1;

		if($c<21 && $val != "|||"){

			$split=explode("|",$val);

			$cos[]=$split[0];

		}

	}

	//checking for remark/comments

	if($ret[21]!=""){

		echo '<tr style="background-color:#FFF; color:#F00; font-style:italic;">	

				<td colspan="5" align="center" style="padding:5px;"><strong>'; 

		$rem=explode("|",$ret[21]);

		foreach($rem as $r){

			$rexp=explode("+",$r);

			echo '<span style="color:#005BAA;">'.$rexp[0].':</span> '.$rexp[1].'<br />';

		}

		echo '</strong></td>

			</tr>';

	}

	//CHECK FOR PREVIOUSLY REGISTERED COURSES

	$level2=$level-100;

	$pcos=listPCourses($level,$semester,$matricno);

	

	//CHECK FOR CARRY OVER COURSES

	$rpt=result("SELECT oc FROM resulttable WHERE matricno='$matricno' ORDER BY id DESC LIMIT 0,1");

	list($oc)=mysql_fetch_array($rpt);

	$n=0;

	$name='cos[]';

	if($oc!=""){

		$exp=explode("|",$oc);

		foreach($exp as $k){

			$sql=result("SELECT courseTitle,credit,cat FROM course WHERE courseCode='$k' AND semester='$semester' AND (dept='$dept' || dept='ALL') ORDER BY id DESC LIMIT 0,1");

			$c=mysql_num_rows($sql);

			if($c==1){

				list($title,$unit,$cat)=mysql_fetch_array($sql);

				$n=$n+1;

				echo'<tr style="background-color:#fff;color:#005BAA; font-weight:bold;">

					<td style="padding:5px;"><b>['.$n.']</b></td>

					<td style="padding:5px;">';

					//echo '<input type="checkbox" name="'.$name.'" value="'.$k.'|'.$title.'|'.$unit.'|CO"'; if(in_array($k,$cos)){ echo "checked"; } echo ' />';

					$exdept=array("Medicine and Surgery","Dentistry");

					if($utyp==2 || (in_array($dept,$exdept) && $level>100)){

						echo '<input type="checkbox" name="'.$name.'" value="'.$k.'|'.$title.'|'.$unit.'|CO"';

						if(in_array($k,$cos)){ echo " checked"; }

						echo ' />';

					}else{

						echo '<input type="hidden" name="'.$name.'" value="'.$k.'|'.$title.'|'.$unit.'|CO" />';

						echo '<input type="checkbox" value="'.$k.'|'.$title.'|'.$unit.'|CO" checked disabled="disabled" />';

					}

					echo $k.'

					</td>		

					<td style="padding:5px;">'.$title.'</td>	

					<td style="padding:5px;">'.$unit.'</td>

					<td style="padding:5px;">'.$cat.'

					</td>	

					</tr>';

			}

		}

	}

	$sql1=result("SELECT * FROM course WHERE semester='$semester' AND level<=$level AND (dept='$dept' || dept='ALL') ORDER BY level DESC, courseCode ASC");

	while($res=mysql_fetch_array($sql1)){

		if(in_array($res[1],$pcos)==false && in_array($res[1],$exp)==false){

		$n=$n+1;

		echo'<tr style="background-color:#fff;color:#005BAA; font-weight:bold;">

            <td style="padding:5px;"><b>['.$n.']</b></td>

            <td style="padding:5px;">';

			if($level==100){

				echo '<input type="hidden" name="'.$name.'" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[8].'" />';

				echo '<input type="checkbox" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[8].'" checked disabled="disabled" />'.$res[1];

			}else{

				echo '<input type="checkbox" name="'.$name.'" value="'.$res[1].'|'.$res[2].'|'.$res[3].'|'.$res[8].'"';

				if(in_array($res[1],$cos)){ echo " checked"; }

				echo ' />'.$res[1];

			}

            echo '</td>		

            <td style="padding:5px;">'.$res[2].'</td>	

			<td style="padding:5px;">'.$res[3].'</td>

			<td style="padding:5px;">

        	'.$res[8].'

            </td>	

			</tr>';

		}

	}//$ret[19]!=100 && 

	if($ret[20]!="APPROVED" || $utyp==2){

		echo '<tr style="background-color:#FFF; color:#F00; font-style:italic;">	

				<td colspan="5" align="center" style="padding:5px;">';

				echo '<textarea class="form-control" id="remb" name="remb" style="width: 500px; height: 70px;" cols="15" rows="1" placeholder="Comment/Note/Remark"></textarea>';

				echo '<input type="hidden" name="rema" value="'.$ret[21].'" />';

				echo '</td>

			</tr>';

		if($utyp==2 && $ret[20]!="APPROVED"){

			echo '<tr style="background-color:#FFF; color:#005BAA;">	

					<td colspan="5" align="center" style="padding:5px;">

						<input type="hidden" name="session" value="'.$session.'" />

						<input type="hidden" name="semester" value="'.$semester.'" />

						<input type="checkbox" name="approved" value="APPROVED" />

						<strong>Check Here and Click Update to Approve this registration if satisfied</strong>

					</td>

				</tr>';

		}

		echo '<tr style="background-color:#FFFFFF;">

				<td colspan="5" align="center"><input type="reset" class="button" name="reset" style="background-color:#DCDCBA;" value="&nbsp;Clear&nbsp;"/>&nbsp;&nbsp;

				<input type="submit" name="update" onclick="return confirm(\'Kindly review the reg., Are you sure you want to Update?\')"  value="&nbsp;Update Course Form!&nbsp;" style="background-color:#DCDCBA;" class="button" />				</td>

			</tr>';

	}

}



function listCoursesUpdate($dept,$level,$semester,$matricno,$session){

	connet();

	$query=result("SELECT course1, course2, course3, course4, course5, course6, course7, course8, course9, course10, course11, course12, course13, course14, course15, course16, course17, course18, course19, course20 FROM course_reg WHERE matricno='$matricno' AND semester='$semester' AND level=$level AND session='$session'");

	$ret=mysql_fetch_row($query);

	foreach($ret as $val){

		if($val != "|||"){

			$courses[]=$val;

		}

	}

	$totcos=sizeof($courses);

	$n=0;

	foreach($courses as $real){

		$n=$n+1;

		$name='cos'.$n.'[]';

		$split=explode("|",$real);

		echo'<tr style="background-color:#3399FF;color:#fff;">

            <td style="padding:5px;"><b>['.$n.']</b></td>

            <td style="padding:5px;">

            <select name="'.$name.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

            	<option>'.$split[0].'</option><option></option>';

				listCodes($dept,$semester,$level);

				echo '</select>

            </td>		

            <td style="padding:5px;"><input type="text" name="'.$name.'" value="'.$split[1].'" style="width:450px; height:18px;" /></td>	

			<td style="padding:5px;">'; numGenerator(1,7,$name,$split[2]); echo '</td>

			<td style="padding:5px;">

        	<select name="'.$name.'" id="type'.$n.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

              <option></option>

			  <option '; if($split[3]=="M"){ echo 'selected="selected"'; } echo '>M</option>

			  <option '; if($split[3]=="C"){ echo 'selected="selected"'; } echo '>C</option>

			  <option '; if($split[3]=="E"){ echo 'selected="selected"'; } echo '>E</option>

              <option '; if($split[3]=="CO"){ echo 'selected="selected"'; } echo '>CO</option>

			  <option '; if($split[3]=="R"){ echo 'selected="selected"'; } echo '>R</option>

			</select>

            </td>	

			</tr>';

	}

	

	$rem=20-$n;

	$m=$n;

	$level2=$level-100;

	for($k=1;$k<=$rem;$k++){

		$m=$m+1;

		$name2='cos'.$m.'[]';

		echo'<tr style="background-color:#3399FF;color:#fff;">

            <td style="padding:5px;"><b>['.$m.']</b></td>

            <td style="padding:5px;">

            <select name="'.$name2.'" id="code'.$k.'" onchange="return showCustomer(this)" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

            	<option value=""></option>';

				listCodesp($dept,$semester,$level2);

				echo '</select>

            </td>		

            <td style="padding:5px;"><input type="text" name="'.$name2.'" id="title'.$k.'" style="width:450px; height:18px;" /></td>	

			<td style="padding:5px;">'; numGenerator(1,7,$name2); echo '</td>

			<td style="padding:5px;">

        	<select name="'.$name2.'" id="type'.$k.'" style="width:80px; font-size:13px; line-height:2em; height:25px;">					

              <option></option>

			  <option>M</option>

			  <option>C</option>

			  <option>E</option>

              <option>CO</option>

			</select>

            </td>	

			</tr>';

	}

}



function listCoursesx($school,$dept,$level,$semester,$type){

	connet();

	$formQuery="SELECT * FROM course where school='$school' AND dept='$dept' AND level='$level' AND semester='$semester' AND type='$type' ORDER BY courseCode ASC";

	$formResult=result($formQuery);

	

	switch($semester){

	case "1ST":	

		$sem="Harmattan";

		break;

	case "2ND":

		$sem="Rain";

		break;

	default:

		$sem="Unknown";

		break;

	}

	

	$n=0;

	echo'<tr>

			<td colspan="4" align="left"><hr /></td>

	</tr>';

	echo'<tr>

			<td colspan="4" align="left">'.$level.' Level '.$sem.' Semester Courses</td>

	</tr>';

	echo'<tr>

			<td colspan="4" align="left"><hr /></td>

	</tr>';

	echo'<tr>

			<td width="20%" align="left"><b>Course Code</b></td>

			<td width="40%" align="left"><b>Course Title</b></td>

			<td width="20%" align="left"><b>Course Unit</b></td>

			<td width="20%" align="left"><b>Course Status</b></td>

		</tr>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<tr>

			<td width="20%" align="left">'.$resultData[1].'</td>

			<td width="40%" align="left">'.$resultData[2].'</td>

			<td width="20%" align="left">'.$resultData[3].'</td>

			<td width="20%" align="left">'.$resultData[4].'</td>

		</tr>';

	}

	//var_dump($setData);

}



function listCoursesDeg($school,$dept,$level,$semester){

	connet();

	$formQuery="SELECT * FROM unicourse where school='$school' AND dept='$dept' AND level='$level' AND semester='$semester' ORDER BY courseCode ASC";

	$formResult=result($formQuery);

	

	switch($semester){

	case "1ST":	

		$sem="Harmattan";

		break;

	case "2ND":

		$sem="Rain";

		break;

	default:

		$sem="Unknown";

		break;

	}

	

	$n=0;

	echo'<tr>

			<td colspan="4" align="left"><hr /></td>

	</tr>';

	echo'<tr>

			<td colspan="4" align="left">'.$level.' Level '.$sem.' Semester Courses</td>

	</tr>';

	echo'<tr>

			<td colspan="4" align="left"><hr /></td>

	</tr>';

	echo'<tr>

			<td width="20%" align="left"><b>Course Code</b></td>

			<td width="40%" align="left"><b>Course Title</b></td>

			<td width="20%" align="left"><b>Course Unit</b></td>

			<td width="20%" align="left"><b>Course Status</b></td>

		</tr>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<tr>

			<td width="20%" align="left">'.$resultData[1].'</td>

			<td width="40%" align="left">'.$resultData[2].'</td>

			<td width="20%" align="left">'.$resultData[3].'</td>

			<td width="20%" align="left">'.$resultData[4].'</td>

		</tr>';

	}

	//var_dump($setData);

}



function checkIfStaff($id){

	connet();

	$checkQuery="SELECT * FROM staff2 where staffNo LIKE '%$id'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_num_rows($checkResult);

	return $returnedRows;

}





function retStaffName($id){

	connet();

	$checkQuery="SELECT * FROM stafflogin where staffId = '$id'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	$retVal=$returnedRows[6].', '.$returnedRows[7];

	return $retVal;

}





function listStaff($id){

	connet();

	/*SELECT BASIC STAFF INFORMATION FROM STAFFLOGIN TABLE*/

	$formQuery="SELECT * FROM stafflogin where staffId='$id'";

	$formResult=result($formQuery);

	$resultData=mysql_fetch_array($formResult);

	

	/*SELECT OTHER STAFF INFORMATION FROM STAFF2 TABLE*/

	if(checkIfStaff($id)>=1){

		$formQuery2="SELECT * FROM staff2 where staffNo LIKE '%$id'";

		$formResult2=result($formQuery2);

		$resultData2=mysql_fetch_array($formResult2);

		

		for($a=0;$a<sizeof($resultData2);$a++){

			if($resultData2[$a]=="" || $resultData2[$a]=="NULL"){

				$newResult[$a]="N/A";

			}else{

				$newResult[$a]=$resultData2[$a];

			}

		}

	}

	

	echo '<table width="60%">';

	echo'<tr><td width="60%" align="left" colspan="3"><span style="font-size:17px;"><B>CURRICULUM VITAE</B></span></td></tr>';

	echo'<tr><td width="60%" align="left" colspan="3"><hr /></td></tr>';

	echo'<tr><td width="30%" align="left" colspan="2"></td><td width="20%" align="left" rowspan="14" valign=top><img src="'.$newResult[2].'" width="90"</td></tr>';

	echo'<tr><td width="30%" align="left"><b>Title</b></td><td width="30%" align="left">'.$resultData[5].'</td></tr>';

	echo'<tr><td  align="left"><b>Name</b></td><td  align="left">'.$resultData[6].' , <span style="font-size:8px;">'.$resultData[7].'</span></td></tr>';

	echo'<tr><td  align="left"><b>Designation</b></td><td  align="left">'.$resultData[9].'</td></tr>';

	echo'<tr><td  align="left"><b>Post Held</b></td><td  align="left">'.$resultData[11].'</td></tr>';

	?>

	<?php

	if(checkIfStaff($id)>=1){

		echo'<tr><td  align="left"><b>Date of Birth</b></td><td  align="left">'.$newResult[6].'/'.$newResult[7].'</td></tr>';

		echo'<tr><td  align="left"><b>Sex</b></td><td  align="left">'.convertGender($newResult[12]).'</td></tr>';

		echo'<tr><td  align="left"><b>Marital Status</b></td><td  align="left">'.ucfirst($newResult[9]).'</td></tr>';

		echo'<tr><td  align="left"><b>State of Origin</b></td><td  align="left">'.ucfirst($newResult[59]).'</td></tr>';

		echo'<tr><td  align="left"><b>Nationality</b></td><td  align="left">'.ucfirst($newResult[13]).'</td></tr>';

		echo'<tr><td  align="left"><b>Email Address</b></td><td  align="left">'.ucfirst($newResult[14]).'</td></tr>';

		echo'<tr><td  align="left"><b>Phone Number</b></td><td  align="left">'.ucfirst($newResult[61]).', '.ucfirst($newResult[62]).'</td></tr>';

		echo'<tr><td  align="left"><b>Address</b></td><td  align="left">'.ucfirst($newResult[57]).'</td></tr>';

		echo'<tr><td  align="left" colspan=2><hr  width=50%/><b>Academic Qualification</b><hr  width=50%/></td></tr>';

		echo'<tr><td  align="left" width=90% colspan=2>';

			echo '<table width=90%>';

				echo '<tr>';

					echo'<td  align="left" width=30%><b>Institution</b></td>';

					echo'<td  align="left" width=30%><b>Qualification</b></td>';

					echo'<td  align="left" width=30%><b>Year</b></td>';

				echo '</tr>';

				echo '<tr>';

					echo'<td  align="left">'.ucfirst($newResult[17]).'</td>';

					echo'<td  align="left">'.ucfirst($newResult[18]).'</td>';

					echo'<td  align="left">'.ucfirst($newResult[19]).'</td>';

				echo '</tr>';

			echo '</table>';

		echo '</td></tr>';

		echo'<tr><td  align="left" colspan=2><hr  width=50%/><b>Working Experience</b><hr  width=50%/></td></tr>';

		echo'<tr><td  align="left" width=90% colspan=2>';

			echo '<table width=90%>';

				echo '<tr>';

					echo'<td  align="left" width=30%><b>Organization</b></td>';

					echo'<td  align="left" width=30%><b>Position</b></td>';

					echo'<td  align="left" width=30%><b>Designation</b></td>';

					echo'<td  align="left" width=10%><b>Year</b></td>';

				echo '</tr>';

				echo '<tr>';

					echo'<td  align="left">'.ucfirst($newResult[41]).'</td>';

					echo'<td  align="left">'.ucfirst($newResult[42]).'</td>';

					echo'<td  align="left">'.ucfirst($newResult[43]).'</td>';

					echo'<td  align="left">'.ucfirst($newResult[44]).'</td>';

				echo '</tr>';

				echo '<tr>';

					echo'<td  align="left">'.ucfirst($newResult[45]).'</td>';

					echo'<td  align="left">'.ucfirst($newResult[46]).'</td>';

					echo'<td  align="left">'.ucfirst($newResult[47]).'</td>';

					echo'<td  align="left">'.ucfirst($newResult[47]).'</td>';

				echo '</tr>';

			echo '</table>';

		echo '</td></tr>';

	}

	//var_dump($setData);

	echo "</table>";

}





function convertGender($gender){

	if(strtoupper($gender)=='F'){

		$retGender='Female';

	}

	if(strtoupper($gender)=='M'){

		$retGender='Male';

	}

	return $retGender;

}



function course_Edit($table,$sch,$dept,$sem,$level){

	connet();

	$sql="SELECT * FROM $table WHERE school='$sch' AND dept='$dept' AND semester='$sem' AND level='$level'";	

	$qresult=result($sql);

	$data=mysql_fetch_array($qresult);

	

}

function cvCategories(){

	echo '<option value="">-- Select --</option>

                <option value="ADMINISTRATION">ADMINISTRATION</option>

                <option value="UNN_DEGREE">UNN_DEGREE</option>

				<option value="NCE SANDWICH">NCE SANDWICH</option>

                <option value="ART_SOCIAL_SCIENCE">ART_SOCIAL_SCIENCE</option>

                <option value="VOC_TECH_EDUCATION">VOC_TECH_EDUCATION</option>

                <option value="SCIENCE">SCIENCE</option>

                <option value="EDUCATION">EDUCATION</option>

                <option value="LANGUAGES">LANGUAGES</option>

                <option value="PRE-NCE_AND_REMEDIAL_PROGRAMMES">PRE-NCE_AND_REMEDIAL_PROGRAMMES</option>';

	

	

}

function listDeptStaff($dept,$school="ADMINISTRATION"){

	connet();

	$formQuery="SELECT * FROM staff_list WHERE school LIKE '%$school%' AND department LIKE '%$dept%' ORDER BY id, rank ASC";

	$formResult=result($formQuery);

	$n=0;

	echo '<table width="100%">';

	echo'<tr>

			<td width="5%" align="left"><b>S/N</b></td>

			<td width="30%" align="left"><b>Name</b></td>

			<td width="25%" align="left"><b>Qualification</b></td>

			<td width="20%" align="left"><b>Designation</b></td>

			<td width="20%" align="left"><b>E-Mail</b></td>

		</tr>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<tr>

			<td width="5%" align="left"><b>['.$n.']</b></td>

			<td width="30%" align="left"><a href="../portal/staffcv/'.$resultData[8].'" target="_blank">'.$resultData[2].'</td>

			<td width="25%" align="left">'.$resultData[3].'</td>

			<td width="20%" align="left">'.$resultData[5].'</td>

			<td width="20%" align="left">'.$resultData[4].'</td>';

		echo

		'</tr>';

	}

	//var_dump($setData);

	echo '

		<tr>

			<td colspan="5" align="left"><hr /></td>

		</tr>

		<tr>

			<td colspan="5" align="left">';

			?>

			<?php

			if(checkIfCourse($dept)>0){

			echo

			'<a href="../displayCourses.php?dept='.$dept.'&school='.$school.'" target="_blank">List of Courses</a>';

			}

			?>

			<?php

			echo

			'</td>

		</tr>

		</table>';

}



function listDeptStaff2($dept,$school="ADMINISTRATION"){

	connet();

	$formQuery="SELECT * FROM staff_list WHERE school LIKE '%$school%' AND department LIKE '$dept%' ORDER BY id, rank ASC";

	$formResult=result($formQuery);

	$n=0;

	echo '<table width="100%">';

	echo'<tr>

			<td width="5%" align="left"><b>S/N</b></td>

			<td width="30%" align="left"><b>Name</b></td>

			<td width="25%" align="left"><b>Qualification</b></td>

			<td width="20%" align="left"><b>Designation</b></td>

			<td width="20%" align="left"><b>E-Mail</b></td>

		</tr>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<tr>

			<td width="5%" align="left"><b>['.$n.']</b></td>

			<td width="30%" align="left"><a href="../portal/staffcv/'.$resultData[8].'" target="_blank">'.$resultData[2].'</td>

			<td width="25%" align="left">'.$resultData[3].'</td>

			<td width="20%" align="left">'.$resultData[5].'</td>

			<td width="20%" align="left">'.$resultData[4].'</td>';

		echo

		'</tr>';

	}

	//var_dump($setData);

	echo '

		<tr>

			<td colspan="5" align="left"><hr /></td>

		</tr>

		<tr>

			<td colspan="5" align="left">';

			?>

			<?php

			if(checkIfCourse($dept)>0){

			echo

			'<a href="../displayCourses.php?dept='.$dept.'&school='.$school.'" target="_blank">List of Courses</a>';

			}

			?>

			<?php

			echo

			'</td>

		</tr>

		</table>';

}



function listDeptStaffx($dept,$school="ADMINISTRATION"){

	connet();

	$formQuery="SELECT * FROM staff_list WHERE school LIKE '%$school%' AND department LIKE '$dept%' ORDER BY id, rank ASC";

	$formResult=result($formQuery);

	$n=0;

	echo '<table width="100%">';

	echo'<tr>

			<td width="5%" align="left"><b>S/N</b></td>

			<td width="30%" align="left"><b>Name</b></td>

			<td width="25%" align="left"><b>Qualification</b></td>

			<td width="20%" align="left"><b>Designation</b></td>

			<td width="20%" align="left"><b>E-Mail</b></td>

		</tr>';

	while($resultData=mysql_fetch_array($formResult)){

		$n+=1;

		echo'<tr>

			<td width="5%" align="left"><b>['.$n.'</b></td>

			<td width="30%" align="left"><a href="../portal/staffcv/'.$resultData[8].'" target="_blank">'.$resultData[2].'</td>

			<td width="25%" align="left">'.$resultData[3].'</td>

			<td width="20%" align="left">'.$resultData[5].'</td>

			<td width="20%" align="left">'.$resultData[4].'</td>';

		echo

		'</tr>';

	}

	//var_dump($setData);

	echo '

		<tr>

			<td colspan="5" align="left"><hr /></td>

		</tr>

		<tr>

			<td colspan="5" align="left">';

			?>

			<?php

			if(checkIfCoursex($dept,"OLD")>0){

			echo

			'[<a href="../displayCoursesx.php?dept='.$dept.'&school='.$school.'&type=OLD" target="_blank">View Old Courses</a>]&nbsp;&nbsp;&nbsp;';

			}

			if(checkIfCoursex($dept,"NEW")>0){

			echo

			'[<a href="../displayCoursesx.php?dept='.$dept.'&school='.$school.'&type=NEW" target="_blank">View New Courses</a>]';

			}

			?>

			<?php

			echo

			'</td>

		</tr>

		</table>';

}



function listDeptStaffDeg($dept,$school){

	connet();

	$formQuery="SELECT * FROM stafflogin where school='trim($school)' AND department='trim($dept)' ORDER BY staffId ASC";

	$formResult=result($formQuery);

	$n=0;

	echo '<table width="100%">';

	echo'<tr>

			<td width="40%" align="left"><b>Name</b></td>

			<td width="20%" align="left"><b>Qualification</b></td>

			<td width="10%" align="left"><b>Speciality</b></td>

			<td width="30%" align="left"><b>Designation</b></td>

		</tr>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<tr>

			<td width="40%" align="left"><a href="staffCvPreview.php?staffId='.$resultData[1].'" target="_blank">'.$resultData[6].'</td>

			<td width="20%" align="left">'.$resultData[7].'</td>

			<td width="10%" align="left">'.$resultData[8].'</td>

			<td width="30%" align="left">'.$resultData[9].'</td>';

		echo

		'</tr>';

	}

	//var_dump($setData);

	echo '

		<tr>

			<td colspan="5" align="left"><hr /></td>

		</tr>

		<tr>

			<td colspan="5" align="left">';

			?>

			<?php

			if(checkIfCourse2($dept,$school)>0){

			echo

			'<a href="displayCourses2.php?dept='.$dept.'&school='.$school.'" target="_blank">List of Courses</a>';

			}

			?>

			<?php

			echo

			'</td>

		</tr>

		</table>';

}



function checkIfCourse($dept){

	connet();

	$cardQuery="select * from course where dept='$dept'";

	$cardResult=result($cardQuery);

	$cardVals=mysql_num_rows($cardResult);

	return $cardVals;

}



function checkIfCoursex($dept,$type){

	connet();

	$cardQuery="select * from course where dept='$dept' and type='$type'";

	$cardResult=result($cardQuery);

	$cardVals=mysql_num_rows($cardResult);

	return $cardVals;

}



function checkIfCourse2($dept,$school){

	connet();

	$cardQuery="select * from unicourse where dept='$dept' AND school='$school'";

	$cardResult=result($cardQuery);

	$cardVals=mysql_num_rows($cardResult);

	return $cardVals;

}



function listDeptStaff3($dept){

	connet();

	$formQuery="SELECT * FROM stafflogin where trim(department)='PROVOST' ORDER BY staffId ASC";

	$formResult=result($formQuery);

	$resultData=mysql_fetch_array($formResult);

	echo $resultData["staffId"];

}



function deleteC($dept,$semester,$level){

	connet();

	$formQuery="SELECT * FROM course where dept='$dept' AND level=$level ORDER BY semester";

	$formResult=result($formQuery);

	$n=0;

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<tr>

			<td width="120" align="left">['.$n.']</td>

			<td width="100" align="left">'.$resultData[1].'</td>

			<td width="230" align="left">'.$resultData[2].'</td>

			<td width="50" align="left"><a href="deleteCourse.php?id='.$resultData[1].'&actionPassed=del" onClick="return confirm(\'Delete Record?\')">Delete</a></td>

		</tr>';

	}

	//var_dump($setData);

}



function selectC(){

	connet();

	$formQuery="SELECT * FROM news ORDER BY pub_date DESC";

	$formResult=result($formQuery);

	$n=0;

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<tr>

			<td width="50" align="left">['.$n.']</td>

			<td width="400" align="left" colspan="2">'.$resultData[1].'</td>

			<td width="50" align="left"><a href="edit_news2.php?id='.$resultData[0].'&actionPassed=edit" onClick="return confirm(\'Edit News?\')">Edit</a></td>

		</tr>';

	}

	//var_dump($setData);

}



function edit_News($id){

	connet();

	$formQuery="SELECT id, caption, summary, details FROM news WHERE id=$id";

	$formResult=result($formQuery);

	list($id,$caption,$summary,$details)=mysql_fetch_array($formResult);

	echo '<form action="edit_news2.php" method="post" >

		 	<tr>

				<td colspan="2" style="padding-top:5px;" align="center">

					<span style="text-align:left;font-size:12px;"> <b>Site News Edit Page</b></span>				</td>

			</tr>

			<tr>

			<td width="123" align="left" style="padding-left:15px;padding-top:6px;">News Caption:</td>

			  <td width="365" align="left" style="padding-top:10px;"><input type="hidden" value="'.$id.'" name="newsSetup[]" />

			  	<input type="text" name="newsSetup[]" id="caption" value="'.$caption.'" maxlength="60" size="50"/>

			  </td>

			</tr>

			<tr>

			<td width="123" align="left" style="padding-left:15px;">News Summary</td>

			  <td width="365" align="left">

			  	<input type="text" name="newsSetup[]" id="summary" value="'.$summary.'" maxlength="120" size="50"/> </td>

			</tr>

			<tr>

			<td width="123" align="left" style="padding-left:15px;">News Details</td>

			  <td width="365" align="left">

			  	<textarea name="newsSetup[]" id="details" cols="38" rows="10">'.$details.'</textarea>	

			  </td>

			</tr>

			<tr>

				<td colspan="2" align="right"><input type="reset" name="reset" value="Clear"/>

				<input type="submit" name="update" value="Update News" onClick="return formCheck()"/>				</td>

			</tr>

		 		</form>';

	

}



function deleteNews(){

	connet();

	$formQuery="SELECT * FROM news ORDER BY pub_date DESC";

	$formResult=result($formQuery);

	$n=0;

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<tr>

			<td width="50" align="left">['.$n.']</td>

			<td width="400" align="left" colspan="2">'.$resultData[1].'</td>

			<td width="50" align="left"><a href="delete_news.php?id='.$resultData[0].'&actionPassed=del" onClick="return confirm(\'Delete Record?\')">Delete</a></td>

		</tr>';

	}

	//var_dump($setData);

}



function delete_News($id){  // deletes aspecified course from the course table

	connet();

	$deleteQuery="DELETE FROM news WHERE id=$id";

	$deleteResult=result($deleteQuery);

	return  $deleteResult;

}



function upcal($calVals,$tableName){

	connet();

	$calQuery="UPDATE $tableName SET session='$calVals[0]', semester='$calVals[1]', startyear='$calVals[2]', endyear='$calVals[3]'";

	$calResult=result($calQuery);

	return $calResult;

}



function updateStudentInfo($calVals,$matric){

	connet();

	$upStudQuery="UPDATE studentiformation SET matricno='$calVals[0]', surname='$calVals[1]', onames='$calVals[2]', sex='$calVals[3]', dob='$calVals[4]', dom='$calVals[5]', doy='$calVals[6]', ayear='$calVals[7]', course='$calVals[8]', school='$calVals[9]', department='$calVals[10]', minor='$calVals[11]', level='$calVals[12]', state='$calVals[13]', postal='$calVals[14]', residential='$calVals[15]', phone='$calVals[16]',  nextofkin='$calVals[17]', relationship='$calVals[18]', address='$calVals[19]' where matricno='$matric'";

	$upStudResult=result($upStudQuery);

	return $upStudResult;

}



function checkReg($matricno,$table,$session,$semester,$level,$dept,$school){

	connet();

	$checkQuery="SELECT * FROM $table WHERE matricno='$matricno' AND dept='$dept' AND school='$school' AND semester='$semester' AND level='$level' AND session='$session'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_num_rows($checkResult);

	return $returnedRows;

}



function checkPreg2($matricno,$semester,$session){

	connet();	

			$q=result("SELECT * FROM uniregistrations WHERE matricno='$matricno' AND semester='$semester' AND session='$session'");

			$c_rows=mysql_num_rows($q);

			if($c_rows>=1){

				return 1;

			}else{

				return 0;

			}	

}



function checkPreg($matricno,$semester,$session){

	connet();

	$checkQuery=result("SELECT department, level FROM studentiformation WHERE matricno='$matricno'");

	list($dept,$level)=mysql_fetch_array($checkQuery);

		switch($level){

			case "PRE-NCE":

			$table="prencereg";

			break;

			default:

			$table=strtolower($dept).$level;

			break;

		}

			$q=result("SELECT * FROM $table WHERE matricno='$matricno' AND semester='$semester' AND session='$session'");

			

			$c_rows=mysql_num_rows($q);

			/*if($level=="PRE-NCE"){

				return 1;

			}else*/

			

			if($c_rows>=1){

				return 1;

			}else{

				return 0;

			}	

}



function tableCreator($tableName){

connet();

	//$courseSize=sizeof($course);

	$tQuery="CREATE TABLE IF NOT EXISTS $tableName (id INT(9) unsigned NOT NULL auto_increment,";

		  

		  for($s=0;$s<25;$s++){

		  	$tQuery = $tQuery."course".$s." VARCHAR(20) NOT NULL,";

			$tQuery = $tQuery."course".$s."title VARCHAR(200) NOT NULL,";

			$tQuery = $tQuery."course".$s."type VARCHAR(20) NOT NULL,";

			$tQuery = $tQuery."course".$s."unit int(5) NOT NULL,";

			$tQuery = $tQuery."course".$s."status VARCHAR(10) NOT NULL,";

		  }

		  $tQuery = $tQuery."

		  matricno VARCHAR(20) NOT NULL,

		  cyear YEAR NOT NULL,

		  dept VARCHAR(75) NOT NULL,

  		  school VARCHAR(75) NOT NULL,

  		  semester VARCHAR(5) NOT NULL,

  		  level VARCHAR(5) NOT NULL,

		  session VARCHAR(12) NOT NULL,

		  rDate DATE NOT NULL,

    	  PRIMARY KEY  (id));";

	

	//return $tQuery;

	$tResult=result($tQuery);

	return $tResult."<br />";

	//echo $tQuery;

}



function tableAlter($tableName,$dept,$semester,$level,$course,$codes){

connet();

	//$tableName = $dept.$semester.$level;

	$courseSize=sizeof($course);

	$tQuery="ALTER TABLE $tableName ADD ";

		  	$tQuery = $tQuery.$course." VARCHAR(8) NOT NULL after $codes,";

			$tQuery = $tQuery."ADD ".$course."unit INT(3) NOT NULL after $course";

		    $tQuery = $tQuery.";";

	$tResult=result($tQuery);

	return $tResult."<br />";

	//echo $tQuery;

}



function listCodes2($major,$minor,$semester,$level,$school,$hid){

	connet();

	

	switch($level){

	case 300:	

		$formQuery="SELECT * FROM course where level='$level' AND  semester='$semester' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%') ORDER BY dept ASC";

		break;

	default:

		$formQuery="SELECT * FROM course where level='$level' AND  semester='$semester' AND type='NEW' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%') ORDER BY dept ASC";

		break;

	}

	

	//$formQuery="SELECT * FROM course where level='$level' AND  semester='$semester' AND type='NEW' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%') ORDER BY dept ASC";

	$formResult=result($formQuery);



		echo '<select name="courses[]" id="'.$hid.'" onchange="return showCustomer(this)"><option value=""></option>';

		while($resultData=mysql_fetch_array($formResult)){

			#$rest[]=$resultData;

                        echo '<option value="'.$resultData['courseCode'].'">'.$resultData['courseCode'].'</option>';



		}

		

		echo '</select>';



}



function listCodes2_nceBack($major,$minor,$semester,$level,$school,$hid){

	connet();

	$formQuery="SELECT * FROM course where level =$level AND  semester='$semester' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%') ORDER BY dept ASC";

	$formResult=result($formQuery);

	$n=0;

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo '<select name="courses[]" id="'.$hid.'" onchange="return showCustomer(this)"><option value=""></option>';

		while($resultData=mysql_fetch_array($formResult)){

			$rest[]=$resultData;

		}

		for($a=0;$a<sizeof($rest);$a++){

			echo'<option value="'.$rest[$a]['courseCode'].'">'.$rest[$a]['courseCode'].'</option>';

		}

		echo '</select>';

	}

	//var_dump($setData);

}



function checkFormCodes($major,$minor,$semester,$level,$school,$vals,$hid){

	connet();

	switch($level){

		case 300:

			$formQuery="SELECT * FROM course where level='$level' AND  semester='$semester' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%')";

			break;

		default:

			$formQuery="SELECT * FROM course where level='$level' AND  semester='$semester' AND type='NEW' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%')";

			break;

	}

	

	//$formQuery="SELECT * FROM course where level='$level' AND  semester='$semester' AND type='NEW' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%')";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="capturedVals[]" id="'.$hid.'" onchange="return showCustomer(this)"><option value="" ';

			?>

			<?php

			if($vals==""){

				echo "selected";

			}

			?>

			<?php

			echo

		'></option>';

	

	while($resultData=mysql_fetch_array($formResult)){

		#$n=$n+1;

			#$val=$resultData['courseCode'];

			echo'<option value="'.$resultData['courseCode'].'"';

			?>

			<?php

			if($vals==$resultData['courseCode']){

				echo "selected";

			}

			?>

			<?php

			echo 

			'>'.$resultData['courseCode'].'</option>';

		}

		echo '</select>';

	#}

	//var_dump($setData);

}



function listdept(){

	connet();

	$formQuery="SELECT DISTINCT(dept) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="addCourse[]" id="dept"><option value=""></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'">'.strtoupper($resultData[0]).'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listdept3(){

	connet();

	$formQuery="SELECT DISTINCT(dept) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="cSetup[]" id="dept"><option value=""></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'">'.strtoupper($resultData[0]).'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listdept2($value){

	connet();

	$formQuery="SELECT DISTINCT(dept) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="pre[]" style="width:200px;"><option value=""></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'"';

		?>

		<?php

		if($resultData[0]==$value){

			echo "selected";

		}

		?>

		<?php

		echo

		'

		>'.strtoupper($resultData[0]).'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function getStateListUpdatep($name,$id,$value,$event){

connet();

	$query="SELECT DISTINCT(state) FROM state";

	$queryResult=result($query);

	$recRet=mysql_num_rows($queryResult);

	//return $recRet;

	echo '<select name="'.$name.'" id="'.$id.'" class="propertySelect" '.$event.' style="width:145px;"><option value="" selected></option>';

	if($recRet>0){

		while($recSet=mysql_fetch_array($queryResult)){

			echo '<option value="'.$recSet[0].'" title="'.$id.'"';

			if($recSet[0]==$value){

				echo 'selected';	

			}

			echo '>'.ucfirst($recSet[0]).'</option>';

		}

	}

	echo '</select>';

}



function getStateListp($name,$id,$title,$event){

connet();

	$query="SELECT DISTINCT(state) FROM state";

	$queryResult=result($query);

	$recRet=mysql_num_rows($queryResult);

	//return $recRet;

	echo '<select name="'.$name.'" id="'.$id.'" class="propertySelect" '.$event.' style="width:125px;"><option value="" selected>Select State</option>';

	if($recRet>0){

		while($recSet=mysql_fetch_array($queryResult)){

			echo '<option value="'.$recSet[0].'" title="'.$id.'">'.ucfirst($recSet[0]).'</option>';

		}

	}

	echo '</select>';

}



function listdept4(){

	connet();

	$formQuery="SELECT DISTINCT(dept) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="cv2[]" id="dept"><option value=""></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'">'.strtoupper($resultData[0]).'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listdeptStudInfo(){

	connet();

	$formQuery="SELECT DISTINCT(dept) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="info[]" style="font-size:9px;"><option value=""></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'">'.strtoupper($resultData[0]).'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listMinorStudInfo(){

	connet();

	$formQuery="SELECT DISTINCT(dept) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="info[]" style="font-size:9px;"><option value=""></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'">'.strtoupper($resultData[0]).'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listdeptStudInfoUp($passedVal,$name,$id=""){

	connet();

	$formQuery="SELECT DISTINCT(dept) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="'.$name.'" id="'.$id.'" style="font-size:10px; width:200px;" id="dept"><option value="" ';

		?>

		<?php

			if($passedVal==""){

				echo "selected";

			}

		?>

		<?php

	echo

	'></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'" ';

			?>

		<?php

			if($passedVal=="$resultData[0]"){

				echo "selected";

			}

		?>

		<?php

	echo

		'>'.strtoupper($resultData[0]).'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listdeptPreUp($passedVal){

	connet();

	$formQuery="SELECT DISTINCT(dept) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="pre[]" style="font-size:10px; width:200px;"><option value="" ';

		?>

		<?php

			if($passedVal==""){

				echo "selected";

			}

		?>

		<?php

	echo

	'></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'" ';

			?>

		<?php

			if($passedVal=="$resultData[0]"){

				echo "selected";

			}

		?>

		<?php

	echo

		'>'.strtoupper($resultData[0]).'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}





function activeDepartments2(){

	connet();

	$activeQuery="SELECT tablename FROM courseregistrationsetuplog";

	$activeResult=result($activeQuery);

	while($activeData=mysql_fetch_array($activeResult)){

		echo "&nbsp;&nbsp;".$activeData[0]."<br />";

	}

}



function activeDepartments(){

	connet();

	$activeQuery="show tables";

	$activeResult=result($activeQuery);

	while($activeData=mysql_fetch_array($activeResult)){

		$storeTables[]=$activeData[0];

	}

	$count=0;

	for($tableIndex=0;$tableIndex<sizeof($storeTables);$tableIndex++){

		$retSubStr[$tableIndex]=substr(strrev($storeTables[$tableIndex]),0,2);

		if($retSubStr[$tableIndex]=='00'){

			$count=$count+1;

			echo '['.$count."]&nbsp;&nbsp;".$storeTables[$tableIndex].'<br />';

		}

	}

}



function listschool(){

	connet();

	$formQuery="SELECT DISTINCT(school) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="addCourse[]" id="skul"><option value=""></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'">'.$resultData[0].'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listschoolStudInfo(){

	connet();

	$formQuery="SELECT DISTINCT(school) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="info[]" style="width:146px;font-size:10px;"><option value=""></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'">'.$resultData[0].'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listschoolStudInfoUp($passedVal,$name){

	connet();

	$formQuery="SELECT DISTINCT(school) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="'.$name.'" style="font-size:10px; width:200px;"><option value="" ';

		?>

		<?php

			if($passedVal==""){

				echo "selected";

			}

		?>

		<?php

	echo

	'></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'" ';

			?>

		<?php

			if($passedVal=="$resultData[0]"){

				echo "selected";

			}

		?>

		<?php

	echo

		'>'.$resultData[0].'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listschoolStudInfoAuto($val,$name,$id=""){

	connet();

	$query="select distinct(school) from course";

	$result=mysql_query($query);

	echo '<select name="'.$name.'" id="'.$id.'" style="width:200px;font-size:10px;" onChange="getState(this.value)" >';

	echo '<option value=""';

	?><?php

			if($val==""){

				echo "selected";

			}

		?>

		<?php

	echo '></option>';

	while($row=mysql_fetch_array($result)) {

		echo '<option value="'.$row[0].'"';

		?>

		<?php

			if($val=="$row[0]"){

				echo "selected";

			}

		?>

		<?php

	echo

		'>'.$row[0].'</option>';

	}

	echo '</select>';





}



function listschoolNce($name){

	connet();

	$query="select distinct(school) from course";

	$result=mysql_query($query);

	echo '<select name="'.$name.'" style="width:200px; font-size:11px;">';

	echo '<option value="">Select School</option>';

	while($row=mysql_fetch_array($result)) {

		echo '<option value="'.$row[0].'">'.$row[0].'</option>';

	}

	echo '</select>';

}



function listschoolDeg($name){

	connet();

	$query="select distinct(school) from unicourse";

	$result=mysql_query($query);

	echo '<select name="'.$name.'" style="width:200px;font-size:10px;">';

	echo '<option value="">Select School</option>';

	while($row=mysql_fetch_array($result)) {

		echo '<option value="'.$row[0].'">'.$row[0].'</option>';

	}

	echo '</select>';

}



function listdeptStudInfoUpAuto($passedVal,$name,$school,$id=""){

	connet();

	$formQuery="SELECT DISTINCT(dept) FROM course WHERE school='$school'";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="'.$name.'" id="'.$id.'" style="font-size:10px; width:200px;"><option value="" ';

		?>

		<?php

			if($passedVal==""){

				echo "selected";

			}

		?>

		<?php

	echo

	'></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'" ';

			?>

		<?php

			if($passedVal=="$resultData[0]"){

				echo "selected";

			}

		?>

		<?php

	echo

		'>'.strtoupper($resultData[0]).'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listschoolStaff(){

	connet();

	$formQuery="SELECT DISTINCT(school) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="cv2[]"><option value=""></option>';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'">'.$resultData[0].'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function listschool2($value){

	connet();

	$formQuery="SELECT DISTINCT(school) FROM course";

	$formResult=result($formQuery);

	$n=0;

	echo '<select name="pre[]" style="width:200px;">';

	while($resultData=mysql_fetch_array($formResult)){

		$n=$n+1;

		echo'<option value="'.$resultData[0].'"';

		?>

		<?php

		if($resultData[0]==$value){

			echo "selected";

		}

		?>

		<?php

		echo

		'>'.$resultData[0].'</option>';

	}

	echo '</select>';

	//var_dump($setData);

}



function checkIfStudentExist($matricno){

	connet();

	$checkQuery="SELECT * FROM studentiformation WHERE matricno='$matricno'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_num_rows($checkResult);

	return $returnedRows;	

}



function infoDisable($matricno){

	connet();

	$checkQuery="SELECT date FROM studentiformation WHERE matricno='$matricno'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	return $returnedRows;	

}



function infoDisable2($matricno){

	connet();

	$checkQuery="SELECT date FROM unistudentiformation WHERE matricno='$matricno'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	return $returnedRows;	

}



function checkIfAdminExist($user,$pass,$phrase){

	$pass=md5($pass);

	$phrase=md5($phrase);

	connet();

	$adminQuery="SELECT * FROM admintable WHERE username='$user' AND password='$pass' AND passphrase='$phrase'";

	$adminResult=result($adminQuery);

	$returnedRows=mysql_num_rows($adminResult);

	return $returnedRows;	

}



function checkIfExistTable($table){

	connet();

	$checkResult=mysql_list_tables("college");

	$returnedRows=mysql_fetch_array($checkResult);

		//var_dump($returnedRows);

		return sizeof($returnedRows);

}

function checkIfExist($matricno,$semester,$level,$session){

	connet();

	$sql=result("SELECT * FROM course_reg WHERE matricno='$matricno' AND semester='$semester' AND level=$level AND session='$session' ORDER BY id DESC LIMIT 0,1");

	$no=mysql_num_rows($sql);

		if($no>=1){

			return $no;

		}else{

			return 0;

		}

}



function checkPdspreg($regno,$session){

	connet();

	$sql=result("SELECT * FROM course_regpdsp WHERE regno='$regno' AND session='$session' ORDER BY id DESC LIMIT 0,1");

	$no=mysql_num_rows($sql);

		if($no>=1){

			return $no;

		}else{

			return 0;

		}

}



function checkPBNPreg($regno,$sem){

	connet();

	$sql=result("SELECT * FROM postbasicnus_course_reg WHERE regno='$regno' AND semester='$sem' ORDER BY id DESC LIMIT 0,1");

	$no=mysql_num_rows($sql);

		if($no>=1){

			return $no;

		}else{

			return 0;

		}

}



function pbnpCal(){

	connet();

	$calendarQuery="SELECT * FROM pbnpcal ORDER BY id DESC LIMIT 0,1";

	$calendarResult=result($calendarQuery);

	$calendarRows=mysql_fetch_array($calendarResult);

	return $calendarRows;	

}



function getCalendarInfo(){

	connet();

	$calendarQuery="SELECT * FROM calendarsetup ORDER BY id DESC LIMIT 0,1";

	$calendarResult=result($calendarQuery);

	$calendarRows=mysql_fetch_array($calendarResult);

	return $calendarRows;	

}



function getCalendarInfob(){

	connet();

	$calendarQuery="SELECT * FROM calendarsetup ORDER BY id DESC LIMIT 1,1";

	$calendarResult=result($calendarQuery);

	$calendarRows=mysql_fetch_array($calendarResult);

	return $calendarRows;	

}



function getSession(){

	connet();

	$calendarQuery="SELECT * FROM calendarsetup ORDER BY id DESC LIMIT 0,1";

	$calendarResult=result($calendarQuery);

	$calendarRows=mysql_fetch_array($calendarResult);

	return $calendarRows;	

}



function delcosreg($matricno,$semester,$level,$session){  // deletes aspecified course reg from the coursereg table

	connet();

	$deleteQuery="DELETE FROM course_reg WHERE matricno='$matricno' AND semester='$semester' AND level=$level AND session='$session'";

	$res=result($deleteQuery);

	return $res;

}



function deleteStudentRecs($matricno){  // delete duplicate student record

	connet();

	$deleteQuery="DELETE FROM studentiformation WHERE matricno='$matricno'";

	$deleteResult=result($deleteQuery);

	return  $deleteResult;

}



function deleteFromRegTable($tableName,$course){    // deletes a specified course from the registration table

connet();

	//$tableName = $dept.$semester.$level;

	$courseSize=sizeof($course);

	$tQuery="ALTER TABLE $tableName DROP ";

		  $tQuery = $tQuery.$course.", DROP ".$course."unit";

		  $tQuery = $tQuery.";";

	$tResult=result($tQuery);

	return $tResult."<br />";

	//echo $tQuery;

}





function updateCourseRegTable($addVals,$start){

	connet();

	$tableName="course";

	return input($addVals,$tableName);



}



function dumpStudentInfo($vals){

	return input($vals, 'studentiformation');

}



function dumpPreRegInfo($vals){

	//input($vals, 'studentIformation');

}



function getLevel2($matric){

	connet();

	$levelQuery="SELECT level FROM studentiformation WHERE matricno='$matric'";

	$levelResult=result($levelQuery);

	list($level)=mysql_fetch_array($levelResult);

	return $level;	

}



function getID($matric,$col,$table,$field){

	connet();

	$levelQuery="SELECT $col FROM $table WHERE $field='$matric'";

	$levelResult=result($levelQuery);

	list($level)=mysql_fetch_array($levelResult);

	return $level;	

}



function checkAndRetPix($upPix){

		$upSize=$upPix["size"];

		$upType=$upPix["type"];

		$upName=$upPix["name"];

		$upTp=$upPix["tmp_name"];

		//if($upType=="IMAGE/JPEG" && $upSize<=30){

			return 1;

		//}else{

		//	return 0;

		//}



}



function checkAndRetPix2($upPix,$formNo,$dumpTable){

		$upSize=$upPix["size"];

		$upType=$upPix["type"];

		$upName=$upPix["name"];

		$upTp=$upPix["tmp_name"];

		$nuDr='../pictures/'.$formNo.'.jpg';

		if(copy($upTp,$nuDr)){

			return $nuDr;

		}else{

			return "error transfering picture";

		}



}



function checkAndRetPix3($upPix,$formNo){

		$upSize=$upPix["size"];

		$upType=$upPix["type"];

		$upName=$upPix["name"];

		$upTp=$upPix["tmp_name"];

		$nuDr='staffPix/'.$formNo.'.jpg';

		if(copy($upTp,$nuDr)){

			return $nuDr;

		}else{

			return "error transfering picture";

		}



}



function setStaffId(){

	connet();

	$checkerQuery="SELECT id FROM staff2";

	$checkResult=result($checkerQuery);

	$resultData=mysql_num_rows($checkResult);

	return $resultData;

}



function sendFormToDbinput($dumpVals,$pix,$formNo,$dumpTable){

	$pixRet=checkAndRetPix($pix,$dumpTable);

	if($pixRet==1){

		$newVals[]=$formNo;

		$newVals[]=checkAndRetPix2($pix,$formNo,$dumpTable);

		$combinedArr=array_merge($newVals,$dumpVals);

		input($combinedArr,$dumpTable);

		//return 1;

		//var_dump($combinedArr);

		

	}else{

		//return 0;

		//var_dump($dumpVals);

	}

}



function staffRecDump($dumpVals,$pix,$formNo,$dumpTable){

	$pixRet=checkAndRetPix($pix,$dumpTable);

	if($pixRet==1){

		$newVals[]=$formNo;

		$newVals[]=checkAndRetPix3($pix,$formNo,$dumpTable);

		$combinedArr=array_merge($newVals,$dumpVals);

		if(input($combinedArr,$dumpTable)==1){

			return 1;

		}else{

			return 0;

		}

	}

}



function retVals4application($formNo,$dumpTable){

	connet();

	$checkerQuery="SELECT * FROM $dumpTable where formNo='$formNo'";

	$checkResult=result($checkerQuery);

	$resultData=mysql_fetch_row($checkResult);

	$resultSize=count($resultData);

	//echo $resultSize;

	//var_dump($resultData);

	for($a=3;$a<$resultSize;$a++){

		$sendData[$a-3]=$resultData[$a];

	}	

	return $sendData;

}



function checkLog($user,$pass,$staffId){

	$pass=md5($pass);

	connet();

	$checkerQuery="SELECT * FROM stafflogin where user='$user' AND pass='$pass' AND staffId='$staffId'";

	$checkResult=result($checkerQuery);

	$resultData=mysql_num_rows($checkResult);

	return $resultData;

}



function staffToLogin($user,$pass,$staffId){

	$pass=md5($pass);

	connet();

	$checkerQuery="SELECT * FROM stafflogin where user='$user' AND pass='$pass' AND staffId='$staffId'";

	$checkResult=result($checkerQuery);

	$resultData=mysql_fetch_array($checkResult);

	return $resultData['staffId'];

}



function staffNoRet($staffId){

	$pass=md5($pass);

	connet();

	$checkerQuery="SELECT staffNo FROM staff2 where staffId=$staffId";

	$checkResult=result($checkerQuery);

	$resultData=mysql_fetch_array($checkResult);

	return $resultData[0];

}



function updateDateStaffRec($user,$pass,$staffNo,$staffId,$upname){

	$pass=md5($pass);

	connet();

	$checkerQuery="UPDATE stafflogin SET user='$user', pass='$pass', staffNo='$staffNo', name='$upname' WHERE staffId=$staffId";

	$checkResult=result($checkerQuery);

	return $checkResult;

}



function updateDateStaffRecs($user,$pass,$staffNo,$staffId,$upname){

	connet();

	$checkerQuery="UPDATE stafflogin SET user='$user', pass='$pass', staffNo='$staffNo', name='$upname' WHERE staffId=$staffId";

	$checkResult=result($checkerQuery);

	return $checkResult;

}



function retVals4application2($formNo,$dumpTable){

	connet();

	$checkerQuery="SELECT * FROM $dumpTable where formNo='$formNo'";

	$checkResult=result($checkerQuery);

	$resultData=mysql_fetch_row($checkResult);

	$resultSize=count($resultData);

	//echo $resultSize;

	//var_dump($resultData);	

	return $resultData;

}



function retPix4application($formNo,$dumpTable){

	connet();

	$checkerQuery="SELECT pix FROM $dumpTable where formNo='$formNo'";

	$checkResult=result($checkerQuery);

	$resultData=mysql_fetch_row($checkResult);	

	$sendData=$resultData[0];

	return $sendData;

}



function checkifNoExist($formNo,$dumpTable){

	connet();

	$checkerQuery="SELECT * FROM $dumpTable where formNo='$formNo'";

	$checkResult=mysql_query($checkerQuery);

	$resultCheck=mysql_num_rows($checkResult);

	return $resultCheck;

}



function checkifCtypeExist($prog,$pin,$cardno,$dumpTable){

	connet();

	$checkerQuery="SELECT * FROM $dumpTable where programme='$prog' and PinNumber='$pin' and CardSerialNumber=$cardno";

	$checkResult=mysql_query($checkerQuery);

	$resultCheck=mysql_num_rows($checkResult);

	return $resultCheck;

}



function checkCardTable($formNo,$cardTable){

	connet();

	$checkerQuery="SELECT * FROM $cardTable where RegistrationNumber LIKE '$formNo%'";

	$checkResult=mysql_query($checkerQuery);

	$resultCheck=mysql_num_rows($checkResult);

	return $resultCheck;

	//return $checkResult;

}



function retFormNo($matricno,$dumpTable){

	connet();

	$checkerQuery="SELECT * FROM $dumpTable where matricno='$matricno'";

	$checkResult=mysql_query($checkerQuery);

	$resultCheck=mysql_fetch_row($checkResult);

	return $resultCheck[1];

}



function retCountry2(){

$rCountry=

'

<select name="cv2[]">

	<option value=""></option>

	<option value="Nigerian">Nigerian</option>

	<option value="Others">Others</option>

</select>

';

return $rCountry;

}



function retCountry(){

$rCountry=

'

<select name="lasu2[]">

	<option value=""></option>

	<option value="Nigerian">Nigerian</option>

	<option value="Others">Others</option>

</select>

';

return $rCountry;

}	

	

function retStudInfo($matric){

	connet();

	$formQuery="SELECT * FROM studentiformation where matricno='$matric'";

	$formResult=result($formQuery);

	$resultData=mysql_fetch_array($formResult);

	return $resultData; 

}



function retStudInfo2($matric,$table){

	connet();

	$formQuery="SELECT * FROM $table where matricno='$matric'";

	$formResult=result($formQuery);

	$resultData=mysql_fetch_array($formResult);

	return $resultData; 

}



function courseCode($dept,$semester,$level,$school){

	connet();

	$q="SELECT * FROM unicourse where level='$level' AND semester='$semester' AND dept='$dept' AND school='$school'";

	$qresult=result($q);

	echo '<select name="codes">';

	while($rdata=mysql_fetch_array($qresult)){

		echo'<option value="'.$rdata['courseCode'].'">'.$rdata['courseCode'].'</option>';

	}

	echo '</select>';

}



function courseUnit($id,$major,$minor,$semester,$level){

	connet();

	switch($level){

	case 300:	

		$q="SELECT credit FROM course where courseCode='$id' AND level='$level' AND  semester='$semester' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%')";

		break;

	default:

		$q="SELECT credit FROM course where courseCode='$id' AND level='$level' AND  semester='$semester' AND type='NEW' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%')";

		break;

	}

	//$q="SELECT credit FROM course where courseCode='$id' AND level='$level' AND  semester='$semester' AND type='NEW' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%')";

	#$q="SELECT credit FROM $table where courseCode='$code' AND semester='$semester'";

	$qresult=result($q);

	list($credit)=mysql_fetch_array($qresult);

	return $credit;

}



function newSites(){

	connet();

		$q="SELECT * FROM libraries ORDER BY id DESC";

		$res=result($q);

		$rows=mysql_num_rows($res);

		if($rows>0){

			while($retRec=mysql_fetch_array($res)){

			echo '<span style="color:#F00; font-weight:bold;">'.stripslashes($retRec[1]).' </span><br /><span style="color:#000; text-align:justify;">'.stripslashes($retRec[2]).'</span><br /><a href="'.$retRec[3].'" style="color:#009; font-size:11px;" target="_blank">'.$retRec[3].'</a><br /><br />';	

			}

		}else{

			echo '<span style="color:#F00; font-weight:bold; padding-left:150px;">Libraries not Registered/Uploaded </span>';	

		}

}



function courseCheck($matricno,$session,$semester){

connet();

	$sql=result("select * from course_reg where matricno='$matricno' AND semester='$semester' AND session='$session'");

	$s_row=mysql_num_rows($sql);

	if($s_row>=1){

		return 1;

	}else{

		return 0;

	}

}



function courseCheck2($matricno,$session,$semester){

connet();

	$sql=result("select * from course_reg where matricno='$matricno' AND semester='$semester' AND session='$session' AND app='APPROVED'");

	$s_row=mysql_num_rows($sql);

	if($s_row>=1){

		return 1;

	}else{

		return 0;

	}

}



function pbnpCosCheck($matricno,$session,$semester){

connet();

	$sql=result("select * from postbasicnus_course_reg where regno='$matricno' AND semester='$semester' AND session='$session'");

	$s_row=mysql_num_rows($sql);

	if($s_row>=1){

		return 1;

	}else{

		return 0;

	}

}



function ipnmeCosCheck($matricno,$session){

connet();

	$sql=result("select * from course_reg_ipnme where matricno='$matricno' AND session='$session'");

	$s_row=mysql_num_rows($sql);

	if($s_row>=1){

		return 1;

	}else{

		return 0;

	}

}



function postUTMEmenu($matricno){

	connet();

		$res=getRecs("post_utme_result","regno",$matricno);

		echo '<tr style="background-color:#063958; color:#FF0;">	

				<td colspan="4" align="center" style="padding:5px;">

				<span class="smenu">

				<a href="post_utme_basic_info.php">&raquo;Basic Information</a>&nbsp;|';

				if(searchRecord("post_utme_basicinfo","regno",$matricno)>=1){

					echo '<a href="post_utme_academic_info.php">&raquo;Academic Record</a>&nbsp;|';

				}else{

					echo '<a href="javascript:;" onclick="alert(\'Please Fill Basic Information First\')">&raquo;Academic Record</a>&nbsp;|';

				}

				$invrec=retInvoice3($matricno,"UNIMED POST-UTME FEE","2020/2021");
				if(searchRecord("post_utme_acad_rec","regno",$matricno)>=1 && searchRecord("post_utme_basicinfo","regno",$matricno)>=1 && $invrec[8]=="Supplementary POST-UTME"){

					echo '&nbsp;<a href="post-utme_slip.php" target="_blank">&raquo;Print Post-UTME Slip</a>&nbsp;|';

				}
				if(searchRecord("admissiontable","regno",$matricno)>=1){

					echo '<a href="admission_status_new.php?regno='.$matricno.'" target="_blank">&raquo;Admission Status</a>&nbsp;|';

				}else{

					echo '<a href="javascript:;" onclick="alert(\'Please Check back later\')">&raquo;Admission Status</a>&nbsp;|';

				}
	/*
				if(searchRecord("admissiontable","regno",$matricno)==0 || searchRecord("post_utme_pdsprecord","regno",$matricno)>=1){

					if(searchRecord("post_utme_pdsprecord","regno",$matricno)>=1){
						echo '<a href="post_utme_changeofcourse.php" >&raquo;Change of Course</a>&nbsp;|';
					}elseif(payverify($matricno,"UNIMED CHANGE OF COURSE FEE")==0){

						echo '<a href="post_utme_changeofcoursepay.php" >&raquo;Change of Course</a>&nbsp;|';

					}elseif(payverify($matricno,"UNIMED CHANGE OF COURSE FEE")>=1){

						echo '<a href="post_utme_changeofcourse.php" >&raquo;Change of Course</a>&nbsp;|';

					}

				}
				*/
				//if(searchRecord("admissiontable","regno",$matricno)==0){
					//echo '<a href="post_utme_changeofcourse.php" >&raquo;Change of Course</a>&nbsp;|';
				//}
				if($res!=0){
					/*
					if(payverify($matricno,"UNIMED CHANGE OF COURSE FEE")==0 && $res[4]>=50){

						echo '<a href="post_utme_changeofcoursepay.php" >&raquo;Change of Course</a>&nbsp;|';

					}elseif(payverify($matricno,"UNIMED CHANGE OF COURSE FEE")>=1 && $res[4]>=50){

						echo '<a href="post_utme_changeofcourse.php" >&raquo;Change of Course</a>&nbsp;|';

					}*/
					echo '&nbsp;<a href="post_utme_result.php" target="_blank" style="color:#FF0;">&raquo;Post-UTME Result</a>&nbsp;|';

				}

				echo '<a href="logout.php?from=postutme">&nbsp;&raquo;Logout</a>

				</span>

                </td>

			</tr>';	

	

	//if(feeCheckc($matricno,"UNIMED POST-UTME PAST QUESTION","2018/2019")>=1){

		echo '

			<tr style="background-color:#FFF; color:#F00;">	

					<td colspan="4" align="center" style="padding:5px;"><strong><a href="../unimed_doc/UNIMED_Post-UTME_Past_Questions.pdf" target="_blank">Click Here</a>&nbsp;for Post-UTME Past-Question.</strong></td>

				</tr>  

		';

	//}

}

function transferMenu($matricno){

	connet();

		echo '<tr style="background-color:#063958; color:#FF0;">	

				<td colspan="4" align="center" style="padding:5px;">

				<span class="smenu">

				<a href="transfer_basic_info.php">&raquo;Basic Information</a>&nbsp;|';

				if(searchRecord("transfer_basicinfo","regno",$matricno)>=1){

					echo '<a href="transfer_academic_info.php">&raquo;Academic Record</a>&nbsp;|';

				}else{

					echo '<a href="javascript:;" onclick="alert(\'Please Fill Basic Information First\')">&raquo;Academic Record</a>&nbsp;|';

				}

				
				if(searchRecord("transfer_acad_rec","regno",$matricno)>=1 && searchRecord("transfer_basicinfo","regno",$matricno)>=1){

					echo '&nbsp;<a href="transfer_slip.php" target="_blank">&raquo;Print Post-UTME Slip</a>&nbsp;|';

				}
				
				echo '<a href="logout.php?from=transfer">&nbsp;&raquo;Logout</a>

				</span>

                </td>

			</tr>';	

	

	//if(feeCheckc($matricno,"UNIMED POST-UTME PAST QUESTION","2018/2019")>=1){

		echo '

			<tr style="background-color:#FFF; color:#F00;">	

					<td colspan="4" align="center" style="padding:5px;"><strong><a href="../unimed_doc/UNIMED_Post-UTME_Past_Questions.pdf" target="_blank">Click Here</a>&nbsp;for Post-UTME Past-Question.</strong></td>

				</tr>  

		';

	//}

}


function UPDPmenu($matricno){

	connet();

		echo '<tr style="background-color:#063958; color:#FF0;">	

				<td colspan="4" align="center" style="padding:5px;">

				<span class="smenu">

				<a href="updp_basic_info.php">&raquo;Basic Information</a>&nbsp;|';

				if(searchRecord("updp_basicinfo","regno",$matricno)>=1){

					echo '<a href="updp_academic_info.php">&raquo;Academic Record</a>&nbsp;|';

				}else{

					echo '<a href="javascript:;" onclick="alert(\'Please Fill Basic Information First\')">&raquo;Academic Record</a>&nbsp;|';

				}
				if(searchRecord("admissiontableupdp","regno",$matricno)>=1 && payverify($matricno,"UNDERGRADUATE AND POSTGRADUATE DIPLOMA ACCEPTANCE")>=1){
					echo '<a href="parent_sponsors.php">&raquo;Parent/Sponsor Info</a>&nbsp;|';
					echo '<a href="other_details.php">&raquo;Other Details</a>&nbsp;|';
					echo '<a href="transport_route.php">&raquo;Transport Rec</a>&nbsp;|';
					echo '<a href="admission/letter.php" target="_blank">&raquo;Admission Letter</a>&nbsp;|';
				}
				if(searchRecord("updp_basicinfo","regno",$matricno)>=1 && searchRecord("updp_acad_rec","regno",$matricno)>=1){

					echo '&nbsp;<a href="updp_slip.php" target="_blank">&raquo;Print Application Slip</a>&nbsp;|';

				}
				
				echo '<a href="logout.php?from=updp">&nbsp;&raquo;Logout</a>

				</span>

                </td>

			</tr>';	
	if(searchRecord("parentsponsor","regno",$matricno)>=1 && searchRecord("academic_record","regno",$matricno)>=1 && searchRecord("otherinfo","regno",$matricno)>=1){
	echo '<tr style="background-color:#063958; color:#FF0;">	

				<td colspan="4" align="center" style="padding:5px;">

				<span class="smenu">

				<a href="clearanceForm.php" target="_blank">&raquo;Print Clearance Form</a>&nbsp;|

				<a href="counsellingForm.php" target="_blank">&raquo;Print Counselling Form</a>&nbsp;|

				<a href="libraryForm.php" target="_blank">&raquo;Print Library Form</a>';

				if(courseCheck2($matricno,$session,$semester)==1){

					echo '&nbsp;|<a href="printForm.php" target="_blank">&raquo;Print Course Form</a>';

				}elseif(courseCheck($matricno,$session,$semester)==1){

					echo '&nbsp;|<a href="javascript:;" onclick="alert(\'Your form is awaiting approval by your Course Adviser\')" >&raquo;Print Course Form</a>';

				}
				//if($brec[22]=="NEW"){

					echo '&nbsp;|<a href="health_centre.pdf" target="_blank">&raquo;Medical Consent</a>';

				//}
				$trec=getRecs("transportation","matricno",$matricno);
				if($trec !=0){

					echo '&nbsp;|<a href="transport_slip.php" target="_blank">&raquo;Transport Slip</a>';

				}

				echo '</span>

                </td>

			</tr>';	
	}

}



function preDegreemenu($matricno){

	connet();

		echo '<tr style="background-color:#063958; color:#FF0;">	

				<td colspan="4" align="center" style="padding:5px;">

				<span class="smenu">

				<a href="pdsp_basic_info.php">&raquo;Basic Information</a>&nbsp;|

				<a href="pdsp_academic_info.php">&raquo;Academic Record</a>&nbsp;|';

				if(searchRecord("pre_deg_acad_rec","regno",$matricno)>=1 && searchRecord("pre_deg_basicinfo","regno",$matricno)>=1){

					echo '&nbsp;<a href="pdsp_slip.php" target="_blank">&raquo;Print Pre-Degree Slip</a>&nbsp;|';

				}

				if(payverify($matricno,"UNIMED PRE-DEGREE SCHOOL FEE")>=1){

					echo '&nbsp;<a href="pdsp_course_reg.php">&raquo;Course Reg.</a>&nbsp;|';

				}

				if(searchRecord("course_regpdsp","regno",$matricno)>=1){

					echo '&nbsp;<a href="printCourseForm.php" target="_blank">&raquo;Print Course Form</a>&nbsp;|';

				}

				echo '<a href="logout.php?from=pdsp">&nbsp;&raquo;Logout</a>

				</span>

                </td>

			</tr>';	

}



function postBasicmenu($matricno){

	connet();

	$sem=pbnpCal();

		echo '<tr style="background-color:#063958; color:#FF0;">	

				<td colspan="5" align="center" style="padding:5px;">

				<span class="smenu">

				<a href="postbasicnus_basic_info.php">&raquo;Basic Information</a>&nbsp;|

				<a href="postbasicnus_academic_info.php">&raquo;Academic Record</a>&nbsp;|';

				/*if(searchRecord("postbasicnus_deg_acad_rec","regno",$matricno)>=1 && searchRecord("postbasicnus_deg_basicinfo","regno",$matricno)>=1){

					echo '&nbsp;<a href="postbasicnus_slip.php" target="_blank">&raquo;Print PBNP Slip</a>&nbsp;|';

				}*/

				if(searchRecord("postbasicnus_result","regno",$matricno)>=1){

					echo '&nbsp;<a href="postbasicnus_result.php" target="_blank">&raquo;Result Slip</a>&nbsp;|';

					$res=getRecs("postbasicnus_result","regno",$matricno);

					if(pbnpAdmit($matricno)!=0){

						echo '&nbsp;<a href="admission/letter.php" target="_blank">&raquo;Admission Letter</a>&nbsp;|';

					}

				}

				if(payverify1($matricno,"POST BASIC NURSING SCHOOL FEES",$sem[1])>=1){

					echo '&nbsp;<a href="postbasicnus_course_reg.php">&raquo;Course Reg.</a>&nbsp;|';

				}

				if(pbnpCosCheck($matricno,$sem[1],$sem[2])!=0){

					echo '&nbsp;<a href="printCourseForm.php" target="_blank">&raquo;Print Course Form</a>&nbsp;|';

				}

				echo '<a href="logout.php?from=pdsp">&nbsp;&raquo;Logout</a>

				</span>

                </td>

			</tr>';	

}



function ipnmeMenu($matricno){

	connet();

	$sem=getCalendarInfo();

		echo '<tr style="background-color:#063958; color:#FF0;">	

				<td colspan="4" align="center" style="padding:5px;">

				<span class="smenu">

				<a href="ipnme_basic_info.php">&raquo;Basic Information</a>&nbsp;|';

				if(payverify($matricno,"IPNME SCHOOL FEE")>=1){

					echo '&nbsp;<a href="ipnme_course_reg.php">&raquo;Course Registration</a>&nbsp;|';

				}

				if(ipnmeCosCheck($matricno,$sem[1])>=1){

					echo '&nbsp;<a href="printCourseForm.php" target="_blank">&raquo;Print Course Form</a>&nbsp;|';

				}

				echo '<a href="password_change.php">&raquo;Change Password</a>&nbsp;|

				<a href="logout.php?from=ipnme">&nbsp;&raquo;Logout</a>

				</span>

                </td>

			</tr>';	

}



function studPrinting($matricno,$session,$semester,$col){

	connet();

	$hspot=getRecs("unimedhotspot","regno",$matricno);

	$trec=getRecs("transportation","matricno",$matricno);

	$brec=recSearch2($matricno);

	if(paymentconfirm1($matricno)==1){

		$srec=getRecs("unimedhotspot","regno",$matricno);

		if($srec==0){

			$lrec=getRecs("unimedhotspot","regno","");

			result("UPDATE unimedhotspot SET regno='$matricno' WHERE id=$lrec[0]");

		}else{

			$lrec=$srec;

		}

		echo '

			<tr style="background-color:#FFF; color:#F00;">	

					<td colspan="4" align="center" style="padding:5px;"><strong>UNIMED Hotspot Internet Access- &nbsp;&nbsp;&nbsp;<span style="color:#005BAA;">Username:-</span> '.$lrec[2].'&nbsp;&nbsp;&nbsp;<span style="color:#005BAA;">Password:-</span> '.$lrec[3].'</strong></td>

				</tr>  

		';

	}

	if(searchRecord("parentsponsor","regno",$matricno)>=1 && searchRecord("academic_record","regno",$matricno)>=1 && searchRecord("otherinfo","regno",$matricno)>=1){

		echo '<tr style="background-color:#063958; color:#FF0;">	

				<td colspan="'.$col.'" align="center" style="padding:5px;">

				<span class="smenu">

				<a href="staffadvisor.php" style="color:#FF0;">&raquo;Staff Advisor</a>&nbsp;|

				<a href="resultChecker.php" style="color:#FF0;">&raquo;Check Result</a>&nbsp;|

				<a href="clearanceForm.php" target="_blank">&raquo;Print Clearance Form</a>&nbsp;|

				<a href="counsellingForm.php" target="_blank">&raquo;Print Counselling Form</a>&nbsp;|

				<a href="libraryForm.php" target="_blank">&raquo;Print Library Form</a>';

				if(courseCheck2($matricno,$session,$semester)==1){

					echo '&nbsp;|<a href="printForm.php" target="_blank">&raquo;Print Course Form</a>';

				}elseif(courseCheck($matricno,$session,$semester)==1){

					echo '&nbsp;|<a href="javascript:;" onclick="alert(\'Your form is awaiting approval by your Course Adviser\')" >&raquo;Print Course Form</a>';

				}

				if(searchRecord("100levelresult","matricno",$matricno)>=1){

					echo '&nbsp;|<a href="resultFormOld.php" target="_blank">&raquo;100Level Result</a>';

				}

				if($brec[22]=="NEW"){

					echo '&nbsp;|<a href="health_centre.pdf" target="_blank">&raquo;Medical Consent</a>';

				}

				if($trec !=0){

					echo '&nbsp;|<a href="transport_slip.php" target="_blank">&raquo;Transport Slip</a>';

				}

				echo '</span>

                </td>

			</tr>';	

	}

	/*

	if(getRecs("student_number","regno",$matricno) == 0){

		echo '

			<tr style="background-color:#FFF; color:#F00;">	

				<td colspan="4" align="center" style="padding:5px;">

					<span id="view">

					<a href="regVerification.php" title="Late Registration">&raquo;&nbsp;Late Registration</a>&nbsp;';

					echo '

					</span>

				</td>

			</tr>  

		';

	}else{

		echo '

			<tr style="background-color:#FFF; color:#F00;">	

				<td colspan="4" align="center" style="padding:5px;">

					<span id="view">

					<a href="courseregistration_late.php" title="Late Registration">&raquo;&nbsp;1ST Semester Registration</a>&nbsp;';

					$sem1=getCalendarInfob();

					if(courseCheck($matricno,$sem1[1],$sem1[2])==1){

						echo '&nbsp;|<a href="printForm2.php" title="Print Late Registration" target="_blank">&raquo;&nbsp;Print 1ST Semester Course Form</a>';

					}

					echo '

					</span>

				</td>

			</tr>  

		';

	}*/

	

}



function progType($type){

	switch($type){

		case "Medicine and Surgery":

		$progname="MBBS";

		break;

		case "Dentistry":

		$progname="Bachelor of Dentistry";

		break;
		case "Physiotherapy":

		$progname="Bachelor of Physiotherapy";

		break;
		
		case "Medical Laboratory Science":

		$progname="B.MLS";

		break;

		default:

		$progname="Bachelor of Science";

		break;

	}

	return $progname;

}



function printForm($matricno,$name,$faculty,$dept,$level,$session,$semester){

	connet();

	$sql=result("select course1, course2, course3, course4, course5, course6, course7, course8, course9, course10, course11, course12, course13, course14, course15, course16, course17, course18, course19, course20 from course_reg where matricno='$matricno' AND semester='$semester' AND session='$session' ORDER BY id DESC LIMIT 0,1");

	$retData=mysql_fetch_row($sql);

			echo "<tr>";

				echo '<td colspan="6" align="left">';

					echo '<table align="center" width="100%">';

						echo "<tr>";

							echo '<td align="left" width="20%">&nbsp;<b>NAME:</b></td>';

							echo '<td align="left" width="80%" colspan="3" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$name.'</td>'; 

						echo '</tr>';

						echo '<tr>

								<td align="left" width="15%">&nbsp;<b>MATRIC/REG. NO.:</b></b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$matricno.'</td> 

								<td align="left" width="15%"><b>SESSION:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$session.'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>PROGRAMME.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.progType($dept).'</td> 

								<td align="left" width="15%"><b>DEPARTMENT:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$dept.'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>FACULTY.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$faculty.'</td> 

								<td align="left" width="15%"><b>LEVEL:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$level.'</td>

							</tr>';

							echo '<tr>

								<td align="center" width="100%" colspan="4"><br /><br />'.$semester.' SEMESTER</td>

							</tr>';

					echo '</table>';

				echo '</td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="left" colspan="6"><hr /></td>';

			echo '</tr>';

			echo '<tr>

				<td align="left" width="5%" style="border-bottom:#000000 solid 1px;"><b>S/N</b></td>

				<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Course Code</b></td>

				<td align="left" width="55%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Course Title</b></td> 				 

				<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Unit</b></td>

				<td align="left" width="5%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Status</b></td>

				<td align="left" width="15%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Signature</b></td>

			</tr>';

			$sn=0;

			$tu=0;

			echo '<div style="line-height:1.8em;">';

	foreach($retData as $val){

		if($val != "|||"){

			$sn+=1;

			$split=explode("|",$val);

			$tu+=$split[2];

			/*if($split[0]=="BIO 111"){

				$sign=result("SELECT * FROM courseofficers WHERE faculty='$faculty' AND cos like '%$split[0]%' ORDER BY id DESC LIMIT 0,1");

			}else{

				$sign=result("SELECT * FROM courseofficers WHERE cos like '%$split[0]%' ORDER BY id DESC LIMIT 0,1");	

			}*/
			$sign=result("SELECT * FROM courseofficers WHERE cos like '%$split[0]%' ORDER BY id DESC LIMIT 0,1");
			$signno=mysql_num_rows($sign);

			if($signno==0){

				$signature="";

			}else{

				$retsign=mysql_fetch_row($sign);

				$signature=str_replace("/","_",strtolower($retsign[1]));

				$signature .=".jpg";

			}

			echo "<tr>";

				echo '<td align="left" width="5%" style="border-bottom:#000000 solid 1px;">&nbsp;['.$sn.']</b></td>';

				echo '<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$split[0].'</td>'; 

				echo '<td align="left" width="55%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$split[1].'</td>'; 				 

				echo '<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$split[2].'</td>';

				echo '<td align="left" width="5%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$split[3].'</td>';

				echo '<td align="left" width="15%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">'; 

					if($signature!=""){

						echo '<img src="../signatures/'.$signature.'" width=70px; height=25px; />';

					}

			echo '</td>';

			echo '</tr>';

		}

	}

	echo '</div>';

			echo "<tr>";

				echo '<td align="right" colspan="3" style="border-bottom:#000000 solid 1px;"><b>Total Number of Units</b></td>';

				echo '<td align="left" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>'.$tu.'</b></td>

				<td align="left" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;</td>

				<td align="left" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;</td>

				';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" colspan="6" style="padding:10px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" colspan="6">

				<table width="100%">';

			echo "<tr>";

				echo '<td align="center"  style="border-top:#000000 dotted 1px;"><b>Student\'s Name/Sign./Date</b></td><td width="5%">&nbsp;</td>';

				echo '<td align="center"  style="border-top:#000000 dotted 1px;"><b>Course Adviser\'s Name/Sign./Date</b></td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" colspan="3" style="padding:10px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo "<tr>";

				if($dept=="Nursing Science"){

					echo '<td align="center">&nbsp;</td><td width="5%">&nbsp;</td>';

				}else{

					echo '<td align="center"   style="border-top:#000000 dotted 1px;"><b>HOD\'S Name/Sign./Date</b></td><td width="5%">&nbsp;</td>';

				}

				echo '<td align="center"   style="border-top:#000000 dotted 1px;"><b>Dean\'s Name/Sign./Date</b></td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" colspan="3" style="padding:5px 0px;">&nbsp;</td>';

			echo '</tr>';

			/*echo "<tr>";

				echo '<td align="center" colspan="3" ><b>FOR OFFICIAL USE ONLY</b></td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" colspan="3" style="padding:10px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" style="border-top:#000000 dotted 1px;"><b>Date of Submission</b></td><td width="5%">&nbsp;</td>';

				echo '<td align="center" style="border-top:#000000 dotted 1px;"><b>Official Stamp</b></td>';

			echo '</tr>';*/

			echo '</table>

				</td>';

			echo '</tr>';

}



function printFormIPNME($matricno,$session){

	connet();

	$sql=result("select course1, course2, course3, course4, course5, course6, course7, course8, course9, course10, course11, course12, course13, course14, course15, course16, course17, course18, course19, course20 from course_reg_ipnme where matricno='$matricno' AND session='$session'");

	$retData=mysql_fetch_row($sql);

	$fs=array();

	$ss=array();

	foreach($retData as $val){

		if($val != "||||"){

			$split=explode("|",$val);

			if($split[4]=="FIRST"){

				$fs[]=$val;

			}elseif($split[4]=="SECOND"){

				$ss[]=$val;

			}

		}

	}

	$ipnmer=getRecs("ipnme_basicinfo","regno",$matricno);

			echo "<tr>";

				echo '<td colspan="6" align="left">';

					echo '<table align="center" width="100%">';

						echo '<tr>

								<td align="left" width="15%">&nbsp;<b>MATRIC/REG. NO.:</b></b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$matricno.'</td> 

								<td align="left" width="15%"><b>SESSION.:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$session.'</td>

							</tr>';

						echo "<tr>";

							echo '<td align="left" width="20%">&nbsp;<b>NAME.:</b></td>';

							echo '<td align="left" width="80%" colspan="3" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$ipnmer[2].'&nbsp;'.$ipnmer[3].'</td>'; 

						echo '</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>DATE OF BIRTH.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$ipnmer[5].'</td> 

								<td align="left" width="15%"><b>MARITAL STATUS.:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$ipnmer[6].'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>STATE.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$faculty.'</td> 

								<td align="left" width="15%"><b>LOCAL GOVT.:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$level.'</td>

							</tr>';

							echo '<tr>

								<td align="center" width="100%" colspan="4"><br /><br />FIRST SEMESTER</td>

							</tr>';

					echo '</table>';

				echo '</td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="left" colspan="6"><hr /></td>';

			echo '</tr>';

			echo '<tr>

				<td align="left" width="5%" style="border-bottom:#000000 solid 1px;"><b>S/N</b></td>

				<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Course Code</b></td>

				<td align="left" width="55%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Course Title</b></td> 				 

				<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Unit</b></td>

				<td align="left" width="5%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Status</b></td>

				<td align="left" width="15%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Signature</b></td>

			</tr>';

			$sn=0;

			$tu=0;

		echo '<div style="line-height:1.8em;">';

		foreach($fs as $f){

			$sn+=1;

			$spf=explode("|",$f);

			$tu+=$spf[2];

			echo "<tr>";

				echo '<td align="left" width="5%" style="border-bottom:#000000 solid 1px;">&nbsp;['.$sn.']</b></td>';

				echo '<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$spf[0].'</td>'; 

				echo '<td align="left" width="55%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$spf[1].'</td>'; 				 

				echo '<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$spf[2].'</td>';

				echo '<td align="left" width="5%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$spf[3].'</td>';

				echo '<td align="left" width="15%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;</td>';

			echo '</tr>';

		}

		echo '</div>';

		echo "<tr>";

				echo '<td colspan="6" align="left">';

					echo '<table align="center" width="100%">';

							echo '<tr>

								<td align="center" width="100%" colspan="4"><br /><br />SECOND SEMESTER</td>

							</tr>';

					echo '</table>';

				echo '</td>';

			echo '</tr>';

	echo '<div style="line-height:1.8em;">';

		foreach($ss as $s){

			$sn+=1;

			$sps=explode("|",$s);

			$tu+=$sps[2];

			echo "<tr>";

				echo '<td align="left" width="5%" style="border-bottom:#000000 solid 1px;">&nbsp;['.$sn.']</b></td>';

				echo '<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$sps[0].'</td>'; 

				echo '<td align="left" width="55%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$sps[1].'</td>'; 				 

				echo '<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$sps[2].'</td>';

				echo '<td align="left" width="5%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;'.$sps[3].'</td>';

				echo '<td align="left" width="15%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;</td>';

			echo '</tr>';

		}

	echo '</div>';

			echo "<tr>";

				echo '<td align="right" colspan="3" style="border-bottom:#000000 solid 1px;"><b>Total Number of Units</b></td>';

				echo '<td align="left" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>'.$tu.'</b></td>

				<td align="left" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;</td>

				<td align="left" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;">&nbsp;</td>

				';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" colspan="6" style="padding:10px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" colspan="6">

				<table width="100%">';

			echo "<tr>";

				echo '<td align="center"  style="border-top:#000000 dotted 1px;"><b>Class Coordinator Signature</b></td><td width="5%">&nbsp;</td>';

				echo '<td align="center"  style="border-top:#000000 dotted 1px;"><b>Principal\'s Signature</b></td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" colspan="3" style="padding:10px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center">&nbsp;</td><td width="5%">&nbsp;</td>';

			echo '<td align="center" style="border-top:#000000 dotted 1px;"><b>AC/O Signature</b></td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="center" colspan="3" style="padding:5px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo '</table>

				</td>';

			echo '</tr>';

}



function printFormpdsp($matricno,$name,$faculty,$dept,$session){

	connet();

	$sql=result("select course1, course2, course3, course4, course5 from course_regpdsp where regno='$matricno' AND session='$session'");

	$retData=mysql_fetch_row($sql);

			echo "<tr>";

				echo '<td colspan="4" align="left">';

					echo '<table align="center" width="100%">';

						echo "<tr>";

							echo '<td align="left" width="20%">&nbsp;<b>NAME:</b></td>';

							echo '<td align="left" width="80%" colspan="3" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$name.'</td>'; 

						echo '</tr>';

						echo '<tr>

								<td align="left" width="15%">&nbsp;<b>APP. NO.:</b></b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$matricno.'</td> 

								<td align="left" width="15%"><b>SESSION:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$session.'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>PROGRAMME.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;PRE-DEGREE</td> 

								<td align="left" width="15%"><b>FACULTY:.</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$faculty.'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>LEVEL.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;PDSP</td> 

								<td align="left" width="15%"><b>DEPARTMENT:.</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$dept.'</td>

							</tr>';

					echo '</table>';

				echo '</td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="left" colspan="4"><hr /></td>';

			echo '</tr>';

			echo '<tr>

				<td align="left" width="5%" style="border-bottom:#000000 solid 1px; padding:5px 0px;"><b>S/N</b></td>

				<td align="left" width="15%" style="border-bottom:#000000 solid 1px; padding:5px 0px;border-left:#000000 solid 1px;"><b>Course Code</b></td>

				<td align="left" width="60%" style="border-bottom:#000000 solid 1px; padding:5px 0px; border-left:#000000 solid 1px;"><b>Course Title</b></td> 				 

				<td align="left" width="20%" style="border-bottom:#000000 solid 1px; padding:5px 0px; border-left:#000000 solid 1px;"><b>Signature</b></td>

			</tr>';

			$sn=0;

			echo '<div style="line-height:1.8em;">';

	foreach($retData as $val){

		if($val != "|"){

			$sn+=1;

			$split=explode("|",$val);

			echo "<tr>";

				echo '<td align="left" width="5%" style="border-bottom:#000000 solid 1px; padding:5px 0px;">&nbsp;['.$sn.']</b></td>';

				echo '<td align="left" width="15%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px; padding:5px 0px;">&nbsp;'.$split[0].'</td>'; 

				echo '<td align="left" width="60%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px; padding:5px 0px;">&nbsp;'.$split[1].'</td>'; 				 

				echo '<td align="left" width="20%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px; padding:5px 0px;">&nbsp;</td>';

			echo '</tr>';

		}

	}

	echo '</div>';

			echo "<tr>";

			echo '<td align="center" colspan="4" style="padding:10px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center" colspan="4">

				<table width="100%">';

			echo "<tr>";

			echo '<td align="center"  style="border-top:#000000 dotted 1px;"><b>Student\'s Name/Sign./Date</b></td><td width="5%">&nbsp;</td>';

			echo '<td align="center"  style="border-top:#000000 dotted 1px;"><b>Course Adviser\'s Name/Sign./Date</b></td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center" colspan="3" style="padding:10px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center">&nbsp;</td><td width="5%">&nbsp;</td>';

			echo '<td align="center"   style="border-top:#000000 dotted 1px;"><b>CCE Director\'s Name/Sign./Date</b></td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center" colspan="3" style="padding:5px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo '</table>

				</td>';

			echo '</tr>';

}



function printFormpbnp($matricno,$name,$sem,$session){

	connet();

	$sql=result("select course1, course2, course3, course4, course5, course6, course7, course8, course9, course10 from postbasicnus_course_reg where regno='$matricno' AND session='$session' AND semester='$sem'");

	$retData=mysql_fetch_row($sql);

			echo "<tr>";

				echo '<td colspan="6" align="left">';

					echo '<table align="center" width="100%">';

						echo "<tr>";

							echo '<td align="left" width="20%">&nbsp;<b>NAME:</b></td>';

							echo '<td align="left" width="80%" colspan="3" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$name.'</td>'; 

						echo '</tr>';

						echo '<tr>

								<td align="left" width="15%">&nbsp;<b>APP. NO.:</b></b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$matricno.'</td> 

								<td align="left" width="15%"><b>SESSION:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$session.'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>PROGRAMME.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;POST BASIC NURSING</td> 

								<td align="left" width="15%"><b>SEMESTER:.</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$sem.'</td>

							</tr>';

					echo '</table>';

				echo '</td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="left" colspan="6"><hr /></td>';

			echo '</tr>';

			echo '<tr>

				<td align="left" width="5%" style="border-bottom:#000000 solid 1px; padding: 5px 0px;"><b>S/N</b></td>

				<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Course Code</b></td>

				<td align="left" width="55%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Course Title</b></td> 				 

				<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Unit</b></td>

				<td align="left" width="5%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Status</b></td>

				<td align="left" width="15%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px;"><b>Signature</b></td>

			</tr>';

			$sn=0;

			echo '<div style="line-height:1.8em;">';

	foreach($retData as $val){

		if($val != "|||"){

			$sn+=1;

			$split=explode("|",$val);

			echo "<tr>";

				echo '<td align="left" width="5%" style="border-bottom:#000000 solid 1px; padding:8px 0px;">&nbsp;['.$sn.']</b></td>';

				echo '<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px; padding:8px 0px;">&nbsp;'.$split[0].'</td>'; 

				echo '<td align="left" width="55%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px; padding:8px 0px;">&nbsp;'.$split[1].'</td>';

				echo '<td align="left" width="10%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px; padding:8px 0px;">&nbsp;'.$split[2].'</td>';

				echo '<td align="left" width="5%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px; padding:8px 0px;">&nbsp;'.$split[3].'</td>';

				echo '<td align="left" width="15%" style="border-bottom:#000000 solid 1px;border-left:#000000 solid 1px; padding:8px 0px;">&nbsp;</td>';

			echo '</tr>';

		}

	}

	echo '</div>';

			echo "<tr>";

			echo '<td align="center" colspan="6" style="padding:10px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center" colspan="6">

				<table width="100%">';

			echo "<tr>";

			echo '<td align="center"  style="border-top:#000000 dotted 1px;"><b>Student\'s Name/Sign./Date</b></td><td width="5%">&nbsp;</td>';

			echo '<td align="center"  style="border-top:#000000 dotted 1px;"><b>Course Adviser\'s Name/Sign./Date</b></td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center" colspan="3" style="padding:10px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center">&nbsp;</td><td width="5%">&nbsp;</td>';

			echo '<td align="center"   style="border-top:#000000 dotted 1px;"><b>Programme Coordinator\'s Name/Sign./Date</b></td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center" colspan="3" style="padding:5px 0px;">&nbsp;</td>';

			echo '</tr>';

			echo '</table>

				</td>';

			echo '</tr>';

}



function grade($cos,$grd){ //merge courses and corresponding scores

	$coses=explode("|",$cos);

	$scores=explode("|",$grd);

	$rec=array_combine($coses,$scores);

	return $rec;

}



function rGrade($score){

	if($score>=70){

		$grade="A";

	}elseif($score>=60){

		$grade="B";

	}elseif($score>=50){

		$grade="C";

	}elseif($score>=45){

		$grade="D";

	}else{

		$grade="F";

	}

	return $grade;

}



function getSession2(){

	connet();

	$sql=result("SELECT * FROM calendarsetup ORDER BY id DESC");

	echo '<select name="session" class="propertySelect" style="width:175px; font-size:12px; height: 28px;">

		<option value="" selected >--Select--</option>';

	while($rec=mysql_fetch_array($sql)){

		echo '<option value="'.$rec[3].'/'.$rec[4].'">'.$rec[3].'/'.$rec[4].'</option>';

	}

	echo '</select>';

}



function getSession22(){

	connet();

	$sql=result("SELECT DISTINCT(session) FROM calendarsetup ORDER BY id DESC");

	echo '<select name="session" class="propertySelect" style="width:175px; font-size:12px; height: 28px;">

		<option value="" selected >--Select--</option>
		<option>2015/2016</option>';

	while($rec=mysql_fetch_array($sql)){

		echo '<option value="'.$rec[0].'">'.$rec[0].'</option>';

	}

	echo '</select>';

}


function remexp($rem){

	switch($rem){

		case "C":

		$exp="Caution";

		break;

		case "P":

		$exp="Probation";

		break;

		case "EP":

		$exp="Extended Probation";

		break;

		case "AW":

		$exp="Academic Withdrawal";

		break;

		case "GS":

		$exp="Good Standing";

		break;

		default:

		$exp="Unknown";

		break;

	}

	return $exp;

}



function studres($matricno,$level,$sem,$sess){

	connet();

	$checkQuery="select * from resulttable where matricno='$matricno' and session='$sess' and level=$level and sem='$sem' AND rdate<>'NO' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$no=mysql_num_rows($checkResult);

	if($no==0){

		$returnedRows=0;

	}else{

		$returnedRows=mysql_fetch_array($checkResult);	

	}

	return $returnedRows;

}



function studreg($matricno,$level,$sem,$sess){

	connet();

	$checkQuery="select * from registration where matricno='$matricno' and session='$sess' and level=$level and sem='$sem' ORDER BY id DESC LIMIT 0,1";

	$checkResult=result($checkQuery);

	$no=mysql_num_rows($checkResult);

	if($no==0){

		$returnedRows=0;

	}else{

		$returnedRows=mysql_fetch_array($checkResult);

	}

	return $returnedRows;

}



function studentResultOld($matricno,$name,$faculty,$dept,$level,$sem,$sess){

	connet();

	//$ret=studres($matricno,$level,$sem,$sess);

	//$regd=studreg($matricno,$level,$sem,$sess);

	//$cos=explode("|",$regd[2]);

	//$tt=grade($regd[2],$regd[5]); //array of total score

	//$un=grade($regd[2],$regd[6]); //array of total score

	$res=getRecs("100levelresult","matricno",$matricno);

			echo "<tr>";

				echo '<td colspan="6" align="left">';

					echo '<table align="center" width="100%" style="line-height:2.5em;">';

						echo "<tr>";

							echo '<td align="left" width="20%">&nbsp;<b>NAME:</b></td>';

							echo '<td align="left" width="80%" colspan="3" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$name.'</td>'; 

						echo '</tr>';

						echo '<tr>

								<td align="left" width="15%">&nbsp;<b>MATRIC/REG. NO.:</b></b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$matricno.'</td> 

								<td align="left" width="15%"><b>SESSION:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$sess.'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>PROGRAMME.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.progType($dept).'</td> 

								<td align="left" width="15%"><b>DEPARTMENT:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$dept.'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>FACULTY.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$faculty.'</td> 

								<td align="left" width="15%"><b>LEVEL:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$level.'</td>

							</tr>';

							echo '<tr>

								<td align="center" width="100%" colspan="4"><br /><strong>'.$sem.' SEMESTER RESULT</strong></td>

							</tr>';

					echo '</table>';

				echo '</td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="left" colspan="6"><hr /></td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center" colspan="6" style="padding:10px 0px;">

				<table style="background-color:#666; line-height:2.5em; text-align:center;" width="100%">'; 

				$n=0;

				echo '<tr style="background-color:#fff; font-weight:bold;">

				<td align="right">COURSE CODE:-&nbsp;</td>';

				echo '<td>PHY121</td>';

				echo '<td>PHY122</td>';

				echo '<td>MAT121</td>';

				echo '<td>CHM121</td>';

				echo '<td>CHM122</td>';

				echo '<td>BIO120</td>';

				echo '<td>GNS121</td>';

				echo '<td>GNS122</td>';

				echo '<td>GNS123</td>';

				echo '

				</tr>';

				echo '<tr style="background-color:#fff; font-weight:bold;">

				<td align="right">COURSE UNIT:-&nbsp;</td>';

				echo '<td>3</td>';

				echo '<td>1</td>';

				echo '<td>3</td>';

				echo '<td>3</td>';

				echo '<td>1</td>';

				echo '<td>4</td>';

				echo '<td>2</td>';

				echo '<td>2</td>';

				echo '<td>2</td>';

				echo '

				</tr>';

				echo '<tr style="background-color:#fff; font-weight:bold;">

				<td align="right">SCORE:-&nbsp;</td>';

				echo '<td>'.$res[3].'</td>';

				echo '<td>'.$res[4].'</td>';

				echo '<td>'.$res[5].'</td>';

				echo '<td>'.$res[6].'</td>';

				echo '<td>'.$res[7].'</td>';

				echo '<td>'.$res[8].'</td>';

				echo '<td>'.$res[9].'</td>';

				echo '<td>'.$res[10].'</td>';

				echo '<td>'.$res[11].'</td>';

				echo '

				</tr>

				<tr style="background-color:#fff;">

				<td colspan="10">&nbsp;</td>

				</tr>';

				echo '<tr style="background-color:#fff;">

				<td>&nbsp;</td><td colspan="9">

				<table width="100%">

				<tr>

				<td colspan="3" align="center"><strong>PREVIOUS</strong></td><td colspan="3" align="center"><strong>PRESENT</strong></td><td colspan="3" align="center"><strong>CUMMULATIVE</strong></td>

				</tr>

				<tr style="background-color:#fff;">

				<td align="center">TLU</td><td align="center">TCP</td><td align="center">GPA</td><td align="center">TLU</td><td align="center">TCP</td><td align="center">GPA</td><td align="center">CLU</td><td align="center">CCP</td><td align="center">CGPA</td>

				</tr>

				<tr style="background-color:#fff;">

				<td align="center">'.$res[15].'</td><td align="center">'.$res[16].'</td><td align="center">'.$res[17].'</td><td align="center">'.$res[12].'</td><td align="center">'.$res[13].'</td><td align="center">'.$res[14].'</td><td align="center">'.$res[18].'</td><td align="center">'.$res[19].'</td><td align="center">'.$res[20].'</td>

				</tr>

				</table>

				</td>

				</tr>';

				echo '<tr style="background-color:#fff;">

				<td colspan="10">

				<table width="100%">

				<tr>

				<td width="25%">OUTSTANDING:-&nbsp;</td><td width="25%">'; 

					//if($ret[12]!=""){

					//	echo implode(",",explode("|",$ret[12]));

					//}else{

						echo $res[22];

					//}

				echo '</td><td width="25%">REMARK:-&nbsp;</td><td width="25%">'.remexp($res[21]).'</td>

				</tr>

				</table>

				</td>

				</tr>

				</table>

				</td>';

			echo '</tr>';

			

}



function studentResult($matricno,$name,$faculty,$dept,$level,$sem,$sess){

	connet();

	$ret=studres($matricno,$level,$sem,$sess);

	$regd=studreg($matricno,$level,$sem,$sess);

	$cos=explode("|",$regd[2]);

	$tt=grade($regd[2],$regd[5]); //array of total score

	$un=grade($regd[2],$regd[6]); //array of total score

			echo "<tr>";

				echo '<td colspan="6" align="left">';

					echo '<table align="center" width="100%" style="line-height:2.5em;">';

						echo "<tr>";

							echo '<td align="left" width="20%">&nbsp;<b>NAME:</b></td>';

							echo '<td align="left" width="80%" colspan="3" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$name.'</td>'; 

						echo '</tr>';

						echo '<tr>

								<td align="left" width="15%">&nbsp;<b>MATRIC/REG. NO.:</b></b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$matricno.'</td> 

								<td align="left" width="15%"><b>SESSION:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$sess.'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>PROGRAMME.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.progType($dept).'</td> 

								<td align="left" width="15%"><b>DEPARTMENT:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$dept.'</td>

							</tr>';

							echo '<tr>

								<td align="left" width="15%">&nbsp;<b>FACULTY.:</b></td>

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;">&nbsp;'.$faculty.'</td> 

								<td align="left" width="15%"><b>LEVEL:</b></td>				 

								<td align="left" width="35%" style="border-bottom:#000000 dotted 1px;" >&nbsp;'.$level.'</td>

							</tr>';

							echo '<tr>

								<td align="center" width="100%" colspan="4"><br /><strong>'.$sem.' SEMESTER RESULT</strong></td>

							</tr>';

					echo '</table>';

				echo '</td>';

			echo '</tr>';

			echo "<tr>";

				echo '<td align="left" colspan="6"><hr /></td>';

			echo '</tr>';

			echo "<tr>";

			echo '<td align="center" colspan="6" style="padding:10px 0px;">

				<table style="background-color:#666; line-height:2.5em; text-align:center;" width="100%">'; 

				$n=0;

				echo '<tr style="background-color:#fff; font-weight:bold;"><td align="right">COURSE CODE:-&nbsp;</td>';

				foreach($cos as $v){

					$n+=1;

					echo '<td>'.$v.'</td>';

				}

				echo '

				</tr>';

				echo '<tr style="background-color:#fff; font-weight:bold;"><td align="right">COURSE UNIT:-&nbsp;</td>';

				foreach($un as $k){

					echo '<td>'.$k.'</td>';

				}

				echo '

				</tr>';

				echo '<tr style="background-color:#fff; font-weight:bold;"><td align="right">SCORE:-&nbsp;</td>';

				foreach($tt as $i){

					echo '<td>'.$i.'('.rGrade($i).')</td>';

				}

				echo '

				</tr>

				<tr style="background-color:#fff;">

				<td colspan="'.($n+1).'">&nbsp;</td>

				</tr>';

				echo '<tr style="background-color:#fff;">

				<td>&nbsp;</td><td colspan="'.$n.'">

				<table width="100%">

				<tr>

				<td colspan="3" align="center"><strong>PREVIOUS</strong></td><td colspan="3" align="center"><strong>PRESENT</strong></td><td colspan="3" align="center"><strong>CUMMULATIVE</strong></td>

				</tr>

				<tr style="background-color:#fff;">

				<td align="center">TLU</td><td align="center">TCP</td><td align="center">GPA</td><td align="center">TLU</td><td align="center">TCP</td><td align="center">GPA</td><td align="center">CLU</td><td align="center">CCP</td><td align="center">CGPA</td>

				</tr>

				<tr style="background-color:#fff;">

				<td align="center">'.$ret[2].'</td><td align="center">'.$ret[3].'</td><td align="center">'.$ret[4].'</td><td align="center">'.$ret[5].'</td><td align="center">'.$ret[6].'</td><td align="center">'.$ret[7].'</td><td align="center">'.$ret[8].'</td><td align="center">'.$ret[9].'</td><td align="center">'.$ret[10].'</td>

				</tr>

				</table>

				</td>

				</tr>';

				echo '<tr style="background-color:#fff;">

				<td colspan="'.($n+1).'">

				<table width="100%">

				<tr>

				<td width="25%">OUTSTANDING:-&nbsp;</td><td width="25%">'; 

					if($ret[12]!=""){

						echo implode(",",explode("|",$ret[12]));

					}else{

						echo "";

					}

				echo '</td><td width="25%">REMARK:-&nbsp;</td><td width="25%">'.remexp($ret[11]).'</td>

				</tr>

				</table>

				</td>

				</tr>

				</table>

				</td>';

			echo '</tr>';

			

}



function screenRec($faculty,$dept){

	connet();

	$sql=result("select a.regno, a.surname, a.onames, a.state, a.level, b.status from studentinformation as a, freshersverification as b where a.faculty='$faculty' AND a.dept='$dept' AND a.regno=b.regno ORDER BY a.level, a.surname ASC");	

	echo '

	<tr>

	<td colspan="6" width="100%">

	<table width="100%" style="background-color:#ccc;">

	<tr style="background-color:#fff;">

				<td align="left" width="5%"><b>S/N</b></td>

				<td align="left" width="15%"><b>Reg. No</b></td>

				<td align="left" width="50%"><b>Name</b></td> 				 

				<td align="left" width="10%"><b>State</b></td>

				<td align="left" width="5%"><b>Level</b></td>

				<td align="left" width="15%"><b>Completed</b></td>

			</tr>';

	$sn=0;

	while($rec=mysql_fetch_array($sql)){

		$sn+=1;

		echo '<tr style="background-color:#fff;">

				<td align="left" width="5%">'.$sn.'</td>

				<td align="left" width="10%">'.$rec[0].'</td>

				<td align="left" width="55%">'.$rec[1].', '.$rec[2].'</td> 				 

				<td align="left" width="10%">'.$rec[3].'</td>

				<td align="left" width="5%">'.$rec[4].'</td>

				<td align="left" width="15%">'.$rec[5].'</td>

			</tr>';

	}

	echo '

	</table>

	<td>

	</tr>

	';

	

}



function admissionRec($faculty,$dept){

	connet();

	$sql=result("select regno, surname, onames, sex, state, lg, level from studentinformation where status='NEW' AND faculty='$faculty' AND dept='$dept' ORDER BY level, surname ASC");	

	echo '

	<tr>

	<td colspan="6" width="100%">

	<table width="100%" style="background-color:#ccc;">

	<tr style="background-color:#fff;">

				<td align="left" width="5%" style="padding:2px 0px;"><b>S/N</b></td>

				<td align="left" width="15%" style="padding:2px 0px;"><b>Reg. No</b></td>

				<td align="left" width="40%" style="padding:2px 0px;"><b>Name</b></td> 

				<td align="left" width="5%" style="padding:2px 0px;"><b>Sex</b></td> 

				<td align="left" width="10%" style="padding:2px 0px;"><b>State</b></td>

				<td align="left" width="20%" style="padding:2px 0px;"><b>Local Govt</b></td>

				<td align="left" width="5%" style="padding:2px 0px;"><b>Level</b></td>

			</tr>';

	$sn=0;

	while($rec=mysql_fetch_array($sql)){

		$sn+=1;

		echo '<tr style="background-color:#fff;">

				<td align="left" width="5%" style="padding:2px 0px;">'.$sn.'</td>

				<td align="left" width="15%" style="padding:2px 0px;">'.$rec[0].'</td>

				<td align="left" width="40%" style="padding:2px 0px;">'.$rec[1].', '.$rec[2].'</td> 				 

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec[3].'</td>

				<td align="left" width="10%" style="padding:2px 0px;">'.$rec[4].'</td>

				<td align="left" width="20%" style="padding:2px 0px;">'.$rec[5].'</td>

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec[6].'</td>

			</tr>';

	}

	echo '

	</table>

	<td>

	</tr>

	';

	

}

function paymentType(){
	connet();
	$sql=result("SELECT DISTINCT(feetype) FROM paymentinvoice");

	echo '<select name="paymenttype" class="propertySelect" style="width:175px; font-size:12px; height: 28px;">

		<option value="" selected >--Select--</option>';
	while($rec=mysql_fetch_array($sql)){

		echo '<option value="'.$rec[0].'">'.$rec[0].'</option>';

	}
	echo '</select>';

}

function faculties(){
	connet();
	$sql=result("SELECT DISTINCT(faculty) FROM faculties_dept");

	echo '<select name="faculty" class="propertySelect" style="width:175px; font-size:12px; height: 28px;">

		<option selected >ALL</option>';
	while($rec=mysql_fetch_array($sql)){

		echo '<option value="'.$rec[0].'">'.$rec[0].'</option>';

	}
	echo '</select>';

}

function distSession(){
	connet();
	$sql=result("SELECT DISTINCT(session) FROM calendarsetup ORDER BY id DESC");

	echo '<select name="session" class="propertySelect" style="width:175px; font-size:12px; height: 28px;">

		<option selected >ALL</option>';
	while($rec=mysql_fetch_array($sql)){

		echo '<option value="'.$rec[0].'">'.$rec[0].'</option>';

	}
	echo '</select>';

}


function payStd($paytype){

	$und=array("UNIMED SCHOOL FEES","UNIMED INTERNET ACCESS","UNIMED ACCOMMODATION FEE","UNIMED ACCEPTANCE FEE"); //undergraduate

	$nakr=array("NURSING AND MIDWIFERY SCHOOL FEE"); //Nursing and Midwifery Akure

	if(in_array($paytype,$und)){

		$a=1;

	}elseif(in_array($paytype,$nakr)){

		$a=2;

	}else{

		$a=0;

	}

	return $a;

}


function paymentRep($start,$end,$paytype){
	connet();
	$query1=result("SELECT transactionid, matricno, sname, oname, faculty, department, level, amount, feetype, upid FROM paymentinvoice WHERE feetype='$paytype' AND feestatus='PAID' AND transactionid IN (SELECT unimedid FROM paymentrep WHERE paytype='$paytype' AND status='APPROVED' AND (refdate BETWEEN '$start' AND '$end'))");
	
	$query2=result("SELECT a.transactionid, a.matricno, a.sname, a.oname, a.faculty, a.department, a.level, a.amount, a.feetype, a.upid, b.endtime FROM paymentinvoice a, payment_data_etz b WHERE a.feetype='$paytype' AND a.feestatus='PAID' AND a.transactionid=b.TRANS_ID AND (b.reason='Payment Successful' || b.reason='Approved') AND (b.refdate BETWEEN '$start' AND '$end')");
	

	echo '

	<tr>

	<td colspan="6" width="100%">

	<table width="100%" style="background-color:#ccc;">

	<tr style="background-color:#fff;">

				<td align="left" width="5%" style="padding:2px 0px;"><b>S/N</b></td>

				<td align="left" width="15%" style="padding:2px 0px;"><b>Matric/Reg. No</b></td>
				<td align="left" width="15%" style="padding:2px 0px;"><b>Transaction ID</b></td>

				<td align="left" width="40%" style="padding:2px 0px;"><b>Name</b></td> 

				<td align="left" width="5%" style="padding:2px 0px;"><b>Payment Type</b></td> 

				<td align="left" width="10%" style="padding:2px 0px;"><b>Amount</b></td>

				<td align="left" width="20%" style="padding:2px 0px;"><b>Session</b></td>

				<td align="left" width="5%" style="padding:2px 0px;"><b>Level</b></td>
				<td align="left" width="5%" style="padding:2px 0px;"><b>Department</b></td>

				<td align="left" width="5%" style="padding:2px 0px;"><b>Collector ID</b></td>
				<td align="left" width="5%" style="padding:2px 0px;"><b>Details</b></td>

				<td align="left" width="5%" style="padding:2px 0px;"><b>Date</b></td>

			</tr>';

	$sn=0;
	$tot=0;
	while($rec=mysql_fetch_array($query1)){

		$sn+=1;
		$tot+=$rec['amount'];
		echo '<tr style="background-color:#fff;">

				<td align="left" width="5%" style="padding:2px 0px;">'.$sn.'</td>

				<td align="left" width="15%" style="padding:2px 0px;">'.$rec[2].'</td>
				<td align="left" width="15%" style="padding:2px 0px;">'.$rec[1].'</td>

				<td align="left" width="40%" style="padding:2px 0px;">'.$rec[2].', '.$rec[3].'</td> 				 

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec['feetype'].'</td>

				<td align="left" width="10%" style="padding:2px 0px;">'.$rec['amount'].'</td>

				<td align="left" width="20%" style="padding:2px 0px;">'.$rec['session'].'</td>

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec['level'].'</td>

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec['department'].'</td>

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec['upid'].'</td>

				<td align="left" width="5%" style="padding:2px 0px;">';

				if($rec['upid']!=""){

				echo '<a href="../portal/payjson.php?id='.$rec[0].'" target="popup" 

  onclick="window.open(\'../portal/payjson.php?id='.$rec[0].'\',\'popup\',\'width=600,height=300\'); return false;">&raquo;&nbsp;View</a>';

				}else{
					echo 'N/A';
				}

  				echo '</td>

			</tr>';

	}
	
	while($rec1=mysql_fetch_array($query2)){

		$sn+=1;
		$tot+=$rec1['amount'];
		echo '<tr style="background-color:#fff;">

				<td align="left" width="5%" style="padding:2px 0px;">'.$sn.'</td>

				<td align="left" width="15%" style="padding:2px 0px;">'.$rec1[2].'</td>
				<td align="left" width="15%" style="padding:2px 0px;">'.$rec1[1].'</td>

				<td align="left" width="40%" style="padding:2px 0px;">'.$rec1[2].', '.$rec1[3].'</td> 				 

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec1['feetype'].'</td>

				<td align="left" width="10%" style="padding:2px 0px;">'.$rec1['amount'].'</td>

				<td align="left" width="20%" style="padding:2px 0px;">'.$rec1['session'].'</td>

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec1['level'].'</td>

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec1['department'].'</td>

				<td align="left" width="5%" style="padding:2px 0px;">'.$rec1['upid'].'</td>

				<td align="left" width="5%" style="padding:2px 0px;">';

				if($rec1['upid']!=""){

				echo '<a href="../portal/payjson.php?id='.$rec1[0].'" target="popup" 

  onclick="window.open(\'../portal/payjson.php?id='.$rec1[0].'\',\'popup\',\'width=600,height=300\'); return false;">&raquo;&nbsp;View</a>';

				}else{
					echo 'N/A';
				}

  				echo '</td>

			</tr>';

	}

	echo '

	</table>

	<td>

	</tr>

	';

	

}



function screenNo(){

	connet();

	$sql=result("SELECT * from freshersverification");

	$no=mysql_num_rows($sql);

	return $no;

}

function retStaffNo($staffId){

	connet();

	$formQuery="SELECT staffNo FROM staffLogin where staffId='$staffId'";

	$formResult=result($formQuery);

	$resultData=mysql_fetch_array($formResult);

	return $resultData[0]; 

}



function checkStaffId($staffId){

	connet();

	$staffNo="Staff_".$staffId;

	$formQuery="SELECT * FROM staff2 where staffNo='$staffNo'";

	$formResult=result($formQuery);

	$resultData=mysql_num_rows($formResult);

	return $resultData; 

}



function retStaffRec($staffId){

	connet();

	$staffNo="Staff_".$staffId;

	$formQuery="SELECT * FROM staff2 where staffNo='$staffNo'";

	$formResult=result($formQuery);

	$resultData=mysql_fetch_array($formResult);

	return $resultData; 

}



function retCourseRec($tableName,$matricno,$semester,$session,$level){

	connet();

	$formQuery="SELECT * FROM $tableName where matricno='$matricno' AND session='$session' AND semester='$semester' AND level='$level'";

	$formResult=result($formQuery);

	$resultData=mysql_fetch_array($formResult);

	return $resultData; 

}





function updateStaffRec1($recVals,$staffNo){

	connet();

	$checkerQuery="UPDATE staff2 SET 

staffNo='$recVals[0]',

picture='$recVals[1]',

title='$recVals[2]',

sname='$recVals[3]',

oname='$recVals[4]',

dom='$recVals[5]',

dob='$recVals[6]',

doy='$recVals[7]',

mStatus='$recVals[8]',

skul='$recVals[9]',

dept='$recVals[10]',

gender='$recVals[11]',

country='$recVals[12]',

email='$recVals[13]',

password='$recVals[14]',

confPass='$recVals[15]',

inst1='$recVals[16]',

qual1='$recVals[17]',

iyear1='$recVals[18]',

inst2='$recVals[19]',

qual2='$recVals[20]',

iyear2='$recVals[21]',

inst3='$recVals[22]',

qual3='$recVals[23]',

iyear3='$recVals[24]',

inst4='$recVals[25]',

qual4='$recVals[26]',

iyear4='$recVals[27]',

inst5='$recVals[28]',

qual5='$recVals[29]',

iyear5='$recVals[30]',

org1='$recVals[31]',

title1='$recVals[32]',

orgyear1='$recVals[33]',

org2='$recVals[34]',

title2='$recVals[35]',

orgyear2='$recVals[36]',

org3='$recVals[37]',

title3='$recVals[38]',

orgyear3='$recVals[39]',

pjob1='$recVals[40]',

pposition1='$recVals[41]',

pdes1='$recVals[42]',

pyear1='$recVals[43]',

pjob2='$recVals[44]',

pposition2='$recVals[45]',

pdes2='$recVals[46]',

pyear2='$recVals[47]',

pjob3='$recVals[48]',

pposition3='$recVals[49]',

pdes3='$recVals[50]',

pyear3='$recVals[51]',

pjob4='$recVals[52]',

pposition4='$recVals[53]',

pdes4='$recVals[54]',

pyear4='$recVals[55]',

address='$recVals[56]',

city='$recVals[57]',

state='$recVals[58]',

zip='$recVals[59]',

phone='$recVals[60]',

mobile='$recVals[61]',

fax='$recVals[62]' WHERE staffNo='$staffNo'";

$checkResult=result($checkerQuery);

return $checkResult;

}



function updateStaffRec2($recVals,$staffNo){

	connet();

	$checkerQuery="UPDATE staff2 SET 

picture='$recVals[1]',

title='$recVals[2]',

sname='$recVals[3]',

oname='$recVals[4]',

dom='$recVals[5]',

dob='$recVals[6]',

doy='$recVals[7]',

mStatus='$recVals[8]',

skul='$recVals[9]',

dept='$recVals[10]',

gender='$recVals[11]',

country='$recVals[12]',

email='$recVals[13]',

password='$recVals[14]',

confPass='$recVals[15]',

inst1='$recVals[16]',

qual1='$recVals[17]',

iyear1='$recVals[18]',

inst2='$recVals[19]',

qual2='$recVals[20]',

iyear2='$recVals[21]',

inst3='$recVals[22]',

qual3='$recVals[23]',

iyear3='$recVals[24]',

inst4='$recVals[25]',

qual4='$recVals[26]',

iyear4='$recVals[27]',

inst5='$recVals[28]',

qual5='$recVals[29]',

iyear5='$recVals[30]',

org1='$recVals[31]',

title1='$recVals[32]',

orgyear1='$recVals[33]',

org2='$recVals[34]',

title2='$recVals[35]',

orgyear2='$recVals[36]',

org3='$recVals[37]',

title3='$recVals[38]',

orgyear3='$recVals[39]',

pjob1='$recVals[40]',

pposition1='$recVals[41]',

pdes1='$recVals[42]',

pyear1='$recVals[43]',

pjob2='$recVals[44]',

pposition2='$recVals[45]',

pdes2='$recVals[46]',

pyear2='$recVals[47]',

pjob3='$recVals[48]',

pposition3='$recVals[49]',

pdes3='$recVals[50]',

pyear3='$recVals[51]',

pjob4='$recVals[52]',

pposition4='$recVals[53]',

pdes4='$recVals[54]',

pyear4='$recVals[55]',

address='$recVals[56]',

city='$recVals[57]',

state='$recVals[58]',

zip='$recVals[59]',

phone='$recVals[60]',

mobile='$recVals[61]',

fax='$recVals[62]' WHERE staffNo='$staffNo'";

$checkResult=result($checkerQuery);

return $checkResult;

}



function displayLevel(){

	echo

	'<select name="info[]" style="width:146px;font-size:10px;">

		<option value="">Level</option>

		<option value="100">100</option>

		<option value="200">200</option>

		<option value="300">300</option>

		<option value="400">400</option>

	</select>';

}



function numGenerators($start,$range,$name){

	$curYear=date("Y");

	$startYear=$start;

	$endYear=$startYear+$range;

	echo '<select name="'.$name.'" style="height:20px; width:120px; font-size:14px;"><option value="">--Select--</option>';

	//$format=sprintf(%02d);

	for($i=$startYear;$i<$endYear;$i++){

		switch($i){

			default:

				echo '<option value='.$i.'>'.$i.'</option>';

				break;

		}

	}

	echo '</select>';

}



function numGenerators2($start,$range,$val){

	$curYear=date("Y");

	$startYear=$start;

	$endYear=$startYear+$range;

	echo '<option value="">--Select--</option>';

	//$format=sprintf(%02d);

	for($i=$startYear;$i<$endYear;$i++){

		switch($i){

			default:

				echo '<option value="'.$i.'"'; if($i==$val){ echo 'selected'; } echo '>'.$i.'</option>';

				break;

		}

	}

}



function numGeneratorsx($start,$range){

	$curYear=date("Y");

	$startYear=$start;

	$endYear=$startYear+$range;

	echo '<option value="">--Select--</option>';

	//$format=sprintf(%02d);

	for($i=$startYear;$i<$endYear;$i++){

		switch($i){

			default:

				echo '<option value="'.$i.'">'.$i.'</option>';

				break;

		}

	}

}



function getlastRec2($school,$dept){

	connet();

	$query="SELECT * FROM staff_list WHERE school='$school' AND department='$dept' ORDER BY rank DESC LIMIT 0,1";

	$result=result($query);

	$rec=mysql_fetch_array($result);

	switch($rec){

		default:

			return $rec;

			break;

	}

}



function getColVal($table,$field,$field1,$val){

	connet();

	$res=result("SELECT $field FROM $table WHERE $field1='$val'");

	list($retVal)=mysql_fetch_array($res);

	if($retVal !=""){

		return $retVal;

	}

	

}



function staffList_load($file){

connet();

	$result=result("LOAD DATA LOCAL INFILE '$file' INTO TABLE staff_list FIELDS TERMINATED BY ','");

	if(isset($result)){

		$message="Staff List successfully loaded";

	}else{

		$message="Records Loading Failed";

	}

	return $message;

}



function resHostel($hall,$room,$space,$session){

	connet();	

	$res=result("SELECT * FROM hostelreservation WHERE hall='$hall' AND room='$room' AND space='$space' AND session='$session'");

	$n=mysql_num_rows($res);

	if($n>=1){

		return 1;	

	}else{

		return 0;	

	}

}



function resHostel2($hall,$room,$space,$session){

	connet();	

	$res=result("SELECT * FROM hostelreservation WHERE hall='$hall' AND room='$room' AND session='$session'");

	$n=mysql_num_rows($res);

	if($n>=1){

		if($n<$space){

			return 0;	

		}else{

			return 1;	

		}	

	}else{

		return 0;	

	}

}



function getHostel($matric,$gen,$session){

	connet();

	$gender=strtoupper($gen);

	$sql=result("SELECT * FROM hostelallocation WHERE matricno='$matric' AND session='$session'");

	$no=mysql_num_rows($sql);

	if($no==1){

		while($retR=mysql_fetch_array($sql)){

			$hostel=getColVal("hostel","name","code",$retR[2]);

			echo '<tr style="background-color:#FFFFFF;color:#000000;">

			 <td align="left" style="padding:5px 5px 5px 15px;"><label for="propertyCode"><strong>Hostel Name.:</strong></label></td>	

				<td align="left" style="padding:5px;">'.$hostel.'</td>

			</tr>';

			echo '<tr style="background-color:#FFFFFF;color:#000000;">

			 <td align="left" style="padding:5px 5px 5px 15px;"><label for="propertyCode"><strong>Room No.:</strong></label></td>	

				<td align="left" style="padding:5px;">'.$retR[3].'</td>

			</tr>

			<tr style="background-color:#FFFFFF;color:#000000;">

			 <td align="left" style="padding:5px 5px 5px 15px;"><label for="propertyCode"><strong>Bed Space.:</strong></label></td>	

				<td align="left" style="padding:5px;">'.$retR[4].'</td>

			</tr>';	

			

		}

	}else{

		$q=result("SELECT * FROM hostel WHERE sex='$gender' AND status<(rooms*space) ORDER BY id DESC LIMIT 0,1");

		$n=mysql_num_rows($q);

		if($n==0){

			echo '<tr style="background-color:#FFFFFF;color:#F00;">

				 <td align="center" colspan="2" style="padding:5px 5px 5px 15px; "><strong>There is no Hostel Space Again, Thanks</strong></td>	

				</tr>';

			

		}else{

			while($rec=mysql_fetch_array($q)){

		echo '<tr style="background-color:#FFFFFF;color:#000000;">

				 <td align="left" style="padding:5px 5px 5px 15px;"><label for="propertyCode"><strong>Hostel Name.:</strong></label></td>	

					<td align="left" style="padding:5px;">'.$rec[2].'</td>

				</tr>';

				if($rec[6]==0){

					for($i=1;$i<=$rec[3];$i++){

						if(resHostel2($rec[1],$i,$rec[4],$session)==0){

							$room=$i;

							break;

						}

					}

					for($k=1;$k<=$rec[4];$k++){

						if(resHostel($rec[1],$room,$k,$session)==0){

							$space=$k;

							break;

						}

					}

				}elseif($rec[6]!=0 && $rec[7]<$rec[4]){

					////

					for($i=$rec[6];$i<=$rec[3];$i++){

						if(resHostel2($rec[1],$i,$rec[4],$session)==0){

							$room=$i;

							break;

						}

					}

					$a=$rec[7]+1;

					for($k=$a;$k<=$rec[4];$k++){

						if(resHostel($rec[1],$room,$k,$session)==0){

							$space=$k;

							break;

						}

					}

					////

				}elseif($rec[6]!=0 && $rec[7]==$rec[4]){

					$b=$rec[6]+1;

					$space=1;

					////

					for($i=$b;$i<=$rec[3];$i++){

						if(resHostel2($rec[1],$i,$rec[4],$session)==0){

							$room=$i;

							break;

						}

					}

					for($k=1;$k<=$rec[4];$k++){

						if(resHostel($rec[1],$room,$k,$session)==0){

							$space=$k;

							break;

						}

					}

					////

				}

				

				$sentR[]=$matric;

				$sentR[]=$rec[1];

				$sentR[]=$room;

				$sentR[]=$space;

				$sentR[]=$gender;

				$sentR[]=$session;

				input($sentR,"hostelallocation");

				result("UPDATE hostel SET status=status+1, orooms='$room', ospace='$space' WHERE code='$rec[1]'");

				echo '<tr style="background-color:#FFFFFF;color:#000000;">

				 <td align="left" style="padding:5px 5px 5px 15px;"><label for="propertyCode"><strong>Room No.:</strong></label></td>	

					<td align="left" style="padding:5px;">'.$room.'</td>

				</tr>

				<tr style="background-color:#FFFFFF;color:#000000;">

				 <td align="left" style="padding:5px 5px 5px 15px;"><label for="propertyCode"><strong>Bed Space.:</strong></label></td>	

					<td align="left" style="padding:5px;">'.$space.'</td>

				</tr>';	

				

			}

		}

	}

}



function viewMatriculationList($school,$session){

	connet();

	//$set=getSession();

	$set=substr($session,0,4);

	$q=result("SELECT * FROM matriculation WHERE school='$school' AND session='$set' ORDER BY department, minor ASC");

	$n=0;

	echo '<table id="tstyle" align="center" style="background-color:#0000;"><tr style="background-color:#FFFFFF;color:#000000;"><td colspan="9" style="padding: 5px;" align="center"><strong>MATRICULATION LIST FOR SCHOOL OF '.strtoupper($school).' '.$session.' SESSION</strong></td></tr><tr style="background-color:#FFFFFF;color:#000000;"><td style="padding: 5px;"><strong>S/N</strong></td><td style="padding: 5px;"><strong>Matric No</strong></td><td style="padding: 5px;"><strong>Other Names</strong></td><td style="padding: 5px;"><strong>Surname</strong></td><td style="padding: 5px;"><strong>Sex</strong></td><td style="padding: 5px;"><strong>Subject Combination</strong></td><td style="padding: 5px;"><strong>State</strong></td><td style="padding: 5px;"><strong>Local Govt.</strong></td><td style="padding: 5px;"><strong>Signature & Date</strong></td></tr>'; 

	while($res=mysql_fetch_array($q)){

		$n+=1;

		$ss=strtoupper($res[6]);

		switch($ss){

			case "male":

			$sex="M";

			break;

			case "female":

			$sex="F";

			break;

			default:

			$sex="";

			break;

		}

		$lg=getLg($res[1]);

		

		echo '<tr style="background-color:#FFFFFF;color:#000000;"><td style="padding: 5px;">'.$n.'</td><td style="padding: 5px;">'.$res[2].'</td><td style="padding: 5px;">'.strtoupper($res[5]).'</td><td style="padding: 5px;">'.strtoupper($res[4]).'</td><td style="padding: 5px;">'.$sex.'</td><td style="padding: 5px;">'.$res[9].'/'.$res[10].'</td><td style="padding: 5px;">'.$res[7].'</td><td style="padding: 5px;">'.$lg.'</td><td style="padding: 5px;">&nbsp;</td></tr>';

	}

	echo '</table>';

	

	

}



function registeredStudents($school,$level,$table,$studentinfo,$session,$semester){

	connet();

	$dsession=getCalendarInfo("session");

	$dset=getSession();

	if($session==$dset[1] && $semester==$dset[2]){

		$card="accessscratchcardtable";

	}else{

		$card="accessscratchcardtable".substr($session,0,4).strtolower($semester);

	}

	

$q="SELECT DISTINCT(dept) FROM $table WHERE school='$school' ORDER BY dept ASC";

		$qr=result($q);

	

	echo '<table id="tstyle" align="center" style="background-color:#0000;"><tr style="background-color:#FFFFFF;color:#000000;"><td colspan="7" style="padding: 5px;" align="center"><h2>COLLEGE OF EDUCATION, IKERE EKITI</h2></td></tr>

		<tr style="background-color:#FFFFFF;color:#000000;"><td colspan="7" style="padding: 5px;" align="center"><strong>'.$semester.' SEMESTER '.$level.' LEVEL REGISTERED STUDENTS\' LIST FOR '; 

		if($table=="course"){ echo 'SCHOOL OF';}

		echo ' '.strtoupper($school).' '.$session.' SESSION</strong></td></tr><tr style="background-color:#FFFFFF;color:#000000;"><td style="padding: 5px;"><strong>S/N</strong></td><td style="padding: 5px;"><strong>Matric No</strong></td><td style="padding: 5px;"><strong>Surname</strong></td><td style="padding: 5px;"><strong>Other Names</strong></td><td style="padding: 5px;"><strong>Sex</strong></td><td style="padding: 5px;"><strong>Subject Combination</strong></td><td style="padding: 5px;"><strong>State</strong></td></tr>'; 

	$n=0;	

	while($qrData=mysql_fetch_array($qr)){

		switch($table){

				case "course":

				$formQuery=result("SELECT card.RegistrationNumber, stud.surname, stud.onames, stud.sex, stud.state, stud.department, stud.minor FROM $card as card, $studentinfo as stud WHERE card.RegistrationNumber=stud.matricno AND stud.level='$level' AND stud.school='$school' AND stud.department='$qrData[0]' AND card.RegistrationNumber<>'' AND stud.date<>'0000-00-00' ORDER BY minor, surname, onames ASC");

				

				break;

				case "unicourse":

					$formQuery=result("SELECT card.RegistrationNumber, stud.surname, stud.onames, stud.sex, stud.state, stud.department FROM $card as card, $studentinfo as stud WHERE card.RegistrationNumber=stud.matricno AND stud.level='$level' AND stud.school='$school' AND stud.department='$qrData[0]' AND card.RegistrationNumber<>'' AND stud.date<>'0000-00-00' ORDER BY surname, onames ASC");

				break;

				default:

				break;

			}



		while($res=mysql_fetch_array($formQuery)){

			$n+=1;

			switch($res[3]){

				case "male":

				$sex="M";

				break;

				case "female":

				$sex="F";

				break;

				default:

				$sex="";

				break;

			}

			echo '<tr style="background-color:#FFFFFF;color:#000000;"><td style="padding: 5px;">'.$n.'</td><td style="padding: 5px;">'.$res[0].'</td><td style="padding: 5px;">'.strtoupper($res[1]).'</td><td style="padding: 5px;">'.strtoupper($res[2]).'</td><td style="padding: 5px;">'.$sex.'</td><td style="padding: 5px;">'.$res[5]; if($table=="course"){ echo '/'.$res[6]; } else{ echo ' '; }  echo '</td><td style="padding: 5px;">'.$res[4].'</td></tr>';

		}

		

	}

	

	echo '</table>';

	

	//return $card;

}



function retCorseCode($id,$major,$minor,$semester,$level){

	connet();

	switch($level){

	case 300:	

		$checkQuery="SELECT * FROM course where courseCode='$id' AND level='$level' AND  semester='$semester' AND type='OLD' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%')";

		break;

	default:

		$checkQuery="SELECT * FROM course where courseCode='$id' AND level='$level' AND  semester='$semester' AND type='NEW' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%')";

		break;

	}

	//$checkQuery="SELECT * FROM course where courseCode='$id' AND level='$level' AND  semester='$semester' AND type='NEW' AND (dept ='$major' OR dept ='$minor' OR dept LIKE 'EDUCATION%' OR dept LIKE 'GENERAL_STUDIES%')";

	//$checkQuery="SELECT * FROM course where courseCode = '$id'";

	$checkResult=result($checkQuery);

	$returnedRows=mysql_fetch_array($checkResult);

	$retVal=$returnedRows['courseTitle'];

	//return $retVal;

	return $returnedRows;

}



function degreeCat(){

	connet();

	$sql="SELECT DISTINCT(school) FROM unicourse";

	$res=result($sql);

	echo '<ol>';

	while($result=mysql_fetch_array($res)){

		echo '<li><a href="degree_courses.php?degree='.$result[0].'">'.$result[0].'</a></li>';

	}

	echo '</ol>';

}



function degreeDept($school,$table){

	connet();

	$sql="SELECT DISTINCT(dept) FROM $table WHERE school='$school'";

	$res=result($sql);

	while($result=mysql_fetch_array($res)){

		echo '<p style="padding:6px;background-image:url(images/paraHead1.jpg); background-position:top; background-repeat:no-repeat;"><strong><span 			style="color:#FFFFFF;">DEPARTMENT OF '.$result[0].'</span></strong><br /><br />';

  

	  	echo listDeptStaffDeg($result[0],$school);

	 		

		echo '</p><br /><br />';

	}

	

}



function degreeStaff(){

	connet();

	$sql="SELECT DISTINCT(department) FROM stafflogin WHERE school='UNN_DEGREE'";

	$res=result($sql);

	while($result=mysql_fetch_array($res)){

		echo '<p style="padding:6px;background-image:url(images/paraHead1.jpg); background-position:top; background-repeat:no-repeat;"><strong><span 			style="color:#FFFFFF;">DEPARTMENT OF '.$result[0].'</span></strong><br /><br />';

  

	  	echo listDeptStaff($result[0],"UNN_DEGREE");

	 		

		echo '</p><br /><br />';

	}

	

}



function retStudentPack($matric){

	connet();

	$studentQuery="SELECT * FROM studentiformation WHERE matricno='$matric'";

	$studentResult=result($studentQuery);

	$returnedRow=mysql_num_rows($studentResult);

	if($returnedRow==1){

		$studentData=mysql_fetch_array($studentResult);

		return $studentData;

	}else{

		return 0;

	}

}



function checkPackCode($matric,$code){

	connet();

	$codeQuery="SELECT * FROM accessscratchcardtable WHERE pinNumber='$code'";

	$codeResult=result($codeQuery);

	$codeRow=mysql_num_rows($codeResult);

	if($codeRow>0){

		$codeData=mysql_fetch_array($codeResult);

		return $codeData;

	}else{

		return 0;

	}

}



function mkDirect(){

	connet();

	$codeQuery="SELECT DISTINCT(dept) FROM course";

	$codeResult=result($codeQuery);

	$path="../study/";

	while($codeData=mysql_fetch_array($codeResult)){

		mkdir($path.$codeData[0]);

		mkdir($path.$codeData[0]."/100");

		mkdir($path.$codeData[0]."/100/1st");

		mkdir($path.$codeData[0]."/100/2nd");

		mkdir($path.$codeData[0]."/200");

		mkdir($path.$codeData[0]."/200/1st");

		mkdir($path.$codeData[0]."/200/2nd");

		mkdir($path.$codeData[0]."/300");

		mkdir($path.$codeData[0]."/300/1st");

		mkdir($path.$codeData[0]."/300/2nd");

		mkdir($path.$codeData[0]."/400");

		mkdir($path.$codeData[0]."/400/1st");

		mkdir($path.$codeData[0]."/400/2nd");

	}

}



function returnFiles($studyPath){

	if(is_dir($studyPath)){

		$dirPath=opendir($studyPath);

		$counter=0;

		while($dirCont=readdir($dirPath)){

			$fileName="$studyPath/".$dirCont;

			if(is_file($fileName)){

				$fileInfo=pathinfo($fileName);

				$fileExtension=$fileInfo["extension"];

				$fileType=filetype($fileName);

				if(strtoupper($fileExtension)=="PDF"){

					$storedFiles[$counter]=$fileName;

					//echo $fileName."<br />";

					$counter=$counter+1;

				}

			}

			

		}

		ksort($storedFiles);

		//for($i=0;$i<sizeof($newFiles);$i++){

			//echo '<a href="gallery.php?val='.$newFiles[$i].'" onclick="return displayPix()" title="Pix '.$newFiles[$i].'"><img src="'.$newFiles[$i].'" width="60" id="thumbPix'.$i.'" title="Pix '.$newFiles[$i].'" vspace="5" hspace="5"/></a><br />';

			

		//}

		return $storedFiles;

	}

}

?>