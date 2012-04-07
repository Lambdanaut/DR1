<?php
require("extra/library.php");

$distance = 3;

$owner = bbsclean($_GET['user']);

$ownerLoc = mysql_fetch_array(mysql_query("select latitude,longitude from user_data where username='".$owner."'"));

$nearbyUsers = mysql_query("select username from user_data where SQRT( POW(latitude - '".$ownerLoc['latitude']."',2) + POW(longitude - '".$ownerLoc['longitude']."',2) ) < '".$distance."'");

$i = 1;
while ($user = mysql_fetch_array($nearbyUsers)){
	$userDir = strtolower(str_replace(" ",".",$user['username']));
	echo "<a href = '".$userDir."'><img src = '/".$userDir."/icon' style='width:75px;height:75px;'></a> ";
	$i++;
}

?>
