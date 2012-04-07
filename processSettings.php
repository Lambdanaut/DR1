<?php
require_once ("prehtmlincludes.php");
require_once ("extra/library.php");
require 'extra/lib/session.php';
if(!class_exists('Crypto')) require 'extra/lib/crypto.php';

$con = con();

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

$pageUsername = strtolower($cookiename);
$curPass      = bbsclean($_POST['currentPass']);
$password1    = bbsclean($_POST['newPass1']);
$password2    = bbsclean($_POST['newPass2']);
$gender       = bbsclean($_POST['gender']);
$month        = clean($_POST['month']);
$day          = clean($_POST['day']);
$year         = clean($_POST['year']);
$dob          = $year."-".$month."-".$day;
$hideDOB      = bbsclean($_POST['hideDOB']);
$nude         = bbsclean($_POST['nude']);
$drug         = bbsclean($_POST['drug']);
$gore         = bbsclean($_POST['gore']);
$sex          = bbsclean($_POST['sex']);
$mature       = bbsclean($_POST['mature']);
$bold         = bbsclean($_POST['bold']);
$italic       = bbsclean($_POST['italic']);
$underlined   = bbsclean($_POST['underlined']);
$aim          = bbsclean($_POST['aim']);
$msn          = bbsclean($_POST['msn']);
$yahoo        = bbsclean($_POST['yahoo']);
$skype        = bbsclean($_POST['skype']);
$email        = bbsclean($_POST['email']);
$phone        = bbsclean($_POST['phone']);
$website      = bbsclean($_POST['website']);
$steam        = bbsclean($_POST['steam']);
$profile      = bbsclean($_POST['profile']);
$hideContact  = bbsclean($_POST['hideContact']);
$latitude     = bbsclean($_POST['latitude']);
$longitude    = bbsclean($_POST['longitude']);

$cResult      = mysql_fetch_array(mysql_query("select * from user_data where username = '".$cookiename."' and password = '".$cookiepass."'"));

if ((!isset($hideDOB)) or $hideDOB == "") {$hideDOB = 0;}
if ((!isset($hideContact)) or $hideContact == "") {$hideContact = 0;}
if ($nude == "") {$nude = '0';}
if ($drug == "") {$drug = '0';}
if ($gore == "") {$gore = '0';}
if ($sex  == "") {$sex  = '0';}
if ($bold == "") {$bold = '0';}
if ($italic == "") {$italic = '0';}
if ($underlined == "") {$underlined = '0';}

//Сheck that the user's cookie name & pass are valid
if(mysql_affected_rows() != 0 and $loggedIn) {

//Icon Upload
//Checks if we have an icon to upload
if (!empty($_FILES["uploadedIcon"]) && $_FILES['uploadedIcon']['error'] == 0) {
	//Check if the file is an image and it's size is less than 250Kb
	$filename = basename($_FILES['uploadedIcon']['name']);
	$ext = substr($filename, strrpos($filename, '.') + 1);
	if (($_FILES["uploadedIcon"]["type"] == "image/jpeg" or $_FILES["uploadedIcon"]["type"] == "image/gif" or $_FILES["uploadedIcon"]["type"] == "image/png" or $_FILES['uploadedIcon']['type'] == "image/pjpeg" or $_FILES['uploadedIcon']['type'] == "image/x-png") && ($_FILES["uploadedIcon"]["size"] / 1024 < 250)) {
		//Attempt to move the uploaded file to it's new place

		if (move_uploaded_file($_FILES['uploadedIcon']['tmp_name'], 'avatars/'.strtolower($pageUsername))) {
			if ($ext != "gif") {
				//Attempt to resize image
				$thumb = new SimpleImage();
				$thumb->load("/avatars/".strtolower($pageUsername));
				$thumb->resize(75,75);
				$thumb->save("/avatars/".strtolower($pageUsername));
			}
			$error = $error."Icon successfully changed! <br>";
		} else {
			$error = $error."Error: A problem occurred during icon upload!<br>";
		}
	} else {
		$error = $error."Error: Only .jpg, .gif, and .png icons under 250Kb are accepted for upload<br>";
	}
}

//Sex change (lol)
if ($gender != $cResult['gender'] and ($gender == "m" or $gender == "f" or $gender == "n") ){
	mysql_query("update user_data set gender = '".$gender."' where username = '".$cookiename."'");
	$error = $error."Gender successfully changed! <br>";
}

//AIM change
if (isset($aim) and $aim != $cResult['aim']){
	mysql_query("update user_data set aim = '".$aim."' where username = '".$cookiename."'");
	$error = $error."AIM successfully changed! <br>";
}

//MSN change
if (isset($msn) and $msn != $cResult['msn']){
	mysql_query("update user_data set msn = '".$msn."' where username = '".$cookiename."'");
	$error = $error."MSN successfully changed! <br>";
}

//Yahoo change
if (isset($yahoo) and $yahoo != $cResult['yahoo']){
	mysql_query("update user_data set yahoo = '".$yahoo."' where username = '".$cookiename."'");
	$error = $error."Yahoo successfully changed! <br>";
}

//Skype change
if (isset($skype) and $skype != $cResult['skype']){
	mysql_query("update user_data set skype = '".$skype."' where username = '".$cookiename."'");
	$error = $error."Skype successfully changed! <br>";
}

//Email change
if (isset($email) and $email != $cResult['email']){
	mysql_query("update user_data set email = '".$email."' where username = '".$cookiename."'");
	$error = $error."Email successfully changed! <br>";
}

//Phone change
if (isset($phone) and $phone != $cResult['phone']){
	mysql_query("update user_data set phone = '".$phone."' where username = '".$cookiename."'");
	$error = $error."Phone number successfully changed! <br>";
}

//Steam change
if (isset($steam) and $steam != $cResult['steam']){
	mysql_query("update user_data set steam = '".$steam."' where username = '".$cookiename."'");
	$error = $error."Steam ID successfully changed! <br>";
}

//Website change
if (isset($website) and $website != $cResult['website']){
	mysql_query("update user_data set website = '".$website."' where username = '".$cookiename."'");
	$error = $error."Website successfully changed! <br>";
}

//Contacts hide/show
if ($hideContact != $cResult['hidecontacts']){
	mysql_query("update user_data set hidecontacts = '".$hideContact."' where username = '".$cookiename."'");
	$error = $error."Contacts visibility toggled! <br>";
}

//DOB change
if ($year != "na" and $month != "na" and $day != "na"){
	mysql_query("update user_data set dob = '".$dob."' where username = '".$cookiename."'");
	$error = $error."Date of birth successfully changed! <br>";
}

//DOB hide/show
if ($hideDOB != $cResult['hidedob']){
	mysql_query("update user_data set hidedob = '".$hideDOB."' where username = '".$cookiename."'");
	$error = $error."Date of birth visibility toggled! <br>";
}

//Nudity hide/show
if ($nude != $cResult['nude']){
	mysql_query("update user_data set nude = '".$nude."' where username = '".$cookiename."'");
}

//Drugs hide/show
if ($drug != $cResult['drug']){
	mysql_query("update user_data set drug = '".$drug."' where username = '".$cookiename."'");
}

//Gore hide/show
if ($gore != $cResult['gore']){
	mysql_query("update user_data set gore = '".$gore."' where username = '".$cookiename."'");
}

//Sex hide/show
if ($sex != $cResult['sex']){
	mysql_query("update user_data set sex = '".$sex."' where username = '".$cookiename."'");
}

//Mature Filter
if ($mature != $cResult['mature']){
	mysql_query("update user_data set mature = '".$mature."' where username = '".$cookiename."'");
	$error = $error."Mature Filter Changed! <br>";
}

//Bold
if ($bold != $cResult['bold']){
	mysql_query("update user_data set bold = '".$bold."' where username = '".$cookiename."'");
	$error = $error."Bold Font Changed! <br>";
}

//Italic
if ($italic != $cResult['italic']){
	mysql_query("update user_data set italic = '".$italic."' where username = '".$cookiename."'");
	$error = $error."Italic Font Changed! <br>";
}

//Underlined
if ($underlined != $cResult['underlined']){
	mysql_query("update user_data set underlined = '".$underlined."' where username = '".$cookiename."'");
	$error = $error."Underlined Font Changed! <br>";
}

//Location change (Latitude Longitude)
if ($latitude != $cResult['latitude'] or $longitude != $cResult['longitude']){
	if ($latitude  == "" or $latitude  == 0) {$latitude  = "null";}
	if ($longitude == "" or $longitude == 0) {$longitude = "null";}
	mysql_query("update user_data set latitude = ".$latitude.", longitude = ".$longitude." where username = '".$cookiename."'");
	$error = $error."Location successfully updated! Your nearby users will be calculated based on this location. <br>";
}

//Profile change
if (isset($profile) and strlen($profile) <= 20000){
	mysql_query("update user_data set profile = '".$profile."' where username = '".$cookiename."'");
	$error = $error."Profile successfully updated! <br>";
}else{
	$error = $error."Profiles can have at most 20000 characters. Your profile had ".strlen($profile)." <br>";
}

//Password change
if ($password1 != "" and $password2 != "" and $curPass != ""){
	if (Crypto::encryptPassword($curPass) == $cResult['password'] and $password1 == $password2){
		//Update database with new password
		mysql_query("update user_data set password = '".Crypto::encryptPassword($password1)."' where username = '".$cookiename."'");
		//Delete old cookie and set new cookies.
		setcookie("user", "", time()-3600, "/");
		setcookie("pass", "", time()-3600, "/");
		setcookie("user", $cookiename);
		setcookie("pass", Crypto::encryptPassword($password1));
		$error = $error."Password successfully changed! <br>";
	}else{
		$error = $error."The passwords didn't match! Try again. <br>";
	}
} 

} else {
  echo ("<script language = 'javascript'>window.location='/logoutScript.php';</script>");
}


mysql_close($con);

$error = $error."<a href = '/".$pageUsername."'>Return to userpage</a>";
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
				<?php require_once("header.php"); ?>
			</h1>
		</div>
                <div id="login">
			<?php echo($loginPrint); ?>
                </div>
	</div>
	<div id="navigation">
		<ul>
			<?php require_once("nav.php"); ?>
		</ul>
	</div>
	<div id="content-container">
		<center>
		<div id = "content" style = "width: 100%;">
			<div class = "newArt" style = "text-align: center;">
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
