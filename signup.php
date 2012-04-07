<?php 
require_once ("extra/library.php");

$tosfile  = fopen("tos.php",'r') or die("Couldn't read tos");
$tos = fread($tosfile,filesize("tos.php")) or die ("Couldn't read tos");
$tos = str_replace(array("\n\r","\r\n","\n","\r"),"<br>",$tos);

require_once ("prehtmlincludes.php");

?>
<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = "javascript" src = "/extra/jquery-ui.min.js"></script>
<script language = "javascript" src = "/extra/jsLib.js"></script>
<script language = 'javascript'>

$(function() {
	$("#submit").button();
});

function isAvailable(){
	var value = document.getElementById('username').value;
	xmlhttp = getXMLHTTP();
	xmlhttp.onreadystatechange=function() {
  		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			document.getElementById("nameTaken").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","/usernameTaken.php?username="+value,true);
	xmlhttp.send();
}

function checking(){
	document.getElementById("nameTaken").innerHTML = "<font color = 'yellow'>Checking for availability.. </font>";
}

</script>
<html>
<head><title>Signup to Drawrawr</title>

<link rel="stylesheet" type="text/css" href="/css/main.css">
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">

</head>
<body>

<div id="container">
	<div id="headerContainer">
		<div id="header">
			<h1>
				<?php require ("header.php") ?>
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
		<div id="content">
			<div class="newArt">
			<center>
			<h2>
				Become a Drawrawr! All the cool kids are doing it!
			</h2>
				<font size = 1px>You wanna be cool, right?</font>
			<p>
			<form method = "post" action = "processSignup.php">
			Username: <br>
			<span style='font-size:10px;'>(Only Alphanumerical characters and spaces allowed!)</span><br>
			<input type = "text" name = "username" id = "username" size = "20" maxlength = 25 onkeyup = "checking();" onchange = "isAvailable();" ><br><span id = 'nameTaken'> </span><br><br>
			Password: <br>
			<input type = "password" name = "password1" size = "20" maxlength = 35><br>
			Password(Repeat): <br>
			<input type = "password" name = "password2" size = "20" maxlength = 35><br><br>
			Email: <br>
			<input type = "text" name = "email1" size = "30" maxlength = 150><br>
			Email(Repeat): <br>
			<input type = "text" name = "email2" size = "30" maxlength = 150><br>
			Testing Password: <br>
			<input type = "password" name = "testingPass" size = "30" maxlength = 150><br><br>
			Gender (Optional!): <br>
			<select name="gender">
			<option value="n">Gender</option>
			<option value="m">Male</option>
			<option value="f">Female</option>
			</select><br><br>
			<?php require ("extra/dobForm.php"); ?><br><br>
			<div style="text-align:left;height:225px;width:700px;font:12 Georgia, Garamond, Serif;overflow:auto;">
				<?php echo ($tos); ?> <br>
			</div><br>
			By clicking "Submit!" you are agreeing to the above terms of service. <br><br>
			<input type = "submit" value = "Submit!" id = "submit">
			</form>
			</p>
			</center>
			</div>
		</div>
		<div id="aside"><div class = "newArt">
			<h2><b> RIGHTEOUS </b></h2>
			<p><b>Drawrawr</b> is a community of the best artists on Earth. Michelangelo and Bosch were Drawrawrs back in their day. Hell, even Bach is a drawrawr. Drawrawr isn't exclusive to just visual artists, you ninny! So, join up, or get out! Also if you get out, catch your finger in the door on the way out, jerk! </div>
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
