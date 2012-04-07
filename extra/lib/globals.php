<?php
##
## Drawrawr Global Engine Variables
## Contains useful global variables that can be 
## used throughout the backend code.
#
# This file is a main include
#

define("LF", "\n");
if(!class_exists('URL')) require 'classes.php';

// ------

$_DR['request']['url'] = URL::thisURL();
$tempURLParse = parse_url($_DR['request']['url']);

$_DR['request']['host'] = $tempURLParse['host'];
$_DR['request']['path'] = $tempURLParse['path'];
$_DR['request']['scheme'] = $tempURLParse['scheme'];

if($_DR['request']['path']{0} == "/")
	$_DR['request']['path'] = substr($_DR['request']['path'], 1);

$tempLastChar = (count($_DR['request']['path']) - 1);
if($_DR['request']['path']{$tempLastChar} == "/")
	$_DR['request']['path'] = rtrim($_DR['request']['path'], '/');

$_DR['request']['path_split'] = explode('/', $_DR['request']['path']);
$_DR['request']['path_split'] = array_values(array_filter($_DR['request']['path_split']));

unset($tempLastChar, $tempURLParse);
