<?php
require_once ("extra/library.php");

$neededVotes = ceil(mysql_num_rows(mysql_query("select id from user_data where moderator >= '1'") ) / 3) ;
require '../lib/session.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];
$voteID     = bbsclean($_POST['id']);
$voteVal    = bbsclean($_POST['val']);

if ($voteVal == "up") {$voteVal = 2;} else {$voteVal = 1;}

mysql_query("select * from user_data where username = '".$cookiename."' and password = '".$cookiepass."' and moderator > 0");

if(mysql_affected_rows() != 0) {
	//Check that a mod isn't voting twice
	if (0 == mysql_num_rows(mysql_query("select id from votes where owner = '".$cookiename."' and location = '".$voteID."'") ) ) {

		mysql_query("insert into votes (owner, location, foragainst) VALUES ('".$cookiename."','".$voteID."','".$voteVal."')");

		$reason       = mysql_fetch_array(mysql_query("select * from vote where id = '".$voteID."'"));	
		$votesfor     = mysql_query("select * from votes where location = '".$voteID."' and foragainst = '2'");
		$votesagainst = 0;//mysql_query("select * from votes where location = '".$voteID."' and foragainst = '1'");
		$voteScore    = mysql_num_rows($votesfor) - mysql_num_rows($votesagainst);

		//Calculate time to ban
		switch ($reason['propose']) {
			case "3":
				$date = time() + (86400 * 3);
				break;
			case "2":
				$date = time() + (604800 * 2);
				break;
			case "5":
				$date = time() + (604800 * 5);
				break;
			case "p":
				$date = time() + (604800 * 100000);
				break;
		}
		//Convert Unix Timestamp to MYSQL Date format
		$date = date("Y-m-d",$date);

		if ($voteScore >= $neededVotes) {
			//Ban User
			mysql_query("insert into bans (username, reason, comment, date) VALUES ('".$reason['username']."','".$reason['reason']."','".$reason['comment']."', '".$date."')") or die (mysql_error());

			//Delete proposition and votes from database
			mysql_query("delete from vote where id = '".$voteID."'");
			mysql_query("delete from votes where location = '".$voteID."'");

			echo("The user has recieved \"".$neededVotes."\" and has been banned! ");
		} else {
			//Echo back amount of votes needed to ban user
			echo("Current Votes/Needed Votes to Ban  ::  ".$voteScore."/".$neededVotes);
		}
	} else {echo("The system has detected a double vote and has blocked your vote. ");}
}

mysql_close($con);

?>
