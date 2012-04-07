<?php
require_once ("prehtmlincludes.php");
if(!class_exists('DrawrawrID')) require 'extra/lib/classes.php';
require 'extra/lib/session.php';

$con = con();

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

$errorLink = "<br><a href = 'javascript:history.go(-1)'>Go back and try again, kid. </a>";
$workedLink = "Submition completed! <br>";

isset($_POST['journal']) ? $journalBool = bbsclean($_POST['journal']) : $journalBool = '';
if(isset($_POST['journalName'])) 	$journalName  	= bbsclean($_POST['journalName']);
if(isset($_POST['journalEntry'])) 	$journalEntry 	= bbsclean($_POST['journalEntry']);
if(isset($_POST['journalMood'])) 	$journalMood  	= bbsclean($_POST['journalMood']);
isset($_POST['news']) ? 			$journalNews  	= bbsclean($_POST['news']) : $journalNews = '';

isset($_POST['image']) ? $imageBool = bbsclean($_POST['image']) : $imageBool = '';
if(isset($_POST['imageName'])) 		$imageName 		= bbsclean($_POST['imageName']);
if(isset($_POST['imageDescription'])) $imageDesc 	= bbsclean($_POST['imageDescription']);


isset($_POST['culinary']) ? $culinaryBool = bbsclean($_POST['culinary']) : $cullinayBool = '';
if(isset($_POST['culinaryName'])) 		$culinaryName 		= bbsclean($_POST['culinaryName']);
if(isset($_POST['culinaryDescription'])) $culinaryDesc 	= bbsclean($_POST['culinaryDescription']);
if(isset($_POST['culinaryWriting'])) 	$culinaryWriting 	= bbsclean($_POST['culinaryWriting']);

isset($_POST['craft']) ? $craftBool = bbsclean($_POST['craft']) : $craftBool = '';
if(isset($_POST['craftName'])) 		$craftName 		= bbsclean($_POST['craftName']);
if(isset($_POST['craftDescription'])) $craftDesc 	= bbsclean($_POST['craftDescription']);

isset($_POST['literature']) ? $litBool = bbsclean($_POST['literature']) : $litBool = '';
if(isset($_POST['litName']))		$litName    	= bbsclean($_POST['litName']);
if(isset($_POST['litDescription'])) $litDesc    	= bbsclean($_POST['litDescription']);
if(isset($_POST['litWriting'])) 	$litWriting 	= bbsclean($_POST['litWriting']);

isset($_POST['flash']) ? $flashBool = bbsclean($_POST['flash']) : $flashBool = '';
if(isset($_POST['flashName'])) 		$flashName 		= bbsclean($_POST['flashName']);
if(isset($_POST['flashDescription'])) $flashDesc 	= bbsclean($_POST['flashDescription']);

isset($_POST['audio']) ? $audioBool = bbsclean($_POST['audio']) : $audioBool = '';
if(isset($_POST['audioName']))		$audioName 		= bbsclean($_POST['audioName']);
if(isset($_POST['audioDescription'])) $audioDesc 	= bbsclean($_POST['audioDescription']);

isset($_POST['nude']) 	? $nude   = bbsclean($_POST['nude']) : $nude = '';
isset($_POST['drug']) 	? $drug   = bbsclean($_POST['drug']) : $drug = '';
isset($_POST['gore']) 	? $gore   = bbsclean($_POST['gore']) : $gore = '';
isset($_POST['sex']) 	? $sex    = bbsclean($_POST['sex'])  : $sex  = '';
$mature = getMature($nude,$drug,$gore,$sex);

if ($nude=='' or (!isset($_POST['nude']) ) ) {
	$nude='0';
}
if ($drug=='' or (!isset($_POST['drug']) ) ) {
	$drug='0';
}
if ($gore=='' or (!isset($_POST['gore']) ) ) {
	$gore='0';
}
if ($sex=='' or (!isset($_POST['sex']) ) ) {
	$sex='0';
}
if ($journalMood=='' or (!isset($_POST['journalMood']) ) ) {
	$journalMood='n';
}

function getMature($nude,$drug,$gore,$sex) {
	if ($nude == '1' or $drug == '1' or $gore == '1' or $sex == '1') {
		return '1';
	} else {
		return '0';
	}
}

$cResult    = mysql_query("select username,password,moderator from user_data where username = '".$cookiename."' and password = '".$cookiepass."'");

//Сheck that the user's cookie name & pass are valid
if(mysql_affected_rows() != 0) {

//Journal Submition
if ($journalBool == 't') {
	if ($journalName != ""){
		if ($journalEntry != ""){
			$entryLength = strlen($journalEntry);
			if ($entryLength < 12000){
				//Check if moderator
				$row = mysql_fetch_array($cResult);
				if ($row['moderator'] >= 3 and $journalNews == 't'){
					$mod = True;
					$newsBool1 = ",news";
					$newsBool2 = ",'1'";
					mysql_query("insert into moderatorAct (owner,action,date) values ('".$cookiename."','posted a news journal. ',CURDATE() )") or exit ("Database couldn't add moderator action.");
				} else {$newsBool1 = ""; $newsBool2 = "";}

				//Put journal into database
				mysql_query("insert into journals(owner,title,entry,mood".$newsBool1.") VALUES ('".$cookiename."', '".$journalName."', '".$journalEntry."', '".$journalMood."'".$newsBool2.")") or exit ("DATABASE ERROR! lol. ");

				//Give updates
				$journalResult = mysql_fetch_array(mysql_query("select id from journals order by id desc limit 0,1"));

				$watchers = mysql_query("select watcher from watching where watching = '".$cookiename."'");
				while ($row = mysql_fetch_array($watchers)) {
					mysql_query("update user_data set newUpdates = '1' where username = '".$row['watcher']."'");
					mysql_query("insert into updates(owner,sender,type,location) values ('".$row['watcher']."','".$cookiename."','journal','".$journalResult['id']."')");
				}

				if (isset($mod) && $mod == True) {
					$users = mysql_query("select username from user_data");
					mysql_query("update user_data set newUpdates = '1'");
					while ($row = mysql_fetch_array($users)) {
						mysql_query("insert into updates(owner,sender,type,location) values ('".$row['username']."','".$cookiename."','news','".$journalResult['id']."')");
					}
				}
			
				//Redirect user to journal submission
				header("Location: /viewJournals.php?owner=".$cookiename."&id=".$journalResult['id']);

			} else {
			$error = "Your journal entry is too long! It is '".$entryLength."' characters long, and must be at most 12000. ".$errorLink;
			}
		} else {
			$error = "The journal entry was empty! ".$errorLink;
		}
	}else {
		$error = "The journal title was empty! ".$errorLink;
	}
}

//Image Submition
if ($imageBool == 't') {
	if ($imageName != "" and (!empty($_FILES["imageUploaded"]) && $_FILES['imageUploaded']['error'] == 0)){
		$descLength = strlen($imageDesc);
		if ($descLength < 5000){
			$filename = basename($_FILES["imageUploaded"]['name']);
			$ext = substr($filename, strpos($filename, '.') + 1);
			if (($_FILES["imageUploaded"]["type"] == "image/jpeg" or $_FILES["imageUploaded"]["type"] == "image/gif" or $_FILES["imageUploaded"]["type"] == "image/png" or $_FILES['imageUploaded']['type'] == "image/pjpeg" or $_FILES['imageUploaded']['type'] == "image/x-png") && ($_FILES["imageUploaded"]["size"] / 1024 < 2000)){
				//Put image into database
				mysql_query("insert into arts(owner,type,title,description,viewers,mature,nude,drug,gore,sex) VALUES ('".$cookiename."', 'image','".$imageName."', '".$imageDesc."', '0','".$mature."','".$nude."','".$drug."','".$gore."','".$sex."')") or exit ("DATABASE ERROR! lol. ");

				//Save image
				$newID   = mysql_insert_id();
				$newName = "arts/".$newID;
				if (move_uploaded_file($_FILES['imageUploaded']['tmp_name'],$newName) ) {
					$error = $workedLink;
				} else {
					$error = $error."Error: A problem occurred during image upload!<br />";
				}

				//Creates Ugly Default Stretched Thumbnail
				resizeSave($newName);

				//Give updates
				$watchers = mysql_query("select watcher from watching where watching = '".$cookiename."'");
				while ($row = mysql_fetch_array($watchers)) {
					mysql_query("update user_data set newUpdates = '1' where username = '".$row['watcher']."'");
					mysql_query("insert into updates(owner,sender,type,location) values ('".$row['watcher']."','".$cookiename."','art','".$newID."')");
				}

				header("Location: cropThumb.php");
			} else {
				$error = $error."Error: Only .jpg, .gif, and .png images under 2000Kb are accepted for upload<br />";
			}
		} else {
		$error = "Your image description is too long! It is '".$descLength."' characters long, and must be at most 5000. ".$errorLink;
		}
	}else {
		$error = "The image title was empty! ".$errorLink;
	}
}

//culinary Submition
if ($culinaryBool == 't') {
	if ($culinaryName != "" and (!empty($_FILES["imageUploaded"]) && $_FILES['imageUploaded']['error'] == 0)){
		$descLength = strlen($imageDesc);
		if ($descLength < 5000){
			$filename = basename($_FILES["imageUploaded"]['name']);
			$ext = substr($filename, strpos($filename, '.') + 1);
			if (($_FILES["imageUploaded"]["type"] == "image/jpeg" or $_FILES["imageUploaded"]["type"] == "image/gif" or $_FILES["imageUploaded"]["type"] == "image/png" or $_FILES['imageUploaded']['type'] == "image/pjpeg" or $_FILES['imageUploaded']['type'] == "image/x-png") && ($_FILES["imageUploaded"]["size"] / 1024 < 2000)){
				//Put image into database
				mysql_query("insert into arts(owner,type,title,description,writing,viewers,mature,nude,drug,gore,sex) VALUES ('".$cookiename."', 'culinary','".$culinaryName."', '".$culinaryDesc."','".$culinaryWriting."', '0','".$mature."','".$nude."','".$drug."','".$gore."','".$sex."')") or exit ("DATABASE ERROR! lol. ");

				//Save image
				$newID   = mysql_insert_id();
				$newName = "arts/".$newID;
				if (move_uploaded_file($_FILES['imageUploaded']['tmp_name'],$newName) ) {
					$error = $workedLink;
				} else {
					$error = $error."Error: A problem occurred during image upload!<br />";
				}

				//Creates Ugly Default Stretched Thumbnail
				resizeSave($newName);

				//Give updates
				$watchers = mysql_query("select watcher from watching where watching = '".$cookiename."'");
				while ($row = mysql_fetch_array($watchers)) {
					mysql_query("update user_data set newUpdates = '1' where username = '".$row['watcher']."'");
					mysql_query("insert into updates(owner,sender,type,location) values ('".$row['watcher']."','".$cookiename."','art','".$newID."')");
				}

				header("Location: cropThumb.php");
			} else {
				$error = $error."Error: Only .jpg, .gif, and .png images under 2000Kb are accepted for upload<br />";
			}
		} else {
		$error = "Your culinary description is too long! It is '".$descLength."' characters long, and must be at most 5000. ".$errorLink;
		}
	}else {
		$error = "The culinary title was empty! ".$errorLink;
	}
}

//Craft Submition
if ($craftBool == 't') {
	if ($craftName != "" and (!empty($_FILES["imageUploaded"]) && $_FILES['imageUploaded']['error'] == 0)){
		$descLength = strlen($imageDesc);
		if ($descLength < 5000){
			$filename = basename($_FILES["imageUploaded"]['name']);
			$ext = substr($filename, strpos($filename, '.') + 1);
			if (($_FILES["imageUploaded"]["type"] == "image/jpeg" or $_FILES["imageUploaded"]["type"] == "image/gif" or $_FILES["imageUploaded"]["type"] == "image/png" or $_FILES['imageUploaded']['type'] == "image/pjpeg" or $_FILES['imageUploaded']['type'] == "image/x-png") && ($_FILES["imageUploaded"]["size"] / 1024 < 2000)){
				//Put image into database
				mysql_query("insert into arts(owner,type,title,description,viewers,mature,nude,drug,gore,sex) VALUES ('".$cookiename."', 'craft','".$craftName."', '".$craftDesc."', '0','".$mature."','".$nude."','".$drug."','".$gore."','".$sex."')") or exit ("DATABASE ERROR! lol. ");

				//Save image
				$newID   = mysql_insert_id();
				$newName = "arts/".$newID;
				if (move_uploaded_file($_FILES['imageUploaded']['tmp_name'],$newName) ) {
					$error = $workedLink;
				} else {
					$error = $error."Error: A problem occurred during image upload!<br />";
				}

				//Creates Ugly Default Stretched Thumbnail
				resizeSave($newName);

				//Give updates
				$watchers = mysql_query("select watcher from watching where watching = '".$cookiename."'");
				while ($row = mysql_fetch_array($watchers)) {
					mysql_query("update user_data set newUpdates = '1' where username = '".$row['watcher']."'");
					mysql_query("insert into updates(owner,sender,type,location) values ('".$row['watcher']."','".$cookiename."','art','".$newID."')");
				}

				header("Location: cropThumb.php");
			} else {
				$error = $error."Error: Only .jpg, .gif, and .png images under 2000Kb are accepted for upload<br />";
			}
		} else {
		$error = "Your craft description is too long! It is '".$descLength."' characters long, and must be at most 5000. ".$errorLink;
		}
	}else {
		$error = "The craft's title was empty! ".$errorLink;
	}
}

//Writing Submission
if ($litBool == 't') {
	if ($litName != ""){
		if ($litWriting != ""){
			$descLength = strlen($litDesc);
			if ($descLength < 5000){
				$writingLength = strlen($litWriting);
				if ($writingLength < 20000){
					if ($_FILES["litIcon"]["name"] != ""){
						if (($_FILES["litIcon"]["type"] == "image/jpeg" or $_FILES["litIcon"]["type"] == "image/gif" or $_FILES["litIcon"]["type"] == "image/png" or $_FILES['litIcon']['type'] == "image/pjpeg" or $_FILES['litIcon']['type'] == "image/x-png") && ($_FILES["litIcon"]["size"] / 1024 < 1000)) {
							//Put writing into database
							mysql_query("insert into arts(owner,type,title,description,writing,viewers,mature,nude,drug,gore,sex) VALUES ('".$cookiename."', 'literature','".$litName."', '".$litDesc."', '".$litWriting."', '0','".$mature."','".$nude."','".$drug."','".$gore."','".$sex."')") or exit ("DATABASE ERROR! lol. ");

							$newID   = mysql_insert_id();
							$newName = "arts/".$newID;

							//Upload thumbnail icon.
							$thumb = new SimpleImage();
							$thumb->load($_FILES["litIcon"]["tmp_name"]);
							$thumb->save($newName);

							$thumb = new SimpleImage();
							$thumb->load($_FILES["litIcon"]["tmp_name"]);
							$thumb->resize(135,110);
							$thumb->save($newName.".thumb");

							//Give updates
							$watchers = mysql_query("select watcher from watching where watching = '".$cookiename."'");
							while ($row = mysql_fetch_array($watchers)) {
								mysql_query("update user_data set newUpdates = '1' where username = '".$row['watcher']."'");
								mysql_query("insert into updates(owner,sender,type,location) values ('".$row['watcher']."','".$cookiename."','art','".$newID."')");
							}
							//Redirect user to submission
							header("Location: /art/".DrawrawrID::encode($newID));
						} else {
							$error = $error."Error: The thumbnail icon must be a .jpg, .gif, or .png of size 1000kb or under. ";
						}
					} else { $error = "The thumbnail Icon is required! Make sure you submitted one. "; }

				} else {
				$error = "Your writing is too long! It is '".$writingLength."' characters long, and must be at most 20000. ".$errorLink;
				}
			} else {
			$error = "Your description is too long! It is '".$descLength."' characters long, and must be at most 5000. ".$errorLink;
			}
		} else {
			$error = "You didn't write anything! ".$errorLink;
		}
	}else {
		$error = "The writing title was empty! ".$errorLink;
	}
}

//Flash Submition
if ($flashBool == 't') {
	if ($flashName != "" and (!empty($_FILES["flashIcon"]) && $_FILES['flashIcon']['error'] == 0) and (!empty($_FILES["flashSWF"]) && $_FILES['flashSWF']['error'] == 0)){
		$descLength = strlen($flashDesc);
		if ($descLength < 5000){
			$filename = basename($_FILES["flashSWF"]['name']);
			$ext = substr($filename, strpos($filename, '.') + 1);
			if (($_FILES["flashIcon"]["type"] == "image/jpeg" or $_FILES["flashIcon"]["type"] == "image/gif" or $_FILES["flashIcon"]["type"] == "image/png" or $_FILES['flashIcon']['type'] == "image/pjpeg" or $_FILES['flashIcon']['type'] == "image/x-png") && ($_FILES["flashIcon"]["size"] / 1024 < 2000) && $_FILES['flashSWF']['type'] == "application/x-shockwave-flash"){
				if ($_FILES["flashIcon"]["size"] / 1024 / 1024 < 15) {
					//Put flash into database
					mysql_query("insert into arts(owner,type,title,description,viewers,width,height,mature,nude,drug,gore,sex) VALUES ('".$cookiename."', 'flash','".$flashName."', '".$flashDesc."', '0', '".$width."', '".$height."','".$mature."','".$nude."','".$drug."','".$gore."','".$sex."')") or exit ("DATABASE ERROR! lol. ");
					$newID   = mysql_insert_id();
					$newName = "arts/".$newID;

					//Save flash
					if (move_uploaded_file($_FILES['flashSWF']['tmp_name'],$newName.".swf")) {
						$error = $workedLink;
					} else {
						$error = $error."Error: A problem occurred during SWF upload!<br />";
					}

					//Creates Ugly Default Stretched Thumbnail
					$thumb = new SimpleImage();
					$thumb->load($_FILES['flashIcon']['tmp_name']);
					$thumb->resize(135,110);
					$thumb->save($newName.".thumb");
	
					//Get flash Width & Height
					list($width, $height, $type, $attr) = getimagesize($newName.".swf");
					mysql_query("update arts set width = '".$width."', height = '".$height."' where id='".$newID."'");

					//Give updates
					$watchers = mysql_query("select watcher from watching where watching = '".$cookiename."'");
					while ($row = mysql_fetch_array($watchers)) {
						mysql_query("update user_data set newUpdates = '1' where username = '".$row['watcher']."'");
						mysql_query("insert into updates(owner,sender,type,location) values ('".$row['watcher']."','".$cookiename."','art','".$newID."')");
					}

					header("Location: /art/".DrawrawrID::encode($newID));
				} else {
					$error = $error."Your flash animation must be at most 15mb in size and must be a .swf file! ";
				}
			} else {
				$error = $error."Your flash animation must be a .swf file! ";
			}
		} else {
		$error = "Your animation description is too long! It is '".$descLength."' characters long, and must be at most 5000. ".$errorLink;
		}
	}else {
		$error = "Something was left unfilled! Make sure you filled in the title, thumbnail, and flash submission. ".$errorLink;
	}
}

//Audio Submition
if ($audioBool == 't') {
	if ($audioName != "" and (!empty($_FILES["audioIcon"]) && $_FILES['audioIcon']['error'] == 0) and (!empty($_FILES["audioFile"]) && $_FILES['audioFile']['error'] == 0)){
		$descLength = strlen($audioDesc);
		if ($descLength < 5000){
			$filename = basename($_FILES["audioFile"]['name']);
			$ext = substr($filename, strpos($filename, '.') + 1);
			if (($_FILES["audioIcon"]["type"] == "image/jpeg" or $_FILES["audioIcon"]["type"] == "image/gif" or $_FILES["audioIcon"]["type"] == "image/png" or $_FILES['audioIcon']['type'] == "image/pjpeg" or $_FILES['audioIcon']['type'] == "image/x-png") && ( ($_FILES['audioFile']['type'] == "audio/mp3" or $_FILES['audioFile']['type'] == "audio/mpeg" or $_FILES['audioFile']['type'] == "audio/x-wav" or $_FILES['audioFile']['type'] == "audio/wave" or $_FILES['audioFile']['type'] == "audio/ogg") ) ){
				if ($_FILES["audioFile"]["size"] / 1024 / 1024 < 20){
					//Put audio into database
					mysql_query("insert into arts(owner,type,title,description,viewers,mediatype,mature,nude,drug,gore,sex) VALUES ('".$cookiename."', 'audio','".$audioName."', '".$audioDesc."', '0', '".$_FILES['audioFile']['type']."','".$mature."','".$nude."','".$drug."','".$gore."','".$sex."')") or exit ("DATABASE ERROR! lol. ");
					$newID   = mysql_insert_id();
					$newName = "arts/".$newID;

					//Save Audio
					if (move_uploaded_file($_FILES['audioFile']['tmp_name'],$newName)) {
						$error = $workedLink;
					} else {
						$error = $error."Error: A problem occurred during Audio upload!<br />";
					}
		
					//Creates Ugly Default Stretched Thumbnail
					$thumb = new SimpleImage();
					$thumb->load($_FILES['audioIcon']['tmp_name']);
					$thumb->resize(135,110);
					$thumb->save($newName.".thumb");

					//Give updates
					$watchers = mysql_query("select watcher from watching where watching = '".$cookiename."'");
					while ($row = mysql_fetch_array($watchers)) {
						mysql_query("update user_data set newUpdates = '1' where username = '".$row['watcher']."'");
						mysql_query("insert into updates(owner,sender,type,location) values ('".$row['watcher']."','".$cookiename."','art','".$newID."')");
					}

					header("Location: /art/".DrawrawrID::encode($newID));
				} else {
					$error = $error."Your audio must be at most 20mb in size. ".$errorLink;
				}
			} else {
				$error = $error."Your audio must be a .mp3, .wav, or .ogg file! ".$errorLink;
			}
		} else {
		$error = "Your description is too long! It is '".$descLength."' characters long, and must be at most 5000. ".$errorLink;
		}
	}else {
		$error = "Something was left unfilled! Make sure you filled in the title, thumbnail, and audio submission. ".$errorLink;
	}
}


//Error Checking
if ($_FILES['imageUploaded']['error'] == 1) {
	$error = $error."THERE WAS AN ERROR PROCESSING YOUR IMAGE";
} else if ($_FILES['flashSWF']['error'] == 1) {
	$error = $error."THERE WAS AN ERROR PROCESSING YOUR FLASH";
} else if ($_FILES['audioFile']['error'] == 1) {
	$error = $error."THERE WAS AN ERROR PROCESSING YOUR AUDIO";
}

} else {
  echo ("<script language = 'javascript'>window.location='/logoutScript.php';</script>");
}

mysql_close($con);

?>
<html>
<head>

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
		<div id="content" style = "width: 100%;">
			<div class="newArt" style = "text-align: center;">
				<?php echo($error); ?>
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
