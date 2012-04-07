<?php
require_once("extra/library.php");

$username = bbsclean($_GET['user']);

$result = mysql_query("select * from updates where owner = '".$username."' and type = 'watch' order by id desc");

while($row = mysql_fetch_array($result)) {
	$userDir = strtolower(str_replace(" ",".",$row['sender']));
	echo("
	<div class = 'update' id = 'update".$row['id']."'>
		<div style='float:right;margin-right: 5px;'>
			<font size = 2 color = 'red' style = 'float:right; margin: 5px; border:solid 1px; padding: 0px 2px 0 2px;cursor:pointer;' onclick='deleteUpdate(\"".$row['id']."\",\"watch\")'>x</font>
		</div>
		<a href='/".$userDir."'><img src='/avatars/".strtolower($userDir)."' style='width:35px;height:35px;' align='absmiddle'></a> <img src='/images/searchico.png'>  <a href='/".$userDir."'><b>".$row['sender']."</b></a> gave you a Watch!
	</div>
	");
}

?>
