<?php
require_once ("extra/library.php");
require '../lib/session.php';
if(!class_exists('DrawrawrID')) require 'extra/lib/classes.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

$userCount    = mysql_num_rows(mysql_query("select id from user_data"));
$modCount     = mysql_num_rows(mysql_query("select id from user_data where moderator > 0"));
$artCount     = mysql_num_rows(mysql_query("select id from arts"));
$journalCount = mysql_num_rows(mysql_query("select id from journals"));
$favCount     = mysql_num_rows(mysql_query("select id from favs"));
$watchCount   = mysql_num_rows(mysql_query("select id from watching"));
$commentCount = mysql_num_rows(mysql_query("select id from comments"));
$banCount = mysql_num_rows(mysql_query("select id from bans"));

$cResult    = mysql_fetch_array(mysql_query("select * from user_data where username = '".$cookiename."' and password = '".$cookiepass."' and moderator > 0"));

//Сheck that the user's cookie name & pass are valid & they're a mod. 
if(mysql_affected_rows() == 0) {
	header("Location: /");
}

$voteResult = mysql_query("select * from vote order by id desc limit 0,5");

$votes = "<ul class='votes'>";
while ($vote = mysql_fetch_array($voteResult) ) {
	$userdir = strtolower(str_replace(" ",".",$vote['username']));

	if (mysql_num_rows(mysql_query("select foragainst from votes where owner='".$cookiename."' and location='".$vote['id']."'")) == 0) {
		$voteExists = False;
	} else {$voteExists = True;}

	switch ($vote['propose']) {
		case "3":
			$propose = "3 day ban of <a href='/".$userdir."'>".$vote['username']."</a>";
			break;
		case "2":
			$propose = "2 week ban of <a href='/".$userdir."'>".$vote['username']."</a>";
			break;
		case "5":
			$propose = "5 week ban of <a href='/".$userdir."'>".$vote['username']."</a>";
			break;
		case "p":
			$propose = "Permaban of <a href='/".$userdir."'>".$vote['username']."</a>";
			break;
		default:
			$propose = "NO PROPOSITION SELECTED ERROR";
	}
	switch ($vote['reason']) {
		case "porn":
			$reason = "for Explicit Pornography";
			break;
		case "hack":
			$reason = "for Hacking Attempts";
			break;
		case "spam":
			$reason = "for Spam";
			break;
		case "theft":
			$reason = "for Art Theft";
			break;
		case "harass":
			$reason = "for Severe Harassment";
			break;
		case "evade":
			$reason = "for Ban Evasion";
			break;
		default:
			$reason = "for NO REASON SELECTED ERROR";
	}
	if (!$voteExists) {
		$votes = $votes."<li id='".$vote['id']."' onclick='openVote(\"".$vote['id']."\")' style='cursor:pointer;'> <img src='/images/dottico.png'> <b>".$propose." ".$reason."</b> <br> - Made by ".$vote['owner']."<span class='reason' style='display:none;' title=\"".$propose." ".$reason."\"><p><b>Reason: </b>".tobbs($vote['comment'])."</p><center><b style='cursor:pointer;' onclick='vote(\"".$vote['id']."\",\"up\")'>ˆVote Upˆ</b><br><br><b style='cursor:pointer;' onclick='vote(\"".$vote['id']."\",\"down\")'>˅Vote Down˅</b></center></span></li>";
	}
}
$votes = $votes."</ul>";

$featuredResult = mysql_query("select * from featuredProp order by id desc limit 0,6");

$featuredText = "<ul class='votes'>";
while ($vote = mysql_fetch_array($featuredResult) ) {
	$userdir = strtolower(str_replace(" ",".",$vote['username']));

	$featuredArt=mysql_fetch_array(mysql_query("select * from arts where id='".$vote['location']."'"));
	
	if (mysql_num_rows(mysql_query("select id from featuredVote where owner='".$cookiename."' and location='".$vote['id']."'")) == 0) {
		$featuredText = $featuredText."<li id='".$vote['id']."' onclick='openVote(\"".$vote['id']."\")' style='cursor:pointer;'> <img src='/images/dottico.png'> <b>".$featuredArt['title']."</b> <br> - Suggested by ".$vote['owner']."<span class='reason' style='display:none;' title='Feature Art'><p><p>".$vote['owner']." suggested <a href='/art/".DrawrawrID::encode(strval($featuredArt['id']))."'>".$featuredArt['title']."</a> to be a featured artwork. </p><b>Reason: </b>".tobbs($vote['reason'])."</p><center><b style='cursor:pointer;' onclick='voteFeatured(\"".$vote['id']."\",\"up\")'>ˆVote Upˆ</b><br></center></span></li>";
	} 
}
$featuredText = $featuredText."</ul>";

$modActionResult = mysql_query("select * from moderatorAct order by id desc limit 0,22");

$modActions = "";
while ($action = mysql_fetch_array($modActionResult) ) {
	$modActions .= " <img src='/images/dottico.png'> <a href='/".$action['owner']."'><b>".$action['owner']."</b></a> just \"<i>".$action['action']."</i>\"<br><br>";
}

require_once ("prehtmlincludes.php");
?>
<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = "javascript" src = "/extra/jquery-ui.min.js"></script>
<script language = 'javascript'>

function openVote (id) {
	$("#" + id + " .reason").dialog({width:500,show:'show'});
}

function vote (id,val) {
	var conf = confirm("Are you sure you want to vote this " + val + "? ");
	if (conf) {
		$("#" + id).fadeOut("slow");
		$(".reason").dialog("close");
		$.ajax({
			type: "POST",
			url: "vote.php",
			data: "user=<?php echo($cookiename); ?>&pass=<?php echo($cookiepass); ?>&id=" + id + "&val=" + val,
			success: function(reply) {
				alert(reply);
			},
			error: function(){
				alert("Voting Failed!");
			}
		});
	}
}

function voteFeatured (id) {
	var conf = confirm("Are you sure you want to vote for this art to be featured? ");
	if (conf) {
		$("#" + id).fadeOut("slow");
		$(".reason").dialog("close");
		$.ajax({
			type: "POST",
			url: "voteFeatured.php",
			data: "user=<?php echo($cookiename); ?>&pass=<?php echo($cookiepass); ?>&id=" + id,
			success: function(reply) {
				alert(reply);
			},
			error: function(){
				alert("Voting Failed!");
			}
		});
	}
}

</script>
<html><title>Moderator</title>
<head>

<link rel="stylesheet" type="text/css" href="/css/main.css">
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">
<style type="text/css">
.left {
	float: left;
	width: 33%;
}
.center {
	float: left;
	width: 33%;
}
.right {
	float: left;
	width: 32%;
}
.input {
	width:100%;
}
.votes {
	list-style-type: none;
	font-size:12px;
	text-indent: -3em;
}
</style>

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
		<div id="content">
			<img src = '/images/staffbadge.png' style="float:left;margin:5px 50px 5px 50px;" align="texttop">
			<div style="margin:20px 50px 50px 50px;font-size:14px;"><b>Welcome to the Staff Center. Here you can vote on and create Voting Propositions, review and resolve User Reports, Observe Recent Actions taken by the staff, etc.

You should put aside at least a short amount of time DAILY to vote. 

If something goes wrong and there are no higher level Staff members online, You can call the Admins at:
1-910-471-5067

If you have any other questions, ask an Admin. </b><p>
			<center> <font size=4> <a href="/staffGuidelines.php">Staff Guideline 
</a> </font> </center> </p>
			</div>
			<span class="left"><div class="newart">
				<h3> <img src='/images/modico.png'> BAN PROPOSITIONS </h3>
				<?php
					echo($votes);
				?>
			</div></span>
			<span class="center"><div class="newart">
				<h3> <img src='/images/modico.png'> FEATURED ART </h3>
				<p>(Most Recent Propositions)</p>
				<?php
					echo($featuredText);
				?>
			</div></span>
			<span class="right"><div class="newart">
				<h3> <img src='/images/modico.png'> MOD ACTIONS </h3>
				<?php
					echo($modActions);
				?>
			</div></span>
		</div>
		<div id="aside">
			<!-- <div class="newart"><div style = "float:right;">DATE</div> <h3 style="margin: 0px 0px 3px 0px;"><img src='/images/newsico.png'> Staff News! </h3><div id = "scrollable" style="height:190px;">ENTRY</div> - <? echo("<a href = '/".$journalDir."'>".$journalOwner."</a>"); ?></div> -->
			<div class="newart"><h3 style="margin: 0px 0px 3px 0px;"><img src='/images/newsico.png'> Site Stats </h3>
			<div style='margin-left:20px;'>
				<b>Users: </b><?php echo($userCount); ?> <br>
				<b>Arts: </b><?php echo($artCount); ?> <br>
				<b>Mods: </b><?php echo($modCount); ?> <br>
				<b>Journals: </b><?php echo($journalCount); ?> <br>
				<b>Favorites: </b><?php echo($favCount); ?> <br>
				<b>Watches: </b><?php echo($watchCount); ?> <br>
				<b>Comments: </b><?php echo($commentCount); ?> <br>
				<b>Banned Users: </b><?php echo($banCount); ?> <br>
			</div>
			</div>
			<div class="newart">
			<h3> <img src='/images/modico.png'> Make a Notice </h3>
			<form action="submitNotice.php" method="post">
			Username: (Check Spelling!)<br>
				<input type="text" value="" name="username" class="input"><br>
			Reason: <br>
				<select name="reason" class="input">
					<option value="porn">Explicit Pornography</option>
					<option value="hack">Hacking</option>
					<option value="spam">Spam</option>
					<option value="theft">Art Theft</option>
					<option value="harass">Severe Harassment</option>
					<option value="evade">Ban Evasion</option>
				</select><br>
			Propose: <br>
				<select name="propose" class="input">
					<option value="3">Three Day Ban</option>
					<option value="2">Two Week Ban</option>
					<option value="5">Five Week Ban</option>
					<option value="p">Permaban</option>
				</select><br>
			Comment: <br>
			<textarea name="comment" cols="15" rows="5" style="width:100%;height:100px;"></textarea><br>
			<input type="submit" value="Submit">

			</form>
			</div>
		

		<div class="newart">
			<h3> <img src='/images/modico.png'> Broadcast PM </h3>
			<form action="submitBroadcast.php" method="post">
			Poster's Alias: <br>
				<select name="alias" class="input">
					<option value="DrawRawr">Drawrawr</option>
					<option value="<?php echo $cookiename; ?>">Your name (<?php echo $cookiename; ?>)</option>
				</select><br>

			Title:<br>
				<input type="text" value="" name="title" class="input"><br>
			Content: <br>
			<textarea name="content" cols="15" rows="5" style="width:100%;height:100px;"></textarea><br>
			<input type="submit" value="Submit">

			</form>
			</div>
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
