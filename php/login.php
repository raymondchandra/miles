<?php
	session_start();
	$email = $_POST['login_email'];
	$password = $_POST['login_pass'];
	echo $email."".$password;
?>
