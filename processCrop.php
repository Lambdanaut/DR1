<?php
require_once("extra/library.php");
require_once("prehtmlincludes.php");

$width  = 135;
$height = 110;

require 'extra/lib/session.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

$loc = bbsclean($_POST['location']);
$x   = bbsclean($_POST['x']);
$y   = bbsclean($_POST['y']);
$w   = bbsclean($_POST['w']);
$h   = bbsclean($_POST['h']);

$con = con();
$result = mysql_fetch_array(mysql_query("select * from arts where id = '".$loc."'"));

$userResult = mysql_fetch_array(mysql_query("select moderator from user_data where username = '".$cookiename."' and password = '".$cookiepass."' "));

if ( (mysql_affected_rows() != 0 and $cookiename == $result['owner']) or $userResult['moderator'] >= 4 ) {
	$thumb = new SimpleImage();
	$thumb->load("arts/".$loc);

	//Preserve PNG alpha
	$croppedImage = ImageCreateTrueColor($width,$height);
	imagealphablending($croppedImage, false);
	$color = imagecolortransparent($croppedImage, imagecolorallocatealpha($croppedImage, 0, 0, 0, 127));
	imagefill($croppedImage, 0, 0, $color);
	imagesavealpha($croppedImage, true);

	//Crop image
	imagecopyresampled ($croppedImage, $thumb->image, 0, 0, $x, $y, $width, $height, $w, $h);
	$thumb->image = $croppedImage;
	
	$thumb->save("arts/".$loc.".thumb");

	echo("Crop successful! ");
} else {echo("You must be logged in. ");}

mysql_close($con);

?>
