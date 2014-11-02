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
	
	//Find all users affected by this closing:
		$userList = query("SELECT u.userId, u.email
						FROM exchangeUser e, user u
						WHERE e.userId = u.userId
						AND e.exchangeId =".$exchangeId);

		$exchangeName = query("select exchangeName from exchange where exchangeId =".$exchangeId)->exchangeName;
		$exchangeAdmin = mysql_fetch_assoc(query("select u.* from user u, exchangeUser e 
												  WHERE u.userId = e.userId 
												  AND e.exchangeId =".$exchangeId."
												  AND e.permission = 3"));
						
	//Add all records to an array
		$userIds = array();
		$userEmails = array();
		if(mysql_num_rows($userList) > 0){
			while($row = mysql_fetch_array($userList))
			{
    			$userIds[] = $row["userId"];
				$userEmails[] = $row["email"];
			}
		}
		
	//Find all gifts that are shared in the exchange, and mark them gifted
		query("UPDATE gifts set gifted = true 
				WHERE userId IN(". implode(",", $userIds).")
				AND gifterId IN(". implode(",", $userIds).")");
		if(mysql_errno() != 0){
			print_error("Could not close exchange");
			return;
		}
		
	//Soft-delete the exchange
		query("UPDATE exchange SET active=FALSE WHERE exchangeId='" . $exchangeId . "'");
		if(mysql_errno() != 0){
			print_error("Could not close exchange");
		}else{
			//Send an email to all affected users
			$to = implode(",", $userEmails);
			$subject = "Gifter: Exchange ".$exchangeName." is now closed";
			$message = "<html><body>
						Hello from Gifter! <br/> 
						We just wanted to let you know that ".$exchangeName." is now closed and all gifts have been marked as gifted.
						You can now take a look at your thank-you list to remember what you got. Check it out <a href = 'http://gifter.site.nfoservers.com/Gifter/main.php'>here</a> <br/>
						<br />
						Thanks!</br>
						".$exchangeAdmin['firstName']."
						</body></email>";
			$headers = "From:".$exchangeAdmin['email']." \r\n" .
			 			"Reply-To: ".$exchangeAdmin['email']." \r\n" .
			 			"MIME-Version: 1.0" . "\r\n" .
			 			"Content-type: text/html; charset=iso-8859-1" . "\r\n".
    					"X-Mailer: PHP/" . phpversion();
			mail($to, $subject, $message,$headers);
				
			
			
			print_success("Exchange Closed");	
		}
		

} else {
		print_error("You must pass in an exchangeId");
}
?>