<?php

$con = con();

function con() {
	$con  = mysql_connect('localhost', 'root', 'google123'); 

	mysql_select_db("drawrawr", $con) or die(mysql_error());
	return $con;
}

//Returns an array of all the ids that are the children of this.
function getChildren($id) {
	$toCheck  = array ();
	$children = array ();
	while (True) {
		$childResult = mysql_query("select id from comments where parent = '".$id."' order by id desc");
		while ($child = mysql_fetch_array($childResult)) {
			array_push($toCheck,$child['id']);
		}
		if (empty($toCheck)) {
			break;
		}
		$id = array_pop($toCheck);
		array_push($children,$id);
	}
	return $children;
}
//Returns an array of the direct children of the given id. 
function directChildren($id) {
	$childResult = mysql_query("select id from comments where parent = '".$id."'");
	$children = array ();
	while($child = mysql_fetch_array($childResult)) {
		array_push($children, $child['id']);
	}
	return $children;
}
//Takes a list of ids and delete them from the comments
function deleteFromArray($list){
	foreach($list as $id) {
		mysql_query ("delete from comments where id = '".$id."'");
		mysql_query ("delete from updates where location = '".$id."' and type = 'comment'");
	}
}
//Gets the depth of the comment
function getDepth($curid) {
	$idResult = mysql_fetch_array(mysql_query("select parent from comments where id = '".$curid."'"));

	if ($idResult['parent'] == "0") {
		return 0;
	} else {
		$parentResult = mysql_fetch_array(mysql_query("select id from comments where id = '".$idResult['parent']."'"));
		return 1 + getDepth($parentResult['id']);
	}
}
//Get the owner of the art/journal that the comment is being posted on. 
function getOwner($type,$location) {
	switch ($type) {
		case "art":
			$result = mysql_fetch_array(mysql_query("select owner from arts where location = '".$location."'"));
			return $result['owner'];
			break;
		case "journal":
			$result = mysql_fetch_array(mysql_query("select owner from journals where id = '".$location."'"));
			return $result['owner'];
			break;
		case "userpage":
			return $location;
			break;
	}
}
function matureHideCheck($artNude,$artDrug,$artGore,$artSex,$nudeFilter,$drugFilter,$goreFilter,$sexFilter,$matureFilter) {
	//If hiding mature content is on, filter by content. 
	if ($matureFilter == '2') {
		if ($artNude == $nudeFilter and $artNude == '1') {
			return False;
		}
		elseif ($artDrug == $drugFilter and $artDrug == '1') {
			return False;
		}
		elseif ($artGore == $goreFilter and $artGore == '1') {
			return False;
		}
		elseif ($artSex == $sexFilter and $artSex == '1') {
			return False;
		}
		else {return True;}
	} else {return True;}
}

function matureMarkCheck($matureFilter){
	if ($matureFilter == '1' or $matureFilter == '') {
		return True;
	} else {return False;}
}

//Checks if user is blocked from page.
function blockCheck($owner,$blockedUser) {
	mysql_query("select id from blockedUsers where owner=\"".$owner."\" and blockedUser=\"".$blockedUser."\"");
	if (mysql_affected_rows() != 0) {
		return True;
	} else {return False;}
}

function makeModIcon($owner) {
	$result = mysql_fetch_array(mysql_query("select moderator from user_data where username = '".$owner."'"));
if ($result['moderator'] == 1) {
		return "<img src = '/images/reddot.png'> ";
	}
	elseif ($result['moderator'] == 2) {
		return "<img src = '/images/golddot.png'> ";
	}
	elseif ($result['moderator'] == 3) {
		return "<img src = '/images/greendot.png'> ";
	}
	elseif ($result['moderator'] == 4) {
		return "<img src = '/images/bluedot.png'> ";
	}
	elseif ($result['moderator'] == 5) {
		return "<img src = '/images/darkbluedot.png'> ";
	}
	elseif ($result['moderator'] >= 6) {
		return "<img src = '/images/purpledot.png'> ";
	}
	else {return "";}
}

function makeModIconFromInt($int) {
if ($int == 1) {
		return "<img src = '/images/reddot.png'> ";
	}
	elseif ($int == 2) {
		return "<img src = '/images/golddot.png'> ";
	}
	elseif ($int == 3) {
		return "<img src = '/images/greendot.png'> ";
	}
	elseif ($int == 4) {
		return "<img src = '/images/bluedot.png'> ";
	}
	elseif ($int == 5) {
		return "<img src = '/images/darkbluedot.png'> ";
	}
	elseif ($int >= 6) {
		return "<img src = '/images/purpledot.png'> ";
	}
	else {return "";}
}

function tobbs($replace)
{
$bbcode = array(
//Formatting
"'\[center\](.*?)\[/center\]'is" => "<center>\\1</center>",
"'\[clearfix\](.*?)\[/clearfix\]'is" => "<div class='clearfix'>\\1</div>",
"'\[floatleft\](.*?)\[/floatleft\]'is" => "<div style='float:left;'>\\1</div>",
"'\[floatright\](.*?)\[/floatright\]'is" => "<div style='float:right;'>\\1</div>",
"'\[left\](.*?)\[/left\]'is" => "<div style='text-align: left;'>\\1</div>",
"'\[right\](.*?)\[/right\]'is" => "<div style='text-align: right;'>\\1</div>",
"'\[pre\](.*?)\[/pre\]'is" => "<pre>\\1</pre>",
"'\[b\](.*?)\[/b\]'is" => "<b>\\1</b>",
"'\[quote\](.*?)\[/quote\]'is" => "<div class='top'><b>Quote:</b><hr>\\1</div>",
"'\[spoiler\](.*?)\[/spoiler\]'is" => "<span style='color:#000000;background-color:#000000;overflow:auto;'>\\1</span>",
"'\[pre\](.*?)\[/pre\]'is" => "<pre>\\1</pre>",
"'\[i\](.*?)\[/i\]'is" => "<i>\\1</i>",
"'\[u\](.*?)\[/u\]'is" => "<u>\\1</u>",
"'\[s\](.*?)\[/s\]'is" => "<del>\\1</del>",
"'\[H1\](.*?)\[/H1\]'is" => "<h2>\\1</h2>",
"'\[H2\](.*?)\[/H2\]'is" => "<h3>\\1</h3>",
"'\[H3\](.*?)\[/H3\]'is" => "<center><b><u>\\1</u></b></center>",
"'\[move\](.*?)\[/move\]'is" => "<marquee>\\1</marquee>",
"'\[url\](.*?)\[/url\]'is" => "<a href='\\1'>\\1</a>",
"'\[xxxxcust\](.*?)\[/xxxxcust\]'is" => "<script type='text/javascript'>function htmlDecode(input){var e = document.createElement('div');e.innerHTML = input;return e.childNodes.length === 0 ? \"\" : e.childNodes[0].nodeValue;};document.write(htmlDecode('\\1'))</script>",
"'\[xxxxcustjs\](.*?)\[/xxxxcustjs\]'is" => "<script type='text/javascript'>document.ready(function (){ \\1 });</script>",
"'\[url=(.*?)\](.*?)\[/url\]'is" => "<a href=\"\\1\">\\2</a>",
"'\[email\](.*?)\[/email\]'is" => "<a href='mailto: \\1'>\\1</a>",
"'\[size=([1-9]?[0-9])\](.*?)\[/size\]'is" => "<span style='font-size: \\1px;'>\\2</span>",
"'\[fuckyou\]'" => "<span style='font-size: 200px;'>ilu!</span><br/><br/>",
"'\[font=(.*?)\](.*?)\[/font\]'is" => "<span style='font-family: \\1;'>\\2</span>",
"'\[color=(.*?)\](.*?)\[/color\]'is" => "<span style=\"color:\\1;\">\\2</span>",
"'\[img\](.*?)\[/img\]'is" => "<img class=\"commentImage\" border=\"0\" src=\"\\1\">",
"'\[img=(.*?)\]'" => "<img class=\"commentImage\" border=\"0\" src=\"\\1\">",
"'\[imgleft=(.*?)\]'" => "<img class=\"commentImage\" border=\"0\" style=\"float:left;\" src=\"\\1\">",
"'\[imgright=(.*?)\]'" => "<img class=\"commentImage\" border=\"0\" style=\"float:right;\" src=\"\\1\">",
"'\[img=(.*?) size=(.*?)\]'" => "<img class=\"commentImage\" border=\"0\" width=\\2 src=\"\\1\">",
"'\[user\](.*?)\[/user\]'is" => "<a href = '/\\1'><img width = \"75\" height = \"75\" border=\"0\" src=\"/avatars/\\1\"></a>",
"'\[user=(.*?)\]'is" => "<a href = '/\\1'><img width = \"75\" height = \"75\" border=\"0\" src=\"/avatars/\\1\"></a>",
"'\[u=(.*?)\]'is" => "<a href = '/\\1'><img width = \"75\" height = \"75\" border=\"0\" src=\"/avatars/\\1\"></a>",
"'\[tinyuser\](.*?)\[/tinyuser\]'is" => "<a href = '/\\1'><img width = \"35\" height = \"35\" border=\"0\" src=\"/avatars/\\1\"></a>",
"'\[tinyuser=(.*?)\]'is" => "<a href = '/\\1'><img width = \"35\" height = \"35\" border=\"0\" src=\"/avatars/\\1\"></a>",
"'\[tu=(.*?)\]'is" => "<a href = '/\\1'><img width = \"35\" height = \"35\" border=\"0\" src=\"/avatars/\\1\"></a>",
"'\[youtube=http://youtu.be/(.*?)\]'is" => "<iframe width='560' height='349' src='http://www.youtube.com/embed/\\1' frameborder='0' allowfullscreen></iframe>",
//Emoticons
"'\[;_;\]'" => "<img src = '/images/emote/crying.png'>",
"'\[1sttrophy\]'" => "<img src = '/images/emote/1sttrophy.png'>",
"'\[2ndtrophy\]'" => "<img src = '/images/emote/2ndtrophy.png'>",
"'\[3rdtrophy\]'" => "<img src = '/images/emote/3rdtrophy.png'>",
"'\[angel\]'" => "<img src = '/images/emote/angel.png'>",
"'\[barf\]'" => "<img src = '/images/emote/barf.gif'>",
"'\[blackheart\]'" => "<img src = '/images/emote/blackheart.png'>",
"'\[blackstar\]'" => "<img src = '/images/emote/blackstar.png'>",
"'\[blueheart\]'" => "<img src = '/images/emote/blueheart.png'>",
"'\[blush\]'" => "<img src = '/images/emote/blush.png'>",
"'\[toast\]'" => "<img src = '/images/emote/toast.png'>",
"'\[bunnyears\]'" => "<img src = '/images/emote/bunnyears.png'>",
"'\[cake\]'" => "<img src = '/images/emote/cake.png'>",
"'\[cool\]'" => "<img src = '/images/emote/cool.gif'>",
"'\[classy\]'" => "<img src = '/images/emote/classy.png'>",
"'\[crown\]'" => "<img src = '/images/emote/crown.png'>",
"'\[dead\]'" => "<img src = '/images/emote/dead.png'>",
"'\[deal\]'" => "<img src = '/images/emote/deal.gif'>",
"'\[devil\]'" => "<img src = '/images/emote/devil.png'>",
"'\[disappoint\]'" => "<img src = '/images/emote/disappoint.png'>",
"'\[dot\]'" => "<img src = '/images/emote/dottico.png'>",
"'\[dotdotdot\]'" => "<img src = '/images/emote/dotdotdot.png'>",
"'\[durrr\]'" => "<img src = '/images/emote/durrr.png'>",
"'\[dunce\]'" => "<img src = '/images/emote/dunce.png'>",
"'\[duncecap\]'" => "<img src = '/images/emote/duncecap.png'>",
"'\[exclamation\]'" => "<img src = '/images/emote/exclamation.png'>",
"'\[fu\]'" => "<img src = '/images/emote/fu.gif'>",
"'\[giggle\]'" => "<img src = '/images/emote/giggle.gif'>",
"'\[greenheart\]'" => "<img src = '/images/emote/greenheart.png'>",
"'\[gunflip\]'" => "<img src = '/images/emote/gunflip.png'>",
"'\[gun\]'" => "<img src = '/images/emote/gun.png'>",
"'\[gunyellow\]'" => "<img src = '/images/emote/gunyellow.png'>",
"'\[gunred\]'" => "<img src = '/images/emote/gunred.png'>",
"'\[gunpurple\]'" => "<img src = '/images/emote/gunpurple.png'>",
"'\[gunorange\]'" => "<img src = '/images/emote/gunorange.png'>",
"'\[gungray\]'" => "<img src = '/images/emote/gungray.png'>",
"'\[gunbrown\]'" => "<img src = '/images/emote/gunbrown.png'>",
"'\[gunblue\]'" => "<img src = '/images/emote/gunblue.png'>",
"'\[happy\]'" => "<img src = '/images/emote/happy.png'>",
"'\[halo\]'" => "<img src = '/images/emote/halo.png'>",
"'\[horns\]'" => "<img src = '/images/emote/horns.png'>",
"'\[wuv\]'" => "<img src = '/images/emote/hearteyes.png'>",
"'\[love\]'" => "<img src = '/images/emote/love.gif'>",
"'\[lightblueheart\]'" => "<img src = '/images/emote/lightblueheart.png'>",
"'\[musicnote\]'" => "<img src = '/images/emote/musicnote.png'>",
"'\[ninja\]'" => "<img src = '/images/emote/ninja.gif'>",
"'\[no\]'" => "<img src = '/images/emote/no.gif'>",
"'\[orangeheart\]'" => "<img src = '/images/emote/orangeheart.png'>",
"'\[ohuman\]'" => "<img src = '/images/emote/onlyhuman.png'>",
"'\[party\]'" => "<img src = '/images/emote/party.png'>",
"'\[partyhat\]'" => "<img src = '/images/emote/partyhat.png'>",
"'\[pinkheart\]'" => "<img src = '/images/emote/pinkheart.png'>",
"'\[plus\]'" => "<img src = '/images/emote/plus.png'>",
"'\[pokeball\]'" => "<img src = '/images/emote/pokeball.png'>",
"'\[purpleheart\]'" => "<img src = '/images/emote/purpleheart.png'>",
"'\[question\]'" => "<img src = '/images/emote/question.png'>",
"'\[rage\]'" => "<img src = '/images/emote/rage.png'>",
"'\[reg\]'" => "<img src = '/images/emote/reg.png'>",
"'\[reg2\]'" => "<img src = '/images/emote/reg2.png'>",
"'\[redheart\]'" => "<img src = '/images/emote/redheart.png'>",
"'\[reddot\]'" => "<img src = '/images/emote/reddot.png'>",
"'\[sad\]'" => "<img src = '/images/emote/sad.png'>",
"'\[skull\]'" => "<img src = '/images/emote/skull.png'>",
"'\[sleepy\]'" => "<img src = '/images/emote/sleepy.gif'>",
"'\[smug\]'" => "<img src = '/images/emote/smug.gif'>",
"'\[tophat\]'" => "<img src = '/images/emote/tophat.png'>",
"'\[ugh\]'" => "<img src = '/images/emote/ugh.png'>",
"'\[wave\]'" => "<img src = '/images/emote/wave.gif'>",
"'\[wingl\]'" => "<img src = '/images/emote/wingl.png'>",
"'\[wingr\]'" => "<img src = '/images/emote/wingr.png'>",
"'\[wink\]'" => "<img src = '/images/emote/wink.png'>",
"'\[wrench\]'" => "<img src = '/images/emote/wrench.png'>",
"'\[woah\]'" => "<img src = '/images/emote/woah.gif'>",
"'\[whiteheart\]'" => "<img src = '/images/emote/whiteheart.png'>",
"'\[yeah\]'" => "<img src = '/images/emote/yeah.gif'>",
"'\[yes\]'" => "<img src = '/images/emote/yes.gif'>",
"'\[yellowheart\]'" => "<img src = '/images/emote/yellowheart.png'>",
"'\[yellowstar\]'" => "<img src = '/images/emote/yellowstar.png'>",
"'\[rainbowheart\]'" => "<img src = '/images/emote/rainbowheart.gif'>",
"'\[bubble\]'" => "<img src = '/images/emote/bubble.png'>",
"'\[fish\]'" => "<img src = '/images/emote/fish.png'>",
"'\[apple\]'" => "<img src = '/images/emote/apple.png'>",
"'\[rainbow\]'" => "<img src = '/images/emote/rainbow.png'>",
//Other
"'\\n'" => "<br>",
"/  /" => "&nbsp;&nbsp;",
);
$replace = preg_replace(array_keys($bbcode), array_values($bbcode), $replace);
return $replace;
}

function resizeSave($f) {
	$thumb = new SimpleImage();
	$thumb->load($f);
	$thumb->resize(135,110);
	$thumb->save($f.".thumb");
}

function zero ($str) {
	if (strlen($str) == 0) {
		return True;
	} else {
		return False;
	}
}

//function uniqueFilename($strExt = '') {
//	$arrIp = explode('.', $_SERVER['REMOTE_ADDR']);
//	list($usec, $sec) = explode(' ', microtime());
//	$usec = (integer) ($usec * 65536);
//	$sec = ((integer) $sec) & 0xFFFF;
//	$strUid = sprintf("%08x-%04x-%04x", ($arrIp[0] << 24) | ($arrIp[1] << 16) | ($arrIp[2] << 8) | $arrIp[3], $sec, $usec);
//	return $strUid . $strExt;
//}

function bbsclean($input) {
    $input=str_replace("'","&#39;",$input);
    $input=str_replace("’","&#39;",$input);
    $input=str_replace("<","&lt;",$input);
    $input=str_replace(">","&gt;",$input);
    $input=str_replace("%3c","",$input);
    $input=str_replace("\"","&#34;",$input);
    $input=str_replace("%3e","",$input);
    $input=str_replace("\\","&#92;",$input);
    $input=strip_tags($input);
    $input=trim($input);
    return $input;
}

function clean($input) {
    $input=str_replace("<","",$input);
    $input=str_replace(">","",$input);
    $input=str_replace("#","",$input);
    $input=str_replace("’","",$input);
    $input=str_replace("'","",$input);
    $input=str_replace("/","",$input);
    $input=str_replace("\"","",$input);
    $input=str_replace("\\","",$input);
    $input=str_replace(";","",$input);
    $input=str_replace("%3c","",$input);
    $input=str_replace("%3e","",$input);
    $input=strip_tags($input);
    $input=trim($input);
    return $input;
}

function stripSpaces($input){
    $input = str_replace(" ","",$input);
    return $input;
}

class SimpleImage {
   var $image;
   var $image_type;
 
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $compression=100, $permissions=null) {
      if( $this->image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename,0);
      }
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagealphablending($new_image, false);
      imagesavealpha($new_image, true);
      $color = imagecolortransparent($new_image, imagecolorallocatealpha($new_image, 0, 0, 0, 127));
      imagefill($new_image, 0, 0, $color);

      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
   }      
}

function curPageURL() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

?>
