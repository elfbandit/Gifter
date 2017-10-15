<?php
include 'dbConnect.php';
session_start();

if (ISSET($_POST['exchangeName'])) {
	$exchangeName = mysqli_real_escape_string($_POST["exchangeName"]);

	//Ensure there are no duplicates
	$result = query("SELECT * FROM exchange WHERE exchangeName LIKE('" . $exchangeName."')");
	if (mysqli_num_rows($result) > 0 || mysqli_errno()) {
		print_error("An exchange by that name already exists");
		return;
	}

	query("INSERT INTO exchange(exchangeName) VALUES ('" . $exchangeName . "')");
	$exchangeId = mysqli_fetch_object(query("SELECT LAST_INSERT_ID() as id from exchange"))->id;
	query("INSERT INTO exchangeUser(exchangeId,userId,permission) VALUES ('".$exchangeId."','" . $_SESSION['userInfo']['userId'] . "','3')");

} else {
	print_error("You must pass in an exchangeName");
}
//return a generic result
print_success("");

/*
 {
 "Result":"OK",
 }*/
?>