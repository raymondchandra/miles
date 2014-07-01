<?php
	session_start();
	$email = $_POST['email'];
	$password = $_POST['password'];
	echo $email."".$password;
?>