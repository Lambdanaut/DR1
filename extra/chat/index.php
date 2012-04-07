<?php
require_once ("extra/library.php");
require_once ("prehtmlincludes.php");
?>

<html><title>Chat</title>
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
	<div id = "ads">
		<?php require ("ads.php"); ?>
	</div>
	<div id="content-container">
		<center>
		<div id = "content" style = "width: 100%;">
			<div class = "newArt" style = "text-align: center;">
				<center>
<iframe width="700" height="750"src="http://widget.mibbit.com/?settings=c3b3c20dab8944be0f849d820d7c968e&server=irc.Mibbit.Net&channel=%23DrawRawrChat_test_channel"></iframe>
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
