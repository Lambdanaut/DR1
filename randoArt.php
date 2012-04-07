<?php
require ("extra/library.php");
if(!class_exists('DrawrawrID')) require 'extra/lib/classes.php';

$query = mysql_query("select id from arts");

$array = array();
while($row = mysql_fetch_array($query)) {
  $array[] = $row['id'];
}

$randKey = array_rand($array,1);

header("Location: /art/".DrawrawrID::encode($array[$randKey]));

?>
