<?php
include 'dbConnect.php';
session_start();

//Defining function actions

function commentList(&$jTableResult) {
	$result = query("SELECT comments.userId,giftId,commentId,comment,CONCAT_WS(' ',firstName,lastName) as name, time FROM comments join user on comments.userId=user.userId where giftId=" . $_GET['giftId']);
	$jTableResult['Result'] = "OK";
	$rows = array();
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_array($result)) {
			$rows[] = $row;
		}
	}
	$jTableResult['Records'] = $rows;
}

function commentCreate(&$jTableResult) {
	$comment = mysqli_real_escape_string($_POST["comment"]);
	if (strlen($comment) > 255) {
		$jTableResult['Message'] = "Your description is too long!";
		$jTableResult['Result'] = "ERROR";
		return;
	}

	//Get records from database
	$result = query("INSERT INTO comments(giftId,userId,comment) values('" . $_GET['giftId'] . "', '" . $_SESSION['userInfo']['userId'] . "', '" . $comment . "')");

	//Get last inserted record (to return to jTable)
	$result = query("SELECT comments.userId,giftId,commentId,comment,CONCAT_WS(' ',firstName,lastName) as name, time FROM comments join user on comments.userId=user.userId WHERE commentId = LAST_INSERT_ID()");
	$jTableResult['Result'] = "OK";
	$jTableResult['Record'] = mysqli_fetch_array($result);
}

function commentDelete(&$jTableResult){
	$result = query("DELETE FROM comments where userId='".$_SESSION['userInfo']['userId']."' AND commentId='".$_POST['commentId']."'");
	$jTableResult['Result'] = "OK";
}

///MAIN DECISION TREE
$jTableResult = array();

if (!isset($_GET['action']) OR !isset($_GET['giftId'])) {
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = "Need action and giftId";
} else {

	switch ($_GET['action']) {
		case 'list' :
			commentList($jTableResult);
			break;
		case 'create' :
			commentCreate($jTableResult);
			break;
		case 'delete' :
			commentDelete($jTableResult);
			break;
		default :
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Command was not recognized";
			break;
	}
}

//Return result to jTable
print json_encode($jTableResult);

/*
 {
 "Result":"OK",
 "Record":{"Name":"Dan Brown","Age":55,"LastUpdateDate":"\/Date(1320262185197)\/"}
 }*/
?>