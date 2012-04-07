<?php
require_once ("extra/library.php");
require 'extra/lib/session.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

$title = bbsclean($_POST['title']);
$entry = bbsclean($_POST['entry']);
$mood  = bbsclean($_POST['journalMood']);
$id    = bbsclean($_POST['id']);

$errorLink  = "<br /><a href = 'javascript:history.go(-1)'>Go back and try again, kid. </a>";
$workedLink = "<script language = 'javascript'>document.location = '/viewJournals.php?owner=".$cookiename."&id=".$id."';</script>";

$cResult    = mysql_query("select username,password from user_data where username = '".$cookiename."' and password = '".$cookiepass."'");

//Сheck that the user's cookie name & pass are valid
if(mysql_affected_rows() != 0) {

//Title
if ($title != "" and strlen($title) < 51){
	mysql_query("update journals set title = '".$title."' where id = '".$id."'");

	$error = $workedLink;
} else {
	$error = "The title was empty! ".$errorLink;
}

//Entry
if (strlen($entry) <= 12000){
	mysql_query("update journals set entry = '".$entry."' where id = '".$id."'");

	$error = $workedLink;
} else {
	$error = "The entry must be under 12000 characters long. Your entry was \"".strlen($entry)."\" characters long. ".$errorLink;
}

//Mood
if ($mood != "n"){
	mysql_query("update journals set mood = '".$mood."' where id = '".$id."'");

	$error = $workedLink;
}


} else {
	echo ("<script language = 'javascript'>window.location='/logoutScript.php';</script>");
}

mysql_close($con);

require_once ("prehtmlincludes.php");

?>

<html>
<head>

<link rel="stylesheet" type="text/css" href="css/onePage.css">

</head>
<body>

<div id="container">
	<div id="headerContainer">
		<div id="header">
			<h1>
			<?php require ("header.php"); ?>
			</h1>
		</div>
                <div id="login">
			<?php echo($loginPrint); ?>
                </div>
	</div>
	<div id="navigation">
		<ul>
			<?php require ("nav.php"); ?>
		</ul>
	</div>
	<div id="content-container">
		<center>
		<div id="content">
			<div id="newArt">
				<?php echo($error); ?>
			</div>
		</div>
	</div>
		<div id="ads">
			<?php require ("ads.php"); ?>
		</div>
		<div id="footer">
			<?php require ("footer.php"); ?>
		</div>
</div>

</body>
</html>
