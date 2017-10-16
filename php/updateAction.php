<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();

$name = mysqli_real_escape_string($mysqli,$_POST["name"]);
$link = mysqli_real_escape_string($mysqli,$_POST["link"]);
$description = mysqli_real_escape_string($mysqli,$_POST["description"]);
if(strlen($description) >=255){
	$jTableResult['Message'] = "Your description is too long!";
	$jTableResult['Result'] = "ERROR";
	print json_encode($jTableResult);
	return;
}
$value = mysqli_real_escape_string($mysqli,$_POST["value"]);


//Get records from database
$result = query("UPDATE gifts SET name = '" . $name . "', link = '" . $link . "', description = '". $description ."',value = '". $value ."' WHERE giftId = " . $_POST["giftId"] . " AND userId = " . $_SESSION["userInfo"]["userId"]);
 if(mysqli_error($mysqli)){
	echo mysqli_error($mysqli);
}
 
 
 
 if (mysqli_errno($mysqli))
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