<?php
##
## Drawrawr Artwork Driver
## This enables simplistic actions for
## art pieces throughout Drawrawr.
#

class Artwork {
	public static function getPopular($database = null) {
		if($database == null) {
			if(!class_exists('DrawrawrDatabase'))
				require 'database.php';
			
			$database = new DrawrawrDatabase();
		}

		if($result = $this->query("SELECT * FROM favs ORDER BY id DESC LIMIT 0,125")) {
			$favoratesResult = $result->fetch_assoc();
			$result->close();

			if($x > 0) return true;	// Banned!
			else return false;		// Not banned!
		} else {
			$result->close();
			return false;
		}

	}

	public static function isMature($artpiece, 
		$preferences = array('nude' => true, 'drug' => true, 'gore' => true, 'sex' => true, 'mature' => true), 
		$database = null) {

	}

	public static function isMatureFromArray($artpieceProperties, 
		$preferences = array('nude' => true, 'drug' => true, 'gore' => true, 'sex' => true, 'mature' => true), 
		$database = null) {

		if($preferences['mature'])
			

	}
		
