<?php
require_once ("extra/library.php");
if(!class_exists('DrawrawrID')) require 'extra/lib/classes.php';

$arts = 40;
$popJournalCount = 10;

$artsResult = mysql_query("select * from arts order by id desc limit 0,".strval($arts)) or die("SQL ERROR! LOL! ! !") ;

$featuredResult = mysql_query("select * from featuredArt order by id desc limit 0,10") or die("SQL ERROR! LOL! ! !") ;

$journalResult = mysql_fetch_array(mysql_query("select * from journals where news = '1' order by id desc limit 0,1"));

$journalId    = $journalResult['id'];
$journalOwner = $journalResult['owner'];
$journalDir   = strtolower(str_replace(" ",".",$journalOwner));
$journalTitle = $journalResult['title'];
$journalEntry = tobbs($journalResult['entry']);
$journalMood  = $journalResult['mood'];
$journalDate  = $journalResult['date'];

require_once ("prehtmlincludes.php");
?>
<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = "javascript" src = "/extra/captionImages.js"></script>

<html>
<head><title>Drawrawr Home page</title>

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
		<div id="content" style="float:right;">
			<div class="newart" style="text-align:center;">
				<h3 style="margin: 0px 0px 2px 0px;" class = "icon"><img src = '/images/starico.png'> POPULAR </h3>
				<div id = "artContainer" style = "height: 120px; text-align: center;"><center>
				<?php
				// POPULAR SECTION
				$con = con();
				$favResult = mysql_query("select * from favs order by id desc limit 0,125") or die("SQL ERROR! LOL! ! !");

				$maxVals = array();
				while($row = mysql_fetch_array($favResult)) {
					if(!isset($maxVals[$row['piece']])) 
						$maxVals[$row['piece']] = 0;
					$maxVals[$row['piece']] += 1;
				}
				asort($maxVals);
				$maxVals = array_reverse($maxVals,True);

				foreach($maxVals as $key => $favNums) {
					$row = mysql_fetch_array(mysql_query("select * from arts where id = '".$key."'"));
					if (matureHideCheck($row['nude'],$row['drug'],$row['gore'],$row['sex'],$cResult['nude'],$cResult['drug'],$cResult['gore'],$cResult['sex'],$cResult['mature'])) {
						if ($row['type'] == 'literature' or $row['type'] == 'flash' or $row['type'] == 'audio' or $row['type'] == 'culinary' or $row['type'] == 'craft'){
							$type = "<div class = 'overlay'><img src = '/images/".$row['type']."overlay.png'></div>";
						} else {$type = "";}
						if ($row['mature'] == 1 and matureMarkCheck($cResult['mature'])) {$mature="<div class = 'overlay'><img src = '/images/maturefilter.png'></div>";} else {$mature="";}
						echo ("<div class = 'zitem' title = '".$row['title']." by ".$row['owner']."'>
							<a href = '/art/".DrawrawrID::encode($row['id'])."'><img src = '/arts/".$row['id'].".thumb' width = '135' height = '110' class = 'img'>".$mature.$type."</a>
								<div class = 'caption'>
									<a href = '/art/".DrawrawrID::encode($row['id'])."'>".$row['title']."</a>
								</div>
							</div>");
						//$z++;
					}
				}

				?>
				</div>
			</center>
			</div>
			<div class="newart" style="text-align:center;">
				<h3 style="margin: 0px 0px 2px 0px;"><img src = '/images/galleryico.png'> NEW </h3>
				<div id = "artContainer" style = "height: 628px ;">
				<?php
				//New Art section
				$z = 0;
				while($row = mysql_fetch_array($artsResult)) {
					if (matureHideCheck($row['nude'],$row['drug'],$row['gore'],$row['sex'],$cResult['nude'],$cResult['drug'],$cResult['gore'],$cResult['sex'],$cResult['mature'])) {
						if ($row['type'] == 'literature' or $row['type'] == 'flash' or $row['type'] == 'audio' or $row['type'] == 'culinary' or $row['type'] == 'craft'){
							$type = "<div class = 'overlay'><img src = '/images/".$row['type']."overlay.png'></div>";
						} else {$type = "";}
						if ($row['mature'] == 1 and matureMarkCheck($cResult['mature'])) {$mature="<div class = 'overlay'><img src = '/images/maturefilter.png'></div>";} else {$mature="";}
						echo ("<div class = 'zitem' title = '".$row['title']." by ".$row['owner']."'>
							<a href = '/art/".DrawrawrID::encode($row['id'])."'><img src = '/arts/".$row['id'].".thumb' width = '135' height = '110' class = 'img'>".$mature.$type."</a>
								<div class = 'caption'>
									<a href = '/art/".DrawrawrID::encode($row['id'])."'>".$row['title']."</a>
								</div>
							</div>");
						$z++;
					}
				}
				?>
				</div>
			</div>
			<div class="newart">
				<h3 style="text-align:center;margin: 0px 0px 2px 0px;" class = "icon"><img src = '/images/trophyico.png'> FEATURED </h3>
				<div id = "artContainer" style = "height: 120px; text-align: center;">
				<?php
				//FEATURED ART
				$z = 0;
				while($row = mysql_fetch_array($featuredResult)) {
				$artResult = mysql_fetch_array(mysql_query("select * from arts where id='".$row['location']."'")) or die("SQL ERROR! LOL! ! !");
					if (matureHideCheck($artResult['nude'],$artResult['drug'],$artResult['gore'],$artResult['sex'],$cResult['nude'],$cResult['drug'],$cResult['gore'],$cResult['sex'],$cResult['mature'])) {
						if ($artResult['type'] == 'literature' or $artResult['type'] == 'flash' or $artResult['type'] == 'audio' or $artResult['type'] == 'culinary' or $artResult['type'] == 'craft'){
							$type = "<div class = 'overlay'><img src = '/images/".$artResult['type']."overlay.png'></div>";
						} else {$type = "";}
						if ($artResult['mature'] == 1 and matureMarkCheck($cResult['mature'])) {$mature="<div class = 'overlay'><img src = '/images/maturefilter.png'></div>";} else {$mature="";}
						echo ("<div class = 'zitem' title = '".$artResult['title']." by ".$artResult['owner']."'>
							<a href = '/art/".DrawrawrID::encode($artResult['id'])."'><img src = '/arts/".$artResult['id'].".thumb' width = '135' height = '110' class = 'img'>".$mature.$type."</a>
								<div class = 'caption'>
									<a href = '/art/".DrawrawrID::encode($artResult['id'])."'>".$artResult['title']."</a>
								</div>
							</div>");
						$z++;
					}
				}
				?>
				</div>
			</div>
		</div>
		<div id="aside" style="float:left;">
			<div class="newart"><div style = "float:right;"><?php echo($journalDate); ?></div> <h3 style="margin: 0px 0px 3px 0px;"><img src='/images/newsico.png'> NEWS </h3><div id = "scrollable" style="height:190px;border:solid 1px #b4b4b4;"><?php echo($journalEntry); ?></div><span style='float:right;font-size:12px;'> <a href="viewJournals.php?owner=<? echo($journalOwner); ?>&id=<? echo($journalId); ?>">View Replies...</a> </span> - <? echo("<a href = '/".$journalDir."'>".$journalOwner."</a>"); ?></div>
			<div class="newart"><?php require_once ("newUsers.php"); ?></div>
<div class="newart" style = "min-height:112px;height:auto !important;height:112px;"><h3><img src='/images/topuserico.png'> FEATURED USER </h3>
			<p style="margin: 2px;font-size:15px;"><a href = "/snowman/"><img src = '/avatars/snowman' width='75' height='75' style='float:left;margin-right:5px;'><span style="margin-bottom:10px;"><b>Snowman</b></a>!</span><br><span style="font-size:11px;"> <b>A truly incredible and highly talented mixed media artist!</b> <br> <a href = "/viewJournals.php?owner=DrawRawr&id=3223">More info...</a></span></p>
			</div>
			<div class="newart" style="overflow:hidden;">
			<h3 style="margin: 0px 0px 3px 0px;"><img src='/images/journalico.png'> POPULAR JOURNALS </h3>
			<?php
				$popJournals = mysql_query("select location from comments where type='journal' and parent='0' order by id desc limit 0,200") or die("SQL ERROR! LOL! ! !");

				$maxVals = array();
				while($row = mysql_fetch_array($popJournals)) {
					if(!isset($maxVals[$row['location']]))
						$maxVals[$row['location']] = 0;
					$maxVals[$row['location']] += 1;
				}
				asort($maxVals);
				$maxVals = array_reverse($maxVals,True);
					
				foreach($maxVals as $key => $comNum) {
					if ($popJournalCount > 0) {
						$row = mysql_fetch_array(mysql_query("select * from journals where id = '".$key."'"));
						$userDir = strtolower(str_replace(" ",".",$row['owner']));
						if (strlen($row['title']) > 35) { $shortTitle = substr($row['title'],0,35)."..."; } else { $shortTitle = $row['title']; }
						echo("<div style='margin:27px 10px 16px 5px;height:15px;'><a href='/".$userDir."'><img src='/avatars/".strtolower($userDir)."' style='float:left;width:35px;height:35px;' title='".$row['owner']."'></a><b style='font-size:14px;'><a title='".htmlspecialchars($row['title'])."' href='/viewJournals.php?owner=".$row['owner']."&id=".$row['id']."'>".$shortTitle."</a></b></div>");
						$popJournalCount -= 1;
					} else {break;}
				}

				mysql_close($con);
			?>
			</div>
		</div>
	</div>
	<div id="ads">
		<?php require_once ("ads.php"); ?>
	</div>
	<div id="footer">
		<?php require_once ("footer.php"); ?>
	</div>
</div>

</body>
</html>
