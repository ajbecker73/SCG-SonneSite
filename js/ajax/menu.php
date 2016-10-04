<?
	include_once('../../inc/config.php');
	include_once("../../inc/app_start.php");
	
	$menuItems = $app->db
		->orderBy('sort','ASC')
		->where('parent','0')
		->get('lmg_navigation');
		$mCount = count($menuItems);
		
	$pages = $app->db
		->orderBy('page_name','ASC')
		->get('lmg_pages');
		$pCount = count($pages);
		
	?>
	<div class="menuList">
		<input type="hidden" name="nid[]" value="" />
		Name: <input class="form-control" style="width:150px;" type="text" name="name[]" value="" autofocus="autofocus" />&nbsp;&nbsp;&nbsp;
		Page Link: <select class="form-control" style="width:100px;" name="link[]">
				<?
				for($p1=0;$p1<$pCount;$p1++){
					?>
					<option value="<?=$pages[$p1]['page_name'];?>"><?=$pages[$p1]['page_name'];?></option>
					<?
				}
				?>
			</select>&nbsp;&nbsp;&nbsp;
		Target: <select class="form-control" style="width:150px;" name="target[]">
				<option value="_self">Same Window</option>
				<option value="_blank">New Window</option>
			</select>&nbsp;&nbsp;&nbsp;
		Parent: <select class="form-control" style="width:150px;" name="parent[]">
				<option value="0">Top Level</option>
				<?
				for($m2=0;$m2<$mCount;$m2++){
					?>
					<option value="<?=$menuItems[$m2]['nid'];?>"><?=$menuItems[$m2]['name'];?></option>
					<?
				}
				?>
			</select>&nbsp;&nbsp;&nbsp;
		Sort: <input class="form-control" style="width:50px;" type="number" name="sort[]" value="" />
	</div>
	<?
	
	include_once("../../inc/app_end.php");
?>