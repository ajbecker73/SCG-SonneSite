<?
	include_once('../inc/config.php');
	include_once(FILE_ROOT.'inc/app_start.php');
	
	$app->db->where('pid',$_GET['delete'])->delete('lmg_gallery_photos');

	include_once(FILE_ROOT.'inc/app_end.php');
?>