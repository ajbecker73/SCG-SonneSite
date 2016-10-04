<form action="" method="post" name="contact form">
	<?php	
	$obitID = $_GET['id'];
	$obit = $app->db->where('id',$obitID)->get('lmg_obituaries');

	if(isset($_REQUEST['Submit'])){
		
		$nameErr = false;
		$commErr = false;
		$keyErr = false;
		
		$person = $_POST['Name'];
		$email = $_POST['Email'];
		$comments = $_POST['Comments'];
		if($person == ""){
			$nameErr = true;
		}
		if($comments == ""){
			$commErr = true;
		}
		if($_POST['verify_num'] != $_POST['verify_them']) { 
			$keyErr = true;
		}
		if($keyErr == false && $nameErr == false && $emailErr == false){
			$subject = "New condolence for ".$obit[0]['lastname'].', '.$obit[0]['firstname'].' ON '.COMPANY;
			$message = "Name: $person<br>";
			$message .= "Comments: <br>$comments<br>";
			LMGmail(COMPANY_EMAIL, $subject, $message ,$message, COMPANY_EMAIL);
			$insArr = array(
				'name' => $person,
				'condolence' => $comments,
				'obit' => $obit[0]['id']
			);
			$app->db->insert('lmg_condolences',$insArr);
			echo '<div class="alert alert-success">Message has been sent</div>';
		}else{
			echo '<div class="alert alert-error">Please fix errors</div>';
		}
		
	}
	?>
	<h1>Send Condolence</h1>
	<?= '<h3>'.$obit[0]['lastname'].', '.$obit[0]['firstname'].'</h3>';?>
	<div style="text-align:right;"><span class="required">* denotes required field.&nbsp;&nbsp;&nbsp;</span></div>
	<p class="input-prepend control-group<? if($nameErr == true){ echo ' error' ;} ?>">
	<label>Name</label>
	<span class="add-on"><i class="icon-user"></i></span><input required style="width:90%;" type="text" name="Name" value="<?=$person;?>" />
	</p>

	<p class="input-prepend control-group<? if($commErr == true){ echo ' error' ;} ?>">
	<label>Message:</label>
	<span class="add-on"><i class="icon-align-justify"></i></span>
	<textarea required style="width:90%; height:125px;" name="Comments"><?=$comments;?></textarea>
	</p>
	
	<p class="input-prepend control-group input-append<? if($keyErr == true){ echo ' error' ;} ?>">
		<?
		$secCode = gen_pass(5)
		?>
		<input type="hidden" name="verify_num" value="<?=$secCode;?>" />
		<label>Security Code</label>
		<span class="add-on"><i class="icon-lock"></i></span>
			<input required type="text" name="verify_them" value="" />
		<span class="add-on"><?=$secCode;?></span>
	</p>
	
	<p>
	<input class="btn" type="submit" name="Submit" value="Submit" />
	</p>
</form>
