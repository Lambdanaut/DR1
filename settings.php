<?php
require 'extra/library.php';
require 'extra/lib/session.php';

$con        = con();

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];
$userdir    = strtolower(str_replace(" ",".",$cookiename));

$profile    = mysql_fetch_array(mysql_query("select * from user_data where username = '".$cookiename."'"));

if (!$con){die('Could not connect: ' . mysql_error());}


mysql_close($con);

require_once ("prehtmlincludes.php");

?>
<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = "javascript" src = "/extra/jquery-ui.min.js"></script>
<script language = "javascript" src = "/extra/modernizr.min.js"></script>
<script language = "javascript">

$(function() {
	$("#tabs").tabs();
	$("#submit").button();
	$("#locButton").button();
	$("#friendsButton").button();
	$("#blockButton").button();
	$("#unblockButton").button();

	$("#whylocbutton").click(function () {
		$("#whyloc").dialog();
	});	

	$("#locButton").click(getloc);

	$("#latitude").val("<?php echo($profile['latitude']); ?>");
	$("#longitude").val("<?php echo($profile['longitude']); ?>");

	//Check Mature Boxes
	if ("<?php echo($profile['nude']); ?>" == "1") {
		$('#nude').attr('checked',true)
	}
	if ("<?php echo($profile['drug']); ?>" == "1") {
		$('#drug').attr('checked',true)
	}
	if ("<?php echo($profile['gore']); ?>" == "1") {
		$('#gore').attr('checked',true)
	}
	if ("<?php echo($profile['sex']); ?>" == "1") {
		$('#sex').attr('checked',true)
	}

	//Check Bold/Italic/Underlined Boxes
	if ("<?php echo($profile['bold']); ?>" == "1") {
		$('#bold').attr('checked',true)
	}
	if ("<?php echo($profile['italic']); ?>" == "1") {
		$('#italic').attr('checked',true)
	}
	if ("<?php echo($profile['underlined']); ?>" == "1") {
		$('#underlined').attr('checked',true)
	}

	//Check if hideDOB if it's already hidden
	if (<?php echo($profile['hidedob']); ?> == 1) {
		$("#hideDOB").attr('checked', true);
	}

	//Check if hidecontacts if it's already hidden
	if (<?php echo($profile['hidecontacts']); ?> == 1) {
		$("#hideContact").attr('checked', true);
	}

	//Profile Preview
	$("#previewButton").click(function () {
		$("#profilePreview").dialog({width: 650,height: 400});
		$.ajax({
			type: "POST",
			url: "/extra/BBPreview.php",
			data: "text=" + escape($("#profile").val()),
			beforeSend: function() {
				$("#profilePreview").html("<br><br><center> :: LOADING ::</center>");
			},
			success: function(reply) {
				$("#profilePreview").html(unescape(reply));
			},
		});
	});

	//Autocomplete friends
	$("#friendsButton").click(addFriend);

	var users = [

	<?php
		$con = con();
		
		$users = mysql_query("select username from user_data order by username asc");
						
		while($row = mysql_fetch_array($users)) {
			echo ("");
			echo (json_encode($row['username']));
			echo (",");
		}
	?>
	];

	$("#friend").autocomplete({
		minLength: 2,
		source: users,
		focus: function(event, ui) {
			$("#friend").val(ui.item.value);
			return false;
		},
		select: function(event, ui) {
			$("#friend").val(ui.item.value);
			$("#friend-name").html(ui.item.value);
			$("#friend-id").val(ui.item.value)
			$("#friend-icon").fadeOut("fast",function () {
				$("#friend-icon").attr("src", "/avatars/" + ui.item.value.toLowerCase().replace(" ","."));
				$("#friend-icon").fadeIn("slow");
			});
			$("#friendsButton").fadeIn("slow");

			return false;
		}
	});

});

function getFriends(){
	$.ajax({
		type: "POST",
		url: "/getFriends.php",
		data: "retrieve=friends&username=<?php echo($cookiename); ?>",
		beforeSend: function() {
			$("#displayFriends").fadeOut("fast");
		},
		success: function(reply) {
			$("#displayFriends").html(reply);
			$("#displayFriends").fadeIn("slow");
		},
		error: function(){
			$('#displayFriends').html("There was a problem loading your friends list! Refresh and try again. ");
		}
	});	
}

function addFriend(){
	$.ajax({
		type: "POST",
		url: "/getFriends.php",
		data: "retrieve=addfriend&username=<?php echo($cookiename); ?>&password=<?php echo($cookiepass); ?>&toadd=" + $("#friend-id").val(),
		beforeSend: function() {
			$("#friendsButton").fadeOut("fast");
			$("#friend-icon").attr("src", "/images/neutraldeficon.png");
			$("#friend-name").html("");
		},
		success: function(reply) {
			getFriends();
		},
		error: function(){
			alert("There was a problem adding your friend. Try again! ");
		}
	});
}

function deleteFriend(id){
	$.ajax({
		type: "POST",
		url: "/getFriends.php",
		data: "retrieve=deletefriend&username=<?php echo($cookiename); ?>&password=<?php echo($cookiepass); ?>&toadd=" + id,
		success: function(reply) {
			getFriends();
		},
		error: function(){
			alert("There was an error when deleting your friend. ");
		}
	});
}

function getloc() {
	if (Modernizr.geolocation) {
		navigator.geolocation.getCurrentPosition(updateloc,locErrors);
	} else {
		if (geo_position_js.init()) {
			geo_position_js.getCurrentPosition(updateloc, locErrors);
		} else {
			$("#noloc").dialog();
		}
	}
}

function updateloc(pos) {
	$("#latitude").val(pos.coords.latitude);
	$("#longitude").val(pos.coords.longitude);
}

function locErrors(err) {
	if      (err.code == 1) {
		alert("Error: Couldn't get permission to access location. ");
	}
	else if (err.code == 2) {
		alert("Error: There was an error finding your location! Try erasing your location settings for Drawrawr and trying again. ");
	}
	else if (err.code == 3) {
		alert("Error: The system took too long to retrieve your location. ");
	}
	else {
		alert("Error: There was an error finding your location! ");
	}
}

function customConf(message,callback) {
	yes = "Yes";no  = "No";
	$("#customConf").dialog({width: 500,height: "auto",modal: true,resizable: false,autoOpen: false});
	$("#customConf").dialog("open");
	$("#customConf").html("<p>"+message+"</p><p><input type='button' id='yesConf' value='"+yes+"'> <input type='button' id='noConf' value='"+no+"'></p>");
	$("#yesConf").click(function () {
		$("#customConf").dialog("close");
		callback();
	});
	$("#noConf").click( function () {$("#customConf").dialog("close"); })
}

function blockUser() {
  customConf("Are you sure you want to block this user?", function () {
    customConf("Positive?", function () {
      customConf("...Really sure?", function () {
        customConf("Has this user intentionally harassed you?", function () {
          customConf("..More than once?", function () {
            customConf("Blocking a person often makes things worse.", function () {
              customConf("It could make them bother you even more!", function () {
                customConf("Are you still sure this is a good idea..?", function () {
                  customConf("ABSOLUTELY CERTAIN?", function () {
	$.ajax({
		type: "POST",
		url: "/blocking.php",
		data: "block=block&username=<?php echo($cookiename); ?>&password=<?php echo($cookiepass); ?>&blockedUser=" + $("#blockedUser").val(),
		beforeSend: function() {
			$("#blockedUser").val("");
		},
		success: function(reply) {
			alert("USER BLOCKED!");
			location.reload(true);
		},
		error: function(){
			alert("There was a problem blocking the user. Try again!  ");
		}
	});
                  });
                });
              });
            });
          });
        });
      });
    });
  }); 
}
function unblockUser() {
	$.ajax({
		type: "POST",
		url: "/blocking.php",
		data: "block=unblock&username=<?php echo($cookiename); ?>&password=<?php echo($cookiepass); ?>&blockedUser=" + $("#unblockedUser").val(),
		beforeSend: function() {
			$("#unblockedUser").val("");
		},
		success: function(reply) {
			alert("USER UNBLOCKED!");
			location.reload(true);
		},
		error: function(){
			alert("There was a problem unblocking the user. Try again! ");
		}
	});
}

$(document).ready(function() {
	getFriends();
	
	$("#blockButton").click(blockUser);
	$("#unblockButton").click(unblockUser);

	//Select dropdown box default values. 
	$("#gender").val("<?php echo($profile['gender']); ?>");
	$("#mature").val("<?php echo($profile['mature']); ?>");

	//Check/Uncheck mature boxes on changing filter mode
	$("#mature").change(function () {
		if ( $("#mature").val() == "0") {
			$('#nude').attr('checked',false);
			$('#drug').attr('checked',false);
			$('#gore').attr('checked',false);
			$('#sex').attr ('checked',false);
		} else {
			$('#nude').attr('checked',true);
			$('#drug').attr('checked',true);
			$('#gore').attr('checked',true);
			$('#sex').attr ('checked',true);
		}
	});
});

</script>
<head><title>Settings</title>

<link rel="stylesheet" type="text/css" href="/css/main.css">
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">

<style type="text/css">
.left {
	float: left;
	width: 33%;
}
.center {
	float: left;
	width: 33%;
}
.right {
	float: left;
	width: 32%;
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
			<?php echo($loginPrint) ?>
                </div>
	</div>
	<div id="navigation">
		<ul>
			<?php require ("nav.php"); ?>
		</ul>
	</div>
	<div id="content-container">
		<div id="content" style = "width: 100%;">
			<div id="tabs" style = "font-size: 15px; overflow: hidden;">
				<ul>
					<li><a href="#tabs-1">General Settings</a></li>
					<li><a href="#tabs-2">Contact Info</a></li>
					<li><a href="#tabs-3">My Userpage</a></li>
					<li><a href="#tabs-4">Block Users</a></li>

				</ul>
					<div id="tabs-1">
						<div class = "left">
						<form enctype="multipart/form-data" action="/processSettings.php" method="post">
						<img src="/avatars/<?php echo($userdir); ?>" align="bottom" style="width:75px;height:75px;">				
						<b>Submit a new icon: </b><br><input name="uploadedIcon" type="file" /><br><br>
						<b>Password: </b><br>
						Current Password:<br>
						<input type = "password" name = "currentPass" /><br>
						New Password:<br>
						<input type = "password" name = "newPass1" /><br>
						New Password(Repeat):<br>
						<input type = "password" name = "newPass2" /><br><br>
						</div>
						<div class = "center">
						<b>Gender: </b><br>
						<select name="gender" id="gender">
						<option value="m">Male</option>
						<option value="f">Female</option>
						<option value="n">Hide Gender</option>
						</select><br><br><br>
						<b>DOB: </b><br>
						<?php require ("extra/dobForm.php"); ?><br>
						<input type="checkbox" name="hideDOB" id="hideDOB" value="1"> <b><font size = '1'> Hide </font></b><br><br><br>
						<b>Mature Filter ONLY For: </b><br>
						<input type='checkbox' name='nude' id='nude' value='1'> Nudity <br>
						<input type='checkbox' name='drug' id='drug' value='1'> Drug Use <br>
						<input type='checkbox' name='gore' id='gore' value='1'> Gore or Strong Violence <br>
						<input type='checkbox' name='sex'  id='sex'  value='1'> Sexual Themes <br><br>
						<select name="mature" id="mature">
						<option value="0">Turn Off Filter</option>
						<option value="1">Mark Mature Content</option>
						<option value="2">Hide Mature Content</option>
						</select><br><br><br>
						</div>
						<div class = "right">
						<b>Location: <font size = 1><span id = "whylocbutton" style="cursor:pointer;">(<u>why?</u>)</span></font></b><br>
						Latitude: <br>
						<input type = "text" id = "latitude" name = "latitude" style = "height: 25px" value = "<? echo($profile['latitude']); ?>"><br>
						Longitude: <br>
						<input type = "text" id = "longitude" name = "longitude" style = "height: 25px" value = "<? echo($profile['longitude']); ?>"><br>
						<input type = "button" value = "Automatically Get Location" id = "locButton"><br><br><br>
						<b>Comments Font:</b> <br>
						<input type='checkbox' name='bold' id='bold' value='1'> <b>BOLD</b> <br>
						<input type='checkbox' name='italic' id='italic' value='1'> <i>ITALIC</i> <br>
						<input type='checkbox' name='underlined' id='underlined' value='1'> <u>UNDERLINED</u> <br>
						<!--<select name="font" id="font">
						<option value="ubuntu">Default</option>
						<option value="serif">Serif</option>
						<option value="sans-serif">Sans-serif</option>
						<option value="monospace">Monospace</option>
						<option value="helvetica">Helvetica</option>
						</select>-->
						</div>
					</div>
					<div id="tabs-2">
						<div class = "left">
							<input type="checkbox" name="hideContact" id="hideContact" value="1"> <b><font size = '2'> Hide Contact Information </font></b><br><br>
							<table border="0">
								<tr><td><b>AIM:</b> </td><td><input type = "text" name = "aim" style = "height: 25px" value = "<? echo($profile['aim']); ?>"></td></tr>
								<tr><td><b>MSN:</b> </td><td><input type = "text" name = "msn" style = "height: 25px" value = "<? echo($profile['msn']); ?>"></td></tr>
								<tr><td><b>Yahoo:</b> </td><td><input type = "text" name = "yahoo" style = "height: 25px" value = "<? echo($profile['yahoo']); ?>"></td></tr>
								<tr><td><b>Skype:</b> </td><td><input type = "text" name = "skype" style = "height: 25px" value = "<? echo($profile['skype']); ?>"></td></tr>
								<tr><td><b>Email:</b> </td><td><input type = "text" name = "email" style = "height: 25px" value = "<? echo($profile['email']); ?>"></td></tr>
								<tr><td><b>Phone:</b> </td><td><input type = "text" name = "phone" style = "height: 25px" value = "<? echo($profile['phone']); ?>"></td></tr>
								<tr><td><b>Steam:</b> </td><td><input type = "text" name = "steam" style = "height: 25px" value = "<? echo($profile['steam']); ?>"></td></tr>  
								<tr><td><b>Website:</b> </td><td><input type = "text" name = "website" style = "height: 25px" value = "<? echo($profile['website']); ?>"></td></tr>
							</table>
						</div>
						<div class = "center">
							SOMETHING GONNA
						</div>
						<div class = "right">
							GO HERE 
						</div>
					</div>
					<div id="tabs-3">
						<div class = "left">
						<b>Profile: </b><br><textarea name = 'profile' id = 'profile' cols=50 rows=12 style="width:90%;height:300px;"><? echo($profile['profile']); ?></textarea><br><br><center><span id='previewButton' style='cursor:pointer;margin-right:10%;'><u>SEE PREVIEW</u></span></center><br>
						</div>
						<div class = "center">
						<b>Friends: </b><br>
						<div id="displayFriends" style="border:solid 1px;overflow:auto;width:90%;height:300px;background:#ffffff;">
						</div>

						</div>
						<div class = "right">
						<br>Type and select a Username to add that person as a friend: <br>
						<input id="friend" name="friend"><input id="friend-id" type="hidden"><br><br>
						<img src="/images/neutraldeficon.png" id = "friend-icon" style="width:75px;height:75px" align="left">
						<b><u> <div id="friend-name"></div></u></b><br>
						<div id = "friendsButton" style="display:none;"> <img src="/images/plusico.png"><font size='3'><b> Add to Friends </b></font></div>
						</div>
					</div>
					<div id="tabs-4">
						<div class="left">
							An Entire Tab for Blocking Users. Go Nuts. 
						</div>
						<div class="center"> 
							User to <b>block</b> from your stuff(Check your spellin'):
							<input type="text" name="blockedUser" id="blockedUser" value="">
							<input type="button" value="Block!" id="blockButton">
							<br><br><br>
							User to <b>unblock</b> from your stuff(Check your spellin'):
							<input type="text" name="blockedUser" id="unblockedUser" value="">
							<input type="button" value="Unblock!" id="unblockButton">
						</div>
						<div class="right">
							<b>Blocked Users:</b>
							<ul>
								<?php
									$blocked = mysql_query("select * from blockedUsers where owner='".$cookiename."' order by id desc;");
									if (mysql_num_rows($blocked) > 0){
										while($row = mysql_fetch_array($blocked) ) {
											echo "<li><a href='/".$row['blockedUser']."'>".$row['blockedUser']."</a></li>";
										}
									} else {
										echo "<li>( You haven't blocked any users )</li>";
									}
								?>
							</ul>
						</div>
					</div>
				</div>
			<center style="margin: 25px;"><input type="submit" id = "submit" value="Apply Changes"><br></center>
			</form>
		</div>
	</div>
		<div id="ads">
			<?php require ("ads.php"); ?>
		</div>
		<div id="footer">
			<?php require ("footer.php"); ?>
		</div>
</div>
<div id="noloc" title="Error!" style="display:none;font-size:15px;">
<p>Your browser doesn't support automatic geolocation. You can either: </p>
<ul>
<li> Download a modern browser such as <a href = 'http://www.google.com/chrome'>Chrome</a> or <a href = 'http://www.mozilla.com/en-US/firefox/'>Firefox</a>.</li>
<li> Get your latitude & longitude manually by typing in your address at <a href = 'http://www.batchgeo.com/lookup/'>batchgeo.com</a>. </li>
</ul>
</div>

<div id="whyloc" title="Nearby Artists" style="display:none;font-size:15px;">
<p>Drawrawr uses your location to help you find artists near you! Your exact latitude and longitude is not shared, only your general distance from other users is shown. </p>
</div>
<div id="profilePreview" title="Preview" style="display:none;">
</div>
<div id="customConf" title="Confirm" style="display:none;">
</div>

<script src="/extra/gears_init.js"></script>
<script src="/extra/geo.js"></script>
</body>
</html>
