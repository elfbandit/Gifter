<?php
include 'dbConnect.php';
session_start();

if (ISSET($_POST['exchangeId'])) {
	$exchangeId = $_POST['exchangeId'];

	//Make sure this user has permission to close the exchange
	$result = query("SELECT permission FROM exchangeUser WHERE userId=".$_SESSION['userInfo']['userId']);
	if(mysql_fetch_object($result)->permission < 3){
		print_error("You do not have permission to close this exchange");
		return;
	}

	//Find all gifts that are shared in the exchange, and mark them gifted
		query("UPDATE gifts set gifted = true WHERE userId IN(
						SELECT userId
						FROM exchangeUser
						WHERE exchangeId =".$exchangeId.")  
					AND gifterId IN (
						SELECT userId
						FROM exchangeUser
						WHERE exchangeId =".$exchangeId.")"
					);
	//Soft-delete the exchange
		query("UPDATE exchange SET active=FALSE WHERE exchangeId='" . $exchangeId . "'");
		if(mysql_errno() != 0){
			print_error("Could not close exchange");
		}else{
			print_success("Exchange Closed");	
		}
		

} else {
		print_error("You must pass in an exchangeId");
}
?>