<?php

require_once("extra/library.php");
if(!class_exists('DrawrawrID')) require '../lib/classes.php';

$username = bbsclean($_GET['user']);

$result = mysql_query("select * from updates where owner = '".$username."' and type = 'art' order by id desc");

$userResult = mysql_fetch_array(mysql_query("select mature from user_data where username = '".$username."'"));

echo("<script language = 'javascript' src = '/extra/captionImages.js'></script>");

while($row = mysql_fetch_array($result)) {
	$artResult = mysql_fetch_array(mysql_query("select * from arts where id = '".$row['location']."'"));
	$userDir = strtolower(str_replace(" ",".",$row['sender']));
	if ($artResult['type'] == 'literature' or $artResult['type'] == 'flash' or $artResult['type'] == 'audio' or $artResult['type'] == 'culinary' or $artResult['type'] == 'craft'){
		$type = "<div class = 'overlay'><img src = '/images/".$artResult['type']."overlay.png'></div>";
	} else {$type = "";}
	if ($artResult['mature'] == 1 and matureMarkCheck($userResult['mature'])) {$mature="<div class = 'overlay'><img src = '/images/maturefilter.png'></div>";} else {$mature="";}
	echo("
	<div class = 'update' style = 'width:175px;height: 100px;height: auto !important;min-height:100px;' id = 'update".$row['id']."'>
		<div style='width:100%;'>
		<div style='position:relative;margin-right: 5px;'>
			<font size = 2 color = 'red' style = 'float:right; margin: 5px; border:solid 1px; padding: 0px 2px 0 2px;cursor:pointer;' onclick='deleteUpdate(\"".$row['id']."\",\"art\")'>x</font>
		</div>
		<div class = 'zitem' style='margin:3px;' title = '".$artResult['title']." by ".$artResult['owner']."'>
			<a href='/art/".DrawrawrID::encode($row['location'])."'><img src='/arts/".$row['location'].".thumb' style='width:135px;height:110px;' class='img'>".$mature.$type."</a>
			<div class = 'caption'>
				<a href = '/art/".DrawrawrID::encode($row['location'])."'>".$artResult['title']."</a>
			</div>
		</div>
		</div>
		<div style='overflow:auto;width:100%;height:45px;font-size:12px;'>
		<a href='/".$userDir."'><img src='/avatars/".strtolower($artResult['owner'])."' style='margin:3px; width:35px;height:35px;float:left;'></a> <b>".$row['sender']."</b><br>
		Date: <b>".$artResult['date']." </div></b>
	</div>
	");
}

?>
