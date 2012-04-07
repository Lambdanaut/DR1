<?php
require_once ("extra/library.php");

require_once ("prehtmlincludes.php");
?>
<html>
<head><title>Search</title>

<link rel="stylesheet" type="text/css" href="/css/main.css">

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
		<div id="content" style = "width: 100%;">
			<div class = "newArt" style = "text-align: center;">
				<h3 style="border-bottom:solid 1px;"><img src = '/images/searchico.png'> Search (Temporary) </h3>

<form action="http://www.drawrawr.com/search.php" id="cse-search-box">
  <div>
    <input type="hidden" name="cx" value="partner-pub-2939388100966181:9116644476" />
    <input type="hidden" name="cof" value="FORID:9" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="55" />
    <input type="submit" name="sa" value="Search" />
  </div>
</form>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("elements", "1", {packages: "transliteration"});</script>
<script type="text/javascript" src="http://www.google.com/cse/t13n?form=cse-search-box&t13n_langs=en"></script>

<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&amp;lang=en"></script>


<div id="cse-search-results"></div>
<script type="text/javascript">
  var googleSearchIframeName = "cse-search-results";
  var googleSearchFormName = "cse-search-box";
  var googleSearchFrameWidth = 900;
  var googleSearchDomain = "www.google.com";
  var googleSearchPath = "/cse";
</script>
<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>



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

