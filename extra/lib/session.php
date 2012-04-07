<?php
if(!defined('CONFIG_LOGIN_SESSION_NAME'))
	define('CONFIG_LOGIN_SESSION_NAME', 'druserident');

session_cache_limiter('nocache');
//session_cache_expire(30);

session_name(CONFIG_LOGIN_SESSION_NAME);
session_start();

if(!isset($_SESSION['last_access']) || (time() - $_SESSION['last_access']) > 60)
	$_SESSION['last_access'] = time();

$lifetime = 6000;
//setcookie(session_name(),session_id(),time() + $lifetime);
