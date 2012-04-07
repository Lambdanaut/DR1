<?php
require_once ("extra/library.php");
require '../lib/session.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];
$pageUsername = strtolower(str_replace(" ",".",$cookiename));
$banName      = bbsclean($_POST['username']);
$banReason    = bbsclean($_POST['reason']);
$banPropose   = bbsclean($_POST['propose']);
$banComment   = bbsclean($_POST['comment']);

$cResult      = mysql_fetch_array(mysql_query("select * from user_data where username = '".$cookiename."' and password = '".$cookiepass."' and moderator > 0"));

//Сheck that the user's cookie name & pass are valid & they're a mod. 
if(mysql_affected_rows() != 0) {

	if (isset($_POST['username']) && $_POST['comment'] != "" ) {
		mysql_query("insert into vote (owner, username, reason, propose, comment) VALUES ('".$cookiename."','".$banName."','".$banReason."','".$banPropose."','".$banComment."')");
	}

	header("Location: /extra/mod");

} else {
	header("Location: /logoutScript.php");
}

mysql_close($con);

?>
