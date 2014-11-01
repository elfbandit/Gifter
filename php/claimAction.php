<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();

//Make sure the requested user has permission to claim
$result = query("SELECT b.userId FROM exchangeUser a, exchangeUser b WHERE a.exchangeId=b.exchangeId AND a.userId=".$_SESSION['userInfo']['userId']." AND b.userId=".$_SESSION['context']['userId']);
if(mysql_num_rows($result) == 0){
	echo "You do not have permission to claim gifts from this user";
	return;
}

//Update the table
$result = query("UPDATE gifts SET gifterId = '" . $_SESSION['userInfo']['userId'] ."' WHERE giftId = " . $_POST["giftId"]." AND userId=".$_SESSION['context']['userId']);
 
 
 if (mysql_errno($con))
  {
  	$jTableResult['Result'] = "FAIL";
  }else{
  	$jTableResult['Result'] = "OK";
	$jTableResult['Record'] = $row;
  }
 
//Return result to jTable
print json_encode($jTableResult);


/*
{
 "Result":"OK",
}*/

?>