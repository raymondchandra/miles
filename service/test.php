<?php
	include("connect.php");
	include("class/place.php");

	$array['place']['name'] ="test";
	$array['place']['telp'] ="1234";
	$array['place']['locations'] ="30 40";
	$array['place']['address'] ="jl abc no 123";
	$array['place']['website'] ="www.abc.com";
	$array['place']['email'] ="abc@abc.com";
	$array['place']['photo'] ="";
	$array['place']['days'] =0;
	$array['feature'][] = "drink";
	$array['feature'][] = "eatery";
	$array['feature'][] = "liquor";
	$array['price'][0] = 40000;
	$array['price'][1] = 100000;

	$array['place']['rating'] =4;
	$string =json_encode($array);
	
	echo $string;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>