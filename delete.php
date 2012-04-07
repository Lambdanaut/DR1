<?php
require_once ("extra/library.php");

$user     = bbsclean($_POST['user']);
$pass     = bbsclean($_POST['pass']);
$location = bbsclean($_POST['loc']);
$type     = bbsclean($_POST['type']);

$modResult = mysql_fetch_array(mysql_query("select moderator from user_data where username = '".$user."' and password = '".$pass."'"));

mysql_query("select username,password from user_data where username = '".$user."' and password = '".$pass."'") or exit ("DATABASE ERROR! lol. ");

if (mysql_affected_rows() != 0 or $modResult['moderator'] >= 4) {
	//Art
	if ($type == "image" or $type == "literature" or $type == "flash" or $type == "audio" or $type == "culinary" or $type == "craft"){
		//Delete file, display page, & thumbnail
		unlink("arts/".$location);
		unlink("arts/".$location.".php");
		unlink("arts/".$location.".thumb");
		unlink("arts/".$location.".swf");

		//Delete all favorites of this art
		mysql_query("delete from favs where piece = '".$location."'") or exit ("DATABASE ERROR! lol. "); 

		//Delete all comments of this art
		mysql_query("delete from comments where location = '".$location."' and type='art'") or exit ("DATABASE ERROR! lol. "); 

		//Delete from Database
		mysql_query("delete from arts where id = '".$location."'") or exit ("DATABASE ERROR! lol. "); 

		//Delete updates
		mysql_query("delete from updates where location = '".$location."' and type = 'art'") or exit ("DATABASE ERROR! lol. "); 
		
		//Add Moderator Action
		if ($modResult['moderator'] >= 4) {
			mysql_query("insert into moderatorAct (owner,action,date) values ('".$user."','deleted an artwork. ',CURDATE() )") or exit ("Database couldn't add moderator action.");
		}
	}
	//Journals
	elseif ($type == "journal") {
		//Delete journal from Database
		mysql_query("delete from journals where id = '".$location."'") or exit ("DATABASE ERROR! lol. "); 

		//Delete all comments of this journal
		mysql_query("delete from comments where location = '".$location."' and type='journal'") or exit ("DATABASE ERROR! lol. "); 

		//Delete updates
		mysql_query("delete from updates where location = '".$location."' and type = 'journal' or type = 'news'") or exit ("DATABASE ERROR! lol. "); 
		
	}

}

?>
