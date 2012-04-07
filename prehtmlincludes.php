<?php
require_once 'extra/library.php';
require 'configuration.php';

if(!class_exists('URL')) require 'extra/lib/classes.php';
require 'extra/lib/session.php';
if(!class_exists('DrawrawrDatabase')) require 'extra/lib/database.php';

if(!isset($_SESSION['user']['authenticated']))
	$_SESSION['user']['authenticated'] = false;

//Login Script
$curPage = curPageURL();
$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

$userDir = strtolower(str_replace(" ",".",$cookiename));
$dot = "<img src = '/images/dottico.png'>";
$loginForm = '
	<form method = "post" action = "/login:'.base64_encode($curPage).'" style="margin:0px;">
	<font size = 1>Username: <input type = "text" name = "loginUser" size = 13 STYLE="background: #efe2de;"><br>
	Password: <input type = "password" name = "loginPass" size = 13 STYLE="background: #efe2de;"></font><br>
	<input type = "submit" value = "Login"> <a href = "/signup.php">or Join Now! </a><br>
	<div style="font-size:10px;cursor:pointer;width:130px;font-weight: bold;" id="openPassReset"> <u>Forgot Your Password?</u> </div>
	</form>
	<div id="passReset" class="newart" style="display:none;z-index:1000;position:absolute;border:solid 1px;text-align:center;font-size:12px;">
		<p>Forgot your password? We will email you a new one! (:</p>
		<b>Email:</b><br><span style="font-size:10px;">(The one you registered with)<br>
		<input type = "text" id = "resetEmail" maxlength = "200"> <br>
		<input type = "button" id = "passResetButon" value="Submit">
		<div id="resetResponse"></div>
	</div>
	';

$con = con();

//Check if Banned
$banResult = mysql_fetch_array(mysql_query("select * from bans where username = '".$cookiename."'") );

if (mysql_affected_rows() != 0) {
	header("Location: /urb&.php?date=".$banResult['date']."&reason=".$banResult['comment']);
	exit;
}


$cResult = mysql_fetch_array(mysql_query("select * from user_data where username = '".$cookiename."' and password = '".$cookiepass."' ") );

if (mysql_affected_rows() == 0) {
	//If user is not logged in, do:
	$loginPrint = $loginForm;
} elseif($cResult['loggedin'] == '1') {
	//If user is logged in, do:

	//Check if logging in is allowed right now.
	if (CONFIG_LOGIN_ENABLE or $cResult['moderator'] >= 4) {
		$loggedIn     = True;
		$loggedInName = $cResult['username'];
		$loggedInMod  = $cResult['moderator'];

		//If user is mod, Display link to mod page
		if ($cResult['moderator'] > 0) {$modLink="<span style='float:right;font-size:10px;'> <a href='/extra/mod'>Staff Center</a></span>";} else {$modLink="";}

		//Print out Login whatnots. 
		$loginPrint = $modLink."<b>".$cResult['username']."</b><br><a href ='/".$userDir."'><img src = '/avatars/".strtolower($cookiename)."' style='float:left;width:75px;height:75px;margin-right:3px;' title='".$cResult['username']."'></a>
<div id='updates'><a href = '/extra/updates'><img src = '/images/updatesico.png'></a> <a href = '/extra/updates'>Updates</a></div>
<div id='updateNum' class='newart' style='display:none;z-index:1000;position:absolute;right:65px;border:solid 1px;text-align:center;font-size:12px;'>
<div id='uinbox'></div>
<div id='unews'></div>
<div id='uart'></div>
<div id='ujournal'></div>
<div id='ucomment'></div>
<div id='ufav'></div>
<div id='uwatch'></div>
<div id='uall'></div>
</div>
".$dot." <a href = '/settings.php'>Settings</a><br>
".$dot." <a href ='/submit.php'>SUBMIT!</a><br>
".$dot." <a href = '/logout:".base64_encode($curPage)."'>Logout</a>";
	} else {
		//If logging in is turned off
		header("Location: /siteDown.php");
	}
} else {
	//Log User Out
	header("Location: /logoutScript.php");
}


$loginPrint = "<div class = 'newArt'>".$loginPrint."</div>";

mysql_close($con);

?>


<script language = "javascript" src = "http://code.jquery.com/jquery.min.js"></script>
<script language = "javascript" src = "/extra/jsLib.js"></script>
<script src="/extra/jquery.timeago.js" type="text/javascript"></script>
<script language="javascript">
jQuery(function($) {
	<?php if($_SESSION['user']['authenticated']) echo 'checkUpdates();'; ?>
	$("#openPassReset").click(showReset);
	$("#passResetButon").click(sendLostPassword);
	$("#updates").mouseover(showUpdates);
	$("#updates").mouseout(hideUpdates);

	$("abbr.reltime").each(function(index, value) {
		$(this).timeago();
	});

	/*$('abbr.reltime').change(function() {
		$("abbr.reltime").each(function(index, value) {
			$(this).timeago();
		});
	});*/
});

function checkUpdates(){
	$.ajax({
		type: "GET",
		url: "/processLoginScript.php",
		success: function(reply) {
			$("#updates").html(reply);
		}
	});
	setTimeout("checkUpdates()",15000); //Check for updates every 15 seconds.
}
</script>

<?php require_once("trophies.php"); ?>

<link ref = "shortcut icon" href="/favicon.ico" type="image/x-icon">
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<link href='http://fonts.googleapis.com/css?family=Ubuntu:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>

<?php if(isset($_GET['migrate']) && $_GET['migrate'] == 'success') { ?>
<div style="background-color:#FFF; padding:2px;"><center><h2>Your password has been changed to the new system!</h2>
<p>Now you can enjoy a twice as secure Drawrawr! Pretty neat, huh?</p></center></div>
<?php } ?>
