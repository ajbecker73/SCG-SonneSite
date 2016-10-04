<h1><?= COMPANY; ?> Appointment Scheduler Admin</h1>
<?
if($_POST['Submit'] == "Save Changes"){
	$app->db->delete('lmg_scheduler_hours');
	$insertData = array();
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$insertData[$k] = $v;
		}
	}
	$app->db->insert('lmg_scheduler_hours',$insertData);
	header('location:'.DOMAIN_ROOT.'backoffice/?url=scheduler2');
}


$hours = $app->db->get('lmg_scheduler_hours');
?>

<form action="" name="Scheduler_settings" method="post">
<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6">
		<h3>Hours Available</h3>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<b>Day</b>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<b>Start Time</b>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<b>End Time</b>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<input type="checkbox" name="scheduler_sunday" value="Yes"<?=($hours[0]['scheduler_sunday'] == 'Yes' ? ' checked' : '');?> /> Sunday 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_sunday_start_time" value="<?=$hours[0]['scheduler_sunday_start_time'];?>" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_sunday_end_time" value="<?=$hours[0]['scheduler_sunday_end_time'];?>" />
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<input type="checkbox" name="scheduler_monday" value="Yes"<?=($hours[0]['scheduler_monday'] == 'Yes' ? ' checked' : '');?> /> Monday 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_monday_start_time" value="<?=$hours[0]['scheduler_monday_start_time'];?>" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_monday_end_time" value="<?=$hours[0]['scheduler_monday_end_time'];?>" />
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<input type="checkbox" name="scheduler_tuesday" value="Yes"<?=($hours[0]['scheduler_tuesday'] == 'Yes' ? ' checked' : '');?> /> Tuesday 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_tuesday_start_time" value="<?=$hours[0]['scheduler_tuesday_start_time'];?>" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_tuesday_end_time" value="<?=$hours[0]['scheduler_tuesday_end_time'];?>" />
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<input type="checkbox" name="scheduler_wednesday" value="Yes"<?=($hours[0]['scheduler_wednesday'] == 'Yes' ? ' checked' : '');?> /> Wednesday 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_wednesday_start_time" value="<?=$hours[0]['scheduler_wednesday_start_time'];?>" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_wednesday_end_time" value="<?=$hours[0]['scheduler_wednesday_end_time'];?>" />
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<input type="checkbox" name="scheduler_thursday" value="Yes"<?=($hours[0]['scheduler_thursday'] == 'Yes' ? ' checked' : '');?> /> Thursday 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_thursday_start_time" value="<?=$hours[0]['scheduler_thursday_start_time'];?>" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_thursday_end_time" value="<?=$hours[0]['scheduler_thursday_end_time'];?>" />
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<input type="checkbox" name="scheduler_friday" value="Yes"<?=($hours[0]['scheduler_friday'] == 'Yes' ? ' checked' : '');?> /> Friday 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_friday_start_time" value="<?=$hours[0]['scheduler_friday_start_time'];?>" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_friday_end_time" value="<?=$hours[0]['scheduler_friday_end_time'];?>" />
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<input type="checkbox" name="scheduler_saturday" value="Yes"<?=($hours[0]['scheduler_saturday'] == 'Yes' ? ' checked' : '');?> /> Saturday 
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_saturday_start_time" value="<?=$hours[0]['scheduler_saturday_start_time'];?>" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<input type="text" class="form-control timepicker" style="display:inline-block;" name="scheduler_saturday_end_time" value="<?=$hours[0]['scheduler_saturday_end_time'];?>" />
			</div>
		</div>
	</div>
</div>
<input type="submit" name="Submit" value="Save Changes" class="btn pull-left" />
</form>