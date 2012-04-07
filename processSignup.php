<?php
require_once ("extra/library.php");

if(!defined('Configuration')) 
	require 'configuration.php';

require 'extra/lib/crypto.php';

$con = con();

$errorLink = "<a href = 'javascript:history.go(-1)'>Go back and try again, kid. </a>";

$username  = clean(str_replace(".","",$_POST['username']));
$lUsername = strtolower($username);
$password1 = clean($_POST['password1']);
$password2 = clean($_POST['password2']);
$email1    = clean($_POST['email1']);
$email2    = clean($_POST['email2']);
$month     = clean($_POST['month']);
$day       = clean($_POST['day']);
$year      = clean($_POST['year']);
$dob       = $year."-".$month."-".$day;
$gender    = clean($_POST['gender']);
$profile   = "\n";

$inboxGreeting = bbsclean("
[size=16][b][u]Welcome aboard, Beta tester!![/b][/u][/size]

[b]You're one of the first users on Drawrawr.com! Pretty cool, right?[/b]

But it's not all fun and games. You have work to do! 
Your job is to use the site, fool around with stuff, and find all those nasty bugs 
that are crawling around. When you find something that just doesn't seem right 
(broken link, layout glitch, or even just a typo)
report it to either [user=!catherine!] or [user=lambdanaut] immediately!

And yes, we listen to suggestions as well.

[b]Also, a few more things.. [/b]

[dot] 1. Please do NOT distribute the Tester Password to your friends (or anyone else for that matter)
unless Catherine and/or Josh tell you it's alright to do so!
The whole purpose of that password is to limit the number of users to JUST you first 80 or so in order to 
find bugs and fix any errors BEFORE opening to the rest of the public. You can link other people to the site, just don't give them the tester password.

[dot] 2. Everything submitted during this point is possibly [i]temporary[/i]. Any artwork submissions, journals, watches, favorites, inbox messages, even the accounts themselves MAY be WIPED FROM THE DATABASE before we open fully to the public. And sometimes we have to make wipes to fix bugs or add a new feature. 
So it's not a good idea to use Drawrawr.com as a way of storing or
saving any files or information just yet. Because you might eventually lose it all. :c

[dot] 3. If for some reason, neither Catherine nor Josh are online, and something is HORRIBLY HORRIBLY WRONG..
I'm talking CODE RED.. 
Then you can call us. Our cell is 910-471-5067. I can't actually even think of a situation when this would be necessary, but it's better to be safe than sorry, I guess.


[size=16][b][u]That's about it! Have fun, and Enjoy your stay!!![/b][/u][/size]
");

function uniqueName ($str,$column){
    $result = mysql_query("select username from user_data where ".$column." = '".$str."'");
    if(!$result) {
        echo (mysql_error());
	exit;
    }
    if (mysql_affected_rows() == 0) {
        return True;
    }else{
        return False;
    }
}

if (zero($username) or zero($password1) or zero($email1) or $password1 != $password2 or $email1 != $email2 or $year == "na" or $month == "na" or $day == "na" or preg_match("/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/",$email1) != True){
	$error = "You missed something! Make sure the email and password fields are both filled in 0:!<br /><br />".$errorLink;
}else{
	if (uniqueName($username,"username") and strstr($username," php") == False and strstr($username,".php") == False and $username != "images" and $username != "extra" and $username != "css" and $username != "arts" and $username != "cgi-bin" and $username != "stats") { //Create user
		if (clean($_POST['testingPass']) != CONFIG_TESTING_PASSWORD){ die("Incorrect Testing Password!"); }
		
		$userDir = strtolower(str_replace(" ",".",$username));
		//Create user icon based on gender.
		if ($gender == 'n'){copy("images/neutraldeficon.png",'avatars/'.$userDir);}
		elseif ($gender == 'm') {copy("images/maledeficon.png",'avatars/'.$userDir);}
		else{copy("images/femaledeficon.png","avatars/".$userDir);}

		//Password to md5 hash.
		$password = Crypto::encryptPassword($password1);

		//Put user into database.
		mysql_query("insert into user_data (username, password, dob, email, gender, profile) VALUES ('".$username."', '".$password."', '".$dob."', '".$email1."', '".$gender."', '".$profile."')");

		//Send Inbox greeting
		mysql_query("insert into inbox(owner,username,title,text) values ('".$username."','drawrawr','Welcome Beta Tester! ','".$inboxGreeting."')");

		//Set greeting for newbies
		$error = "<img src = '/images/signupwelcome.png' /><br /><br /><h1>Welcome <b>".$username."</b>, to Drawrawr! (:! </h1>".
                "<p>You can now <a href = '/".$userDir."'>edit your personal page</a>, upload art to your gallery, and call other users horrible names, because they're gaywads! </p>";
	}
	else{
		$error = "That username is already taken! <br /><br />".$errorLink;
	}
}

mysql_close($con);

require_once ("prehtmlincludes.php");
?>

<head><title> Signup! </title>

<link rel="stylesheet" type="text/css" href="/css/main.css">

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
		<div id="content" style = "width: 100%;">
			<div class="newArt" style = "text-align: center;">
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
