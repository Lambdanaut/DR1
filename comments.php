<?php
require_once("extra/library.php");
if(!class_exists('URL')) require 'extra/lib/classes.php';

$id          = bbsclean($_POST['id']);
$owner       = bbsclean($_POST['owner']);
$type        = bbsclean($_POST['type']);
$location    = bbsclean($_POST['location']);
$range       = bbsclean($_POST['range']);
$ammount     = bbsclean($_POST['ammount']);
$loggedIn    = bbsclean($_POST['loggedIn']);
$getReplies  = bbsclean($_POST['getReplies']);
$cookiename  = bbsclean($_POST['cookiename']);
$originDepth = bbsclean($_POST['originDepth']);

$moderator   = mysql_fetch_array(mysql_query("select moderator from user_data where username = '".$cookiename."'"));
$moderator   = $moderator['moderator'];

if ($originDepth != "") {
	$originDepth = intval($originDepth);
} else {$originDepth = 0;}

if ($range == "" or (!isset($range)) ) {
	$range = 0;
}
if ($ammount == "" or (!isset($ammount)) ) {
	$ammount = $range + 5;
}

function makeNewerCommentsButton($range,$ammount,$location) {
	if ($range != 0) {
		return "<div style = 'float: left;' onmouseover='this.style.cursor=\"pointer\"' onclick = 'getComments(\"".($range - $ammount)."\",\"".$ammount."\",true)'> <font size = '5'>←</font> <u><b>Newer Comments</b></u></div>";
	} else {return "";}
}

function makeOlderCommentsButton($range,$ammount,$location) {
	$commentsResult = mysql_query("select * from comments where location = '".$location."' and parent = '0' limit ".($range + $ammount).",".($ammount * 2)) or die ("Mysql error lol!");
	if (mysql_affected_rows() != 0) {
		return "<div style = 'float: right;' onmouseover='this.style.cursor=\"pointer\"' onclick = 'getComments(\"".($range + $ammount)."\",\"".$ammount."\",true,true)'><u><b>Older Comments</b></u> <font size = '5'>→</font> </div>";
	} else {return "";}
}

function makeDeleteButton($id,$owner,$cookiename,$location,$type,$moderator) {
	$pageOwner = getOwner($type,$location);
	if ($owner == $cookiename or $location == $cookiename or $pageOwner == $cookiename or $moderator >= 4) {
		return "<font size = 2 color = 'red' style = 'float:right; margin: 5px; border:solid 1px; padding: 1px 2px 0 2px;cursor: pointer' onclick = 'deleteComment(".$id.")'>X</font>";
	} else {return "";}
}

function makeReplyButton($id,$loggedIn) {
	if ($loggedIn == True) {
		return "<u style = 'position: relative; float: right; margin: 0 15px 0 0;' onmouseover='this.style.cursor=\"pointer\"' onclick = 'openComment(\"".$id."\")'><b><img src = '/images/forumsico.png'> Reply</b></u>";
	} else {
		return "";
	}
}

function makeComment($id,$username,$timestamp,$text,$cookiename,$location,$loggedIn,$userDir,$children,$depth,$type,$moderator) {
	$fontResult = mysql_fetch_array(mysql_query("select bold,italic,underlined from user_data where username = '".$username."'"));
	$font1="";$font2="";
	if ($fontResult['bold'] == '1') {$font1=$font1."<b>";$font2=$font2."</b>";}
	if ($fontResult['italic'] == '1') {$font1=$font1."<i>";$font2=$font2."</i>";}
	if ($fontResult['underlined'] == '1') {$font1=$font1."<u>";$font2=$font2."</u>";}
	return "<div id = 'comment".$id."' class = 'comment' style='margin-left:".($depth * 25)."px;font-size:14px;'>".makeDeleteButton($id,$username,$cookiename,$location,$type,$moderator).makeReplyButton($id,$loggedIn)."<a href = '/".$userDir."'><img src = '/avatars/".strtolower($userDir)."' class = 'usericon' title='".$username."'></a> <font size='4' style='margin-left:10px;'>".makeModIcon($username)." <a href = '/".$userDir."'><b>".$username."</b></font></a> - <font size = '1'>(".timeagoFormat($timestamp).")</font> ".$children."<p>".$font1.tobbs($text).$font2."</p></div><div id = 'replies".$id."'></div>";
}

if ($id == "" or (!isset($id)) ) {
	//Show newest comments with view reply buttons
	$toPrint = "";
	$result = mysql_query("select * from comments where type = '".$type."' and location = '".$location."' and parent = '0' order by id desc limit ".$range.",".$ammount);
	if (mysql_affected_rows() != 0) {
		while($row = mysql_fetch_array($result)) {
			$children = count(getChildren($row['id']));
			if ($children != 0) {$children = "<b><u><font size = 1 id = 'replyButton".$row['id']."' onmouseover='this.style.cursor=\"pointer\"' onclick = 'getReplies(\"".$row['id']."\")'><img src = '/images/dottico.png'> View Replies(".$children.")</font></u></b>";} else {$children = "";}
			$userDir = $row['username'];
			$toPrint = $toPrint.makeComment($row['id'],$row['username'],$row['timestamp'],$row['text'],$cookiename,$location,$loggedIn,$userDir,$children,0,$type,$moderator);
		}
		$toPrint = $toPrint.makeOlderCommentsButton($range,$ammount,$location).makeNewerCommentsButton($range,$ammount,$location);
	} else {
		$toPrint = "<br><br><center><b> No Comments </b></center>";
	}
} elseif ($getReplies != "") {
	//Show replies to comment
	$repliesShown = array ();
	$children = getChildren($id);

	foreach($children as $child) {
		$depth = getDepth($child) - $originDepth;
		if ($depth < 15) {
			$row = mysql_fetch_array(mysql_query("select * from comments where id = '".$child."'"));
			$userDir = $row['username'];

			$toPrint = $toPrint.makeComment($row['id'],$row['username'],$row['timestamp'],$row['text'],$cookiename,$location,$loggedIn,$userDir,"",$depth,$type,$moderator);
			$parent = $child;
		}
		//If the depth is 15, print out a "view more replies" button that opens up a dialog with more replies
		if ($depth == 15 and (!in_array($parent,$repliesShown) ) ) {
			$toPrint = $toPrint."<div id='moreRepliesButton".$parent."' style='cursor:pointer;font-size:17px;margin-bottom:25px;margin-left:".($depth * 25)."px' onclick='moreReplies(\"".$parent."\",\"".($depth + $originDepth)."\")'> [<b>View more replies</b>] </div>
			<div id = 'moreReplies".$parent."' class='moreRepliesDialog' style='display: none;' title='More Replies :: Page ".(1 + $originDepth / 15)."'>
				<div class = 'viewMoreReplies' style='float:center;text-align:left;overflow: auto;width:100%;height:100%;'>
					
				</div>
			</div>";
			array_push($repliesShown,$parent);
		}
	}
} else {
	//Show comment in updates
	$result = mysql_fetch_array(mysql_query("select * from comments where id = '".$id."'"));
	$parent = mysql_fetch_array(mysql_query("select * from comments where id = '".$result['parent']."'"));

	if ($result['type'] == "journal") {
		$journal = mysql_fetch_array(mysql_query("select * from journals where id = '".$result['location']."'"));
		$loc = " <img src='/images/journalico.png'> <a href='/viewJournals.php?owner=".$journal['owner']."&id=".$journal['id']."'>".$journal['title']."</a>";
	}
	if ($result['type'] == "art") {
		$art = mysql_fetch_array(mysql_query("select * from arts where id = '".$result['location']."'"));
		$loc = " <img src='/images/galleryico.png'> <a href = '/arts/".$art['id'].".php'>".$art['title']."</a>";
	}
	if ($result['type'] == "userpage") {
		$user = mysql_fetch_array(mysql_query("select username from user_data where username = '".$result['location']."'"));
		$userDir = $user['username'];
		$loc = " <img src='/images/dottico.png'> <a href='/".$userDir."'>".$user['username']."</a>'s userpage" ;
	}
	if ($result['parent'] != "0") {
		$parent = mysql_fetch_array(mysql_query("select * from comments where id = '".$result['parent']."'"));
		$parentButton = " <img src = '/images/dottico.png'> <span onclick='showParent(\"".$id."\")' style='cursor:pointer;'><u><b>Show Parent</b></u></span>";
	}
	$fontResult = mysql_fetch_array(mysql_query("select bold,italic,underlined from user_data where username = '".$result['username']."'"));
	$font1="";$font2="";
	if ($fontResult['bold'] == '1') {$font1=$font1."<b>";$font2=$font2."</b>";}
	if ($fontResult['italic'] == '1') {$font1=$font1."<i>";$font2=$font2."</i>";}
	if ($fontResult['underlined'] == '1') {$font1=$font1."<u>";$font2=$font2."</u>";}

	$userDir = $result['username'];
	$toPrint = "<div id = 'comment".$id."' class = 'comment'>".makeReplyButton($id,True)."<div style='display:block;float:right; margin: 7px 5px;'>".$parentButton."</div><a href = '/".$userDir."'><img src = '/avatars/".strtolower($userDir)."' class = 'usericon' title='".$result['username']."'></a> <font size='4' style='margin-left:10px;'>".makeModIcon($result['username'])." <a href = '/".$userDir."'><b>".$result['username']."</b></font></a> - <font size = '1'>(".timeagoFormat($result['timestamp']).")</font><span style='white-space: nowrap; overflow: hidden;'><font size='1'> ".$loc." </font></span> <p>".$font1.tobbs($result['text']).$font2."</p></div>
	<div id='parentView".$id."' style='display:none;font-size:12px;'>".tobbs($parent['text'])."</div><div id = 'replies".$id."'></div>";

}

echo($toPrint);

mysql_close($con);

?>
