<?php
 
 $mainFolder = $_POST['name'];
 $location = $_POST['location'];
foreach ($_FILES["images"]["error"] as $key => $error) {
  if ($error == UPLOAD_ERR_OK) {
    $name = $_FILES["images"]["name"][$key];
	if(!file_exists("../file_upload/place/".$mainFolder."-".$location."/")){
		mkdir("../file_upload/place/".$mainFolder."-".$location."/");
	}
    move_uploaded_file( $_FILES["images"]["tmp_name"][$key], "../file_upload/place/".$mainFolder."-".$location."/". $_FILES['images']['name'][$key]);
  }
}
 
echo "<h2>Successfully Uploaded Images</h2>";