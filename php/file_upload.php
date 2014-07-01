<?php
session_start();
$ds = DIRECTORY_SEPARATOR;

$storeFolder = '../file_upload';
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];
      
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
 
    $targetFile =  $targetPath. $_FILES['file']['name'];
    move_uploaded_file($tempFile,$targetFile);
	//$listfile = $targetFile;
	//$_SESSION['file'][$_SESSION['count']]=$targetFile;
	//$_SESSION['count'] += 1;
	$_SESSION['list']=$targetFile;
}
?>