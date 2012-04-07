<?php
##
## Drawrawr Vanity Processor
## This processes incoming URLs if they
## are not existing files or folders.
#
# This file is always called.
# Don't edit this guize.
#

@include 'extra/lib/session.php';

// UIV2.
if(isset($_SESSION['uiv2']) && $_SESSION['uiv2']) {
	include 'extra/uiv2/processor.php';
	exit;
}


// Aquire any missing dependencies.
if(!defined('Configuration')) require 'configuration.php';
if(!isset($_DR)) require 'extra/lib/globals.php';

if(!class_exists('URL')) require 'extra/lib/classes.php'; 
if(!class_exists('DrawrawrDatabase')) require 'extra/lib/database.php';


// General definitions.
define('DirectorEngine', true); // We're virtualizing stuff!
define('DIRECTOR_BASEPATH', $_DR['request']['scheme'].'://'.$_DR['request']['host'].'/');

if(isset($_DR['request']['path_split'][0])) {

	// Are we committing an action like "http://drawrawr.com/art:edit" ..?
	String::contains(':', $_DR['request']['path_split'][0]) ? 
		$__modeltemp = explode(':', $_DR['request']['path_split'][0], 2) : 	// Yup!
		$__modeltemp = array($_DR['request']['path_split'][0], '');			// Nope!

	define('DIRECTOR_MODEL', urldecode($__modeltemp[0]));
	define('DIRECTOR_MODEL_ACTION', urldecode($__modeltemp[1]));

	isset($_DR['request']['path_split'][1]) ? 
		define('DIRECTOR_VIEW', $_DR['request']['path_split'][1]) :
		define('DIRECTOR_VIEW', '');
} else {
	// We're simply at http://drawrawr.com/
	define('DIRECTOR_MODEL', '');
	define('DIRECTOR_MODEL_ACTION', '');
}
/*
echo '<h2>Director engine, your model is: ' . DIRECTOR_MODEL . '</h2>';
echo '<h2>Your action is: ' . urldecode(DIRECTOR_MODEL_ACTION) . '</h2>';
*/
$db = new DrawrawrDatabase();
if(!isset($director_urls[DIRECTOR_MODEL])) {

	// Non-standard URL! It might be a user.
	$userURL = $db->getUserFromUsername(DIRECTOR_MODEL);
	
	if(!$userURL) { 
		include '404.php'; 
		exit; 
	} else {
		if(CONFIG_DIRECTOR_PERFECT_USERURLS && 
		(DIRECTOR_MODEL != $userURL['username'])) {
			header ('HTTP/1.1 301 Moved Permanently');
  			header ('Location: ' . DIRECTOR_BASEPATH . urlencode($userURL['username']));
			exit;
		}

		define('VANITY', true);
		include 'userTemplate.php';
	}
} else {
	
	// Standard URL! Yay!
	
	if(!isset($director_urltypes[$director_urls[DIRECTOR_MODEL]]))
		{ throw new DirectorMisconfigurationException('Model is missing from URL types!'); }
	else $__d_type = $director_urltypes[$director_urls[DIRECTOR_MODEL]];

	define('DIRECTOR_ENGINE_REFERENCE', $director_urls[DIRECTOR_MODEL]);

	// Force Canonical if not on proper URL and enabled.
	if($__d_type[2] && (strtolower($__d_type[1]) != strtolower(DIRECTOR_MODEL))) {
		header ('HTTP/1.1 301 Moved Permanently');
  		header ('Location: '. DIRECTOR_BASEPATH . urlencode($__d_type[1]));
		exit;
	}

	include $__d_type[0];
}

class DirectorMisconfigurationException extends Exception { }

## ID conversion.

if(isset($_GET['todrawrawrid']))
	if(filter_var($_GET['todrawrawrid'], FILTER_VALIDATE_FLOAT)) {
		echo base_convert($_GET['todrawrawrid'], 10, 35); exit;
	}
if(isset($_GET['fromdrawrawrid'])) {
	echo base_convert($_GET['fromdrawrawrid'], 35, 10); exit;
}

