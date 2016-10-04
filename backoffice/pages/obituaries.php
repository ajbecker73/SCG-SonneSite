<h1><?= COMPANY; ?> Obituaries</h1>
<p>&nbsp;</p>
<?
$boolErr = false;
$obitID = '';

if($_GET['id'] != ''){
	$obitID = $_GET['id'];
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit' && $k != 'photo' && $k != 'specialtyicon' && $k != 'dateadded' && $k != 'lastupdated'){
			$updateData = array(
				$k => $v
			);
			$app->db->where('id',$obitID);
			$app->db->update('lmg_obituaries',$updateData);
		}
	}
	if($_FILES['photo']['name'] != ''){
		$dir = FILE_ROOT.'img/uploads/';
		$_FILES['photo']['type'] = strtolower($_FILES['photo']['type']);
		 
		    // setting file's mysterious name
		    $file = md5(date('YmdHis')).$_FILES['photo']['name'];
		 
		    // copying
		    move_uploaded_file($_FILES["photo"]["tmp_name"],$dir.$file);
			$updateData = array(
				'photo' => $file
			);
			$app->db->where('id',$obitID);
			$app->db->update('lmg_obituaries',$updateData);
	}
	if($_FILES['c_specialtyicon']['name'] != ''){
		$dir = FILE_ROOT.'img/icons/';
		$_FILES['c_specialtyicon']['type'] = strtolower($_FILES['c_specialtyicon']['type']);
		 
		    // setting file's mysterious name
		    $file = md5(date('YmdHis')).$_FILES['c_specialtyicon']['name'];
		 
		    // copying
		    move_uploaded_file($_FILES["c_specialtyicon"]["tmp_name"],$dir.$file);
			$updateData = array(
				'specialtyicon' => $file
			);
			$app->db->where('id',$obitID);
			$app->db->update('lmg_obituaries',$updateData);
	}else{
		if($_POST['specialtyicon'] != ''){
			$updateData = array(
				'specialtyicon' => $_POST['specialtyicon']
			);
			$app->db->where('id',$obitID);
			$app->db->update('lmg_obituaries',$updateData);
		}
	}
}

if($_POST['Submit'] == "Save Obituary"){
	$insData = array();
	foreach($_POST as $k => $v){
		if($k != 'Submit' && $k != 'photo' && $k != 'specialtyicon' && $k != 'dateadded' && $k != 'lastupdated'){
			$insData[$k] = $v;
		}
	}
	$insData['dateadded'] = date("Y-m-d H:i:s");
	$obitID = $app->db->insert('lmg_obituaries',$insData);
	if($_FILES['photo']['name'] != ''){
		$dir = FILE_ROOT.'img/uploads/';
		$_FILES['photo']['type'] = strtolower($_FILES['photo']['type']);
		 
		    // setting file's mysterious name
		    $file = md5(date('YmdHis')).$_FILES['photo']['name'];
		 
		    // copying
		    move_uploaded_file($_FILES["photo"]["tmp_name"],$dir.$file);
			$updateData = array(
				'photo' => $file
			);
			$app->db->where('id',$obitID);
			$app->db->update('lmg_obituaries',$updateData);
	}
	if($_FILES['c_specialtyicon']['name'] != ''){
		$dir = FILE_ROOT.'img/icons/';
		$_FILES['c_specialtyicon']['type'] = strtolower($_FILES['c_specialtyicon']['type']);
		 
		    // setting file's mysterious name
		    $file = md5(date('YmdHis')).$_FILES['c_specialtyicon']['name'];
		 
		    // copying
		    move_uploaded_file($_FILES["c_specialtyicon"]["tmp_name"],$dir.$file);
			$updateData = array(
				'specialtyicon' => $file
			);
			$app->db->where('id',$obitID);
			$app->db->update('lmg_obituaries',$updateData);
	}else{
		if($_POST['specialtyicon'] != ''){
			$updateData = array(
				'specialtyicon' => $_POST['specialtyicon']
			);
			$app->db->where('id',$obitID);
			$app->db->update('lmg_obituaries',$updateData);
		}
	}
}
?>
<div class="container">
	<div class="row">
		<div class="span4">
			<h3>Current Obituaries</h3>
			<div class="data-grid">
			<?
			$tot_obits = $app->db
				->orderBy('deathdate','DESC')
				->get('lmg_obituaries');
				$evCount = count($tot_obits);
				
			for($t=0;$t<$evCount;$t++){
				?>
				<div>
					<a title="Edit Obituary" class="icon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=obituaries&id=<?=$tot_obits[$t]['id'];?>"></a>
					<a title="Delete Obituary" class="icon-remove deleteObituary" href="javascript:void()" id="<?=$tot_obits[$t]['id'];?>"></a>
					<b><?=stripslashes($tot_obits[$t]['lastname']).', '.stripslashes($tot_obits[$t]['firstname']);?></b><br>
					<?=writeDate($tot_obits[$t]['birthdate'],$tot_obits[$t]['deathdate']);?><br>
				</div>
				<?
			}
			?>
			</div>
		</div>
		<div class="span8">
			<?
			if($obitID != ''){
				$sel_obit = $app->db
					->where('id',$obitID)
					->get('lmg_obituaries');
				?>
				<h2>Edit Obituary (<?=stripslashes($sel_obit[0]['lastname']).', '.stripslashes($sel_obit[0]['firstname']);?>)</h2>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=obituaries&id=<?=$obitID;?>" method="post" name="EditEvent" enctype="multipart/form-data">
				<div class="row-fluid">
					<div class="span12">
					<div class="well">
						<h3>Decedent Information</h3>
						<table style="width:100%;">
							<tr>
								<td style="width:50%;">
									<label><b>Publish</b></label>
									<input type="radio" name="publish" value="yes"<? echo($sel_obit[0]['publish'] == 'yes' ? ' checked' : '');?> />Yes
									&nbsp;&nbsp;&nbsp;
									<input type="radio" name="publish" value="no"<? echo($sel_obit[0]['publish'] == 'no' ? ' checked' : '');?> />No
									<br /><br />
								</td>
								<td style="width:50%;">
									<label><b>Allow Comments &amp; Condolences</b></label>
									<input type="radio" name="comments" value="yes"<? echo($sel_obit[0]['comments'] == 'yes' ? ' checked' : '');?> />Yes
									&nbsp;&nbsp;&nbsp;
									<input type="radio" name="comments" value="no"<? echo($sel_obit[0]['comments'] == 'no' ? ' checked' : '');?> />No
									<br /><br />
								</td>
							</tr>
							<tr>
								<td style="width:50%;">
									<label><b>First Name</b></label>
									<input style="width:90%;" type="text" name="firstname" value="<?=htmlspecialchars(stripslashes($sel_obit[0]['firstname']));?>" />
								</td>
								<td style="width:50%;">
									<label><b>Last Name</b></label>
									<input style="width:90%;" type="text" name="lastname" value="<?=stripslashes($sel_obit[0]['lastname']);?>" />
								</td>
							</tr>
							<tr>
								<td>
									<label><b>Date of Birth</b> (yyyy-mm-dd)</label>
									<input style="width:90%;" class="datepicker" type="text" name="birthdate" value="<?=$sel_obit[0]['birthdate'];?>" />
								</td>
								<td>
									<label><b>Date of Death</b> (yyyy-mm-dd)</label>
									<input style="width:90%;" class="datepicker" type="text" name="deathdate" value="<?=$sel_obit[0]['deathdate'];?>" />
								</td>
							</tr>
							<tr>
								<td style="vertical-align:top;">
									<label><b>Photo</b></label>
									<?
									if($sel_obit[0]['photo'] != ''){
										?>
										<img src="<?=DOMAIN_ROOT;?>img/uploads/<?=$sel_obit[0]['photo'];?>" style="max-height:100px; max-width:100px;" />
										<?
									}
									?>
									<input style="width:90%;" type="file" name="photo" />
								</td>
								<td>
									<label><b>Specialty Icon</b></label>
									Choose one<br />
									<?
									if ($handle = opendir(FILE_ROOT.'img/icons/')) {
										while (false !== ($entry = readdir($handle))) {
											if ($entry != "." && $entry != "..") {
												?>
												<input type="radio" name="specialtyicon" value="<?=$entry;?>"<? echo($sel_obit[0]['specialtyicon'] == $entry ? ' checked' : '');?> />
												<img src="<?=DOMAIN_ROOT;?>img/icons/<?=$entry;?>" />
												&nbsp;&nbsp;&nbsp;
												<?
											}
										}
										closedir($handle);
									}
									?>
									<br />
									OR
									<br />
									<input style="width:90%;" type="file" name="c_specialtyicon" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Description</b></label>
									<textarea style="height:150px;" name="description"><?=stripslashes($sel_obit[0]['description']);?></textarea>
									<script>
										CKEDITOR.replace( 'description',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br />
								</td>
							</tr>
						</table>
					</div>
					<div class="well">
						<h3>Visitation Information</h3>
						<table style="width:100%;">
							<tr>
								<td>
									<label><b>Visitation Date</b> (yyyy-mm-dd)</label>
									<input style="width:90%;" class="datepicker" type="text" name="visitationdate" value="<?=stripslashes($sel_obit[0]['visitationdate']);?>" />
								</td>
								<td>
									<label><b>Visitation Time</b> (hh:mm am/pm)</label>
									<input style="width:90%;" class="timepicker" type="text" name="visitationtime" value="<?=stripslashes($sel_obit[0]['visitationtime']);?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Visitation Location</b></label>
									<input style="width:95%;" type="text" name="visitationlocation" value="<?=stripslashes($sel_obit[0]['visitationlocation']);?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Description</b></label>
									<textarea style="height:150px;" name="visitationdescription"><?=stripslashes($sel_obit[0]['visitationdescription']);?></textarea>
									<script>
										CKEDITOR.replace( 'visitationdescription',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br />
								</td>
							</tr>
						</table>
					</div>
					<div class="well">
						<h3>Memorial Information</h3>
						<table style="width:100%;">
							<tr>
								<td>
									<label><b>Memorial Date</b> (yyyy-mm-dd)</label>
									<input style="width:90%;" class="datepicker" type="text" name="memorialdate" value="<?=stripslashes($sel_obit[0]['memorialdate']);?>" />
								</td>
								<td>
									<label><b>Memorial Time</b> (hh:mm am/pm)</label>
									<input style="width:90%;" class="timepicker" type="text" name="memorialtime" value="<?=stripslashes($sel_obit[0]['memorialtime']);?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Memorial Location</b></label>
									<input style="width:95%;" type="text" name="memoriallocation" value="<?=stripslashes($sel_obit[0]['memoriallocation']);?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Description</b></label>
									<textarea style="height:150px;" name="memorialdescription"><?=stripslashes($sel_obit[0]['memorialdescription']);?></textarea>
									<script>
										CKEDITOR.replace( 'memorialdescription',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br />
								</td>
							</tr>
						</table>
					</div>
					<div class="well">
						<h3>Funeral Information</h3>
						<table style="width:100%;">
							<tr>
								<td>
									<label><b>Funeral Date</b> (yyyy-mm-dd)</label>
									<input style="width:90%;" class="datepicker" type="text" name="funeraldate" value="<?=stripslashes($sel_obit[0]['funeraldate']);?>" />
								</td>
								<td>
									<label><b>Funeral Time</b> (hh:mm am/pm)</label>
									<input style="width:90%;" class="timepicker" type="text" name="funeraltime" value="<?=stripslashes($sel_obit[0]['funeraltime']);?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Funeral Location</b></label>
									<input style="width:95%;" type="text" name="funerallocation" value="<?=stripslashes($sel_obit[0]['funerallocation']);?>" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Description</b></label>
									<textarea style="height:150px;" name="funeraldescription"><?=stripslashes($sel_obit[0]['funeraldescription']);?></textarea>
									<script>
										CKEDITOR.replace( 'funeraldescription',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br />
								</td>
							</tr>
						</table>
					</div>
					<div class="well">
						<h3>Other Information</h3>
						<table style="width:100%;">
							<tr>
								<td style="vertical-align:top;">

								</td>
								<td>

								</td>
							</tr>
						</table>
					</div>
				</div>
					<input type="submit" name="Submit" value="Save Changes" class="btn pull-right" />
				</form>
				<?
			}else{
				?>
				<h2>Add Obituary</h2>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=obituaries" method="post" name="AddEvent" enctype="multipart/form-data">
				<div class="row-fluid">
					<div class="span12">
					<div class="well">
						<h3>Decedent Information</h3>
						<table style="width:100%;">
							<tr>
								<td style="width:50%;">
									<label><b>Publish</b></label>
									<input type="radio" name="publish" value="yes" />Yes
									&nbsp;&nbsp;&nbsp;
									<input type="radio" name="publish" value="no" checked />No
									<br /><br />
								</td>
								<td style="width:50%;">
									<label><b>Allow Comments &amp; Condolences</b></label>
									<input type="radio" name="comments" value="yes" />Yes
									&nbsp;&nbsp;&nbsp;
									<input type="radio" name="comments" value="no" checked />No
									<br /><br />
								</td>
							</tr>
							<tr>
								<td style="width:50%;">
									<label><b>First Name</b></label>
									<input required style="width:90%;" type="text" name="firstname" value="" />
								</td>
								<td style="width:50%;">
									<label><b>Last Name</b></label>
									<input required style="width:90%;" type="text" name="lastname" value="" />
								</td>
							</tr>
							<tr>
								<td>
									<label><b>Date of Birth</b> (yyyy-mm-dd)</label>
									<input required style="width:90%;" class="datepicker" type="text" name="birthdate" value="" />
								</td>
								<td>
									<label><b>Date of Death</b> (yyyy-mm-dd)</label>
									<input required style="width:90%;" class="datepicker" type="text" name="deathdate" value="" />
								</td>
							</tr>
							<tr>
								<td style="vertical-align:top;">
									<label><b>Photo</b></label>
									<input style="width:90%;" type="file" name="photo" />
								</td>
								<td>
									<label><b>Specialty Icon</b></label>
									Choose one<br />
									<?
									if ($handle = opendir(FILE_ROOT.'img/icons/')) {
										while (false !== ($entry = readdir($handle))) {
											if ($entry != "." && $entry != "..") {
												?>
												<input type="radio" name="specialtyicon" value="<?=$entry;?>" />
												<img src="<?=DOMAIN_ROOT;?>img/icons/<?=$entry;?>" />
												&nbsp;&nbsp;&nbsp;
												<?
											}
										}
										closedir($handle);
									}
									?>
									<br />
									OR
									<br />
									<input style="width:90%;" type="file" name="c_specialtyicon" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Description</b></label>
									<textarea required style="height:150px;" name="description"></textarea>
									<script>
										CKEDITOR.replace( 'description',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br />
								</td>
							</tr>
						</table>
					</div>
					<div class="well">
						<h3>Visitation Information</h3>
						<table style="width:100%;">
							<tr>
								<td>
									<label><b>Visitation Date</b> (yyyy-mm-dd)</label>
									<input style="width:90%;" class="datepicker" type="text" name="visitationdate" value="" />
								</td>
								<td>
									<label><b>Visitation Time</b> (hh:mm am/pm)</label>
									<input style="width:90%;" class="timepicker" type="text" name="visitationtime" value="" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Visitation Location</b></label>
									<input style="width:95%;" type="text" name="visitationlocation" value="" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Description</b></label>
									<textarea style="height:150px;" name="visitationdescription"></textarea>
									<script>
										CKEDITOR.replace( 'visitationdescription',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br />
								</td>
							</tr>
						</table>
					</div>
					<div class="well">
						<h3>Memorial Information</h3>
						<table style="width:100%;">
							<tr>
								<td>
									<label><b>Memorial Date</b> (yyyy-mm-dd)</label>
									<input style="width:90%;" class="datepicker" type="text" name="memorialdate" value="" />
								</td>
								<td>
									<label><b>Memorial Time</b> (hh:mm am/pm)</label>
									<input style="width:90%;" class="timepicker" type="text" name="memorialtime" value="" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Memorial Location</b></label>
									<input style="width:95%;" type="text" name="memoriallocation" value="" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Description</b></label>
									<textarea style="height:150px;" name="memorialdescription"></textarea>
									<script>
										CKEDITOR.replace( 'memorialdescription',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br />
								</td>
							</tr>
						</table>
					</div>
					<div class="well">
						<h3>Funeral Information</h3>
						<table style="width:100%;">
							<tr>
								<td>
									<label><b>Funeral Date</b> (yyyy-mm-dd)</label>
									<input style="width:90%;" class="datepicker" type="text" name="funeraldate" value="" />
								</td>
								<td>
									<label><b>Funeral Time</b> (hh:mm am/pm)</label>
									<input style="width:90%;" class="timepicker" type="text" name="funeraltime" value="" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Funeral Location</b></label>
									<input style="width:95%;" type="text" name="funerallocation" value="" />
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label><b>Description</b></label>
									<textarea style="height:150px;" name="funeraldescription"></textarea>
									<script>
										CKEDITOR.replace( 'funeraldescription',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br />
								</td>
							</tr>
						</table>
					</div>
					<div class="well">
						<h3>Other Information</h3>
						<table style="width:100%;">
							<tr>
								<td style="vertical-align:top;">

								</td>
								<td>

								</td>
							</tr>
						</table>
					</div>
				</div>
					<input type="submit" name="Submit" value="Save Obituary" class="btn pull-right" />
				</form>
				<?
			}
			?>
		</div>
	</div>
</div>


