<?
$step = $_GET['step'];
switch($step){
	
	case '2':
		$nameErr = false;
		$emailErr = false;
		$keyErr = false;
		
		$person = $_POST['Name'];
		$email = $_POST['Email'];
		$phone = $_POST['Phone'];
		$company = $_POST['Company'];
		$details = $_POST['details'];
		$comments = $_POST['Comments'];
		if($person == ""){
			$nameErr = true;
		}
		if($email == ""){
			$emailErr = true;
		}
		if($_POST['verify_num'] != $_POST['verify_them']) { 
			$keyErr = true;
		}
		if($keyErr == false && $nameErr == false && $emailErr == false){
			$subject = "New appointment from ".COMPANY;
			$message = "Name: $person<br>";
			$message .= "Company: $company<br>";
			$message .= "Email: $email<br>";
			$message .= "Phone: $phone<br>";
			$message .= "Details: $details<br>";
			$message .= "Comments: $comments";
				LMGmail(array(COMPANY_EMAIL,'messages@lmgnow.com','webmaster@lmgnow.com'), $subject, $message ,$message, COMPANY_EMAIL);
				LMGmail($email, $subject, $message ,$message, COMPANY_EMAIL);
			$insArr = array(
				'app_date' => $_POST['app_date'],
				'app_time' => $_POST['app_time'],
				'app_name' => $_POST['Name'],
				'app_email' => $_POST['Email'],
				'app_phone' => $_POST['Phone'],
				'app_comments' => $_POST['Comments']
			);
			$app->db->insert('lmg_scheduler_appointments',$insArr);
			echo '<div class="alert alert-success">Appointment has been scheduled for '.$_POST['details'].'. You will also get a confirmation email with the details.</div>';
		}else{
			echo '<div class="alert alert-danger">Please fix errors</div>';
			?>
			<form action="scheduler?step=2" method="post" name="Scedule">
			<h1>Appointment Scheduler</h1>
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4" style="text-align:right;">
					<h3>Review Appointment</h3>
					<b><?=$_POST['details'];?>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8">
					<h3>Contact Information</h3>
					<input type="hidden" name="details" value="<?=$_POST['details'];?>" />
				<input type="hidden" name="app_date" value="<?=$_POST['app_date'];?>" />
				<input type="hidden" name="app_time" value="<?=$_POST['app_time'];?>" />
					<p class="form-group<? if($nameErr == true){ echo ' has-error' ;} ?>">
					<input placeholder="Name" type="text" class="form-control" name="Name" value="<?=$person;?>" />
					</p>
				
					<p class="form-group">
					<input placeholder="Company" type="text" class="form-control" name="Company" value="<?=$company;?>" />
					</p>
				
					<p class="form-group<? if($emailErr == true){ echo ' has-error' ;} ?>">
					<input placeholder="Email" type="email" class="form-control" name="Email" value="<?=$email;?>" />
					</p>
				
					<p class="form-group">
					<input placeholder="Phone" type="text" class="form-control" name="Phone" value="<?=$phone;?>" />
					</p>
					
					<p class="form-group">
					<textarea placeholder="Comments" class="form-control" name="Comments"><?=$comments;?></textarea>
					</p>
					
					<p class="form-group<? if($keyErr == true){ echo ' has-error' ;} ?>">
						<?
						$secCode = gen_pass(5)
						?>
						<input type="hidden" name="verify_num" value="<?=$secCode;?>" />
						<label class="control-label">Security Code: <span style="font-size:20px; font-weight:normal;"><?=$secCode;?></span></label>
						<input class="form-control" type="text" name="verify_them" value="" />
					</p>
					
					<p>
					<input class="btn btn-primary" type="submit" name="Submit" value="Submit" />
					</p>
				</div>
			</div>
			</form>
			<?
		}
		break;
	case '1':
		?>
		<form action="scheduler?step=2" method="post" name="Scedule">
		<h1>Appointment Scheduler</h1>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4" style="text-align:right;">
				<h3>Review Appointment</h3>
				<b><?=$_POST['AppointmentService'];?></b><br />
				<?=date("l, F d, Y - g:i A",strtotime($_POST['AppointmentDate'].' '.$_POST['AppointmentTime']));?>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				<h3>Contact Information</h3>
				<input type="hidden" name="details" value="<?=$_POST['AppointmentService'];?> - <?=date("l, F d, Y - g:i A",strtotime($_POST['AppointmentDate'].' '.$_POST['AppointmentTime']));?>" />
				<input type="hidden" name="app_date" value="<?=date("Y-m-d",strtotime($_POST['AppointmentDate']));?>" />
				<input type="hidden" name="app_time" value="<?=date("g:i A",strtotime($_POST['AppointmentTime']));?>" />
				<p class="form-group<? if($nameErr == true){ echo ' has-error' ;} ?>">
				<input placeholder="Name" type="text" class="form-control" name="Name" value="<?=$person;?>" />
				</p>
			
				<p class="form-group">
				<input placeholder="Company" type="text" class="form-control" name="Company" value="<?=$company;?>" />
				</p>
			
				<p class="form-group<? if($emailErr == true){ echo ' has-error' ;} ?>">
				<input placeholder="Email" type="email" class="form-control" name="Email" value="<?=$email;?>" />
				</p>
			
				<p class="form-group">
				<input placeholder="Phone" type="text" class="form-control" name="Phone" value="<?=$phone;?>" />
				</p>
				
				<p class="form-group">
				<textarea placeholder="Comments" class="form-control" name="Comments"><?=$comments;?></textarea>
				</p>
				
				<p class="form-group<? if($keyErr == true){ echo ' has-error' ;} ?>">
					<?
					$secCode = gen_pass(5)
					?>
					<input type="hidden" name="verify_num" value="<?=$secCode;?>" />
					<label class="control-label">Security Code: <span style="font-size:20px; font-weight:normal;"><?=$secCode;?></span></label>
					<input class="form-control" type="text" name="verify_them" value="" />
				</p>
				
				<p>
				<input class="btn btn-primary" type="submit" name="Submit" value="Submit" />
				</p>
			</div>
		</div>
		</form>
		<?
		break;
		
	default:
		?>
		<form action="scheduler?step=1" method="post" name="Scedule">
		<h1>Appointment Scheduler</h1>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				<h3>Choose a Service</h3>
				<?
				$params = array('1');
				$aServices = $app->db->rawQuery("SELECT * FROM lmg_scheduler_services WHERE active = ?",$params);
				if(count($aServices) > 0){
					for($s=0;$s<count($aServices);$s++){
						?>
						<input required="required" type="radio" name="AppointmentService" value="<?=$aServices[$s]['service_name'];?>" /> <?=$aServices[$s]['service_name'];?><br />
						<?
					}
				}else{
					echo '<b><i>There are no services available.</i></b>';
				}
				?>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<h3>Choose a date</h3>
				<input readonly="readonly" required="required" type="text" name="AppointmentDate" id="altDate" value="" class="datepicker form-control" style="width:auto; display:inline-block; margin-right:10px;" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<h3>Choose a Time</h3>
				<div id="SchTimes">
					Select Service and date to see available times.
				</div>
			</div>
		</div>
		<input type="submit" name="Submit" value="CONTINUE" />
		</form>
		<?
		break;
}
?>