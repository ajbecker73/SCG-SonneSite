<?
$boolErr = false;
$mbrID = '';

if($_GET['id'] != ''){
	$mbrID = $_GET['id'];
}

if($_POST['Submit'] == "Save User"){
	$insData = array();
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			switch($k){
				case 'mbr_password':
					$insData[$k] = encrypt($v,$_POST['mbr_username']);
					break;
					
				case 'mbr_phone':
					$insData[$k] =  formatPhone($v);
					break;
					
				default:
					$insData[$k] = $v;
					break;
			}
		}
	}
	$mbrID = $app->db->insert('lmg_users',$insData);
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			switch($k){
				case 'mbr_password':
					$updateData = array(
						$k => encrypt($v,$_POST['mbr_username'])
					);
					break;
					
				case 'mbr_phone':
					$updateData = array(
						$k => formatPhone($v)
					);
					break;
					
				default:
					$updateData = array(
						$k => $v
					);
					break;
			}
			$app->db->where('mid',$mbrID);
			$app->db->update('lmg_users',$updateData);
		}
	}
}

if($mbrID != ''){
	$editMember = $app->db
		->where('mid',$mbrID)
		->get('lmg_users');
}
?>
<h1>Site Users</h1>
<p>&nbsp;</p>
<?
$directory = $app->db
	->orderBy('mbr_lastname','ASC')
	->orderBy('mbr_firstname','ASC')
	->get('lmg_users');
	$dCount = count($directory);
?>
<div class="container">
	<div class="row">
		<div class="col-lg-4 col-md-4">
			<h3>Current Users</h3>
			<div class="data-grid">
			<?
			for($mbr=0;$mbr<$dCount;$mbr++){
				?>
				<div>
					<a title="Edit Member" class="icon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=members&id=<?=$directory[$mbr]['mid'];?>"></a>
					<a title="Delete Member" class="icon-remove deleteUser" href="javascript:void()" id="<?=$directory[$mbr]['mid'];?>"></a>
					<b><?=$directory[$mbr]['mbr_company'];?></b>
					<?
					if($directory[$mbr]['mbr_firstname'] != ''){
						?>
						<br><?=$directory[$mbr]['mbr_firstname'];?> <?=$directory[$mbr]['mbr_lastname'];?><br>
						<?
					}
					?>
				</div>
				<?
			}
			?>
			</div>
		</div>
		<div class="col-lg-8 col-md-8">
		<?
		if($mbrID != ''){
			?>
			<h3>Edit User (<?=$editMember[0]['mbr_company'];?>)</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=users&id=<?=$mbrID;?>" method="post" name="EditMember">
				<?
				foreach($editMember[0] as $fK => $fV){
					if($fK != 'mid' && $fK != 'last_updated'){
						switch($fK){
							case 'mbr_active':
								?>
								<label><b><?=str_replace("mbr_","",$fK);?></b></label>
								<input type="radio" name="mbr_active" value="1" checked="checked" />Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="mbr_active" value="0"<? echo( $editMember[0]['mbr_active'] == '0' ? ' checked' : '');?> />No
								<br>
								<br>
								<?
								break;
								
							case 'mbr_state':
								?>
								<label><b><?=str_replace("mbr_","",$fK);?></b></label>
								<?=$app->getStateDropdown($fK,$app->states_arr,$_POST['state']);?>
								<?
								break;
								
							case 'mbr_password':
								?>
								<br><b class="badge badge-info">Leave passwords blank unless you wish to change your password</b>
								<label><b><?=str_replace("mbr_","",$fK);?></b></label>
								<input autocomplete="off" id="pass1" type="password" name="<?=$fK;?>" />
								<a id="passBtn" class="btn">Generate Password</a> <span id="genPass" class="alert blue" style="letter-spacing:1px;"></span>
								<?
								break;
								
							case 'date_joined':
								?>
								<label><b><?=str_replace("mbr_","",$fK);?></b></label>
								<input class="datepicker" type="text" name="<?=$fK;?>" value="<? echo ($_POST['Submit'] == "Save Changes" ? $Member{$fK} : $fV);?>" />
								<?
								break;
								
							default:
								?>
								<label><b><?=str_replace("mbr_","",$fK);?></b></label>
								<input type="text" name="<?=$fK;?>" value="<? echo ($_POST['Submit'] == "Save Changes" ? $Member{$fK} : $fV);?>" />
								<?
								break;
								
						}
					}
				}
				?>
				<p>&nbsp;</p>
				<a class="btn pull-right" style="margin-left:10px;" href="<?=$_SERVER['PHP_SELF'];?>?url=members">Cancel</a>
				<input class="btn pull-right" type="submit" name="Submit" value="Save Changes" />
				</form>
			<?
		}else{
				$add_mbr = $app->db
					->rawQuery('SHOW columns FROM lmg_users');
				$mbrCt = count($add_mbr);
			?>
			<h3>Add User</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=users" method="post" name="AddMember">
				<?
				for($f=0;$f<$mbrCt;$f++){
					if($add_mbr[$f]['Field'] != 'mid'){
						switch($add_mbr[$f]['Field']){
							case 'mbr_active':
								?>
								<label><b><?=str_replace("mbr_","",$add_mbr[$f]['Field']);?></b></label>
								<input type="radio" name="mbr_active" value="1" checked="checked" />Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="mbr_active" value="0"<? echo( $editMember[0]['mbr_active'] == '0' ? ' checked' : '');?> />No
								<br>
								<br>
								<?
								break;
								
							case 'mbr_state':
								?>
								<label><b><?=str_replace("mbr_","",$add_mbr[$f]['Field']);?></b></label>
								<?=$app->getStateDropdown($add_mbr[$f]['Field'],$app->states_arr,$_POST['state']);?>
								<?
								break;
								
							case 'mbr_password':
								?>
								<br><b class="badge badge-info">Leave passwords blank unless you wish to change your password</b>
								<label><b><?=str_replace("mbr_","",$add_mbr[$f]['Field']);?></b></label>
								<input autocomplete="off" id="pass1" type="password" name="<?=$add_mbr[$f]['Field'];?>" />
								<a id="passBtn" class="btn">Generate Password</a> <span id="genPass" class="alert blue" style="letter-spacing:1px;"></span>
								<?
								break;
								
							case 'date_joined':
								?>
								<label><b><?=str_replace("mbr_","",$add_mbr[$f]['Field']);?></b></label>
								<input class="datepicker" type="text" name="<?=$add_mbr[$f]['Field'];?>" value="<? echo ($editMember[0][$fK]);?>" />
								<?
								break;
								
							default:
								?>
								<label><b><?=str_replace("mbr_","",$add_mbr[$f]['Field']);?></b></label>
								<input type="text" name="<?=$add_mbr[$f]['Field'];?>" value="<? echo ($editMember[0][$fK]);?>" />
								<?
								break;
								
						}
					}
				}
				?>
				<p>&nbsp;</p>
				<input class="btn pull-right" type="submit" name="Submit" value="Save User" />
				</form>
			<?
		}
		?>
		</div>
	</div>
</div>
