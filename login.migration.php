<?php

if(!defined('Configuration')) require 'configuration.php';
if(!class_exists('DrawrawrDatabase')) require 'extra/lib/database.php';

function needsMigration($username, $db = null) {
	if($db == null) $db = new DrawrawrDatabase();
	$username = $db->real_escape_string($username);
	$result = $db->query("SELECT password FROM user_data WHERE username='$username' LIMIT 1");

	$x = $result->fetch_array();
	$x = $x['password'];

	if(strlen($x) == 32) return true;
	else return false;
}

function migrate($username, $inputPassword, $db = null) {
	if($db == null) $db = new DrawrawrDatabase();

	// Authenticate via old method.
	$username = $db->real_escape_string($username);
	$passwordMD5 = $db->real_escape_string(md5($inputPassword));

	$result = $db->query("SELECT password FROM user_data WHERE username='$username' AND password='$passwordMD5' LIMIT 1");
	if($result->num_rows <= 0) return false;

	// Update!
	$newPassword = $db->real_escape_string(Crypto::encryptPassword($inputPassword));
	$result = $db->query("UPDATE user_data SET password='$newPassword' WHERE username='$username'");

	if($result) return true;
	else return false;
}
