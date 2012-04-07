<?php
require_once("extra/library.php");
require '../lib/session.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

$result = mysql_query("select * from user_data where username = '".$username."' and password = '".$password."'");

if (mysql_affected_rows() != 0) {
	mysql_query("delete from inbox where owner = '".$username."' and outbox = '0'");
	mysql_query("delete from inbox where username = '".$username."' and outbox = '1'");
}

?>
