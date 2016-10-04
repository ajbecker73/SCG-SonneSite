<?
	include_once('../inc/config.php');
	include_once(FILE_ROOT.'inc/app_start.php');
	
	$app->db->where('bid',$_GET['delete'])->delete('lmg_boxes');

	include_once(FILE_ROOT.'inc/app_end.php');
?>