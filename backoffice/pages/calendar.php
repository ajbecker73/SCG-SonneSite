<h1><?= COMPANY; ?> Calendar</h1>
<p>&nbsp;</p>
<?
$boolErr = false;
$calID = '';

if($_GET['id'] != ''){
	$calID = $_GET['id'];
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit' && $k != 'cal_pdf'){
			$updateData = array(
				$k => $v
			);
			$app->db->where('id',$calID);
			$app->db->update('lmg_calendar',$updateData);
		}
	}
	if($_FILES['cal_pdf']['name'] != ''){
		$dir = FILE_ROOT.'doc/uploads/';
		$_FILES['cal_pdf']['type'] = strtolower($_FILES['cal_pdf']['type']);
		 
		    // setting file's mysterious name
		    $file = md5(date('YmdHis')).$_FILES['cal_pdf']['name'];
		 
		    // copying
		    move_uploaded_file($_FILES["cal_pdf"]["tmp_name"],$dir.$file);
			$updateData = array(
				'cal_pdf' => $file
			);
			$app->db->where('id',$calID);
			$app->db->update('lmg_calendar',$updateData);
	}
}

if($_POST['Submit'] == "Save Event"){
	$insData = array();
	foreach($_POST as $k => $v){
		if($k != 'Submit' && $k != 'cal_pdf'){
			$insData[$k] = $v;
		}
	}
	$mbrID = $app->db->insert('lmg_calendar',$insData);
	if($_FILES['cal_pdf']['name'] != ''){
		$dir = FILE_ROOT.'doc/uploads/';
		$_FILES['cal_pdf']['type'] = strtolower($_FILES['cal_pdf']['type']);
		 
		    // setting file's mysterious name
		    $file = md5(date('YmdHis')).$_FILES['cal_pdf']['name'];
		 
		    // copying
		    move_uploaded_file($_FILES["cal_pdf"]["tmp_name"],$dir.$file);
			$updateData = array(
				'cal_pdf' => $file
			);
			$app->db->where('id',$mbrID);
			$app->db->update('lmg_calendar',$updateData);
	}
}
?>
	<div class="row">
		<div class="col-lg-4 col-md-4">
			<h3>Current Events</h3>
			<div class="data-grid">
			<?
			$tot_events = $app->db
				->orderBy('cal_startdate','DESC')
				->get('lmg_calendar');
				$evCount = count($tot_events);
				
			for($t=0;$t<$evCount;$t++){
				?>
				<div>
					<a title="Edit Event" class="glyphicon glyphicon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=calendar&id=<?=$tot_events[$t]['id'];?>"></a>
					<a title="Delete Event" class="glyphicon glyphicon-remove deleteEvent" href="javascript:void()" id="<?=$tot_events[$t]['id'];?>"></a>
					<b><?=writeDate($tot_events[$t]['cal_startdate'],$tot_events[$t]['cal_enddate']);?></b><br>
					<span style="margin-left:36px;"><?=$tot_events[$t]['cal_title'];?></span><br>
				</div>
				<?
			}
			?>
			</div>
		</div>
		<div class="col-lg-8 col-md-8">
			<?
			if($calID != ''){
				$sel_event = $app->db
					->where('id',$calID)
					->get('lmg_calendar');
				?>
				<h2>Edit Event (<?=$sel_event[0]['cal_title'];?>)</h2>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=calendar&id=<?=$calID;?>" method="post" name="EditEvent" enctype="multipart/form-data">
				<div class="row-fluid">
					<div class="span12">
					<?
					foreach($sel_event[0] as $fK => $fV){
						if($fK != 'id'){
							switch($fK){
								case 'cal_startdate':
									?>
									<div class="pull-left" style="height:400px; width:350px;">
									<label><b><?=str_replace("cal_","",$fK);?></b> (yyyy-mm-dd)</label>
									<input class="datepicker" type="text" name="<?=$fK;?>" value="<?=$fV;?>" />
									<br><br>
									<?
									break;
									
								case 'cal_enddate':
									?>
									<label><b><?=str_replace("cal_","",$fK);?></b> (yyyy-mm-dd)</label>
									<input class="datepicker" type="text" name="<?=$fK;?>" value="<?=$fV;?>" />
									<br><br>
									<?
									break;
									
								case 'cal_starttime':
									?>
									<label><b><?=str_replace("cal_","",$fK);?></b> (hh:mm am/pm)</label>
									<input class="timepicker" type="text" name="<?=$fK;?>" value="<?=$fV;?>" />
									<br><br><br><br>
									<?
									break;
									
								case 'cal_endtime':
									?>
									<label><b><?=str_replace("cal_","",$fK);?></b> (hh:mm am/pm)</label>
									<input class="timepicker" type="text" name="<?=$fK;?>" value="<?=$fV;?>" />
									<br><br>
									<?
									break;
									
								case 'cal_description':
									?>
									<label><b><?=str_replace("cal_","",$fK);?></b></label>
									<textarea style="height:150px;" name="<?=$fK;?>"><?=$fV;?></textarea>
									<script>
										CKEDITOR.replace( 'cal_description',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br><br>
									<?
									break;
									
								case 'cal_pdf':
									?>
									<label><b><?=str_replace("cal_","",$fK);?></b></label>
									current file: <? echo ($fV != '' ? $fV : 'N/A');?><br>
									<input type="file" name="<?=$fK;?>" />
									<br><br>
									</div>
									<?
									break;
									
								case 'cal_memberprice':
								case 'cal_nonmemberprice':
									?>
									<label><b><?=str_replace("cal_","",$fK);?></b> ($$.&cent;&cent;)</label>
									<input type="number" name="<?=$fK;?>" value="<?=$fV;?>" min="0" max="9999.99" step=".01" />
									<br><br>
									<?
									break;
									
								case 'cal_featured':
								case 'cal_registration':
									?>
									<label><b><?=str_replace("cal_","",$fK);?></b></label>
									<input type="radio" name="<?=$fK;?>" value="yes"<? echo ($fV == "yes" ? ' checked' : '');?> /> Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="<?=$fK;?>" value="no"<? echo ($fV == "no" ? ' checked' : '');?> /> No
									<br><br>
									<?
									break;
									
								case 'cal_title':
									?>
									<label><b><?=str_replace("cal_","",$fK);?></b></label>
									<input style="width:100%;" type="text" name="<?=$fK;?>" value="<?=$fV;?>" />
									<br><br>
									<?
									break;
									
								default:
									?>
									<label><b><?=str_replace("cal_","",$fK);?></b></label>
									<input type="text" name="<?=$fK;?>" value="<?=$fV;?>" />
									<br><br>
									<?
									break;
							}
						}
					}
					?>
					</div>
				</div>
					<input type="submit" name="Submit" value="Save Changes" class="btn pull-right" />
				</form>
				<?
			}else{
				$add_event = $app->db
					->rawQuery('SHOW columns FROM lmg_calendar');
				$colCt = count($add_event);
				?>
				<h2>Add Event</h2>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=calendar" method="post" name="AddEvent" enctype="multipart/form-data">
				<div class="row-fluid">
					<div class="span12">
					<?
					for($f=0;$f<$colCt;$f++){
						if($add_event[$f]['Field'] != 'id'){
							switch($add_event[$f]['Field']){
								case 'cal_startdate':
									?>
									<div class="pull-left" style="width:350px;">
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b> (yyyy-mm-dd)</label>
									<input class="datepicker" type="text" name="<?=$add_event[$f]['Field'];?>" value="" />
									<br><br>
									<?
									break;
									
								case 'cal_enddate':
									?>
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b> (yyyy-mm-dd)</label>
									<input class="datepicker" type="text" name="<?=$add_event[$f]['Field'];?>" value="" />
									<br><br>
									<?
									break;
									
								case 'cal_starttime':
									?>
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b> (hh:mm am/pm)</label>
									<input class="timepicker" type="text" name="<?=$add_event[$f]['Field'];?>" value="" />
									<br><br><br><br>
									<?
									break;
									
								case 'cal_endtime':
									?>
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b> (hh:mm am/pm)</label>
									<input class="timepicker" type="text" name="<?=$add_event[$f]['Field'];?>" value="" />
									<br><br>
									<?
									break;
									
								case 'cal_description':
									?>
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b></label>
									<textarea style="height:150px;" name="<?=$add_event[$f]['Field'];?>"></textarea>
									<script>
										CKEDITOR.replace( 'cal_description',
											{
												filebrowserUploadUrl : '../upload-file.php?type=Files'
											});
									</script>
									<br><br>
									<?
									break;
									
								case 'cal_pdf':
									?>
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b></label>
									<input type="file" name="<?=$add_event[$f]['Field'];?>" />
									<br><br>
									</div>
									<?
									break;
									
								case 'cal_memberprice':
								case 'cal_nonmemberprice':
									?>
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b> ($$.&cent;&cent;)</label>
									<input type="number" name="<?=$add_event[$f]['Field'];?>" value="" min="0" max="9999.99" step=".01" />
									<br><br>
									<?
									break;
									
								case 'cal_featured':
								case 'cal_registration':
									?>
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b></label>
									<input type="radio" name="<?=$add_event[$f]['Field'];?>" value="yes" /> Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio" name="<?=$add_event[$f]['Field'];?>" value="no" /> No
									<br><br>
									<?
									break;
									
								case 'cal_title':
									?>
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b></label>
									<input type="text" name="<?=$add_event[$f]['Field'];?>" value="" />
									<br><br>
									<?
									break;
									
								default:
									?>
									<label><b><?=str_replace("cal_","",$add_event[$f]['Field']);?></b></label>
									<input type="text" name="<?=$add_event[$f]['Field'];?>" value="" />
									<br><br>
									<?
									break;
							}
						}
					}
					?>
					</div>
				</div>
					<input type="submit" name="Submit" value="Save Event" class="btn pull-right" />
				</form>
				<?
			}
			?>
		</div>
	</div>