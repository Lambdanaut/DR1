<?php require '../prehtmlincludes.php';
?>
<html>
<head><title>Drawrboard by Drawrawr</title>

<link rel="stylesheet" type="text/css" href="/css/main.css">
<script type="text/javascript" src="http://canto-js.googlecode.com/svn/trunk/canto.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>

</head>
<body>

<div id="container">
	<div id="headerContainer">
		<div id="header">
			<h1>
				<?php require '../header.php'; ?>
			</h1>
		</div>
                <div id="login">
			<?php echo($loginPrint); ?>
                </div>
	</div>
	<div id="navigation">
		<ul>
			<?php require '../nav.php'; ?>
		</ul>
	</div>
	<div id="content-container">
		<div id="content" style="float:right;">
			<div class="newart" style="text-align:center;">


			</div>

		</div>
		<div id="aside" style="float:left;">


			<div class="newart" style="overflow:hidden;width:200px;">
			<h3 style="margin: 0px 0px 3px 0px"> TOOLS </h3>
				
			</div>
		</div>
	</div>
	<div id="ads">
		<?php require '../ads.php'; ?>
	</div>
	<div id="footer">
		<?php require '../footer.php'; ?>
	</div>
</div>

</body>
</html>
