<?php
require_once ("extra/library.php");

mysql_query("select id from user_data where id = '".$_GET['id']."' and password = '".$_GET['p']."'") or die(mysql_error());
if (mysql_affected_rows() == 0) {
	header("Location: index.php");
}

require_once ("prehtmlincludes.php");
?>
<script language = "javascript">

$(document).ready(function () {
	$("#pass1,#pass2").keyup(function () {
		var pass1 = $("#pass1").val();
		var pass2 = $("#pass2").val();
		if (pass1 == pass2 && pass1.length > 1) {
			$("#submitButton").fadeIn("slow");
			$("#smiley1").html("☺");
			$("#smiley2").html("☺");
		} else {
			$("#submitButton").hide("fast");
			$("#smiley1").html("☹");
			$("#smiley2").html("☹");
		}
	});
});

</script>

<html><title>Password Reset</title>
<head>

<link rel="stylesheet" type="text/css" href="/css/main.css" />

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
		<div id = "content" style = "width: 100%;">
			<div class = "newArt" style = "text-align: center;">
				<center>
					<h3>Password Reset</h3>
					<form method="post" action="/processResetPassword.php">
						<input type="hidden" name="p" value="<?php echo($_GET['p']); ?>">
						<input type="hidden" name="id" value="<?php echo($_GET['id']); ?>">
						New Password: <br>
						<input type = "password" name = "pass1" id = "pass1"><font size=6 id="smiley1"></font>
						<br>
						New Password(Repeat): <br>
						<input type = "password" name = "pass2" id = "pass2"><font size=6 id="smiley2"></font> 
						<br>
						<div id="submitButton" style="display:none;">
							<input type = "submit" value = "Submit">
						</div>
					</form>
				</center>
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
