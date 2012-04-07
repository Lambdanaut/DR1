<?php
require_once("extra/library.php");

$con = con();

$retrieve = bbsclean($_POST['retrieve']);
$username = bbsclean($_POST['username']);
$password = bbsclean($_POST['password']);
$toadd    = bbsclean($_POST['toadd']);

if ($retrieve == "friends") {
	$friends = mysql_query("select * from friends where friender = '".$username."' order by friend asc");
						
	while($row = mysql_fetch_array($friends)) {
		$frienddir = strtolower(str_replace(" ",".",$row['friend']));
		echo ("<span style='margin:5px;'><font color='red' style='padding: 0px 3px 1px 3px;border:solid 1px;cursor:pointer;' onclick='deleteFriend(\"".$row['id']."\")'>x</font> <a href='/".$frienddir."'><img src = '/avatars/".$frienddir."' style='width:75px;height:75px;' align='middle'></a> <b>".$row['friend']."</b></span><br>");
	}
}

elseif ($retrieve == "addfriend") {
	mysql_query("select * from user_data where username = '".$username."' and password = '".$password."'");

	if (mysql_affected_rows() != 0 and strtolower($username) != strtolower($toadd)) {
		mysql_query("select * from friends where friender = '".$username."' and friend = '".$toadd."'");
		if (mysql_affected_rows() == 0) {
			mysql_query("select id from user_data where user_name = '".$toadd."'");
			if (mysql_affected_rows() != 0) {
				mysql_query("insert into friends (friender, friend) values ('".$username."', '".$toadd."') ") or die("MYSQL ERROR LOL");
			}
		}
	}
}

elseif ($retrieve == "deletefriend") {
	mysql_query("select * from user_data where username = '".$username."' and password = '".$password."'");

	if (mysql_affected_rows() != 0) {
		if (mysql_affected_rows() != 0) {
			mysql_query("DELETE FROM friends WHERE id = ".$toadd);
		}
	}

}

?>
