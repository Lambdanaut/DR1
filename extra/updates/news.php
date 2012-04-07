<?php

require_once("extra/library.php");

$username = bbsclean($_GET['user']);

$result = mysql_query("select * from updates where owner = '".$username."' and type = 'news' order by id desc");

while($row = mysql_fetch_array($result)) {
	$journalResult = mysql_fetch_array(mysql_query("select * from journals where id = '".$row['location']."'"));
	$userDir = strtolower(str_replace(" ",".",$row['sender']));
	echo("
	<div class = 'update' style = 'width:260px;height: 100px;height: auto !important;min-height:100px;' id = 'update".$row['id']."'>
		<div style='float:right;margin-right: 5px;'>
			<font size = 2 color = 'red' style = 'float:right; margin: 5px; border:solid 1px; padding: 0px 2px 0 2px;cursor:pointer;' onclick='deleteUpdate(\"".$row['id']."\",\"news\")'>x</font>
		</div>
		<div style='float:left'>
		<a href='/".$userDir."'><img src='/avatars/".$userDir."' style='margin:3px; width:75px;height:75px;float:left;'></a> <br>
		<font style='color:red;font-size:20px;'>!!!</font><b><span style='font-size:12px;'> ".$journalResult['date']." </span></b>
		</div>
		<span style='font-size:12px;'>".$row['sender']."</span> <br>
		<a href='/viewJournals.php?owner=".$row['sender']."&id=".$row['location']."'><b> ".$journalResult['title']." </b></a><br>
	</div>
	");
}

?>
