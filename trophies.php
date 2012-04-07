<?php
if(!defined('Configuration')) require 'configuration.php';

if ($loggedIn) {

$con = con();

$date = date("Y-m-d");
$artResult = mysql_query("select id from arts where owner='".$cookiename."' and DAY(timestamp)='".date('d')."'") or die("LOL MYSQL TROPHY ERRLRRORORORO");
$trophyResult = mysql_query("select * from trophies where owner='".$cookiename."'") or die("LOL MYSQL TROPHY ERRLRRORORORO");
$commentResult = mysql_query("select id from comments where username='".$cookiename."'") or die("LOL MYSQL TROPHY ERRLRRORORORO");
$watchingResult = mysql_query("select id from watching where watching='".$cookiename."'") or die("LOL MYSQL TROPHY ERRLRRORORORO");
$watcherResult = mysql_query("select id from watching where watcher='".$cookiename."'") or die("LOL MYSQL TROPHY ERRLRRORORORO");
$friendResult = mysql_query("select id from friends where friender='".$cookiename."'") or die("LOL MYSQL TROPHY ERRLRRORORORO");
$friendedResult = mysql_query("select id from friends where friend='".$cookiename."'") or die("LOL MYSQL TROPHY ERRLRRORORORO");

$watcherCount = mysql_num_rows($watcherResult);
$watchingCount = mysql_num_rows($watchingResult);
$friendCount = mysql_num_rows($friendResult);
$friendedCount = mysql_num_rows($friendedResult);
$trophyCommentCount = mysql_num_rows($commentResult);

$trophyList = array();
while($trophy = mysql_fetch_array($trophyResult) ) {
	$trophyList[$trophy['type'] ] = 1;
}

//Spam Master
if      (mysql_num_rows($artResult) >= 15 and $trophyList['spammaster'] != 1) {
	mysql_query("insert into trophies (owner,type,date) values ('".$cookiename."','spammaster',CURDATE()) ");
	
	$trophyTitle = "SPAM MASTER";
	$trophyText  = "You've submitted over <u>fifteen</u> arts today. That means you've broken our <a href='/viewTOS.php'>Terms of Service</a> for spamming and are about to be <b>BANNED</b>. Hahahaha... no, not really, we're actually going to give you a trophy. Here. Have a trophy: ";
	$trophyImage = "<img src='/images/trophies/spammaster.png'>";
	$trophyBool  = True;
}
//Green Ribbon
else if ($trophyCommentCount >= 1000 and $trophyList['greenribbon'] != 1) {
	mysql_query("insert into trophies (owner,type,date) values ('".$cookiename."','greenribbon',CURDATE()) ");
	
	$trophyTitle = "GREEN RIBBON";
	$trophyText  = "Congratz br0. You've made over <b>1,000</b> comments! You sure are a chatterbox, aren't you? Here, shuttup and have a green ribbon~";
	$trophyImage = "<img src='/images/trophies/greenribbon.png'>";
	$trophyBool  = True;
}
//Red Ribbon
else if ($trophyCommentCount >= 10000 and $trophyList['redribbon'] != 1) {
	mysql_query("insert into trophies (owner,type,date) values ('".$cookiename."','redribbon',CURDATE()) ");
	
	$trophyTitle = "RED RIBBON";
	$trophyText  = "Dear lawd! You've made over <b>10,000</b> comments! You are surely a great and powerful being. Have this red ribbon as a sign of your excellence. ";
	$trophyImage = "<img src='/images/trophies/redribbon.png'>";
	$trophyBool  = True;
}
//Blue Ribbon
else if ($trophyCommentCount >= 100000 and $trophyList['blueribbon'] != 1) {
	mysql_query("insert into trophies (owner,type,date) values ('".$cookiename."','blueribbon',CURDATE()) ");
	
	$trophyTitle = "BLUE RIBBON";
	$trophyText  = "You've made over <b>100,000</b> comments! Have a blue ribbon, you're a true DrawRawr veteran now. ";
	$trophyImage = "<img src='/images/trophies/blueribbon.png'>";
	$trophyBool  = True;
}
//Cool Kid Trophy
else if ($watchingCount >= 100 and $trophyList['coolkid'] != 1) {
	mysql_query("insert into trophies (owner,type,date) values ('".$cookiename."','coolkid',CURDATE()) ");
	
	$trophyTitle = "COOL KID";
	$trophyText  = "You've got <b>100</b> watchers! You're everyone's role model and all the female trophies love you! Have a cool trophy to match your coolness. ";
	$trophyImage = "<img src='/images/trophies/coolkid.png'>";
	$trophyBool  = True;
}
//Stalker Trophy
else if ($watcherCount >= 100 and $trophyList['stalker'] != 1) {
	mysql_query("insert into trophies (owner,type,date) values ('".$cookiename."','stalker',CURDATE()) ");
	
	$trophyTitle = "STALKER";
	$trophyText  = "Woah man, you're watching <b>100</b> users. That's just <i>creepy</i>, and I have the right mind to report you to the popo for mass stalking. But hey, enjoy a trophy while you're waiting for the police to arive:  ";
	$trophyImage = "<img src='/images/trophies/stalker.png'>";
	$trophyBool  = True;
}
//Incapable of Hate Plaque
else if ($friendCount >= 20 and $trophyList['incapableofhate'] != 1) {
	mysql_query("insert into trophies (owner,type,date) values ('".$cookiename."','incapableofhate',CURDATE()) ");
	
	$trophyTitle = "INCAPABLE OF HATE";
	$trophyText  = "Woah Gandi! Chill it with all the love and acceptance before you get assassinated! You have over 20 users in your friends list! Here, I'll give you this plaque if you promise to try to hate people a little more. ";
	$trophyImage = "<img src='/images/trophies/incapableofhate.png'>";
	$trophyBool  = True;
}
//Impossible to Hate Plaque
else if ($friendedCount >= 20 and $trophyList['impossibletohate'] != 1) {
	mysql_query("insert into trophies (owner,type,date) values ('".$cookiename."','impossibletohate',CURDATE()) ");
	
	$trophyTitle = "IMPOSSIBLE TO HATE";
	$trophyText  = "Huh, 20 people have friended you. Well aren't you just the lovable muchkin! Have a plaque for being so lovable. ";
	$trophyImage = "<img src='/images/trophies/impossibletohate.png'>";
	$trophyBool  = True;
}
//Moderator Trophy
else if ($cResult['moderator'] >= 1 and $trophyList['moderator'] != 1) {
	mysql_query("insert into trophies (owner,type,date) values ('".$cookiename."','moderator',CURDATE()) ");

	//Send moderator inbox message
	$inboxGreeting = "[center][size=14][img=http://i87.photobucket.com/albums/k125/kimisakitsune/ModBadge.png]
[b][u]Congratulations! You are now a Drawrawr Moderator!![/u][/b][/size][/center]


[b]Now listen up, maggot![/b]

You're a moderator of Drawrawr.com now. And that means you have a responsibility.

[u]That responsibility is the following!:[/u]

[dot] 1.) [b]Read the Terms of Service. Know the Terms of Service. It's important. Really.[/b]
If you haven't read the Terms of Service, seriously do so NOW. If you have any questions or concerns about the Terms of Service, ASK AN ADMIN. 
(AKA- [tinyuser]!catherine![/tinyuser] or [tinyuser]lambdanaut[/tinyuser]).

One of your most important jobs as a Moderator is to enforce the Terms of Service. If it's not in the TOS, either don't take action, or bring it up to an Admin. Do NOT take action against a user unless you're 100% sure.

And if a user PMs you with an Issue or Question, rather than using the Report Center of FAQ, we expect you to be able to handle it [i]appropriately.[/i]

[dot] 2.) On Drawrawr.com, our biggest (or at least one of our biggest) principles is [b]leniency towards user conduct.[/b]
This isn't kindergarten. You don't get banned for calling someone a name.
If two people want to call each other racial slurs, so be it. And if someone wants to get offended about it, that's fine too. But if they report it expecting us to punish the offender, we are NOT taking action.
They can either learn to cope or leave the site, we are not babysitters.
And neither are you. [b][u]So don't act like one.[/b][/u]

[dot] 3.) Unfortunately, the thing you probably were looking forward to the most does not exist on Drawrawr. [b]There is no mod banhammer. :'((((([/b]
On Drawrawr, before any member is banned (unless it's an obvious ban offense like hacking), it is put to a [b]vote![/b]

To the right of your Username, in the top right corner of the page, you will see a link that says [u]Staff Center[/u]. In the Staff Center, you can see all voting notices. You [b][u]MUST[/b][/u] vote on every voting notice. Otherwise, the issue will not get resolved properly. 
If something happens that keeps you from accessing DrawRawr, you should let an admin know beforehand, or call 1-910-471-5067.

[b]You should set aside at least a small amount of time [i]Daily[/i] to vote on voting notices.[/b]

[b]You can also create voting notices.[/b] If you see something that you believe constitutes a temporary ban, permanent ban, temporary comment disabling, or other such action, make a Notice in the Staff Center. 

[u]When making a Notice, you should:[/u] 
* Post the name of the User, or the URL to the offending material. 
* Select a Ban Reason from the Reason dropbox.
* Select a proposed punishment from the Propose dropbox.
* Type out any additional details, such as a summary of the situation, etc.
 
Action will then be taken depending on the voting results.

You are expected to be [b]unbiased[/b] when voting. Voting against the ban only because the user is a buddypal of yours, or voting for the ban because the person is someone you dislike is bad, and you should feel bad. You'll also be removed from the Staff if we have reason to believe you're doing that.

Anyways, cases like this will most likely be rare due to our leniency. 

[dot] 4.) One of the abilities of a Mod is to post [b]News on the frontpage.[/b] You can do this by submitting a journal and checking the checkbox at the bottom that shows only for Staff Members.
If you are using user icons in your News post, please use the [tinyuser] BBcode
and [b]do not post large images.[/b]
Also, we're pretty laid back around here, but we'd still prefer it if you did NOT post scat porn on the homepage or anything, thanks.
If you're going to post News, at least TRY to keep it relevant to the site. And try to only post if it's actually important. Like something new the users should know about.

If your name is Tom, feel free to use the News feature to finally come out to the public. 
We support you and your homosexual lifestyle.

[dot] 5.) I know it's very very fun, but we'd appreciate it if you didn't act like a gigantic D-Bag now that you're a [i]super cool[/i] Moderator. Moderator actions are NOT anonymous, and users do have the ability to Report you for Moderator abuse sooooooo yeah.



[center][size=14][b][u]Now that that's said and done, get out there, soldier!![/b][/u][/size][/center]";

	mysql_query("insert into inbox(owner,username,title,text,date) values ('".$cResult['username']."','drawrawr','Welcome Aboard! ','".bbsclean($inboxGreeting)."',CURDATE())");
	
	$trophyTitle = "Moderator Trophy";
	$trophyText  = "You're a <b>moderator</b> now! This trophy is our way of saying </u>get the fuck to work</u>. ";
	$trophyImage = "<img src='/images/trophies/moderator.png'>";
	$trophyBool  = True;
}

//Trophy Text
if (isset($trophyBool) && $trophyBool) {
echo("
	<script language='javascript'>
	$(function (){
     		$().animate({scrollTop: $('#trophyDialog').offset().top},'slow');
	}
	</script>
<div id='trophyDialog' class='newart' style='padding:150px;'>
	<audio autoplay=true>
	<source src='/extra/audio/trophy.ogg'>
	<source src='/extra/audio/trophy.mp3'>
	</audio>
	<center>
	<font color='red'>!</font> <u>Award Unlocked</u> <font color='red'>!</font>
	<h3>".$trophyTitle."</h3>
	<p>".$trophyText."</p>
	".$trophyImage."
	</center>
</div>
");
}

mysql_close($con);

}

?>
