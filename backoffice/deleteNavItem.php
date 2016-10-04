<?
	include_once('../inc/config.php');
	include_once(FILE_ROOT.'inc/app_start.php');
	
	$app->db->where('nid',$_GET['delete'])->delete('lmg_navigation');

	include_once(FILE_ROOT.'inc/app_end.php');
?>