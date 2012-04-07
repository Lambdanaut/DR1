<?php
require_once ("prehtmlincludes.php");
require("extra/moodform.php");
require 'extra/lib/session.php';

$con = con();

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];
$cResult    = mysql_fetch_array(mysql_query("select moderator from user_data where username = '".$cookiename."'"));

if(isset($_POST['submitType'])) {
	$submitType = $_POST['submitType'];

	//Mature Boxes
	$mature = "<b>Does this submission contain: </b><br> 
		<div style='text-align:left;width:35%;margin-left:30%;margin-right:30%;'>
		<input type='checkbox' name='nude' value='1'> Nudity <br>
		<input type='checkbox' name='drug' value='1'> Drug Use <br>
		<input type='checkbox' name='gore' value='1'> Gore or Strong Violence <br>
		<input type='checkbox' name='sex'  value='1'> Sexual Themes <br>
		</div>
		"
		;

	//Checks if user is mod. If so, adds a checkbox for submiting journal as news.
	if ($cResult['moderator'] >= 3){
		$newsBox = '<input type="checkbox" name="news" value="t" > Check this to put this journal on the front page. If news, then use [tinyuser] instead of [user], and dont include large images. <br> <br>';
	} else {$newsBox = "";}

	//Print this if the user selects to submit a journal. 
	if ($submitType == "journal"){
	  $submitEcho = "<div class='newArt' style = 'text-align: center;'>
		<h1><img src ='images/journalico.png' >Journal</h1>
		<form method = 'post' action ='/processSubmition.php'>
		<input type = 'hidden' name = 'journal' value = 't'>
		<b>Title:</b> <br><input type = 'text' name = 'journalName' width = '20' size = '78' maxlength = '50' ><br><br>
		<b>Entry:</b> <br><textarea name = 'journalEntry' cols=100 rows=28></textarea><br><br>
		<b>Mood:</b> <br>
		<input id='journalMood' name='journalMood'><input id='mood-id' type='hidden'><br><br>
		<br><br>
		".$newsBox."
		<input type = 'submit' value ='Post Journal!'></form></div>
	";
	}

	//Print this if user selects to submit Image
	if ($submitType == "image"){
	  $submitEcho = "
		<div class='newArt' style = 'text-align: center;'>
		<h1><img src ='images/paletteico.png'>Image</h1>
		<h5>(Drawing, Painting, Pixelwork, Digital vector, etc etc..)</h5><br>
		<form enctype='multipart/form-data' action='/processSubmition.php' method='post'>
		<b>Image File: (.gif,.png, or .jpg)</b><br><input name='imageUploaded' type='file'><br><br>
		<input type = 'hidden' name = 'image' value = 't'>
		<b>Title:</b> <br><input type = 'text' name = 'imageName' width = '20' size = '78' maxlength = '50'><br><br>
		<b>Description:</b> <br><textarea name = 'imageDescription' cols=100 rows=10></textarea><br><br>
		".$mature."
		<input type = 'submit' value ='Submit'></form></div>
	";
	}

	//Print this if user selects to submit Image
	if ($submitType == "culinary"){
	  $submitEcho = "
		<div class='newArt' style = 'text-align: center;'>
		<h1><img src ='/images/emote/cake.png'>Culinary</h1>
		<form enctype='multipart/form-data' action='/processSubmition.php' method='post'>
		<b>culinary Image File: (.gif,.png, or .jpg)</b><br><input name='imageUploaded' type='file'><br><br>
		<input type = 'hidden' name = 'culinary' value = 't'>
		<b>Title:</b> <br><input type = 'text' name = 'culinaryName' width = '20' size = '78' maxlength = '50'><br><br>
		<b>Recipe:</b> <br><textarea name = 'culinaryWriting' cols=100 rows=25></textarea><br><br>
		<b>Description:</b> <br><textarea name = 'culinaryDescription' cols=100 rows=10></textarea><br><br>
		".$mature."
		<input type = 'submit' value ='Submit'></form></div>
	";
	}
	//Print this if user selects to submit Image
	if ($submitType == "craft"){
	  $submitEcho = "
		<div class='newArt' style = 'text-align: center;'>
		<h1><img src ='/images/crafticon.png'>Craft</h1>
		<form enctype='multipart/form-data' action='/processSubmition.php' method='post'>
		<b>Craft Image File: (.gif,.png, or .jpg)</b><br><input name='imageUploaded' type='file'><br><br>
		<input type = 'hidden' name = 'craft' value = 't'>
		<b>Title:</b> <br><input type = 'text' name = 'craftName' width = '20' size = '78' maxlength = '50'><br><br>
		<b>Description:</b> <br><textarea name = 'craftDescription' cols=100 rows=10></textarea><br><br>
		".$mature."
		<input type = 'submit' value ='Submit'></form></div>
	";
	}

	//Print this if user selects to submit Literature
	if ($submitType == "literature"){
	  $submitEcho = "<div class='newArt' style = 'text-align: center;'>
		<h1><img src ='images/litico.png'>Literature</h1>
		<h5>(Prose, Poetry, Pretty much anything)</h5>
		<form enctype='multipart/form-data' action='/processSubmition.php' method='post'>
		<input type = 'hidden' name = 'literature' value = 't' >
		<b>Title:</b> <br><input type = 'text' name = 'litName' width = '20' size = '78' maxlength = '50'><br><br>
		<b>Thumbnail Icon: </b><br><input name='litIcon' type='file' ><br><br>
		<b>Description:</b> <br><textarea name = 'litDescription' cols=100 rows=8></textarea><br><br>
		<b>Writing:</b> <br><textarea name = 'litWriting' cols=100 rows=25></textarea><br><br>
		".$mature."
		<input type = 'submit' value ='Submit' ></form></div>
	";
	}

	//Print this if user selects to submit Flash
	if ($submitType == "flash"){
	  $submitEcho = "<div class='newArt' style = 'text-align: center;'>
		<h1><img src ='images/flashico.png'>Flash Animation</h1>
		<h5>(Animation, Games, Tutorials, etcetcetc...)</h5>
		<form enctype='multipart/form-data' action='/processSubmition.php' method='post'>
		<input type = 'hidden' name = 'flash' value = 't'>
		<b>Title:</b> <br><input type = 'text' name = 'flashName' width = '20' size = '78' maxlength = '50'><br><br>
		<b>Thumbnail Icon: </b><br><input name='flashIcon' type='file'><br><br>
		<b>Flash Animation (.swf file): </b><br><input name='flashSWF' type='file'><br><br>
		<b>Description:</b> <br><textarea name = 'flashDescription' cols=100 rows=8></textarea><br><br>
		".$mature."
		<input type = 'submit' value ='Submit' ></form></div>
	";
	}

	if ($submitType == "audio"){
	  $submitEcho = "<div class='newArt' style = 'text-align: center;'>
		<h1><img src ='images/radioico.png'>Audio</h1>
		<form enctype='multipart/form-data' action='/processSubmition.php' method='post'>
		<input type = 'hidden' name = 'audio' value = 't'>
		<b>Title:</b> <br><input type = 'text' name = 'audioName' width = '20' size = '78' maxlength = '50'><br><br>
		<b>Audio (.wav, .mp3, OR .ogg file!): </b><br><input name='audioFile' type='file'><br><br>
		<b>Thumbnail Icon: </b><br><input name='audioIcon' type='file'><br><br>
		<b>Description:</b> <br><textarea name = 'audioDescription' cols=100 rows=8></textarea><br><br>
		".$mature."
		<input type = 'submit' value ='Submit' ></form></div>
	";
	}
}

?>

<html>
<head><title>Submit</title>

<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = "javascript" src = "/extra/jquery-ui.min.js"></script>
<script language = 'javascript'>
$(function() {

	var moods = <?php require("extra/moodlist"); ?>;

	$("#journalMood").autocomplete({
		minLength: 1,
		source: moods,
		focus: function(event, ui) {
			$("#journalMood").val(ui.item.value);
			return false;
		},
		select: function(event, ui) {
			$("#journalMood").val(ui.item.value);
			$("#mood-id").val(ui.item.value)
			return false;
		}
	});

});
</script>

<link rel="stylesheet" type="text/css" href="css/main.css">
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
	<div id = "ads">
		<?php require ("ads2.php"); ?>
	</div>
	<div id="content-container">
		<center>
		<div id="content" style = "width: 100%;">
			<div class="newArt" style = "text-align: center;">
			<form id="subType" method="post" action="/submit.php"> 
			<h2><b> <img src = "/images/dottico.png" ><u> Select a Submission Type <img src = "/images/dottico.png" ></u></b></h2><br>
			<select name="submitType" onChange = "document.forms[0].submit()">
			<option value="n"></option>
			<option value="journal">Journal</option>
			<option value="image">Image</option>
			<option value="literature">Literature</option>
			<option value="flash">Flash Animation</option>
			<option value="audio">Audio</option>
			<option value="culinary">Culinary</option>
			<option value="craft">Craft</option>
			</select>
			</form>
			</div>
			<?php if(isset($submitEcho)) echo $submitEcho; ?>
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
