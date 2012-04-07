<?php

require_once("extra/library.php");
if(!class_exists('DrawrawrID')) require '../lib/classes.php';

$username = bbsclean($_GET['user']);

$result = mysql_query("select * from updates where owner = '".$username."' and type = 'fav' order by id desc");

while($row = mysql_fetch_array($result)) {
	$artResult = mysql_fetch_array(mysql_query("select * from arts where id = '".$row['location']."'"));
	$userDir = strtolower(str_replace(" ",".",$row['sender']));
	echo("
	<div class = 'update' id = 'update".$row['id']."'>
		<div style='float:right;margin-right: 5px;'>
			<font size = 2 color = 'red' style = 'float:right; margin: 5px; border:solid 1px; padding: 0px 2px 0 2px;cursor:pointer;' onclick='deleteUpdate(\"".$row['id']."\",\"fav\")'>x</font>
		</div>
		<a href='/".$userDir."'><img src='/avatars/".$userDir."' style='width:35px;height:35px;' align='absmiddle'></a> <img src='/images/favico.png'>  <a href='/".$userDir."'><b>".$row['sender']."</b></a> has added <a href = '/art/".DrawrawrID::encode($artResult['id'])."'>".$artResult['title']."</a> to their Favorites!
	</div>
	");
}


?>
