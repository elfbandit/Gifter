<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();

//Get the exchange name, exchange id, and weather this user is a part of the exchange
$result = query("SELECT exchangeName,exchange.exchangeId,permission, IF(userId IS NULL, false, true) as joined FROM exchange LEFT OUTER JOIN (select * from exchangeUser where userId=".$_SESSION['userInfo']['userId'].") as a on exchange.exchangeId=a.exchangeId WHERE active=TRUE");
 
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
