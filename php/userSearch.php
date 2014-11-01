<?php
include 'dbConnect.php';
session_start();

$term = mysql_real_escape_string($_GET["term"]);
if(strlen($description) >=255){
	$jTableResult['Message'] = "Your search term is too long!";
	$jTableResult['Result'] = "ERROR";
	print json_encode($jTableResult);
	return;
}
$exchangeId = mysql_real_escape_string($_GET["exchangeId"]);

//Select all users that are associated with this user's active exchanges
$result = query("SELECT CONCAT(firstName,' ',lastName) as label, CONCAT(firstName,' ',lastName) as value,userId,firstName,lastName,email 
						FROM user
						WHERE (firstName like('".$term."%')
						OR lastName like('".$term."%'))
						AND userId NOT IN(
							SELECT userId
							FROM exchangeUser
							WHERE exchangeId=".$exchangeId."
							)
						");
 
//Add all records to an array
$rows = array();
while($row = mysql_fetch_array($result))
{
    $rows[] = $row;
}
 
//Return result to jTable

print json_encode($rows);


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
