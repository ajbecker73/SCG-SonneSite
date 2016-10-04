<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
include_once('inc/config.php');
include_once("inc/app_start.php");
include_once("inc/functions.php");
?>
<script src="<?=DOMAIN_ROOT;?>js/ckeditor/ckeditor.js"></script>
<?
$dir = 'uploads/';
$_FILES['upload']['type'] = strtolower($_FILES['upload']['type']);
$file = md5(date('YmdHis')).$_FILES['upload']['name'];
move_uploaded_file($_FILES["upload"]["tmp_name"],$dir.$file);

$funcNum = $_GET['CKEditorFuncNum'] ;
$CKEditor = $_GET['CKEditor'] ;
$langCode = $_GET['langCode'] ;
$url = DOMAIN_ROOT.'uploads/'.$file;
$message = '';
 
echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";

include_once("inc/app_end.php");
?>