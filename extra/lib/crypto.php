<?php
##
## Drawrawr Cryptographics Library
## Contains generic functionality for the processing
## of user passwords and other forms of encryptable
## plaintext input.
#
# This file is used throughout Drawrawr,
# including the login system and session
# retention system.
#

// if(!defined("Drawrawr")) exit;

class Crypto {
	public static function encryptPassword($plaintext, $salt = 'nochinesehackrsplz', $dSalt = true) {
		if($plaintext == null || $plaintext == '')
			return '';

		if($dSalt) $salt .= $plaintext{0};
        
		if (CRYPT_SHA512 == 1) return substr(crypt($plaintext, '$6$rounds=2000$'.$salt.'$'), 32, 100);
		else throw new SecurityException('SHA512 does not exist!');
	}
}

class SecurityException extends Exception { }
