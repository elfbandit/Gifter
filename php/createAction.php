<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();

if(mysqli_errno($mysqli)){
	$jTableResult['Result'] = "ERROR";
}else{
	$jTableResult['Result'] = "OK";
}

$name = mysqli_real_escape_string($mysqli,$_POST["name"]);
$link = mysqli_real_escape_string($mysqli,$_POST["link"]);
$description = mysqli_real_escape_string($mysqli,$_POST["description"]);
if(strlen($description) > 255){
	$jTableResult['Message'] = "Your description is too long!";
	$jTableResult['Result'] = "ERROR";
	print json_encode($jTableResult);
	return;
}
$value = mysqli_real_escape_string($mysqli,$_POST["value"]);

//Insert new gift
$result = query("INSERT INTO gifts(name,link,description,value,userId) values('" . $name . "', '" . $link . "', '" . $description. "', '" . $value."','".$_SESSION["userInfo"]["userId"]."')");
 if($result == FALSE){
 	$jTableResult['Result'] = "ERROR";
 } else{
 
	//Get last inserted record (to return to jTable)
	$result = query("SELECT * FROM gifts WHERE giftId = LAST_INSERT_ID()");
	$row = mysqli_fetch_array($result);
 
	//Return result to jTable
	$jTableResult = array();
	$jTableResult['Result'] = "OK";
	$jTableResult['Record'] = $row;
}
print json_encode($jTableResult);


/*
{
 "Result":"OK",
 "Record":{"Name":"Dan Brown","Age":55,"LastUpdateDate":"\/Date(1320262185197)\/"}
}*/

?>