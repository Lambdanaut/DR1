<?php
require_once ("extra/library.php");
require 'extra/lib/session.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];
$userDir    = strtolower(str_replace(" ",".",$cookiename));

$title       = bbsclean($_POST['title']);
$description = bbsclean($_POST['description']);
$writing     = bbsclean($_POST['writing']);
$location    = bbsclean($_POST['location']);

$nude = bbsclean($_POST['nude']);if ($nude != '1') {$nude = '0';}
$drug = bbsclean($_POST['drug']);if ($drug != '1') {$drug = '0';}
$gore = bbsclean($_POST['gore']);if ($gore != '1') {$gore = '0';}
$sex  = bbsclean($_POST['sex']); if ($sex  != '1') {$sex  = '0';}


$errorLink  = "<br><a href = 'javascript:history.go(-1)'>Go back and try again, kid. </a>";
$workedLink = "<script language = 'javascript'>document.location = '/arts/".$location.".php';</script>";

$artResult  = mysql_query("select id from arts where owner = '".$cookiename."' and id='".$location."'");
$modResult  = mysql_fetch_array(mysql_query("select moderator from user_data where username = '".$cookiename."' and password = '".$cookiepass."'"));
$cResult    = mysql_query("select username,password from user_data where username = '".$cookiename."' and password = '".$cookiepass."'");

//Сheck that the user's cookie name & pass are valid
if( (mysql_affected_rows() != 0 and mysql_num_rows($artResult) != 0) or $modResult['moderator'] >= 4) {

//Title
if ($title != "" and strlen($title) < 51){
	mysql_query("update arts set title = '".$title."' where id = '".$location."'");

	$error = $workedLink;
} else {
	$error = "The image title was empty! ".$errorLink;
}

//Description
if (strlen($description) < 5000){
	mysql_query("update arts set description = '".$description."' where id = '".$location."'");

	$error = $workedLink;
} else {
	$error = "The description must be under 5000 characters long. Your description was \"".strlen($description)."\" characters long. ".$errorLink;
}

//Writing
if (strlen($description) < 20001){
	mysql_query("update arts set writing = '".$writing."' where id = '".$location."'");

	$error = $workedLink;
} else {
	$error = "The writing must be under 20000 characters long. Your writing was \"".strlen($writing)."\" characters long. ".$errorLink;
}

//Literature Thumbnail
if (!empty($_FILES["thumbnail"]) && $_FILES['thumbnail']['error'] == 0) {
	if (($_FILES["thumbnail"]["type"] == "image/jpeg" or $_FILES["thumbnail"]["type"] == "image/gif" or $_FILES["thumbnail"]["type"] == "image/png" or $_FILES["thumbnail"]['type'] == "image/pjpeg" or $_FILES["thumbnail"]['type'] == "image/x-png") && ($_FILES["thumbnail"]["size"] / 1024 < 1000)) {
		//Makes thumbnail and saves it
		$thumb = new SimpleImage();
		$thumb->load($_FILES["thumbnail"]["tmp_name"]);
		$thumb->save("arts/".$location);

		$thumb = new SimpleImage();
		$thumb->load($_FILES["thumbnail"]["tmp_name"]);
		$thumb->resize(115,90);
		$thumb->save("arts/".$location.".thumb");

		$error = $workedLink;
	} else {
	$error = "Error: Only .jpg, .gif, and .png icons under 1000Kb are accepted as thumbnails. <br>".$errorLink;
	}
}

//Flash Thumbnail
if (!empty($_FILES["flashthumb"]) && $_FILES['flashthumb']['error'] == 0) {
	if (($_FILES["flashthumb"]["type"] == "image/jpeg" or $_FILES["flashthumb"]["type"] == "image/gif" or $_FILES["flashthumb"]["type"] == "image/png" or $_FILES["flashthumb"]['type'] == "image/pjpeg" or $_FILES["flashthumb"]['type'] == "image/x-png") && ($_FILES["flashthumb"]["size"] / 1024 < 1000)) {
		//Makes thumbnail and saves it
		$thumb = new SimpleImage();
		$thumb->load($_FILES["flashthumb"]["tmp_name"]);
		$thumb->resize(115,90);
		$thumb->save("arts/".$location.".thumb");

		$error = $workedLink;
	} else {
	$error = "Error: Only .jpg, .gif, and .png icons under 1000Kb are accepted as thumbnails. <br>".$errorLink;
	}
}

//Image
if (!empty($_FILES["artwork"]) && $_FILES['artwork']['error'] == 0) {
	if ( (($_FILES["artwork"]["type"] == "image/jpeg" or $_FILES["artwork"]["type"] == "image/gif" or $_FILES["artwork"]["type"] == "image/png" or $_FILES["artwork"]['type'] == "image/pjpeg" or $_FILES["artwork"]['type'] == "image/x-png") && ($_FILES["artwork"]["size"] / 1024 < 2000)) or ( ($_FILES['artwork']['type'] == "application/x-shockwave-flash" or $_FILES['artwork']['type'] == "audio/mp3" or $_FILES['artwork']['type'] == "audio/mpeg" or $_FILES['artwork']['type'] == "audio/x-wav" or $_FILES['artwork']['type'] == "audio/ogg") && $_FILES["artwork"]["size"] / 1024 / 1024 < 20) ){
		//Makes artwork and saves it
		if (move_uploaded_file($_FILES['artwork']['tmp_name'],"arts/".$location)) {
			$error = $workedLink;
		} else {
			header("location: ".$location);
			$error = $error."Error: A problem occurred during image upload!<br>";
		}
		list($width, $height, $type, $attr) = getimagesize("arts/".$location);

		mysql_query("update arts set width = '".$width."', height = '".$height."' where id = '".$location."'");

	} else {
	$error = "Error: Artwork is either not the correct filesize, or it's too large! Try again! <br>".$errorLink;
	}
}

//Mature
mysql_query("update arts set nude = '".$nude."', drug = '".$drug."', gore = '".$gore."', sex = '".$sex."' where id = '".$location."'");

if ($nude == '1' or $drug == '1' or $gore == '1' or $sex == '1') {
	mysql_query("update arts set mature = '1' where id = '".$location."'");
}
if ($nude == '0' and $drug == '0' and $gore == '0' and $sex == '0') {
	mysql_query("update arts set mature = '0' where id = '".$location."'");
}

} else {
	header("Location: /logoutScript.php");
}

mysql_close($con);

require_once ("prehtmlincludes.php");

?>

<html>
<head>

<link rel="stylesheet" type="text/css" href="css/main.css">

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
			<div class="newArt">
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
