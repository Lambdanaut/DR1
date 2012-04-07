<?php
require '../library.php';
require '../commentForm.php';
require '../../configuration.php';
require '../lib/session.php';
require '../lib/database.php';

if(!$_SESSION['user']['authenticated']) header("Location: /");

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];
$userdir    = strtolower(str_replace(" ",".",$cookiename));

$updates = mysql_query("select * from updates where owner = '".$cookiename."' and type <> 'inbox'");
if (mysql_affected_rows() == 0) {
	$noUpdates    = "<li><a href='#noUpdates'> No Updates! </a></li>";
	$noUpdatesDiv = "<div id = 'noUpdates' style = 'text-align:center;position:relative;padding-top:10%;padding-bottom:10%'>
				<b>No Updates!</b>
			</div>";
}

$db = new DrawrawrDatabase();
$db->query("UPDATE updates SET viewed='1' WHERE owner= '{$_SESSION['user']['data']['username']}' AND viewed='0'");
$db->close();


require_once ("prehtmlincludes.php");

?>
<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = "javascript" src = "/extra/jquery-ui.min.js"></script>
<script language = "javascript" src = "/extra/captionImages.js"></script>
<script language = "javascript">

$(function() {
	$("#tabs").tabs({
		select: function (x,y) {disableTab()}
	});
	$("#aside").tabs();

	$("#writeNewButton").click(function() {writeMessage();});
	$("#messageSubmit").button();
	$("#messageSubmit").click(postMessage);
	$("#deleteAll").click(deleteAll);
	$("#deleteAllInbox").click(deleteAllInbox);

	refreshTabs("news");
	refreshTabs("art");
	refreshTabs("journal");
	refreshTabs("comment");
	refreshTabs("fav");
	refreshTabs("watch");
});
function writeMessage(user,title) {
	$("#writeMessage").dialog({title:"New Message",width:725});
	if (user != undefined) {
		$("#messageAddress").val(user);
	}
	if (title != undefined) {
		$("#messageTitle").val(title);
	}
}
function postMessage() {
	title   = escape($("#messageTitle").val());
	address = escape($("#messageAddress").val());
	area    = escape($("#messageArea").val());

	$.ajax({
		type: "POST",
		url: "/getMessage.php",
		data: "posting=true&user=<? echo($cookiename); ?>&pass=<? echo($cookiepass); ?>&title=" + title + "&address=" + address + "&area=" + area,
		success: function(reply) {
			$("#writeMessage").dialog("close");
			alert("Message Sent! ");
		}
	});
	$("#messageTitle").val("");
	$("#messageAddress").val("");
	$("#messageArea").val("");
}
function getMessage(id) {
	//Title
	$.ajax({
		type: "POST",
		url: "/getMessage.php",
		data: "id=" + id + "&titleOnly=true",
		success: function(reply) {
			titleText = reply;
		}
	});
	//Message and Display
	$.ajax({
		type: "POST",
		url: "/getMessage.php",
		data: "id=" + id,
		success: function(reply) {
			$("#displayMessage").dialog({title:titleText,width:700,height:400});
			$("#displayMessage").html(reply);
		}
	});
}
function disableTab() {
	for (var x = 0; x < 7; x++) {
		switch (x) {
			case 0:
				var index = "news";
				break;
			case 1:
				var index = "art";
				break;
			case 2:
				var index = "journal";
				break;
			case 3:
				var index = "comment";
				break;
			case 4:
				var index = "fav";
				break;
			case 5:
				var index = "watch";
				break;
			default : var index = "watch";
		}
		if ($("#" + index + "Num").html() == "0") {
			$("#tabs").tabs("disable",x);
		}
	}
}

function refreshTabs(type) {
	$.ajax({
		type: "POST",
		url: "/extra/updates/getUpdateCount.php",
		data: "user=" + <? echo(json_encode($cookiename)); ?> + "&type=" + type,
		success: function(reply) {
			$("#" + type + "Num").html(reply);
			disableTab();
		},
	});
}

function moreComments(range) {
	$.ajax({
		type: "GET",
		url: "/extra/updates/comment.php",
		data: "user=<? echo($cookiename); ?>&range=" + range,
		success: function(reply) {
			$("#updatesCommentArea").html(reply);
		},
	});
}

function deleteUpdate(id,type) {
	var toDelete = true;
	if (type=="inbox") {
		toDelete = confirm("Are you sure you want to delete this? ");
	}
	if (toDelete == true) {
		$.ajax({
			type: "POST",
			url: "/extra/updates/deleteUpdate.php",
			data: "user=" + <? echo(json_encode($cookiename)); ?> + "&pass=" + <? echo(json_encode($cookiepass)); ?> + "&id=" + id + "&type=" + type,
			success: function(reply) {
				$("#update" + id).fadeOut(100,function () {
					$("#update" + id).html("");
					refreshTabs(type);
				});
			},
			error: function(){
				alert("Deleting update failed! ");
			}
		});
	}
}
function deleteAll() {
	if (confirm("Are you sure you want to delete EVERYTHING? ") ) {
		$.ajax({
			type: "POST",
			url: "/extra/updates/deleteAll.php",
			data: "user=" + <? echo(json_encode($cookiename)); ?> + "&pass=" + <? echo(json_encode($cookiepass)); ?>,
			success: function(reply) {
				location.reload(true);
			},
			error: function(){
				alert("Deleting updates failed! ");
			}
		});
	}
}
function deleteSection() {
	if (confirm("Are you sure you want to delete EVERYTHING? ") ) {
		$.ajax({
			type: "POST",
			url: "/extra/updates/deleteAll.php",
			data: "user=" + <? echo(json_encode($cookiename)); ?> + "&pass=" + <? echo(json_encode($cookiepass)); ?>,
			success: function(reply) {
				location.reload(true);
			},
			error: function(){
				alert("Deleting updates failed! ");
			}
		});
	}
}
function deleteAllInbox() {
	if (confirm("Are you sure you want to delete all of your inbox and outbox messages? ") ) {
		$.ajax({
			type: "POST",
			url: "/extra/updates/deleteAllInbox.php",
			data: "user=" + <? echo(json_encode($cookiename)); ?> + "&pass=" + <? echo(json_encode($cookiepass)); ?>,
			success: function(reply) {
				location.reload(true);
			},
			error: function(){
				alert("Deleting inbox failed! ");
			}
		});
	}
}
</script>
<script language = "javascript" src = "/extra/comment.js"></script>
<head><title>Updates</title>

<link rel="stylesheet" type="text/css" href="/css/main.css" />
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">

<style type="text/css">
.tab {
	height: 20px;
	font-size: 14px;
}
.tab img {
	vertical-align:bottom;
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
			<?php if(isset($loginPrint)) echo($loginPrint); ?>
                </div>
	</div>
	<div id="navigation">
		<ul>
			<?php require ("nav.php"); ?>
		</ul>
	</div>
	<div id="ads">
		<?php require ("ads2.php"); ?>
	</div>
	<div id="content-container">
		<div id="content">
			<div id="tabs" style = "min-height: 327px;font-size: 15px; overflow: hidden;">
				<ul>
					<span id="deleteAll" style="float:right;font-size:12px;margin:7px;color:#513727;cursor:pointer;"> Delete All <img src='/images/garbageico.png' align="absbottom"></span>
					<?php if(isset($noUpdates)) echo($noUpdates); ?>
					<li><a href="/extra/updates/news.php?user=<? echo($cookiename); ?>" class="tab"><font style="color:red;font-size:20px;">!!!</font> <span id="newsNum"></span> </a></li>
					<li><a href="/extra/updates/art.php?user=<? echo($cookiename); ?>" class="tab"><img src='/images/galleryico.png'> <span id="artNum"></span> </a></li>
					<li><a href="/extra/updates/journal.php?user=<? echo($cookiename); ?>" class="tab"><img src='/images/journalico.png'> <span id="journalNum"></span> </a></li>
					<li><a href="/extra/updates/comment.php?user=<? echo($cookiename); ?>&range=10" class="tab"><img src='/images/forumsico.png'> <span id="commentNum"></span> </a></li>
					<li><a href="/extra/updates/fav.php?user=<? echo($cookiename); ?>" class="tab"><img src='/images/favico.png'> <span id="favNum"></span> </a></li>
					<li><a href="/extra/updates/watch.php?user=<? echo($cookiename); ?>" class="tab"><img src='/images/searchico.png'> <span id="watchNum"></span> </a></li>
				</ul>
				<?php if(isset($noUpdatesDiv)) echo($noUpdatesDiv); ?>
				</div>

		</div>
		<div id = "aside" style='width:23%;'>

			<ul>
			<li><a href="#inbox" class="tab"> INBOX </a></li>
			<li><a href="#outbox" class="tab"> OUTBOX </a></li>
			</ul>

			<div id="inbox" style="padding:0px;">
				<div class = "newart" style="height:235px;overflow:auto;">
					<?php require ("inbox.php"); ?>
				</div>
			</div>
			<div id="outbox" style="padding:0px;">
				<div class = "newart" style="height:235px;overflow:auto;">
					<?php require ("outbox.php"); ?>
				</div>
			</div>
			<div id='displayMessage' style='display:none;'></div>
			<div id='writeMessage' style='display:none;'>
				<b>Title: </b> <input id = 'messageTitle' type = 'text' style='width:200px' maxlength='50'>
				<b>To: </b> <input id = 'messageAddress' type = 'text' style='width:200px' maxlength='30'>
				<span style="border-bottom:solid 1px;width:100%;"></span>
				<p>
				<textarea id = 'messageArea' style='width:100%;' rows = '10' cols = '65'></textarea>
				</p>
				<span style='float:center;'>
					<input id='messageSubmit' type='button' value='Send'>
				</span>
			</div>
			<div class = "newart" style='font-size:12px;'>
				<span id='deleteAllInbox' style='float:right;cursor:pointer;'> <u><b>Dump Inbox</b></u> <img src='/images/dottico.png'> </span>
				<span id='writeNewButton' style='cursor:pointer;'><img src='/images/dottico.png'> <u><b>Write New</b></u> <br></span>
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
