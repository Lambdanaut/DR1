<?php
require 'configuration.php';
require 'extra/lib/database.php';
require 'extra/lib/session.php';

$db = new DrawrawrDatabase();
$updateCount = $db->query("SELECT viewed FROM updates WHERE owner= '{$_SESSION['user']['data']['username']}' AND viewed='0'")->num_rows;

if($updateCount > 0)
	echo "<a href = '/extra/updates'><img src = '/images/fullupdatesico.png'></a> <a href = '/extra/updates'><b>Updates!</b></a> <span style='font-size:10px;font-weight:bold;'>($updateCount)</span>";
else
	echo "<a href = '/extra/updates'><img src = '/images/updatesico.png'></a> <a href = '/extra/updates'>Updates</a>";
