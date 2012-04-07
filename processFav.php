<?php
require_once ("extra/library.php");
require 'extra/lib/session.php';

$action   = bbsclean($_GET['action']);
$location = bbsclean($_GET['location']);
$owner    = bbsclean($_GET['owner']);

if(isset($_SESSION['user']['authenticated']) && $_SESSION['user']['authenticated']) {
	$faver = $_SESSION['user']['data']['username'];

	//favs
	$favResult = mysql_query("select * from favs where faver = '".$faver."' and piece = '".$location."'") or exit ("DATABASE ERROR! lol. ");
	if ($action == "Favorite" and mysql_affected_rows() == 0){
		mysql_query("insert into favs(faver,piece) VALUES ('".$faver."','".$location."')") or exit ("DATABASE ERROR! lol. ");

		if (mysql_num_rows(mysql_query("select id from updates where owner = '".$owner."' and sender = '".$faver."' and location = '".$location."' and type = 'fav'")) == 0){ 
			mysql_query("insert into updates(owner,sender,type,location) values ('".$owner."','".$faver."','fav','".$location."')");
		}
	} 

	if ($action == "De-Fav"){
		mysql_query("delete from favs where piece = '".$location."' and faver = '".$faver."'") or exit ("DATABASE ERROR! lol. ");
	}
	//watches
	$favResult = mysql_query("select * from watching where watcher = '".$faver."' and watching = '".$owner."'") or exit ("DATABASE ERROR! lol. ");
	if ($action == "Watch" and mysql_affected_rows() == 0){
		mysql_query("insert into watching(watcher,watching) VALUES ('".$faver."','".$owner."')") or exit ("DATABASE ERROR! lol. ");

		if (mysql_num_rows(mysql_query("select id from updates where owner = '".$owner."' and sender = '".$faver."' and type = 'watch'")) == 0){ 
			mysql_query("insert into updates(owner,type,sender) values ('".$owner."','watch','".$faver."')");
		}
	}
	if ($action == "De-Watch"){
		mysql_query("delete from watching where watching = '".$owner."' and watcher = '".$faver."'") or exit ("DATABASE ERROR! lol. ");
	}

}

?>
