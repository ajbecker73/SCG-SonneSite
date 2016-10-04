<form action="" method="post" name="contact form">
	<?php
	if(isset($_REQUEST['Submit'])){
		
		$nameErr = false;
		$emailErr = false;
		$keyErr = false;
		
		$person = $_POST['Name'];
		$email = $_POST['Email'];
		$phone = $_POST['Phone'];
		$company = $_POST['Company'];
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
			$subject = "New message from ".COMPANY;
			$message = "Name: $person<br>";
			$message .= "Company: $company<br>";
			$message .= "Email: $email<br>";
			$message .= "Phone: $phone<br>";
				//LMGmail(array(COMPANY_EMAIL,'messages@lmgnow.com','webmaster@lmgnow.com'), $subject, $message ,$message, COMPANY_EMAIL);
				
				$to = array(
					array(COMPANY_EMAIL,COMPANY), // (EMAIL,NAME)
					array('webmaster@lmgnow.com','LMG Webmaster'),
					array('messages@azspe.com','LMG Office')
				);
				$from = array(SMTP_USERNAME,COMPANY); 
				if($app->PonyExpress($to, $subject, $message ,$message, $from, $replyTo, $CC, $BCC, $attachment) === true){ // $replyTo, $CC, $BCC, $attachment are optional
					header('location:'.DOMAIN_ROOT.'thank-you');
				}
		}else{
			echo '<div class="alert alert-danger">Please fix errors</div>';
		}
		
	}
	?>
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
</form>
