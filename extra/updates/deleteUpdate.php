<?php
require_once("extra/library.php");

$username = bbsclean($_POST['user']);
$password = bbsclean($_POST['pass']);
$id       = bbsclean($_POST['id']);
$type     = bbsclean($_POST['type']);

$result = mysql_query("select * from user_data where username = '".$username."' and password = '".$password."'");

if (mysql_affected_rows() != 0) {
	if ($type == "inbox") {
		mysql_query("delete from inbox where id = '".$id."'");		
	} else {
		mysql_query("delete from updates where id = '".$id."'");
	}
}

?>
