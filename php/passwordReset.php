<?php

/*
 * This page is intended to be linked at the top of the login page
 * If the user wants to reset their password, pass email in the GET
 * The email will contain a temporary password, which can be used to set a new password
 */

include_once 'dbConnect.php';
session_start();

//See if we need to do something
if (isset($_POST['resetemail'])) {//need email to be passed in request
	$email = mysql_real_escape_string($_POST["resetemail"]);
	$query = "SELECT password FROM user WHERE email='" . $email . "'";
	$result = query($query);

	//check result
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);
		$new_password = md5($row[0]);

		//save the new password to the DB
		$query = "UPDATE user SET password='" . md5($new_password) . "' WHERE email='" . $email . "'";
		$result = query($query);
		if (mysql_info() == false) {//return an error if transaction was unsuccessful
			$message = ('Error: was not able to update password. Please check database status.');
		}

		//send email with temporary link
		$emailBody = "Greetings from MyGifter!
				We understand that you forgot your password (Whoops!). Don't worry- here is a temporary
				link to rest it. This link will only work once, so if you get an error, just use the
				password reset on the login page again.
				
				Happy gifting!
				<a>http://www.mygifter.com/index.php?hash=" . $new_password . "</a>
	";

		mail($email, 'MyGifter password reset', $emailBody, 'From: noreply@mygifter.com');
	}
	//regardless of email status, print success message
	$message = ('Success: Check your email to reset your password');

} elseif (isset($_POST['newpassword']) && isset($_POST['hash'])) {//password reset request
	//check the hash for correctness
	$hash = mysql_real_escape_string($_POST["hash"]);
	$query = "SELECT count(0) FROM user WHERE password='" . md5($hash) . "'";
	$result = query($query);

	if (mysql_num_rows($result) > 0) {//hash is valid; set the new password
		$newpassword = mysql_escape_string($_POST['newpassword']);
		$query = "UPDATE user SET password = '" . md5($newpassword) . "' WHERE password = '" . md5($hash) . "'";
		query($query);
		if (mysql_info() == false) {//return an error if transaction was unsuccessful
			$message = 'Error: was not able to update password. Please check database status.';
		} else {
			$message = 'Password reset! Please try logging in below';
		}
	} else{
		$message = 'Password reset link is invalid.';
	}

}

?>
