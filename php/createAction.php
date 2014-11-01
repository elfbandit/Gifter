<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();

if(mysql_errno($con)){
	$jTableResult['Result'] = "ERROR";
}else{
	$jTableResult['Result'] = "OK";
}

$name = mysql_real_escape_string($_POST["name"]);
$link = mysql_real_escape_string($_POST["link"]);
$description = mysql_real_escape_string($_POST["description"]);
if(strlen($description) > 255){
	$jTableResult['Message'] = "Your description is too long!";
	$jTableResult['Result'] = "ERROR";
	print json_encode($jTableResult);
	return;
}
$value = mysql_real_escape_string($_POST["value"]);

//Get records from database
$result = mysql_query("INSERT INTO gifts(name,link,description,value,userId) values('" . $name . "', '" . $link . "', '" . $description. "', '" . $value."','".$_SESSION["userInfo"]["userId"]."')");
 
//Get last inserted record (to return to jTable)
$result = mysql_query("SELECT * FROM gifts WHERE giftId = LAST_INSERT_ID()");
$row = mysql_fetch_array($result);
 
//Return result to jTable
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Record'] = $row;
print json_encode($jTableResult);


/*
{
 "Result":"OK",
 "Record":{"Name":"Dan Brown","Age":55,"LastUpdateDate":"\/Date(1320262185197)\/"}
}*/

?>