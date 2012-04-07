<?php
require_once ("/var/www/html/extra/library.php");

$bans = mysql_query("select * from bans");

while ($ban = mysql_fetch_array($bans) ) {
	//If the ban's date has passed..
	if (strtotime($ban['date']) <= time() ){
		//Unban the user
		mysql_query("delete from bans where username = '".$ban['username']."'");
	}
}

mysql_close($con);

?>
