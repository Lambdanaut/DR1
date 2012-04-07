<?php
require_once ("extra/library.php");
require("extra/moodform.php");

require_once ("prehtmlincludes.php");
if(!class_exists('DrawrawrID')) require 'extra/lib/classes.php';

$con  = con();
mysql_select_db("drawrawr", $con) or die(mysql_error());

if (isset($_POST['location'])) {
	$location = $_POST['location'];
} elseif (isset($_GET['location'])) {
	$location = $_GET['location'];
} else {
	$result  = mysql_fetch_array(mysql_query("select * from arts where owner = '".$cookiename."' order by id desc limit 0,1"));
	$location = $result['id'];
}

mysql_close($con);

?>
<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = "javascript" src = "/extra/jquery-ui.min.js"></script>
<script language = "javascript" src = "/extra/jquery.Jcrop.min.js"></script>
<script language = "javascript">

var loc = "<?php echo($location); ?>";
var cookiename = "<?php echo($cookiename); ?>";
var cookiepass = "<?php echo($cookiepass); ?>";

function processCrop () {
	$.ajax({
		type: "POST",
		url: "/processCrop.php",
		data: "cookiename=" + cookiename + "&cookiepass=" + cookiepass + "&location=" + loc + "&x=" + $('#x').val() + "&y=" + $('#y').val() + "&w=" + $('#w').val() + "&h=" + $('#h').val(),
		success: function(reply) {
			$("#cropPost").submit();
		},
		error: function(){
			alert("Uploading Crop Failed! ");
		}
	});
}

$(function() {
	$("#submit").button();
	$("#submit").click(processCrop);
});

$(function(){
	var width  = $('#jcrop_target').width();
	var height = $('#jcrop_target').height();
	$('#jcrop_target').Jcrop({
		onChange: showPreview,
		onSelect: showPreview,
		aspectRatio: 1.2778,
		bgOpacity: 0.5,
		setSelect: [width * 0.2, height * 0.2, width * 0.8 , height * 0.8]
	});

	$('#x').val(coords.x);
	$('#y').val(coords.y);
	$('#w').val(coords.w);
	$('#h').val(coords.h);
});	

function showPreview(coords) {
	var width  = $('#jcrop_target').width();
	var height = $('#jcrop_target').height();
	
	var rx = 135 / coords.w;
	var ry = 110 / coords.h;

	$('#x').val(coords.x);
	$('#y').val(coords.y);
	$('#w').val(coords.w);
	$('#h').val(coords.h);

	$('#preview').css({
		width: Math.round(rx * width) + 'px',
		height: Math.round(ry * height) + 'px',
		marginLeft: '-' + Math.round(rx * coords.x) + 'px',
		marginTop: '-' + Math.round(ry * coords.y) + 'px'
	});
};
</script>
<html>
<head><title>Crop Thumbnail</title>

<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/css/jquery.Jcrop.css">

</head>
<body>

<div id="container" >
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
		<div class = "newArt">
			<center>
			<h3><u> Thumbnail Cropper </u></h3>
			<p>Click and drag on your artwork submission to select a thumbnail image. </p>
			</center>
		</div>
		<div id = "content" style = "width: 24%;">
			<div class = "newArt" style="position: relative; overflow: hidden;">
				<center>
				<h3><u> Cropped Preview Image </u></h3>
				<div style="
					width: 135px;
					height: 110px;
					overflow: hidden;
					border: 2px solid #c99681;
					margin: 5px 5px 5px 5px;
					-moz-box-shadow: 3px 3px 4px #d8bbb1;
					-webkit-box-shadow: 3px 3px 4px #d8bbb1;
					box-shadow: 3px 3px 4px #d8bbb1;
					-ms-filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#d8bbb1');
					filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#d8bbb1');
				">
					<img src = "/arts/<?php echo($location); ?>" id = "preview">
				</div><br>
				<b>X:</b> <input type = "text" id = "x" style = "width: 50px"> 
				<b>Y:</b> <input type = "text" id = "y" style = "width: 50px"><br>
				<b>W:</b> <input type = "text" id = "w" style = "width: 50px"> 
				<b>H:</b> <input type = "text" id = "h" style = "width: 50px"><br>
				<input type = "submit" value = "Submit!" id ="submit" style = "font-size: 40px;">
				</center>
			</div>

		</div>
		<div id = "aside" style = "width: 76%;">
			<div class = "newArt" style="position: relative;overflow:visible;">
				<center>
				<img src = "/arts/<?php echo($location); ?>" id = "jcrop_target" style = "border: 2px solid #c99681; margin: -2px;"><br>
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

<form id = "cropPost" method="post" action="/art/<?php echo(DrawrawrID::encode($location)); ?>">
<input type = "hidden" name = "cropBool" value = "true">
</form>

</body>
</html>
