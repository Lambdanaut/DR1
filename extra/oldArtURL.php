<?php
require 'lib/classes.php';
if(isset($_GET['art']) && $_GET['art'] != '') header('Location: /art/'.DrawrawrID::encode($_GET['art']));
else header('Location: /');
