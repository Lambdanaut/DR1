<script language = "javascript" src = "/extra/jquery.min.js"></script>
<script language = 'javascript'>
$(function () {
	$("#showThumbs").click(function () {
		$(".thumb").slideDown("slow");
		$("#showThumbs").hide();
	});
});

function openURL(sURL) {
	opener.document.location = sURL;
}
</script>
<?php
if(!class_exists('DrawrawrID')) require 'extra/lib/classes.php';
require_once ("extra/library.php");

$con = con();

echo('<body bgcolor="#ffffff">');

$username = bbsclean($_GET['username']);

$favResult = mysql_query("select * from favs where faver = '".$username."' order by id desc");

echo("<html><title>".$username."'s Favorites</title><span id='showThumbs' style='float:right;cursor:pointer;'><u><b><font color = '#513727'>Show Thumbnails!</font></b></u></span><center><h2><img src = '/images/favico.png'><u><font color = '#513727'>".$username."'s</u> Favorites</font></h2></center>");

$z = 0;
while($row = mysql_fetch_array($favResult)) {
	$art = mysql_fetch_array(mysql_query("select * from arts where id = '".$row['piece']."'"));
	if ($z % 2 == 0)
		{echo ("<div style = 'width:100%;background: #f1e3d1;'>");}
		else {echo("<div style = 'width:100%;background: #d5bda4;'>");}
	echo ("<div style='float:right'> ".$row['date']." </div><a href = 'javascript:openURL(\"/art/".DrawrawrID::encode($row['piece'])."\");'>".$art['title']."</a></div><span class='thumb' style='display:none;'><img src = '/arts/".$row['piece'].".thumb' style='width:115px;height:90px;'></span>");
	$z++;
}

?>
</html>
