<?php
require("extra/library.php");

$message = bbsclean($_POST['text']);
$from    = bbsclean($_POST['user']);

if (empty($_POST['user'])) {
	$from = "Anonymous";
}

if (strlen($message) >= 10) {
	echo("<h2>Question Sent!</h2> ");
	mail("drawrawr@live.com" , "Question from ".$from , $message, "From: ".$from);
} else {
	echo("<h3>Your question must be at least 10 characters in length, and was not sent! </h3> ");
}

?>
