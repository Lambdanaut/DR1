<?php
require_once ("extra/library.php");

$con = con();

$block = bbsclean($_POST['block']);
$blockedUser = bbsclean($_POST['blockedUser']);
$username = bbsclean($_POST['username']);
$password = bbsclean($_POST['password']);

mysql_query("select * from user_data where username = '".$username."' and password = '".$password."'");

if (mysql_affected_rows() != 0) {
	if ($block == "block") {
		mysql_query("insert into blockedUsers (owner, blockedUser) values (\"".$username."\",\"".$blockedUser."\")");
	} else {
		mysql_query("delete from blockedUsers where owner=\"".$username."\" and blockedUser=\"".$blockedUser."\"");
	}
}

?>
