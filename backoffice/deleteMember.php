<?
	include_once('../inc/config.php');
	include_once(FILE_ROOT.'inc/app_start.php');
	
	$app->db->where('mid',$_GET['delete'])->delete('lmg_members');

	include_once(FILE_ROOT.'inc/app_end.php');
?>