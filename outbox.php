<?php
require_once("extra/library.php");

$con = con();

$result = mysql_query("select * from inbox where username = '".$cookiename."' and outbox = '1' order by id desc");

if (mysql_affected_rows() == 0) {
	echo("<center> No Messages </center>");
}

mysql_query("select id from user_data where username = '".$cookiename."' and password = '".$cookiepass."'");
if (mysql_affected_rows() != 0) {

while($row = mysql_fetch_array($result)) {
	if ($row['new'] == 1) {
		$envelope = " <img src='/images/fullupdatesico.png'> ";
	} else {$envelope = " <img src='/images/updatesico.png' >"; }
	echo("
	<div class = 'update' id = 'update".$row['id']."' style='border:0px;width: 95%;'>
		<span style='margin-right: 5px;'>
			<font size = 2 color = 'red' style = 'margin: 5px; border:solid 1px; padding: 0px 2px 0 2px;cursor:pointer;' onclick='deleteUpdate(\"".$row['id']."\",\"inbox\")'>x</font>
		</span>
		<span style='cursor:pointer;' onclick='getMessage(\"".$row['id']."\")'>
		".$envelope." 
		<font size='3'><b>
		".$row['title']."
		</b></font>
		</span>
	</div>
	");
}

}

?>
