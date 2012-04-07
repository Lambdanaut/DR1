<?php
require_once ("extra/library.php");
require_once("extra/commentForm.php");
require 'extra/lib/session.php';
if(!class_exists('DrawrawrID')) require 'extra/lib/classes.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];
$userDir    = str_replace(" ",".",(strtolower($cookiename)));

if(defined('DirectorEngine')) {
	if(DIRECTOR_VIEW == '') header('Location: /');

	$id 		= DrawrawrID::decode(DIRECTOR_VIEW);
	$imageLink 	= '/arts/'.$id;
} else {
	$filename  = basename(curPageURL());
	$imageLink = str_replace(".php","",$filename);
	$id	= $imageLink;
}

$cResult    = mysql_fetch_array(mysql_query("select moderator from user_data where username = '".$cookiename."'"));
$pageResult = mysql_fetch_array(mysql_query("select * from arts where id = '".$id."'"));
$featuredResult = mysql_fetch_array(mysql_query("select * from featuredArt where location = '".$id."'"));
if ($featuredResult['id']!="") {$featuredBool = True;} else {$featuredBool = False;}

$pageID     = $pageResult["id"];
$pageTitle  = $pageResult["title"];
$pageOwner  = $pageResult["owner"];
$ownerDir   = str_replace(" ",".",(strtolower(bbsclean($pageOwner))));
$pageType   = $pageResult["type"];
$pageMedia  = $pageResult["mediatype"];
$pageDate   = timeagoFormat($pageResult["timestamp"]);
$pageViews  = $pageResult["viewers"];
$pageloc    = $pageResult["id"];
$pageDesc   = tobbs($pageResult["description"]);
$pageWrite  = tobbs($pageResult["writing"]);
$pageWidth  = $pageResult["width"];
$pageHeight = $pageResult["height"]; 

$blocked = blockCheck($pageOwner,$cookiename);

$nextArt    = mysql_fetch_array(mysql_query("select * from arts where id > '".$pageID."' and owner='".$pageOwner."' order by id asc limit 0,1")) or $nextArt=False;
$prevArt    = mysql_fetch_array(mysql_query("select * from arts where id < '".$pageID."' and owner='".$pageOwner."' order by id desc limit 0,1")) or $prevArt=False;

if ($cResult['moderator'] >= 4) {$mod = True;}

$cropBool   = tobbs($_POST['cropBool']);

$favResult = mysql_query("select * from favs where faver = '".$cookiename."' and piece = '".$id."'") or exit ("DATABASE ERROR! lol. ");
if (mysql_affected_rows() == 0) {$favText = "Favorite";} else {$favText = "De-Fav";}
$favResult = mysql_query("select * from favs where piece = '".$id."'") or exit ("DATABASE ERROR! lol. ");
$favNum    = mysql_num_rows($favResult);

$watchResult = mysql_query("select * from watching where watcher = '".$cookiename."' and watching = '".$pageOwner."'") or exit ($cookiename);
if (strtolower($cookiename) != strtolower($pageOwner)){$showWatch = True;} else {$showWatch = False;}
if (mysql_affected_rows() == 0 and $showWatch) {$watchText = "Watch";} else {$watchText = "De-Watch";}

//Add 1 to page views if viewer is not art owner. 
if (strtolower($cookiename) != strtolower($pageOwner)){$pageResult = mysql_query("update arts set viewers = '".strval(intval($pageViews) + 1)."' where id = '".$id."'");}



function mimeToType($mime) {
	if ($mime=="audio/mpeg" or $mime=="audio/mp3"){
		return "mp3";
	}
	if ($mime=="audio/x-wav"){
		return "wav";
	}
	if ($mime=="audio/ogg"){
		return "oga";
	}
}

if ($pageMedia == "") {
	$audioType = "NOTAUDIO";
} else {
	$audioType = mimeToType($pageMedia);
}

require_once ("prehtmlincludes.php");

?>
<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = "javascript" src = "/extra/jquery-ui.min.js"></script>
<script type="text/javascript" src="/extra/jquery.jplayer.min.js"></script>
<script language = 'javascript'>
//<![CDATA[
var cookiename  = "<?php echo($cookiename); ?>";
var cookiepass  = "<?php echo($cookiepass); ?>";
var pageLoc     = "<?php echo($pageloc); ?>";
var commentType = "art";
var loggedIn    = "<?php echo($loggedIn); ?>";
var range       = 0;
var ammount     = 10;

$(document).ready(function () {
	$("#edit").click(function () {
		$("#editFormDiv").dialog({ width: 750 });
	});

	//Rollover Next & Prev
	$(".show").mouseout(function () {
		$(this).find(".hidden").hide();
	});
	$(".show").mouseover(function () {
		$(this).find(".hidden").show();
	});

	//Check Edit Mature Boxes
	if ("<?php echo($pageResult['nude']); ?>" == "1") {
		$('#nude').attr('checked','checked')
	}
	if ("<?php echo($pageResult['drug']); ?>" == "1") {
		$('#drug').attr('checked','checked')
	}
	if ("<?php echo($pageResult['gore']); ?>" == "1") {
		$('#gore').attr('checked','checked')
	}
	if ("<?php echo($pageResult['sex']); ?>" == "1") {
		$('#sex').attr('checked','checked')
	}
});

$("#picture img").ready(function () {
	$("#picture").css("cursor","pointer");
	minimizeImage();
}); 

function reloadImg(id) {
	var obj = document.getElementById(id);
	var src = obj.src;
	var pos = src.indexOf('?');
	if (pos >= 0) {
		src = src.substr(0, pos);
	}
	var date = new Date();
	obj.src = src + '?v=' + date.getTime();
	return false;
}

function enlargeImage () {
	$("#picture").unbind('click');
	$("#picture").fadeOut("fast",function () {
		$("#fullview").html("");
		$("#picture").css("max-height","none");
		$("#picture").css("max-width","none");
		$("#picture").css("height","auto");
		$("#picture").fadeIn("slow");

		$("#picture").unbind('click');
		$("#picture").click(minimizeImage);
	});
}

function minimizeImage () {
	$("#picture").fadeOut("fast",function () {
		$("#fullview").html("<br><b> Click Image to Full View </b>");
		$("#picture").css("max-height","400px");
		$("#picture").css("max-width","100%");
		if ( $("#picture").height() >= 400) {
			$("#picture").css("height","400px");
		}
		$("#picture").css("height","auto !important");
		$("#picture").fadeIn("slow");
		$("#fullview").html("<br><b> Click Image to Full View </b>");

		$("#picture").unbind('click');
		$("#picture").click(enlargeImage);
	});
}

function favwatch (val){
	var favVal = $("#" + val).html();

	$("#" + val).html("Processing..");
	$.ajax({
		type: "GET",
		url: "/processFav.php",
		data: "action="+favVal+"&location=<?php echo($id); ?>&owner=<?php echo($pageOwner); ?>",
		success: function(reply) {
			if (favVal == "Favorite" && val == "fav") {
				$("#fav").html("De-Fav");
			} 
			else if (favVal == "De-Fav" && val == "fav"){
				$("#fav").html("Favorite");
			}
			else if (favVal == "Watch" && val == "watch") {
				$("#watch").html("De-Watch");
			} 
			else if (favVal == "De-Watch" && val == "watch") {
				$("#watch").html("Watch");
			}
		},
		error: function(){
			alert(val + " failed!");
		}
	});
}

function replaceLit(){
	$("#lit").html("<div class = 'newArt' style='width:65%;text-align:left;padding: 5px;min-height: 100px;'><b><u><center>" + <?php echo(json_encode($pageTitle)); ?> + "</center></b></u> " + <?php echo(json_encode($pageWrite)); ?> + "</div>");
}

function replaceFlash(){
	$("#flash").html("<object id = 'flashFile' width = '<? echo($pageWidth); ?>' height = '<? echo($pageHeight); ?>'><param name='movie' value='/arts/<? echo($pageloc); ?>.swf'><param name='wmode' value='opaque'><embed src='/arts/<? echo($pageloc); ?>.swf' width = '<? echo($pageWidth); ?>' height = '<? echo($pageHeight); ?>' wmode='opaque'></embed></object>");
}

function replaceAudio(){
	$("#audioControls").html("<div class='jp-audio' class='jp-playlist' style='margin-top:30px;margin-bottom:30px;'><div class='jp-type-single'><div id='jp_interface_1' class='jp-interface'><ul class='jp-controls'><li><a href='#' class='jp-play' tabindex='1'>play</a></li><li><a href='#' class='jp-pause' tabindex='1'>pause</a></li><li><a href='#' class='jp-stop' tabindex='1'>stop</a></li><li><a href='#' class='jp-mute' tabindex='1'>mute</a></li><li><a href='#' class='jp-unmute' tabindex='1'>unmute</a></li></ul><div class='jp-progress'><div class='jp-seek-bar'><div class='jp-play-bar'></div></div></div><div class='jp-volume-bar'><div class='jp-volume-bar-value'></div></div><div class='jp-current-time'></div><div class='jp-duration'></div></div><div id='jp_playlist_1' class='jp-playlist'><ul><li><?php echo($pageTitle);?></li></ul></div></div></div>");
	$("#audio").jPlayer({
		ready: function () {
			$(this).jPlayer("setMedia", {
				<?php echo($audioType); ?>: "/arts/<?php echo($pageloc); ?>",
			}).jPlayer("play");
		},
		ended: function (event) {
			$(this).jPlayer("play");
		},
		swfPath: "/extra",
		supplied: "<?php echo($audioType); ?>",
		volume: 1.0
	});
	//$("#audio").html("<audio src='/<?php echo($pageloc); ?>' controls='controls'>");
}

function deleteArt(){
	var answer = confirm("Are you sure you want to delete this artwork? ");
	if (answer == true) {
		$("#delete").html("Deleting...");
		$.ajax({
			type: "POST",
			url: "/delete.php",
			data: "loc=<?php echo($pageloc); ?>&user=<?php echo($cookiename); ?>&pass=<?php echo($cookiepass); ?>&type=<?php echo($pageType); ?>",
			success: function(reply) {
				window.location = "/<?php echo($userDir); ?>";
			},
			error: function(){
				alert("Deleting Failed!");
			}
		});
	}
}

function showFeatureArt(){
	$("#featureArt").show();
}
//]]>
</script>
<script language = "javascript" src = "/extra/comment.js"></script>

<head><title><?php echo($pageTitle); ?> :: Drawrawr</title>
<style type="text/css">
.show {
	height: 20px;
	font-size: 14px;
}
.show a {text-decoration:none}
.hidden {
	position:absolute;
	display:none;
	border:solid 2px;
}
</style>

<link rel="stylesheet" type="text/css" href="/css/main.css">
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">
<link href="/css/jplayer.css" rel="stylesheet" type="text/css">

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
		<div id = "content" style="width:100%;">
			<div class = "newArt">
				<center><span id = 'submission'>
				<?php
				if ($pageType == "image" || $pageType == "culinary" || $pageType == "craft"){
					echo("<div id = 'fullview'><img src = '/images/loading.gif' style = 'margin: 65px 0 30px 0;'></div><img src = '/arts/".($pageloc)."' id = 'picture' style = 'display: none; margin: 65px 0 30px 0;'>");
				} if ($pageType == "culinary"){
					if (strlen($pageWrite) > 0) {echo ("<h2>RECIPE</h2><p>".$pageWrite."</p>");}
				} elseif ($pageType == "literature"){
					echo("<div id = 'lit'><h2>Click thumbnail to view literature</h2><img src = '/arts/".$pageloc."' onmouseover=\"this.style.cursor='pointer'\" onclick = 'replaceLit()'></div>"); 
				} elseif ($pageType == "flash") {
					echo("<div id = 'flash'><h2>Click thumbnail to view animation</h2><img src = '/arts/".$pageloc.".thumb' onmouseover=\"this.style.cursor='pointer'\" onclick = 'replaceFlash()'></div>");
				} elseif ($pageType == "audio") {
					echo("<div id = 'audio'><h2>Click thumbnail to listen to audio</h2><img src = '/arts/".$pageloc.".thumb' onmouseover=\"this.style.cursor='pointer'\" onclick = 'replaceAudio()'></div><div id = 'audioControls'></div>");
				}
				?>
				</span>
			</div>
			<div id='prevnext' style='text-align:center;'>
			<?php
				if ($nextArt != False) {
					echo ("<span class='show'> <a href='/art/".DrawrawrID::encode($nextArt['id'])."'> <img src='/arts/".$nextArt['id'].".thumb' class='hidden' style='left:40%;'> <font size='6'>←</font><b>NEXT</b></a></span> ");
				}
				if ($nextArt != False && $prevArt != False) {echo "|";}
				if ($prevArt != False) {
					echo ("<span class='show'><a href='/art/".DrawrawrID::encode($prevArt['id'])."'> <b>PREV</b> <font size='6'>→</font> <img src='/arts/".$prevArt['id'].".thumb' class='hidden' style='right:40%;'> </a></span> ");
				}
			?>
			<br>
			</div>
			<div class = "newArt" style="width:65%;margin-left:16%;margin-right:16%;text-align:left;padding: 5px;min-height: 275px;">
				<div class='topRightContainer'>
					<div class='topRight'>
						<b>Submitted: </b><?php echo $pageDate; ?><br><b>By: </b><?php echo("<a href = '/".$ownerDir."'>".$pageOwner."</a>"); ?><br><b>Page Views: </b><?php echo($pageViews); ?><br><b>Faves: </b><?php echo($favNum); ?>
					</div>
					<?php if ($cookiename != "") { echo ("<div class='topRight'>"); } ?>
						<?php if (strtolower($pageOwner) != strtolower($cookiename) and $cookiename != "") { echo ('<font size = \'3\' onclick =\'favwatch("fav")\' onmouseover="this.style.cursor=\'pointer\'"><img src=\'/images/favico.png\' align=\'absmiddle\'> <b><u id = \'fav\'>'.$favText.'</u></b></font><br><br>');} ?>
						<?php if ($showWatch and $cookiename != "") { echo ('<font size = "3" onclick ="favwatch(\'watch\')" onmouseover="this.style.cursor=\'pointer\'"><img src=\'/images/searchico.png\' align=\'absmiddle\'> <b> <u id = \'watch\'>'.$watchText.'</u></b></font><br><br >');} ?>
						<?php if ($cookiename != "" && !$blocked) { echo('<font size = \'4\' id = "leaveComment" onmouseover="this.style.cursor=\'pointer\'" onclick="openComment()"><img src=\'/images/forumsico.png\' align=\'absmiddle\'> <b><u>Comment!</u></b></font>'); } ?>
						<?php 
						if (strtolower($pageOwner) == strtolower($cookiename) or $mod == True){
							echo ("<span id ='edit'><br><br><font size = '4' onclick = 'displayEdit()' onmouseover='this.style.cursor=\"pointer\"'><img src='/images/editico.png' align='absmiddle' s/> <b> <u>Edit</u></b></font></span>"); 
							echo ("<br><br><span onclick ='deleteArt();' style='cursor:pointer;'><font size = '4' color='red'> X </font><font size = '4'><b><u>Delete</u></b></font></span>"); 
						}
						if ($mod == True and !$featuredBool) {
							echo ("<br><br><span onmouseover='this.style.cursor=\"pointer\"' onclick=\"showFeatureArt()\"> <font size='4'><img src='/images/trophyico.png' align='absmiddle'> <b><u>Feature Art</u></b></font></span>");
						}
						
						 ?>
					<?php if ($cookiename != "") { echo ("</div>"); } ?>
				</div>
				<?php echo("<a href = '/".$ownerDir."' /><img src = '/avatars/".strtolower($pageOwner)."' width='75' height='75' align='left' style='margin-right: 10px;margin-bottom: 10px;' /></a>"); ?>
				<div id = 'title'><b><u><?php echo($pageTitle); ?></u></b></div>
				<div id = 'description' style="margin-left: 92px;"><p><?php echo($pageDesc); ?></p></div>
			</div>
			</center>
			</div>
			<?php
			//Featured Art Stuff
			if ($cResult['moderator']>=1) {
				echo "
				<div class = 'newArt' id='featureArt' style='text-align:center;display:none;'>
				<h3>Nominate this for Featured Art</h3>
				<p>Write a well thought out paragraph describing why this artwork deserves to be featured. Moderators should base their votes on the artwork's quality AND on the reasons you give for it to be featured. Avoid speaking in first person as you'll be speaking for DrawRawr. </p>
				<form action='/extra/mod/proposeFeatured.php' method='post'>
				<input type='hidden' name='location' value='".$pageID."'>
				<textarea name='message' cols=100 rows=6 style='width:90%;height:150px;'></textarea><br>
				<input type='submit' value=' Submit For Voting '>
				</form>
				</div>";
			}
			if ($featuredBool) {
				echo "<div class='newArt' style='text-align:center;'><img src='/images/trophyico.png'> <h3>Featured</h3>
				<p>".$featuredResult['reason']."</p></div>";
			}
			?>
			<div id = "comments">
			<div class = "newArt">
				<?php
				if ($blocked) {
					echo "You've been blocked by this user, and can't comment on their stuff! ";
				} else {
					echo '
				<center><h3 style="margin-top:0px;margin-bottom:0px;"><img src = "/images/forumsico.png"> Comments: </h2></center>
				<br>
				<div id = "leaveComment">
				<div id = "leaveCommentHTML" style = "display: none;">
					'.$commentForm.'
				</div>
				<div id="previewCommentDialog" title="PREVIEW" style="display:none;"></div>
				<div id = "loadComments">Loading Comments...</div>';
				}
				?>
			</div>
			</div>
		</div>
		<br>
		<div id="ads">
			<?php require ("ads.php"); ?>
		</div>
		<div id="footer">
			<?php require ("footer.php"); ?>
		</div>
</div>
<div id = 'editFormDiv' title = "Edit Submission" style = "display: none;text-align:center;font-size: 14px;">
	<form enctype='multipart/form-data' id='editForm' action='/processEditArt.php' method='post'>
	<input type ='hidden' name = 'location' value = "<?php echo($pageloc); ?>">
	<input type ='hidden' name = 'type' value = "<?php echo($pageType); ?>">
	<?php
	if ($pageType == "literature") {
		echo("<br><b>Writing: </b><br><textarea cols='70' rows='20' name = 'writing'> ".$pageResult["writing"]."</textarea><br><br><b>Thumbnail icon: </b><input name='thumbnail' type='file' ><br><br>");
	} elseif ($pageType == "image" || $pageType == "culinary" || $pageType == "craft") {
		echo("<br><b>Re-Submit Artwork: </b><input name='artwork' type='file' ><br>or<br><a href = '/cropThumb.php?location=".$pageloc."'><b>Re-Crop Thumbnail</b></a><br><br>");
	} elseif ($pageType == "flash") {
		echo("<br><b>Re-Submit Animation: </b><input name='artwork' type='file' ><br><br><b>Thumbnail icon: </b><input name='flashthumb' type='file' ><br><br>");
	} elseif ($pageType == "audio") {
		echo("<br><b>Re-Submit Audio: </b><input name='artwork' type='file' ><br><br><b>Thumbnail icon: </b><input name='flashthumb' type='file' ><br><br>");
	}
	?>
	<b>Title: </b>
	<input type = 'text' name = 'title' maxlength = '50' value = "<?php echo($pageTitle); ?>"><br><br>
	<b>Description: </b><br>
	<textarea cols='42' rows='12' name = 'description'><?php echo($pageResult["description"]); ?></textarea><br><br>
	<br><b>Contains: </b><br> 
	<div style='text-align:left;width:35%;margin-left:30%;margin-right:30%;'>
	<input type='checkbox' name='nude' id='nude' value='1'> Nudity <br>
	<input type='checkbox' name='drug' id='drug' value='1'> Drug Use <br>
	<input type='checkbox' name='gore' id='gore' value='1'> Gore or Strong Violence <br>
	<input type='checkbox' name='sex'  id='sex'  value='1'> Sexual Themes <br><br>
	</div>
	<input type = 'submit' value = 'Submit!' id = 'editSubmit'></center><br></form>
</div>

</body>
</html>
