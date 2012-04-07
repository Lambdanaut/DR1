<?php
require_once("extra/library.php");

$con = con();

$users = 21;

$result = mysql_query("select username from user_data order by id desc limit 0,".strval($users));

?><b><h3><img src = '/images/clubsico.png' style="margin: 0px; border: none;"> NEW USERS </h3></b><div id="artContainer" style="height: 79px;"><?
$z = 0;
while($row = mysql_fetch_array($result)) {
	$userDir = strtolower(str_replace(" ",".",$row['username']));
	echo "<a href = '/".$userDir."'><img src = '/avatars/".strtolower($userDir)."' width = '35' height = '35' style = 'margin: 2px; border: none;' title='".$row['username']."'></a>";
	$z++;
}
?>
</div>

