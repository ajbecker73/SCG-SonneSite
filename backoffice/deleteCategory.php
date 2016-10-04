<?
	include_once('../inc/config.php');
	include_once(FILE_ROOT.'inc/app_start.php');
	
	$app->db->where('id',$_GET['delete'])->delete('lmg_cart_categories');

	include_once(FILE_ROOT.'inc/app_end.php');
?>