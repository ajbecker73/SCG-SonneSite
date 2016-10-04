<?
	include_once('../inc/config.php');
	include_once(FILE_ROOT.'inc/app_start.php');
	
	$app->db->where('sid',$_GET['delete'])->delete('lmg_slideshow');

	include_once(FILE_ROOT.'inc/app_end.php');
?>