<?
if($_POST['Submit'] == "Save Changes"){
	$totalLinks = count($_POST['name']);
	$app->db->delete('lmg_navigation');
	for($rec=0;$rec<$totalLinks;$rec++){
		$insertData = array(
			'nid' => $_POST['nid'][$rec],
			'parent' => $_POST['parent'][$rec],
			'name' => $_POST['name'][$rec],
			'link' => $_POST['link'][$rec],
			'target' => $_POST['target'][$rec],
			'sort' => $_POST['sort'][$rec]
		);
		if($_POST['delete'.$_POST['nid'][$rec]] != "delete"){
			$app->db->insert('lmg_navigation',$insertData);
		}
	}
}
?>
<h1>Navigation Menu</h1>
<p>&nbsp;</p>
	<div class="row">
		<div class="col-lg-12">
			<p>
			Manage the main navigation bar. You must have pages already created to link to them here.
			</p>
			
			<h3>Current Menu Items</h3>
			<form class="form-inline" action="" method="post" name="MenuAdmin">
			<?
			$menuItems = $app->db
				->orderBy('sort','ASC')
				->where('parent','0')
				->get('lmg_navigation');
				$mCount = count($menuItems);
				
			$pages = $app->db
				->orderBy('page_name','ASC')
				->get('lmg_pages');
				$pCount = count($pages);
				
				for($m1=0;$m1<$mCount;$m1++){
					?>
					<div class="menuList">
						<input type="hidden" name="nid[]" value="<?=$menuItems[$m1]['nid'];?>" />
						
					Name: <input class="form-control" style="width:150px;" type="text" name="name[]" value="<?=$menuItems[$m1]['name'];?>" />&nbsp;&nbsp;&nbsp;
					Page Link: <select class="form-control" style="width:100px;" name="link[]">
							<?
							for($p1=0;$p1<$pCount;$p1++){
								?>
								<option<? echo($pages[$p1]['page_name'] == $menuItems[$m1]['link'] ? ' selected' : ''); ?> value="<?=$pages[$p1]['page_name'];?>"><?=$pages[$p1]['page_name'];?></option>
								<?
							}
							?>
						</select>&nbsp;&nbsp;&nbsp;
					Target: <select class="form-control" style="width:150px;" name="target[]">
							<option<? echo($menuItems[$m1]['target'] == '_self' ? ' selected' : ''); ?> value="_self">Same Window</option>
							<option<? echo($menuItems[$m1]['target'] == '_blank' ? ' selected' : ''); ?> value="_blank">New Window</option>
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
					Sort: <input class="form-control" style="width:50px;" type="number" name="sort[]" value="<?=$menuItems[$m1]['sort'];?>" />
					<?
					$secondary = $app->db
						->orderBy('sort','ASC')
						->where('parent',$menuItems[$m1]['nid'])
						->get('lmg_navigation');
						$sCount = count($secondary);
						if($sCount > 0){
							?>
							<a title="Delete Menu Item" class="glyphicon glyphicon-remove deleteNavItemTopDep pull-right" href="javascript:void()" id="<?=$menuItems[$m1]['nid'];?>"></a>
							<?
						}else{
							?>
							<a title="Delete Menu Item" class="glyphicon glyphicon-remove deleteNavItemTop pull-right" href="javascript:void()" id="<?=$menuItems[$m1]['nid'];?>"></a>
							<?
						}
					
					?>
					</div>
					<?
						for($m3=0;$m3<$sCount;$m3++){
							?>
							<div class="menuListSec">
							<input type="hidden" name="nid[]" value="<?=$secondary[$m3]['nid'];?>" />
							Name: <input class="form-control" style="width:130px;" type="text" name="name[]" value="<?=$secondary[$m3]['name'];?>" />&nbsp;&nbsp;&nbsp;
							Page Link: <select class="form-control" style="width:100px;" name="link[]">
									<?
									for($p1=0;$p1<$pCount;$p1++){
										?>
										<option<? echo($pages[$p1]['page_name'] == $secondary[$m3]['link'] ? ' selected' : ''); ?> value="<?=$pages[$p1]['page_name'];?>"><?=$pages[$p1]['page_name'];?></option>
										<?
									}
									?>
								</select>&nbsp;&nbsp;&nbsp;
							Target: <select class="form-control" style="width:130px;" name="target[]">
									<option<? echo($secondary[$m3]['target'] == '_self' ? ' selected' : ''); ?> value="_self">Same Window</option>
									<option<? echo($secondary[$m3]['target'] == '_blank' ? ' selected' : ''); ?> value="_blank">New Window</option>
								</select>&nbsp;&nbsp;&nbsp;
							Parent: <select class="form-control" style="width:150px;" name="parent[]">
									<option value="0">Top Level</option>
									<?
									for($m4=0;$m4<$mCount;$m4++){
										?>
										<option<? echo($secondary[$m3]['parent'] == $menuItems[$m4]['nid'] ? ' selected' : ''); ?> value="<?=$menuItems[$m4]['nid'];?>"><?=$menuItems[$m4]['name'];?></option>
										<?
									}
									?>
								</select>&nbsp;&nbsp;&nbsp;
							Sort: <input class="form-control" style="width:50px;" type="number" name="sort[]" value="<?=$secondary[$m3]['sort'];?>" />
							<a title="Delete Menu Item" class="glyphicon glyphicon-remove deleteNavItemSec pull-right" href="javascript:void()" id="<?=$secondary[$m3]['nid'];?>"></a>
							</div>
							<?
						}
				}
			?>
			<input id="addMenuItems" type="button" class="btn" name="AddItem" value="Add Menu Item" />
			<div id="addMenuItemsDiv"></div>
			<input type="submit" name="Submit" value="Save Changes" class="btn pull-right" />
			</form>
		</div>
	</div>