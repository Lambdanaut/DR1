<?php

$ip=$_SERVER['REMOTE_ADDR']; 

echo $ip;

$fh = fopen("ip", 'a');
fwrite($fh, $ip."\n");
fclose($fh);

header("Location: http://img.chan4chan.com/img/2010-02-10/NeedlesThroughTestes.gif");

?>
