<?php
require_once ("extra/library.php");

$cookiename = bbsclean($_COOKIE['user']);

require_once ("prehtmlincludes.php");
?>
<html><title>FAQ</title>
<head>
<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = "javascript" src = "/extra/jquery-ui.min.js"></script>
<script language = "javascript">
$(document).ready(function () {
	$("#submitQuestion").button();
	$("#submitQuestion").click(function () {
		$("#submitQuestion").unbind("click");
		$("#submitQuestion").fadeOut("slow");
		var text = $("textarea#text").val();
		$.ajax({
			type: "POST",
			url: "/processFAQ.php",
			data: "&user=<?php echo($cookiename); ?>&text=" + text,
			success: function(reply) {
				$("#text").fadeOut("slow",function () {
					$("#reply").html(reply);
					$("#reply").fadeIn("fast");
				});

			},
		});
	});
});
</script>

<style type="text/css">
.question {
	border: solid 2px #c9a393;
	font-size: 12px;
	padding: 5px;
	margin: 5px;
	min-height:75px;
}
.q {
	font-size: 20px;
	color: red;
}
.a {
	font-size: 20px;
	color: green;
}
</style>

<link rel="stylesheet" type="text/css" href="/css/main.css">
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">

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
			<center><h2> <img src='/images/dottico.png'> Frequently Asked Questions <img src='/images/dottico.png'> </h2>
			<p> To search the FAQ, press ctrl + F (or apple + F) on your keyboard, and type in a keyword!<br>
			Got a question that isn't listed? Send it to us using the form below and a moderator will message you with the answer!
			Or, Message <a href='/!catherine!'><img src='avatars/!catherine!' style='width:35px;height:35px;'></a> and ask her!
			</p>
			<textarea rows='5' cols='70' style='display:block;width:90%;' id='text' name='text'></textarea>
			<div id = 'reply' style='display:none;'></div>
			<input type='button' id='submitQuestion' value='Submit'>
			</center>
			<div class = 'question'>
				<p>
				<?php
				echo(tobbs(" <span class='q'>Q: </span> <b><u>BBCODE. HOW DOES IT WORK?</u></b>
<span class='a'>A: </span>Here's all the BBCodes so far and what they do. (remove all *)

[*b][*/b] Makes text bold. For example, [b]TEXT[/b]
[*i][*/i] Makes text italic. For example, [i]TEXT[/i]
[*u][*/u] Makes text underlined. For example, [u]TEXT[/u]
[*s][*/s] Makes text striken. For example, [s]TEXT[/s]

[*left][*/left] Places text on the left of the page. For example,
[left]TEXT[/left]
[*center][*/center] Centers text. For example,
[center]TEXT[/center]
[*right][*/right] Places text on the right of the page. For example,
[right]TEXT[/right]
[*move][*/move] Makes text move across the screen. For example,
[move]TEXT[/move]
[*user][*/user] Displays the user's icon. [user]!catherine![/user]
[*user=USERNAME] works as well.
[*tinyuser][*/tinyuser] Displays the user's icon, but 50% smaller. 
For example,[tinyuser]!catherine![/tinyuser]
[*url]writeurlhere[*/url] Displays a url link.
[*url=writeurlhere]writetesthere[*/url] Displays text that links to a url. For example, [url=http://74.52.88.156/]TEXT[/url]
[*email][*/email] Makes an e-mail link.
[*size=???][*/size] Replace ??? with a number (such as 12, 48, etc) and it will change your text to that size. For example,
[size=18]TEXT[/size]
There's also Emoticon BBCode. You can see all Emoticons and their BBCode in the Emoticon box located to the right whenever making or replying to a comment.
[*img][*/img] Displays an image. 
[*color=WHATEVERCOLOR][*/color] Changes the color of the text.
[*font=FONTTYPE][*/font] Changes the font. For instance, arial, comic-sans, etc. 
[*spoiler][*/spoiler] Hides the text behind a black background. [spoiler]This is a spoiler![/spoiler]
[*youtube=SHARELINK] embeds a youtube video! Make sure to use the 'share' link located underneath the video and not the regular url!!
"));
				?>
				</p>
			</div>
			<div class = 'question'>
				<p> 
				<span class='q'>Q: </span><b><u>How do I submit a journal/image/audio file/flash animation/culinary/craft/literature piece?</u></b> <br>
				<span class='a'>A: </span>Sign in to your Drawrawr account and click "SUBMIT!" next to your user icon on the top right of the page. A dropdown box will appear that will let you choose from submitting journals, images, audio, animation, culinary, craft, or literature! 
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>Why does the default male icon have that stupid moustache?</u></b> <br>
				<span class='a'>A: </span>Because SHUT UP, that's why.
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>Can I be a Staff member?</u></b> <br>
				<span class='a'>A: </span>No. Well, maybe. If we are hiring and you meet the qualifications, feel free to try out! You can find out if we're hiring by going to <a href='/drawrawr/'><img src='avatars/drawrawr' style='width:35px;height:35px;'></a>.
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>I made some Emoticons! Can you put them on the site for use?</u></b> <br>
				<span class='a'>A: </span>Maybe! Send them to <a href='/!catherine!'><img src='avatars/!catherine!' style='width:35px;height:35px;'></a> and we might add them!.
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>I found a Bug/Error/Typo on DrawRawr.com! WHAT DO I DO?!</u></b> <br>
				<span class='a'>A: </span> Woah, relax! Just click 'Report Center' at the bottom of the page, and fill out your report. If that doesn't work, Send a Private Message to <a href='/drawrawr'><img src='avatars/drawrawr' style='width:35px;height:35px;'></a>, or <a href='/!catherine!'><img src='avatars/!catherine!' style='width:35px;height:35px;'></a>.
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>I have a site related complaint/concern/suggestion. What do I do?</u></b> <br>
				<span class='a'>A: </span> Click 'Report Center' at the bottom of the page! If that doesn't work, send a private message to <a href='/drawrawr/'><img src='avatars/drawrawr' style='width:35px;height:35px;'></a> or <a href='/!catherine!'><img src='avatars/!catherine!' style='width:35px;height:35px;'></a>
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>What am I allowed to submit!?</u></b> <br>
				<span class='a'>A: </span> Basically, anything! The only exceptions to this are: <u>EXPLICIT pornography</u> (ie: <b>videos</b> or <b>photography</b> showing sexual acts of <i>real people</i>), or <u>Stolen Artwork</u> (ie: Anything that was not created by <b>you</b> in some way, or that you did not get <b>permission from the creator</b> to post on our site.)
For more information, Read the <a href='/viewTOS.php'>Terms of Service!</a>
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>What is the mascot's name?</u></b> <br>
				<span class='a'>A: </span> Nobody knows...
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>Can I change my username?</u></b> <br>
				<span class='a'>A: </span> No. Choose wisely when you register!
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>What is acceptable content for a comment?</u></b> <br>
				<span class='a'>A: </span> Here on DrawRawr, we believe in being able to freely speak your mind. Say whatever you want. The only thing you can't put in a comment is any image added in a comment that contains photography or animation of explicit pornography. If you do that, the comment will be removed and you MAY be banned. 
				Keep in mind however that even though you are typically allowed to say whatever you want, "SEVERE Harassment" can be a bannable offense. As long as you don't get a noticable amount of the community pissed off at you, it should be fine!
				For more information, Read the <a href='/viewTOS.php'>Terms of Service!</a>
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>How do I edit Artwork?</u></b> <br>
				<span class='a'>A: </span> Easy! Go to the artwork you want to Edit, and click 'Edit' to the right of the submission's description.
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>Uugh! Look at all this filth! How do I change my Mature Settings?</u></b> <br>
				<span class='a'>A: </span> Click on "Settings" next to your user icon on the top right of the page. In your "General Settings" you may choose to MARK mature artworks or HIDE mature artworks. Marked artworks will have a warning over the artwork. Hidden artworks will be completely invisible to you while you are logged in to your account.
				You may also choose which things you don't want to see by checking or unchecking the boxes next to "Nudity, Drug Use, Gore or Strong Violence and Sexual Themes". 
				</p>
			</div>
			<div class = 'question'>
				<p>
				<span class='q'>Q: </span><b><u>"Nearby Users"? What is that for and how do I use it?</u></b> <br>
				<span class='a'>A: </span> Simple! The "Nearby Users" feature is a way to find users that live near you! It does NOT reveal your Address, City, or even your State, Province or Country to other users. It simply shows which users live within a certain distance radius of you.<br>
				The distance is approximately a three hour drive or less. All results are <b>in order from closest to farthest.</b> <br>
				To use this feature, click on your "Settings" next to your user icon on the top right of the page. Go into your "General Settings" and click the button to the right that says "Automatically get Location". A popup window may appear. Allow it. 
				The results (if there are any) should be on your userpage under "Nearby Users"!
				</p>
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
