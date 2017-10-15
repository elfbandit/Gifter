<?php
include 'dbConnect.php';
session_start();

$jTableResult = array();

if(mysqli_errno($con)){
	$jTableResult['Result'] = "FAIL";
}else{
	$jTableResult['Result'] = "OK";
}


//Get records from database
if(isset($_GET['table']) AND $_GET['table'] == "shop"){ //requesting the shopping list
	$query = "SELECT giftId,name,link,description,value,CONCAT( firstName,  ' ', lastName ) AS  'recipient' FROM gifts join user on gifts.userId=user.userId WHERE gifterId='".$_SESSION['userInfo']['userId']."' and gifted=FALSE";
}else if(isset($_GET['table']) AND $_GET['table'] == "thank"){ //requesting the thank-you list
	$query = "SELECT giftId,name,link,description,email,CONCAT( firstName,  ' ', lastName ) AS  'gifter',email FROM gifts join user on gifts.gifterId=user.userId WHERE gifts.userId='".$_SESSION['userInfo']['userId']."' and gifted=TRUE and thanked=FALSE";
} else if($_SESSION['context']['userId'] == $_SESSION['userInfo']['userId']){ //user viewing his own table; don't show the gifter
	$query = "SELECT giftId,name,link,description,value FROM gifts WHERE userId='".$_SESSION['context']['userId']."' and gifted=FALSE";
} else{ //show the main table with the gifter column

	$query = "SELECT giftId,name,link,description,value,gifterId FROM gifts LEFT OUTER JOIN user ON gifts.gifterId = user.userId WHERE gifts.userId='".$_SESSION['context']['userId']."' and gifted=FALSE";
}
$result = query($query);
if(mysqli_error()){
	echo mysqli_error();
}

//Add all records to an array
$rows = array();
if(mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_array($result))
	{
    	$rows[] = $row;
	}
}
 
//Return result to jTable

$jTableResult['Records'] = $rows;
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
