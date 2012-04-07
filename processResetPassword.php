<?php
require_once ("extra/library.php");
if(!class_exists('Crypto')) require 'extra/lib/crypto.php';

$pass1   = $_POST['pass1'];
$pass2   = $_POST['pass2'];
$id      = $_POST['id'];
$oldPass = $_POST['p'];

mysql_query("select id from user_data where id = '".$id."' and password = '".$oldPass."'") or die(mysql_error());
if (mysql_affected_rows() != 0 and $pass1 == $pass2 and strlen($pass1) > 1) {
	mysql_query("update user_data set password = '".Crypto::encryptPassword($pass1)."' where id = '".$id."'") or die(mysql_error());
}
header("Location: index.php");

?>

