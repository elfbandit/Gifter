<?php
include 'dbConnect.php';

$jTableResult = array();

$name = mysql_real_escape_string($_POST["name"]);
$link = mysql_real_escape_string($_POST["link"]);
$description = mysql_real_escape_string($_POST["description"]);
if(strlen($description) >=255){
	$jTableResult['Message'] = "Your description is too long!";
	$jTableResult['Result'] = "ERROR";
	print json_encode($jTableResult);
	return;
}
$value = mysql_real_escape_string($_POST["value"]);


//Get records from database
$result = mysql_query("UPDATE gifts SET name = '" . $name . "', link = '" . $link . "', description = '". $description ."',value = '". $value ."' WHERE giftId = " . $_POST["giftId"] . " AND userId=".$_SESSION['userInfo']['userId']);
 if(mysql_error()){
	echo mysql_error();
}
 
 
 
 if (mysql_errno($con))
  {
  	$jTableResult['Result'] = "ERROR";
  }else{
  	$jTableResult['Result'] = "OK";
  }
 
//Return result to jTable
print json_encode($jTableResult);


/*
{
 "Result":"OK",
}*/

?>