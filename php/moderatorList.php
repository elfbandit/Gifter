<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();

//Make sure the exchange is still open
if (exchangeActive($_POST['exchangeId']) == FALSE) {
	print_error("The requested exchange is closed");
	return;
}

//First, make sure the accessing user has moderator prmissions on the exchange
// 0=applicant, 1=participant, 2=moderator, 3=admin
$result = query("SELECT permission FROM exchangeUser WHERE userId=" . $_SESSION['userInfo']['userId'] . " AND exchangeId=" . $_POST['exchangeId'] . " AND permission > 1");
if (mysqli_num_rows($result) == 0) {
	echo "You do not have permission to moderate this exchange";
	return;
}

//Get the users who are in the exchange and their permission level
$result = query("SELECT CONCAT( firstName,  ' ', lastName ) AS 'name', user.userId, permission FROM exchangeUser JOIN  user on exchangeUser.userId=user.userId WHERE exchangeId=" . $_POST['exchangeId']);

//Add all records to an array
$rows = array();
while ($row = mysqli_fetch_array($result)) {
	$rows[] = $row;
}

//Return result

$jTableResult['records'] = $rows;
print json_encode($jTableResult);
?>
