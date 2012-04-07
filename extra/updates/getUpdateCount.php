<?php

require_once("extra/library.php");

$username = bbsclean($_REQUEST['user']);
$type     = bbsclean($_REQUEST['type']);

if ($type == 'all') {
	$result = mysql_num_rows(mysql_query("select id from updates where owner = '".$username."'"));
	$result = $result + mysql_num_rows(mysql_query("select id from inbox where owner = '".$username."' and outbox = '0' and new = '1'"));
} elseif ($type == 'inbox') {
	$result = mysql_num_rows(mysql_query("select id from inbox where owner = '".$username."' and outbox = '0' and new = '1'"));
} else {
	$result = mysql_num_rows(mysql_query("select id from updates where owner = '".$username."' and type = '".$type."'"));
}

echo ($result);

?>
