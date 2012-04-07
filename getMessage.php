<?php
require_once("extra/library.php");

$id      = bbsclean($_POST['id']);
$titleOnly = bbsclean($_POST['titleOnly']);
$title   = bbsclean($_POST['title']);
$posting = bbsclean($_POST['posting']);
$area    = bbsclean($_POST['area']);
$address = bbsclean($_POST['address']);
$user    = bbsclean($_POST['user']);
$pass    = bbsclean($_POST['pass']);

if ($id != "") {
	$result = mysql_fetch_array(mysql_query("select * from inbox where id = '".$id."'"));
}

if ($titleOnly == "true") {
	echo($result['title']);
} elseif ($posting == "true") {
	mysql_query("select id from user_data where username = '".$user."' and password = '".$pass."'");

	if (mysql_affected_rows() != 0){
		mysql_query("insert into inbox(owner,username,title,text) values ('".$address."','".$user."','".$title."','".$area."')");
		mysql_query("insert into inbox(owner,username,title,text,outbox) values ('".$address."','".$user."','".$title."','".$area."','1')");

		echo("Post successful");
	}
} else {
	$userDir  = strtolower(str_replace(" ",".",$result['username']));
	$ownerDir = strtolower(str_replace(" ",".",$result['owner']));
	echo("
	<h5 style='border-bottom:solid 1px;float:left;width:100%;'>
	<span style='float:right;cursor:pointer;font-size:18px;' onclick='writeMessage(\"".$result['username']."\",\"Re: ".$result['title']."\")'>
		<u>Reply</u>
	</span>
	<a href='/".$userDir."'><img src='/".$userDir."/icon' style='float:left;margin-right:10px;width:75px;height:75px;'></a>
	From: <a href = '/".$userDir."'>".$result['username']."</a><br>
	To: <a href = '/".$ownerDir."'>".$result['owner']."</a><br>
	Date: ".$result['date']."<br>
	</h5>
	<span style='font-size:12px;'><p>".tobbs($result['text'])."</p></span>
	");
	mysql_query("update inbox set new = '0' where id = '".$id."'");
}

?>
