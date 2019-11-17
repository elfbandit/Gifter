<?php
include_once ("dbConnect.php");
session_start();

if (isset($_GET['logout'])) {
	$_SESSION = array();
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		// Finally, destroy the session.
		session_destroy();
	}
}

if (isset($_POST['email']) AND isset($_POST['password'])) {
	if ($_POST['email'] != NULL AND $_POST['password'] != NULL) {

		if (isset($_POST['login'])) {
			//attempt to log in the user
			//Prevent sql or html to be executed in db or page.
			$usr_good = $mysqli->real_escape_string(htmlspecialchars($_POST['email']));
			$psw_good = $mysqli->real_escape_string(md5($_POST['password']));
			$answer_query = query('SELECT email,userId,firstName,lastName,password FROM user WHERE email=\'' . $usr_good . '\' LIMIT 0, 1');
			$stored_info = $answer_query->fetch_assoc();
			$stored_psw = $mysqli->real_escape_string(htmlspecialchars($stored_info['password']));

			if ($psw_good == $stored_psw) {
				$_SESSION['userInfo'] = $stored_info;
			}
		} else if (isset($_POST['create']) AND isset($_POST['firstName']) AND isset($_POST['lastName'])) {
			$usr_good = $mysqli->real_escape_string(htmlspecialchars($_POST['email']));
			$first_good = $mysqli->real_escape_string(htmlspecialchars($_POST['firstName']));
			$last_good = $mysqli->real_escape_string(htmlspecialchars($_POST['lastName']));
			$psw_good = $mysqli->real_escape_string(md5($_POST['password']));
			$query = "INSERT INTO user(email,password,firstName,lastName) values('" . $usr_good . "','". $psw_good ."','" . $first_good . "','" . $last_good . "') ";
			$create_query = query($query);

			if (mysqli_errno($mysqli)) {
				echo mysqli_error();
			} else {

				$answer_query = query('SELECT email,userId,firstName,lastName,password FROM user WHERE email=\'' . $usr_good . '\' LIMIT 0, 1');
				$stored_info = mysqli_fetch_assoc($answer_query);
				$stored_psw = $stored_info['password'];

				if ($psw_good == $stored_psw) {
					$_SESSION['userInfo'] = $stored_info;
				}
			}
		}
	}
}

//set up context object for table display
if (isset($_SESSION['userInfo'])) {// only set the context if user is logged in
	if (isset($_GET['context'])) {
		set_context($_GET['context']);
	} else {
		set_context($_SESSION['userInfo']['userId']);
	}

}

function set_context($contextId) {
	if (is_numeric($contextId) AND isset($contextId)) {
		$answer_query = query('SELECT email,userId,firstName,lastName,COALESCE((SELECT MAX(updated) FROM gifts g WHERE g.userId = u.userId),u.created) AS lastUpdated FROM user u WHERE userId=\'' . $contextId . '\' LIMIT 0, 1');
		if ($answer_query == false) {
			echo mysqli_error($mysqli);
		} else {
			$context = mysqli_fetch_assoc($answer_query);
			$_SESSION['context'] = $context;
		}
	}
}

?>