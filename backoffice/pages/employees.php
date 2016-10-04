<?
$boolErr = false;
$mbrID = '';

if($_GET['id'] != ''){
	$mbrID = $_GET['id'];
}

if($_POST['Submit'] == "Save Employee"){
	$insData = array();
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			switch($k){
				case 'adm_password':
					$insData[$k] = encrypt($v,$_POST['adm_username']);
					break;
					
				case 'adm_phone':
					$insData[$k] =  formatPhone($v);
					break;
					
				default:
					$insData[$k] = $v;
					break;
			}
		}
	}
	$mbrID = $app->db->insert('lmg_administrators',$insData);
	?>
	<div class="alert alert-success">Employee has been added</div>
	<?
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			switch($k){
				case 'adm_password':
					if($v != ''){
						$updateData = array(
							$k => encrypt($v,$_POST['adm_username'])
						);
					}
					break;
					
				case 'adm_phone':
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
			$app->db->where('aid',$mbrID);
			$app->db->update('lmg_administrators',$updateData);
		}
	}
	$mbrID = '';
	?>
	<div class="alert alert-success">Employee has been updated</div>
	<?
}

if($mbrID != ''){
	$editMember = $app->db
		->where('aid',$mbrID)
		->get('lmg_administrators');
}
?>
<h1>Employees</h1>
<p>&nbsp;</p>
<?
$directory = $app->db
	->orderBy('adm_lastname','ASC')
	->orderBy('adm_firstname','ASC')
	->get('lmg_administrators');
	$dCount = count($directory);
?>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4">
			<h3>Current Employees</h3>
			<div class="data-grid">
			<?
			for($mbr=0;$mbr<$dCount;$mbr++){
				?>
				<div>
					<a title="Edit Employee" class="glyphicon glyphicon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=employees&id=<?=$directory[$mbr]['aid'];?>"></a>
					<a title="Delete Employee" class="glyphicon glyphicon-remove deleteEmployee" href="javascript:void()" id="<?=$directory[$mbr]['aid'];?>"></a>
					&nbsp;
					<?=$directory[$mbr]['adm_firstname'];?> <?=$directory[$mbr]['adm_lastname'];?><br>
				</div>
				<?
			}
			?>
			</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8">
		<?
		if($mbrID != ''){
			?>
			<h3>Edit Employee (<?=$editMember[0]['adm_firstname'];?> <?=$editMember[0]['adm_lastname'];?>)</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=employees&id=<?=$mbrID;?>" method="post" name="EditMember">
				<?
				foreach($editMember[0] as $fK => $fV){
					if($fK != 'aid'){
						switch($fK){
							case 'adm_active':
								?>
								<label><b><?=str_replace("adm_","",$fK);?></b></label>
								<input type="radio" name="adm_active" value="1" checked="checked" />Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="adm_active" value="0"<? echo( $editMember[0]['adm_active'] == '0' ? ' checked' : '');?> />No
								<br>
								<?
								break;
								
							case 'adm_access':
								?>
								<label><b><?=str_replace("adm_","",$fK);?></b></label>
								<input type="radio" name="adm_access" value="Basic" checked="checked" />Basic&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="adm_access" value="Mid-Level"<? echo( $editMember[0]['adm_access'] == 'Mid-Level' ? ' checked' : '');?> />Mid-Level&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="adm_access" value="Administrator"<? echo( $editMember[0]['adm_access'] == 'Administrator' ? ' checked' : '');?> />Administrator
								<br>
								<br>
								<?
								break;
								
							case 'adm_password':
								?>
								<br><b class="badge badge-info">Leave passwords blank unless you wish to change your password</b><br />
								<label><b><?=str_replace("adm_","",$fK);?></b></label>
								<input class="form-control" autocomplete="off" id="pass1" type="password" name="<?=$fK;?>" />
								<a id="passBtn" class="btn">Generate Password</a> <span id="genPass" class="alert blue" style="letter-spacing:1px;"></span>
								<?
								break;
								
							default:
								?>
								<label><b><?=str_replace("adm_","",$fK);?></b></label>
								<input class="form-control" type="text" name="<?=$fK;?>" value="<? echo ($_POST['Submit'] == "Save Changes" ? $Member{$fK} : $fV);?>" />
								<?
								break;
								
						}
					}
				}
				?>
				<p>&nbsp;</p>
				<a class="btn pull-right" style="margin-left:10px;" href="<?=$_SERVER['PHP_SELF'];?>?url=employees">Cancel</a>
				<input class="btn pull-right" type="submit" name="Submit" value="Save Changes" />
				</form>
			<?
		}else{
				$add_mbr = $app->db
					->rawQuery('SHOW columns FROM lmg_administrators');
				$mbrCt = count($add_mbr);
			?>
			<h3>Add Employee</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=employees" method="post" name="AddMember">
				<?
				for($f=0;$f<$mbrCt;$f++){
					if($add_mbr[$f]['Field'] != 'aid'){
						switch($add_mbr[$f]['Field']){
							case 'adm_active':
								?>
								<label><b><?=str_replace("adm_","",$add_mbr[$f]['Field']);?></b></label>
								<input type="radio" name="adm_active" value="1" checked="checked" />Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="adm_active" value="0"<? echo( $editMember[0]['adm_active'] == '0' ? ' checked' : '');?> />No
								<br>
								<?
								break;
								
							case 'adm_access':
								?>
								<label><b><?=str_replace("adm_","",$add_mbr[$f]['Field']);?></b></label>
								<input type="radio" name="adm_access" value="Basic" checked="checked" />Basic&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="adm_access" value="Mid-Level"<? echo( $editMember[0]['adm_access'] == 'Mid-Level' ? ' checked' : '');?> />Mid-Level&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="radio" name="adm_access" value="Administrator"<? echo( $editMember[0]['adm_access'] == 'Administrator' ? ' checked' : '');?> />Administrator
								<br>
								<br>
								<?
								break;
								
							case 'adm_password':
								?>
								<br><b class="badge badge-info">Leave passwords blank unless you wish to change your password</b><br />
								<label><b><?=str_replace("adm_","",$add_mbr[$f]['Field']);?></b></label>
								<input class="form-control" autocomplete="off" id="pass1" type="password" name="<?=$add_mbr[$f]['Field'];?>" />
								<a id="passBtn" class="btn">Generate Password</a> <span id="genPass" class="alert blue" style="letter-spacing:1px;"></span>
								<?
								break;
								
							default:
								?>
								<label><b><?=str_replace("adm_","",$add_mbr[$f]['Field']);?></b></label>
								<input class="form-control" type="text" name="<?=$add_mbr[$f]['Field'];?>" value="<? echo ($editMember[0][$fK]);?>" />
								<?
								break;
								
						}
					}
				}
				?>
				<p>&nbsp;</p>
				<input class="btn pull-right" type="submit" name="Submit" value="Save Employee" />
				</form>
			<?
		}
		?>
		</div>
	</div>
