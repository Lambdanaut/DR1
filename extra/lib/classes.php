<?php

###############
## URL Class ##
###############

class URL {
	public static function thisURL($forceSSL = false) {
		if(!isset($_SERVER['REQUEST_URI']))
	        	$serverrequri = $_SERVER['PHP_SELF'];
	    	else
			$serverrequri = $_SERVER['REQUEST_URI'];
	
		if($forceSSL) $s = 's';
		else $s = empty($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on') ? 's' : '';
		
		$protocol = String::left(strtolower($_SERVER['SERVER_PROTOCOL']), '/').$s;
		$port = ($_SERVER['SERVER_PORT'] == '80') ? '' : (':'.$_SERVER['SERVER_PORT']);

		return $protocol.'://'.$_SERVER['SERVER_NAME'].$port.$serverrequri;   
	}
}

class DrawrawrID {
	public static function encode($integer) {
		if(filter_var($integer, FILTER_VALIDATE_FLOAT))
			return base_convert($integer, 10, 35);
		else return false;
	}

	public static function decode($drawrawrid) {
		try { return base_convert($drawrawrid, 35, 10); }
		catch(Exception $x) { return false; }
	}

}

################
## Byte Class ##
################

class Byte {
	public static function Readable($val, $round = 0) {
		$unit = array('','K','M','G','T','P','E','Z','Y');
		
		while($val >= 1000) {
			$val /= 1024;
			array_shift($unit);
		}
		
		return round($val, $round) . array_shift($unit) . "B";
	}
}


##################
## String Class ##
##################

class String {
	public static function contains($str, $content, $ignorecase = true){
		if ($ignorecase){
			$str = strtolower($str);
			$content = strtolower($content);
		}  
		return (boolean)strpos($content,$str) ? true : false;
	}
	
	public static function endsWith($needle, $haystack){
		return (boolean) (substr($haystack, strlen($needle)*(-1)) == $needle);
	}
	
	public static function beginsWith($needle, $haystack) {
    	return (boolean) (substr($haystack, 0, strlen($needle))==$needle);
	}
	
	public static function toLinks($string = null) {
		if($string == null || !preg_match('/(http|www\.|ftp\.|@)/i', $string)) { return $string; }
		$lines = explode('\n', $string); $return = '';
		
		while (list($k,$l) = each($lines)) { 
			$l = preg_replace("/([ \t]|^)www\./i", "\\1http://www.", $l);
    		$l = preg_replace("/([ \t]|^)ftp\./i", "\\1ftp://ftp.", $l);
			$l = preg_replace("/(http:\/\/[^ )\r\n!]+)/i", "<a href=\"\\1\">\\1</a>", $l);
			$l = preg_replace("/(https:\/\/[^ )\r\n!]+)/i", "<a href=\"\\1\">\\1</a>", $l);
			$l = preg_replace("/(ftp:\/\/[^ )\r\n!]+)/i", "<a href=\"\\1\">\\1</a>", $l);
			$l = preg_replace("/([-a-z0-9_]+(\.[_a-z0-9-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)+))/i", "<a href=\"mailto:\\1\">\\1</a>", $l);
			$return .= $l."\n";
		}
		
		return $return;
	}
	
	public static function getBetween($start, $end, $haystack){
		$r = explode($start, $haystack);
		if(isset($r[1])){
			$r = explode($end, $r[1]);
			return $r[0];
		}
		return '';
	}
	
	public static function removeLinks($string) {
		return preg_replace('/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i', '', $string);
	}

	public static function left($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}
}


################
## Slug Class ##
################


class Slug {
	private static function my_str_split($string) {
		$slen = strlen($string);
		for($i=0; $i<$slen; $i++) $sArray[$i]=$string{$i};
		return $sArray;
	}
	
	private static function noDiacritics($string) {
		$cyrylicFrom = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 
	  						'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 
							'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 
							'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 
							'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 
							'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 
							'ъ', 'ы', 'ь', 'э', 'ю', 'я');
							
		$cyrylicTo = array('A', 'B', 'W', 'G', 'D', 'Ie', 'Io', 'Z', 'Z', 'I', 
	  						'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 
							'U', 'F', 'Ch', 'C', 'Tch', 'Sh', 'Shtch', '', 'Y', 
							'', 'E', 'Iu', 'Ia', 'a', 'b', 'w', 'g', 'd', 'ie', 
							'io', 'z', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 
							'p', 'r', 's', 't', 'u', 'f', 'ch', 'c', 'tch', 'sh', 
							'shtch', '', 'y', '', 'e', 'iu', 'ia'); 
							
		$from = array("Á", "À", "Â", "Ä", "Ă", "Ā", "Ã", "Å", "Ą", "Æ", "Ć", "Ċ", "Ĉ", 
	  				"Č", "Ç", "Ď", "Đ", "Ð", "É", "È", "Ė", "Ê", "Ë", "Ě", "Ē", "Ę", 
					"Ə", "Ġ", "Ĝ", "Ğ", "Ģ", "á", "à", "â", "ä", "ă", "ā", "ã", "å", 
					"ą", "æ", "ć", "ċ", "ĉ", "č", "ç", "ď", "đ", "ð", "é", "è", "ė", 
					"ê", "ë", "ě", "ē", "ę", "ə", "ġ", "ĝ", "ğ", "ģ", "Ĥ", "Ħ", "I", 
					"Í", "Ì", "İ", "Î", "Ï", "Ī", "Į", "Ĳ", "Ĵ", "Ķ", "Ļ", "Ł", "Ń", 
					"Ň", "Ñ", "Ņ", "Ó", "Ò", "Ô", "Ö", "Õ", "Ő", "Ø", "Ơ", "Œ", "ĥ", 
					"ħ", "ı", "í", "ì", "i", "î", "ï", "ī", "į", "ĳ", "ĵ", "ķ", "ļ", 
					"ł", "ń", "ň", "ñ", "ņ", "ó", "ò", "ô", "ö", "õ", "ő", "ø", "ơ", 
					"œ", "Ŕ", "Ř", "Ś", "Ŝ", "Š", "Ş", "Ť", "Ţ", "Þ", "Ú", "Ù", "Û", 
					"Ü", "Ŭ", "Ū", "Ů", "Ų", "Ű", "Ư", "Ŵ", "Ý", "Ŷ", "Ÿ", "Ź", "Ż", 
					"Ž", "ŕ", "ř", "ś", "ŝ", "š", "ş", "ß", "ť", "ţ", "þ", "ú", "ù", 
					"û", "ü", "ŭ", "ū", "ů", "ų", "ű", "ư", "ŵ", "ý", "ŷ", "ÿ", "ź", "ż", "ž");
					
		$to = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "C", "C", "C", 
	  				"C", "C", "D", "D", "D", "E", "E", "E", "E", "E", "E", "E", "E", 
					"G", "G", "G", "G", "G", "a", "a", "a", "a", "a", "a", "a", "a", 
					"a", "ae", "c", "c", "c", "c", "c", "d", "d", "d", "e", "e", "e", 
					"e", "e", "e", "e", "e", "g", "g", "g", "g", "g", "H", "H", "I", 
					"I", "I", "I", "I", "I", "I", "I", "IJ", "J", "K", "L", "L", "N", 
					"N", "N", "N", "O", "O", "O", "O", "O", "O", "O", "O", "CE", "h", 
					"h", "i", "i", "i", "i", "i", "i", "i", "i", "ij", "j", "k", "l", 
					"l", "n", "n", "n", "n", "o", "o", "o", "o", "o", "o", "o", "o", 
					"o", "R", "R", "S", "S", "S", "S", "T", "T", "T", "U", "U", "U", 
					"U", "U", "U", "U", "U", "U", "U", "W", "Y", "Y", "Y", "Z", "Z", 
					"Z", "r", "r", "s", "s", "s", "s", "B", "t", "t", "b", "u", "u", 
					"u", "u", "u", "u", "u", "u", "u", "u", "w", "y", "y", "y", "z", "z", "z");
					
		$from = array_merge($from, $cyrylicFrom);
		$to = array_merge($to, $cyrylicTo);
		
		$newstring = str_replace($from, $to, $string);   
		return $newstring;
	}
	
	public static function makeSlug($string, $maxlen = 0) {
		$newStringTab = array();
		$string = strtolower(self::noDiacritics($string));
		
		if(function_exists('str_split')) $stringTab=str_split($string);
		else $stringTab = self::my_str_split($string);
		
		$numbers = array("0","1","2","3","4","5","6","7","8","9","-");
		
		foreach($stringTab as $letter) {
			if(in_array($letter, range("a", "z")) || in_array($letter, $numbers))
				$newStringTab[] = $letter;
			elseif($letter == " ")
				$newStringTab[]="-";
		}
		
		if(count($newStringTab)) {
			$newString = implode($newStringTab);
			if($maxlen > 0) $newString=substr($newString, 0, $maxlen);
			$newString = self::removeDuplicates('--', '-', $newString);
		}
		else $newString = '';
		return $newString;
	}
	
	public static function checkSlug($sSlug) {
		if(ereg("^[a-zA-Z0-9]+[a-zA-Z0-9\_\-]*$", $sSlug))
			return true;
		else return false;
	}
	
	private static function removeDuplicates($sSearch, $sReplace, $sSubject) {
		$i = 0;
		
		do {
			$sSubject = str_replace($sSearch, $sReplace, $sSubject);
			$pos = strpos($sSubject, $sSearch);
			
			$i++;
			
			if($i > 100) throw new \Exception("Loop error.");
		
		} while($pos !== false);
		
		return $sSubject;
	}
}


class Error extends Exception
{
    protected $types = array(
		E_ERROR				=> 'ERROR',
		E_WARNING			=> 'WARNING',
		E_PARSE				=> 'PARSING ERROR',
		E_NOTICE			=> 'NOTICE',
		E_CORE_ERROR		=> 'CORE ERROR',
		E_CORE_WARNING		=> 'CORE WARNING',
		E_COMPILE_ERROR		=> 'COMPILE ERROR',
		E_COMPILE_WARNING	=> 'COMPILE WARNING',
		E_USER_ERROR		=> 'USER ERROR',
		E_USER_WARNING		=> 'USER WARNING',
		E_USER_NOTICE		=> 'USER NOTICE',
		E_STRICT			=> 'STRICT NOTICE',
		E_RECOVERABLE_ERROR	=> 'RECOVERABLE ERROR');

    public function handle($code, $string, $file, $line, $context)
    {
        $exception = new self($string, $code);
        $exception->line = $line;
        $exception->file = $file;
        throw $exception;
    }

    public function getType()
    {
        return isset($this->types[$this->getCode()]) ? $this->types[$this->getCode()] : null ;
    }
}

function ExceptionHandler($exception) {
	include 'exceptionDisplay.php';
}


function timeagoFormat($timestamp) {
	return '<abbr class="reltime" title="'.date('c', strtotime($timestamp)).'">'.date("F j, Y", strtotime($timestamp)).'</abbr>';
}
















# Errors.
#error_reporting(E_ALL);
#set_error_handler(array('Error', 'handle'), E_ALL);
#set_exception_handler('ExceptionHandler');
