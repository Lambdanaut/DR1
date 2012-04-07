<?php
require_once ("extra/library.php");

require_once ("prehtmlincludes.php");
?>

<title>404 - File not found</title>
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
				<?php
				switch (rand(0,5)) {
					case 0:
						echo("<center><img src = '/images/cute404.png'><br>File Not Found</center>");
						break;
					case 1:
						echo("<center><img src = '/images/scary404.png'><br>File Not Found</center>");
						break;
					case 2:
						echo("<center><img src = '/images/sexy404.png'><br>File Not Found</center>");
						break;
					case 3:
						echo("<center><img src = '/images/bile404.png'><br>File Not Found</center>");
						break;
					case 4:
						echo("<center><img src = '/images/bomb404.png'><br>File Not Found</center>");
						break;
                                        case 5:
                                                echo("<center><img src = '/images/vonderdevil404.png'><br>File Not Found</center>");
                                                break;
				}
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
