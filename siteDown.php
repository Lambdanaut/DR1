<?php
require_once ("extra/library.php");

?>

<title>DrawRawr Login is Down! </title>
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
				<p><img src='/images/drawrawrdown.png'></p>
				<p>Looks like DrawRawr user login is down for maintenance! </p>
				<p>We're working our gnarled, disfigured tusks to death trying to get everything back up and running! Please, bear with us! </p>
				<?php
				setcookie("user", "", time()-3600,"/");
				setcookie("pass", "", time()-3600,"/");
				?>
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
