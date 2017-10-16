<?php

$mysqli = mysqli_connect("localhost", "shiden", "DCNUSgdSWy","shiden_gifter");
//$mysqli->select_db("shiden_gifter");

if(isset($_GET['XDEBUG_SESSION_START'])){
	//local conneciton, use local path
	$server_path = "http://localhost/gifter";
} else{
	//production server; use no path
	$server_path = "";
}

// Check connection
if (mysqli_connect_errno($mysqli)) {
    die("Connection failed: " . $mysqli->connect_error);
} 

//generic query function
function query($queryString) {
$mysqli = mysqli_connect("localhost", "shiden", "DCNUSgdSWy","shiden_gifter");

$result = mysqli_query($mysqli,$queryString);
if($mysqli->error !== ''){
	echo "Error executing query: " . $mysqli->error;
}

return $result;
}

function print_error($errorString) {
	$result = array();

	$result['Result'] = "ERROR";
	$result['Message'] = $errorString;
	print json_encode($result);
	return;
}

function print_success($message) {
	$result = array();

	$result['Result'] = "OK";
	$result['Message'] = $message;
	print json_encode($result);
	return;
}

function exchangeActive($exchangeId){
	$result = query("SELECT active FROM exchange WHERE exchangeId=".$exchangeId);
	return mysqli_fetch_object($result)->active;
}
?>