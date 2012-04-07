<?php

require_once("extra/library.php");
require_once("extra/commentForm.php");
require '../lib/session.php';
if(!defined('URL')) require '../lib/classes.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

$username = bbsclean($_GET['user']);
$range    = bbsclean($_GET['range']);

$result = mysql_query("select * from updates where owner = '".$username."' and type = 'comment' order by id desc limit 0,".$range);
$numComments = mysql_num_rows(mysql_query("select * from updates where owner = '".$username."' and type = 'comment'"));

echo("
<div id='updatesCommentArea'>
<script language = 'javascript'>
var cookiename  = ".json_encode($_SESSION['user']['data']['username']).";
var cookiepass  = ".json_encode($_SESSION['user']['data']['password']).";
</script>
<script language = 'javascript' src = '/extra/comment.js'></script>");

while($row = mysql_fetch_array($result)) {
	$comment = mysql_fetch_array(mysql_query("select * from comments where id = '".$row['location']."'"));
	echo("
	<div id = 'update".$row['id']."'>
		<div style='float:right;margin-right: 5px;'>
			<font size = 2 color = 'red' style = 'float:right; margin: 5px; border:solid 1px; padding: 0px 2px 0 2px;cursor:pointer;' onclick='deleteUpdate(\"".$row['id']."\",\"comment\")'>x</font>
		</div>
		<div id = 'loadComments".$row['location']."'><center><img src = '/images/loading.gif'></center></div>
		<div id = 'leaveCommentHTML' style = 'display: none;'>
		".$commentForm."
		</div>
		<div id='previewCommentDialog' title='PREVIEW' style='display:none;'></div>
		<input type='hidden' id='pageLoc".$row['location']."' value='".$comment['location']."'>
		<input type='hidden' id='commentType".$row['location']."' value='".$comment['type']."'>
		<input type='hidden' id='updateID".$row['location']."' value='".$row['id']."'>
		<input type='hidden' id='updateBool' value='true'>
	</div>
	<script language = 'javascript'>
	$(document).ready(function () {
		getUpdate('".$row['location']."');
	});
	</script>
	");
}

if ($numComments > $range) {echo("<br><br><center><span onclick='moreComments(\"".($range*2)."\")' style='cursor:hand;'><b> ↓ MORE COMMENTS ↓ </b></span></center></div>");}

?>
