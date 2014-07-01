<?php
	$sqlserver = "localhost";
	$sqlusername = "milesyou_jasmi";
	$sqlpassword = "DuaPuluh5Jut@";
	$sqldbname= "milesyou_miles_social";
	$link = mysqli_connect($sqlserver,$sqlusername,$sqlpassword);
	if(!$link){
		die('could not connect: '.mysqli_error());
	}
	$db_selected = mysqli_select_db($link,$sqldbname);
	if(!$db_selected){
		die('cannot use database :' .mysqli_error());
	}
?>