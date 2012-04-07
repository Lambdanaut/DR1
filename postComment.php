<?php

//die('Thou shall not post a comment.... yet.');

require_once("extra/library.php");
@require 'extra/lib/session.php';
if(!defined('Configuration')) require 'configuration.php';
if(!class_exists('DrawrawrDatabase')) require 'extra/lib/database.php';

$username = bbsclean($_POST['user']);
$password = bbsclean($_POST['pass']);
$type     = bbsclean($_POST['type']);
$text     = bbsclean($_POST['text']);
$location = bbsclean($_POST['location']);
$delete   = bbsclean($_POST['delete']);
$id       = bbsclean($_POST['id']);

$pageOwner = getOwner($type, $location);

$db = new DrawrawrDatabase();

if($id != '')
	$result = $db->query("SELECT * FROM comments WHERE id = '$id'")->fetch_array();


if(isset($_SESSION['user']['authenticated']) && $_SESSION['user']['authenticated']){
	if(($delete == '' or (!isset($delete))) and $id == '0') {
		$db->query("INSERT INTO comments (username, text, type, location, parent) 
						VALUES ('$username', '$text', '$type', '$location', '0')");
		$newComment = $db->query("SELECT * FROM comments WHERE id = '{$db->insert_id}'")->fetch_array();

		switch(strtolower($type)) {
			default: break;

			case 'art':
				$artResult = $db->query("SELECT * FROM arts WHERE id = '$location'")->fetch_array();

				if($artResult['owner'] != $username)
					$db->query("INSERT INTO updates(owner, sender, type, location) 
						VALUES ('{$artResult['owner']}', '$username', 'comment', '{$newComment['id']}')");
				break;

			case 'journal':
				$journalResult = $db->query("SELECT * FROM journals WHERE id = '$location'")->fetch_array();
			
				if($journalResult['owner'] != $username)
					$db->query("INSERT INTO updates(owner, sender, type, location) 
						VALUES ('{$journalResult['owner']}', '$username', 'comment', '{$newComment['id']}')");
				break;

			case 'userpage':
				if($location != $username)
					$db->query("INSERT INTO updates(owner, sender, type, location) 
						VALUES ('$location', '$username', 'comment', '{$newComment['id']}')");
		}

	} elseif(($delete == '' or (!isset($delete))) and $id != '0') {
		$db->query("INSERT INTO comments (username, text, type, location, parent) 
						VALUES ('$username', '$text', '$type', '$location', '$id')");
		$newComment = $db->query("SELECT * FROM comments WHERE id = '{$db->insert_id}'")->fetch_array();

		$db->query("INSERT INTO updates(owner, sender, type, location) 
						VALUES ('{$result['username']}', '$username', 'comment', '{$newComment['id']}')");
		$db->query("DELETE FROM updates WHERE location = '$id' AND type = 'comment' AND owner='$username'"); 

	} elseif($result['username'] == $username or $location == $username or $pageOwner == $username or 
				($_SESSION['user']['moderator'] && $_SESSION['user']['moderator']['modifyjournals'])) {

		deleteFromArray(getChildren($id));
		$db->query("DELETE FROM comments WHERE id = '$id'");
		$db->query("DELETE FROM updates where location = '$id' and type = 'comment'");
		if ($_SESSION['user']['moderator'] ) {
			mysql_query("insert into moderatorAct (owner,action,date) values ('".$username."','deleted a comment. ',CURDATE() )") or exit ("Database couldn't add moderator action.");
		}
	} 
}

$db->close();
?>
