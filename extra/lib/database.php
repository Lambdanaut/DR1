<?php
##
## Drawrawr Database Driver
## This will process most database transactions
## throughout the lifetime of Drawrawr.
#

//mysqli_report(MYSQLI_REPORT_OFF);
if(!class_exists('Crypto')) require 'crypto.php';

// Cutting edge, but classic.
class DrawrawrDatabase extends \mysqli
{	
    public function __construct($host = null, $user = null, $pass = null, $database = null, $port = null, $socket = null) {

        return parent::__construct(
			is_null($host)		? CONFIG_MYSQL_HOST 	: $host,
			is_null($user)		? CONFIG_MYSQL_USERNAME : $user,
			is_null($pass)		? CONFIG_MYSQL_PASSWORD : $pass,
			is_null($database)	? CONFIG_MYSQL_DATABASE : $database,
			is_null($port)		? CONFIG_MYSQL_PORT 	: $port,
			is_null($socket)	? CONFIG_MYSQL_SOCKET 	: $socket);

        if(mysqli_connect_errno())
			throw new \Exception(mysqli_connect_error(), mysqli_connect_errno()); 
    }
	
	public function validateUserLogin($username, $password, $useLegacyMD5 = true, $ghost = false) {
		$username = $this->real_escape_string($username);
		($useLegacyMD5) ?
			$password = $this->real_escape_string(md5($password)) :
			$password = $this->real_escape_string(Crypto::encryptPassword($password));
		
		if(!$ghost)
		{
			if($result = $this->query("SELECT * FROM user_data WHERE username='$username' AND password='$password' LIMIT 1")) {
				$x = $result->fetch_assoc();
				$result->close();
				if(count($x) > 0) return $x;
				else return false;
			} else {
				return false;
			}
		} else {
			if($result = $this->query("SELECT * FROM user_data WHERE username='$username' LIMIT 1")) {
				$x = $result->fetch_assoc();
				$result->close();
				if(count($x) > 0) return $x;
				else return false;
			} else {
				return false;
			}
		}
	}

	public function isBanned($username) {
		$username = $this->real_escape_string($username);
		
		if($result = $this->query("SELECT * FROM bans WHERE username='$username' LIMIT 1")) {
			$x = $result->field_count;
			$result->close();

			if($x > 0) return true;	// Banned!
			else return false;		// Not banned!
		} else {
			$result->close();
			return false;
		}
	}

	public function getVanityURL($slug, $allowInactive = false) {
		$slug = $this->real_escape_string($slug);
		$allowInactive ? 	$queryText = "SELECT * FROM vanity WHERE slug='$slug' LIMIT 1" :
							$queryText = "SELECT * FROM vanity WHERE slug='$slug' AND active=1 LIMIT 1";
		
		if($result = $this->query($queryText)) {
			$x = $result->fetch_assoc();
			$result->close();
			return $x;
		} else {
			$result->close();
			return false;
		}
	}

	public function getUserFromUsername($username) {
		$username = $this->real_escape_string($username);
		$username = str_replace('.', ' ', $username);

		if($result = $this->query("SELECT * FROM user_data WHERE username='$username' LIMIT 1")) {
			$x = $result->fetch_assoc();
			$result->close();
			if(count($x) > 0) return $x;
			else return false;
		} else {
			$result->close();
			return false;
		}
	}

}
