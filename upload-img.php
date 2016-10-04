<?php
include_once('inc/config.php');
include_once("inc/app_start.php");
include_once("inc/functions.php");

// files storage folder
$dir = 'img/uploads/';
 
$_FILES['file']['type'] = strtolower($_FILES['file']['type']);
 
if ($_FILES['file']['type'] == 'image/png' 
|| $_FILES['file']['type'] == 'image/jpg' 
|| $_FILES['file']['type'] == 'image/gif' 
|| $_FILES['file']['type'] == 'image/jpeg'
|| $_FILES['file']['type'] == 'image/pjpeg')
{	
    // setting file's mysterious name
    $file = md5(date('YmdHis')).'.jpg';
 
    // copying
    move_uploaded_file($_FILES["file"]["tmp_name"],$dir.$file);
 
    // displaying file
    $array = array(
        'filelink' => DOMAIN_ROOT.$dir.$file
    );
	
    echo stripslashes(json_encode($array));   
}

include_once("inc/app_end.php");
?>