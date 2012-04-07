<?php
require("extra/library.php");

$to      = bbsclean($_POST['email']);
$subject = 'Password Reset';

$users = mysql_query("select * from user_data where email='".$to."'");

if(mysql_affected_rows() != 0) {
	while ($user = mysql_fetch_array($users)) {
		$userDir = strtolower(str_replace(" ",".",$user['username']));
		$message = "
Hey ".$user['username']."! DrawRawr here! We got a message from you asking us to reset your password. If you're sure you wanna reset your password, click the following link:

	http://drawrawr.com/resetPassword.php?id=".$user['id']."&p=".$user['password']."


If not, delete this message and move along with your sad, pathetic and short existence.
";

		mail($to , $subject , $message, "From: DontRespond@drawrawr.com");
		echo("Email Sent! ");
	}
} else {
	echo("That email isn't registered to an account ): ");
}


?>
