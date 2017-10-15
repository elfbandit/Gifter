<?php
if(!defined($mysqli)){
//$con=mysqli_connect("127.0.0.1","gifterUser","","gifter");
$mysqli = new mysqli("localhost", "shiden", "DCNUSgdSWy");
$mysqli->select_db("shiden_gifter");
}
$server_path = "";

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

//generic query function
function query($queryString) {

if(!defined($mysqli)){
//$con=mysqli_connect("127.0.0.1","gifterUser","","gifter");
$mysqli = new mysqli("localhost", "shiden", "DCNUSgdSWy");
$mysqli->select_db("shiden_gifter");
}

if ($result = $mysqli->query($queryString) === TRUE) {
	echo $queryString;
	echo $result->num_rows;
return $result;
} else {
echo "Error executing query: " . $mysqli->error;
}
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