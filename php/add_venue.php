<?php
	session_start();
	$name = $_POST['name'];
	//$location = $_POST['location'];
	$day = $_POST['day'];
	$list="";
	$counter = $_SESSION['count']-1;
	while($counter!=0){
		$list=$list."-".$_SESSION['file'][$counter];
		$counter -= 1;
	}
	session_destroy();
	echo $name."".$day."".$list;
?>