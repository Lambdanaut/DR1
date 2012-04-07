<?php

require '../../configuration.php';
require '../lib/database.php';
require '../lib/session.php';

$cookiename = $_SESSION['user']['data']['username'];
$cookiepass = $_SESSION['user']['data']['password'];

if(!isset($_SESSION['user']['moderator']) || $_SESSION['user']['moderator'] == false) {
	header('Location: /'); exit;
}

if($_SESSION['user']['moderator']['postnews'] == false) {
	header('Location: http://drawrawr.com/extra/mod/?broadcastUnallowed'); exit;
}

if(!isset($_POST['alias'])) { header('Location: http://drawrawr.com/extra/mod/'); exit; }
if(!isset($_POST['title'])) { header('Location: http://drawrawr.com/extra/mod/'); exit; }
if(!isset($_POST['content'])) { header('Location: http://drawrawr.com/extra/mod/'); exit; }

$database = new DrawrawrDatabase();
$alias = $database->real_escape_string($_POST['alias']);
$title = $database->real_escape_string($_POST['title']);
$content = $database->real_escape_string($_POST['content']);
		
$results = $database->query("SELECT username FROM user_data", MYSQLI_USE_RESULT);

$users = array();
while($addResult = $results->fetch_array())
	$users[] = $addResult[0];

$results->free();

foreach($users as $user)
	$x = $database->query("INSERT INTO inbox(id, owner, username, title, text, new) VALUES (NULL, '$user', '$alias', '[Broadcast] $title', '$content', '1')");

header('Location: http://drawrawr.com/extra/mod/?broadcastSuccess'); exit;
