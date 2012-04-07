<?php
require_once ("extra/library.php");
require_once ("prehtmlincludes.php");
?>
<html><title>Staff</title>
<head>

<style type="text/css">
.staffUser {
	border: solid 2px #c9a393;
	padding: 5px;
	min-height:75px;
}
.staffUser img{
	float: left;
	margin-right: 7px;
	width: 75px;
	height: 75px;
}
</style>

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
		<div id = "content" style = "width: 100%;">
			<div class = "newArt">
			<center><h2>Staff</h2></center>
			<h3>Admins</h3>
			<div class = 'staffUser'>
				<a href='/lambdanaut'><img src='/avatars/lambdanaut'> <h4>Josh</h4></a>
				<p>Josh is the King of Drawrawr! Everything you see was put together with Josh's pro
programming skills. He's also the main investor, paying for the hosting that keeps Drawrawr alive!
As well as anything else the site might need. </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/!catherine!'><img src='/avatars/!catherine!'> <h4>Catherine</h4></a>
				<p>and Catherine is the Queen of DrawRawr! She's the site's art director, designer, and PR gal. She makes the art on the site and handles most of the hiring, events, planning, advertising, etc! She has purple hair and is a total mom. </p>
			</div>
			<h3>Moderators</h3>
			<div class = 'staffUser'>
				<a href='/jonnylitt'><img src='/avatars/jonnylitt'> <h4>Jonny</h4></a>
				<p>Jonathan Paul Littman is a partially insane creative programmer with a widely versed vocabulary of computer programming languages. He's a computer scientist in my own crazy sense and he loves to experiment with new methods of developing large-scale SaaS applications. </p>
			</div><br>
			<!--<div class = 'staffUser'>
				<a href='/fish'><img src='/avatars/fish'> <h4>Tom</h4></a>
				<p>Fish is a very, very homosexual Fish. But it's okay. We respect his lifestyle choices.
He's also the Head Moderator! He watches over the site as well as the other Mods. </p>
			</div><br>-->
			<div class = 'staffUser'>
				<a href='/asheskun'><img src='/avatars/asheskun'> <h4>Asheskun</h4></a>
				<p><img src="http://img499.imageshack.us/img499/8502/iiam29pb.gif" style="width:auto;height:auto;"><img src="http://img499.imageshack.us/img499/8502/iiam29pb.gif" style="width:auto;height:auto;"><img src="http://img499.imageshack.us/img499/8502/iiam29pb.gif" style="width:auto;height:auto;"><img src="http://img499.imageshack.us/img499/8502/iiam29pb.gif" style="width:auto;height:auto;"><img src="http://img499.imageshack.us/img499/8502/iiam29pb.gif" style="width:auto;height:auto;"><br></p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/the.ringmaster'><img src='/avatars/the.ringmaster'> <h4>The Ringmaster</h4></a>
				<p>The Ringmaster is a tophatted aspiring writer of indeterminate gender who hates California. It touches small boys and has various disturbing fetishes that are probably best left unmentioned. </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/toast'><img src='/avatars/toast'> <h4>Toast</h4></a>
				<p>She comes with a vass assortment of fruit preserves and jams, salted or unsalted butter, cinnamon, and powdered sugar! :9 </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/chinoumi'><img src='/avatars/chinoumi'> <h4>Chinoumi</h4></a>
				<p>This is Chinoumi, she regrets what she did for a Klondike bar and is unable to look at your mom the same way ever again. She is a digital/traditional artist with a passion for drawing boobs. Hardcore.Naked.Boobs. </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/inter'><img src='/avatars/inter'> <h4>Inter</h4></a>
				<p>Tev's one of those gosh darned music majors, meaning he's totally wasting all his parents' hard-earned money on fruitless Californian hipster pursuits. Oh well. Aside from that, he's also a local DJ, occasional writer, and in general a pretty swell guy. </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/woif'><img src='/avatars/woif'> <h4>Woif</h4></a>
				<p>Woif is just a furry. Also a mod and always watching. always. </p>
			</div><br>
			<!--<div class = 'staffUser'>
				<a href='/nguillemot'><img src='/avatars/nguillemot'> <h4>Nicolas</h4></a>
				<p>Nic is a trillingual Engineer-to-be currently studying at UVIC. He is ridiculous. On DrawRawr, he's the assistant programmer! </p>
			</div><br>-->
			<div class = 'staffUser'>
				<a href='/mcbob'><img src='/avatars/mcbob'> <h4>McBob</h4></a>
				<p>Ｉ＇ｌｌ　ｓｈｏｗ　ｙｏｕ　ｗｈｏ＇ｓ　ｂｏｓｓ　ｏｆ　ｔｈｉｓ　ｇｙｍ </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/augustus'><img src='/avatars/augustus'> <h4>Augustus</h4></a>
				<p><b>Age:</b> Of consent | <b>Gender:</b> Fucked | <b>Sex:</b> on the pool table!</p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/superporn'><img src='/avatars/superporn'> <h4>Superporn</h4></a>
				<p>Make me a sandwich.</p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/extradonut'><img src='/avatars/extradonut'> <h4>EXtraDonut</h4></a>
				<p>DON'T GIVE A FUCK 
				<br> G G G G G G G G G G G G G G G G G G G G G G G G G G G G</p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/general'><img src='/avatars/general'> <h4>General</h4></a>
				<p>General is generally a general in general. </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/chelko'><img src='/avatars/chelko'> <h4>Chelko</h4></a>
				<p>Level 1 Moderator. </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/latchkey'><img src='/avatars/latchkey'> <h4>Latchkey</h4></a>
				<p>Level 1 Moderator. </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/tenzo'><img src='/avatars/tenzo'> <h4>Tenzo</h4></a>
				<p>Level 1 Moderator. </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/bil.e.horse'><img src='/avatars/bil.e.horse'> <h4>Bil E Horse</h4></a>
				<p>Level 1 Moderator. </p>
			</div><br>
			<div class = 'staffUser'>
				<a href='/triple-q'><img src='/avatars/triple-q'> <h4>Triple-Q</h4></a>
				<p>Level 1 Moderator. </p>
			</div><br>

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
