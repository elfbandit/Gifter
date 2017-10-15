<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();
$userId = mysqli_real_escape_string($_POST['userId']);
$exchangeId = mysqli_real_escape_string($_POST['exchangeId']);

//Make sure the exchange is still open
if (exchangeActive($exchangeId) == FALSE) {
	print_error("The requested exchange is closed");
	return;
}

//Make sure the accessing user has moderator prmissions on the exchange
// 0=applicant, 1=participant, 2=moderator, 3=admin
$result = query("SELECT permission FROM exchangeUser WHERE userId=" . $_SESSION['userInfo']['userId'] . " AND exchangeId=" . $exchangeId . " AND permission > 1");
if (mysqli_num_rows($result) == 0) {
	echo "You do not have permission to moderate this exchange";
	return;
} else if (!isset($_POST['action']) || !isset($_POST['userId']) || !isset($exchangeId)) {
	echo "You must specify an exchange, an action to perform, and a user to perform it on";
	return;
}

$permission =  mysqli_fetch_object($result) -> permission;

switch ($_POST['action']) {
	case 'add' :
		query("INSERT INTO exchangeUser(exchangeId,userId,permission) VALUES ('".$exchangeId."','" . $userId . "','1')");
		break;
	case 'approve' :
		query("UPDATE exchangeUser SET permission=1 where userId=" . $userId . " AND exchangeId=" . $exchangeId);
		break;
	case 'deny' :
		query("DELETE FROM exchangeUser where userId=" . $userId  . " AND exchangeId=" . $exchangeId);
		break;
	case 'remove' :
		query("DELETE FROM exchangeUser where userId=" . $userId  . " AND exchangeId=" . $exchangeId);
		//unclaim all gifts that this user is no longer associated with
		//except for users where he is still associated with in another exchange
		$result = query("SELECT giftId
			FROM gifts,
				(SELECT userId 
							FROM exchangeUser 
							WHERE exchangeId =" . $exchangeId . " 
							
							AND userId NOT IN(
							
							SELECT userId 
							FROM exchangeUser 
							WHERE exchangeId in(
								SELECT exchangeId 
								FROM exchangeUser 
								WHERE userId=" . $userId  . " 
								AND exchangeId != " . $exchangeId . "
								)
							)
				) as deleteUsers
			WHERE (gifts.gifterId='" . $userId  . "'
					AND gifts.userId IN (deleteUsers.userId))
					OR
					(gifts.userId='" . $userId  . "'
					AND gifts.gifterId IN (deleteUsers.userId))
			AND gifts.gifted = FALSE");

		$giftList = "";
		while ($row = mysqli_fetch_array($result, mysqli_ASSOC)) {
			$giftList .= $row['giftId'] . ",";
		}

		query("UPDATE gifts set gifterId = NULL where giftId in(" . substr($giftList, 0, strlen($giftList) - 1) . ")");
		break;
	case 'promote' :
		if ($permission > 2)
			query("UPDATE exchangeUser SET permission=2 where userId=" . $userId  . " AND exchangeId=" . $exchangeId);
		break;
	case 'demote' :
		if ($permission > 2)
			query("UPDATE exchangeUser SET permission=1 where userId=" . $userId  . " AND exchangeId=" . $exchangeId);
		break;
	default :
		echo "Error: action " . $_POST['action'] . " not recognized";
		break;
}

//Add all records to an array
$rows = array();
while ($row = mysqli_fetch_array($result)) {
	$rows[] = $row;
}

//Return result

$jTableResult['records'] = $rows;
print json_encode($jTableResult);
?>
