<?php

require 'extra/lib/session.php';
session_destroy();

if(defined('DirectorEngine'))
	if(defined('DIRECTOR_MODEL_ACTION') && DIRECTOR_MODEL_ACTION != '')
		header('Location: ' . base64_decode(DIRECTOR_MODEL_ACTION));
	else header('Location: /');
else header('Location: /');
