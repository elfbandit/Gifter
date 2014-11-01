<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();

//Get records from database
if(isset($_GET['table']) AND $_GET['table'] == "shop"){ //requesting the shopping list
	$result = mysql_query("UPDATE gifts SET gifterId = NULL WHERE gifterId = ".$_SESSION['userInfo']['userId']." AND giftID = ". $_POST["giftId"]);
}else if(isset($_GET['table']) AND $_GET['table'] == "thank"){ //requesting the thank-you list
	$result = mysql_query("UPDATE gifts SET thanked = TRUE WHERE userId = ".$_SESSION['userInfo']['userId']." AND giftID = ". $_POST["giftId"]);
}else{
	$result = mysql_query("DELETE FROM gifts WHERE giftId = " . $_POST["giftId"]." AND userId = ".$_SESSION['userInfo']['userId']);
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