<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();
$exchangeId = mysqli_real_escape_string($_POST["exchangeId"]);

if(isset($_POST["exchangeId"])){
	$result = query("SELECT userId,firstName,lastName,email 
						FROM user where userId IN(
							SELECT userId 
							FROM exchangeUser
							WHERE exchangeUser.exchangeId =".$exchangeId."
							AND permission > 0
							AND EXISTS(SELECT *
										FROM exchangeUser
										WHERE exchangeId = ".$exchangeId."
										AND userId = '".$_SESSION['userInfo']['userId']."'
										AND permission > 0)
						)
						AND userId !=".$_SESSION['userInfo']['userId']);
}else{
//Select all users that are associated with this user's active exchanges
$result = query("SELECT userId,firstName,lastName,email 
						FROM user where userId IN(
							SELECT userId 
							FROM exchangeUser
							WHERE exchangeUser.exchangeId IN(
								SELECT exchange.exchangeId 
								FROM exchangeUser JOIN exchange ON exchangeUser.exchangeId=exchange.exchangeId
								WHERE userId = '".$_SESSION['userInfo']['userId']."'
								AND active = TRUE
								) 
							AND permission > 0
						)
						AND userId !=".$_SESSION['userInfo']['userId']);
}
 
//Add all records to an array
$rows = array();
while($row = mysqli_fetch_array($result))
{
    $rows[] = $row;
}
 
//Return result to jTable

$jTableResult['records'] = $rows;
print json_encode($jTableResult);


/*
 {
 "Result":"OK",
 "Records":[
  {"PersonId":1,"Name":"Benjamin Button","Age":17,"RecordDate":"\/Date(1320259705710)\/"},
  {"PersonId":2,"Name":"Douglas Adams","Age":42,"RecordDate":"\/Date(1320259705710)\/"},
  {"PersonId":3,"Name":"Isaac Asimov","Age":26,"RecordDate":"\/Date(1320259705710)\/"},
  {"PersonId":4,"Name":"Thomas More","Age":65,"RecordDate":"\/Date(1320259705710)\/"}
 ]
}
 */

?>
