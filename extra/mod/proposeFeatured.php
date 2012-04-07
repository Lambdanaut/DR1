<?php
require_once ("extra/library.php");
require '../lib/session.php';


$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

$message  = bbsclean($_POST['message']);
$location = bbsclean($_POST['location']);

$cResult = mysql_fetch_array(mysql_query("select * from user_data where username = '".$cookiename."' and password = '".$cookiepass."' and moderator > 0"));

if(mysql_affected_rows() != 0) {
  mysql_query("insert into featuredProp (owner,location,reason) values ('".$cookiename."','".$location."','".$message."')");
}

header("Location: /extra/mod");

?>