<?php
require_once("extra/library.php");
require '../lib/session.php';

$username = $_SESSION['user']['data']['username'];
$password = $_SESSION['user']['data']['password'];

$result = mysql_query("select * from user_data where username = '".$username."' and password = '".$password."'");

if (mysql_affected_rows() != 0) {
	mysql_query("delete from updates where owner = '".$username."'");
}

?>
