<?php
require_once ("extra/library.php");

require_once ("prehtmlincludes.php");

$blockingUser = $_GET['blockingUser'];

?>

<title>Blocked <?php echo $blockingUser; ?></title>
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
			Sorry, you're blocked.
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
