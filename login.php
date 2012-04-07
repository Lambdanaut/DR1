<?php

if(!defined('Configuration')) require 'configuration.php';
if(!class_exists('DrawrawrDatabase')) require 'extra/lib/database.php';

require 'extra/lib/session.php';

if((!isset($_POST['loginUser']) || !isset($_POST['loginPass'])) && !isset($_GET['ghost'])) {
	header('Location: /'); exit;
}

if(!CONFIG_LOGIN_ENABLE) { header("Location: /siteDown.php"); exit; }

$db = new DrawrawrDatabase();

include 'login.migration.php';
if(needsMigration($_POST['loginUser']) && $db->validateUserLogin($_POST['loginUser'], $_POST['loginPass'], true))
	$migrateResult = migrate($_POST['loginUser'], $_POST['loginPass']);

	
if(isset($_GET['ghost']) && $_GET['ghost'] != '' && $_SESSION['user']['moderator'])
	$userdata = $db->validateUserLogin($_GET['ghost'], "", false, true);
else
	$userdata = $db->validateUserLogin($_POST['loginUser'], $_POST['loginPass'], false, false);

if($userdata) {

	// Good login!
	$_SESSION['user']['authenticated'] = true;
	$_SESSION['user']['data'] = $userdata;

	switch($userdata['moderator']) {
		case 0:
		default:
			$_SESSION['user']['moderator'] = false;
			break;
		case 1:
			$_SESSION['user']['moderator']['vote'] 				= true;
			$_SESSION['user']['moderator']['propose'] 			= true;
			$_SESSION['user']['moderator']['viewreports'] 		= false;
			$_SESSION['user']['moderator']['postnews'] 			= false;
			$_SESSION['user']['moderator']['modifyjournals'] 	= false;
			$_SESSION['user']['moderator']['databaseaccess'] 	= false;
			break;

		case 2:
			$_SESSION['user']['moderator']['vote'] 				= true;
			$_SESSION['user']['moderator']['propose'] 			= true;
			$_SESSION['user']['moderator']['viewreports'] 		= true;
			$_SESSION['user']['moderator']['postnews'] 			= false;
			$_SESSION['user']['moderator']['modifyjournals'] 	= false;
			$_SESSION['user']['moderator']['databaseaccess'] 	= false;
			break;

		case 3:
			$_SESSION['user']['moderator']['vote'] 				= true;
			$_SESSION['user']['moderator']['propose'] 			= true;
			$_SESSION['user']['moderator']['viewreports'] 		= true;
			$_SESSION['user']['moderator']['postnews'] 			= true;
			$_SESSION['user']['moderator']['modifyjournals'] 	= false;
			$_SESSION['user']['moderator']['databaseaccess'] 	= false;
			break;

		case 4:
			$_SESSION['user']['moderator']['vote'] 				= true;
			$_SESSION['user']['moderator']['propose'] 			= true;
			$_SESSION['user']['moderator']['viewreports'] 		= true;
			$_SESSION['user']['moderator']['postnews'] 			= true;
			$_SESSION['user']['moderator']['modifyjournals'] 	= true;
			$_SESSION['user']['moderator']['databaseaccess'] 	= false;
			break;

		case 7:
			$_SESSION['user']['moderator']['vote'] 				= true;
			$_SESSION['user']['moderator']['propose'] 			= true;
			$_SESSION['user']['moderator']['viewreports'] 		= true;
			$_SESSION['user']['moderator']['postnews'] 			= true;
			$_SESSION['user']['moderator']['modifyjournals'] 	= true;
			$_SESSION['user']['moderator']['databaseaccess'] 	= true;
			$_SESSION['user']['moderator']['GOD'] 				= true;
			break;
	}

	$db->query("UPDATE user_data SET ip = '".$_SERVER['REMOTE_ADDR']."' WHERE username = '".$userdata['username']."'");
	$db->query("UPDATE user_data SET loggedin='1' where username='".$userdata['username']."'");

} else {

	// Bad login ):
	$_SESSION['user']['authenticated'] = false;
}

session_write_close();

($_SESSION['user']['authenticated']) ? $returnAppend = '?loginSuccess' : $returnAppend = '?loginFailure';
if(isset($migrateResult))
	($migrateResult) ? $returnAppend .= '&migrate=success' : $returnAppend .= '&migrate=failure';

if(defined('DirectorEngine'))
	if(defined('DIRECTOR_MODEL_ACTION') && DIRECTOR_MODEL_ACTION != '')
		header('Location: ' . base64_decode(DIRECTOR_MODEL_ACTION) . $returnAppend);
	else header('Location: /' . $returnAppend);
else header('Location: /' . $returnAppend);
