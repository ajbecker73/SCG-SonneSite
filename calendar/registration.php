<h1>Event Registration</h1>
<?
$eid = $_GET['id'];
$evt = $app->db
	->where('id',$eid)
	->get('lmg_calendar');
$regStep = $_GET['step'];
if($regStep == ''){ $regStep = 1; }

if($_POST['Submit'] != ''){
	
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$_SESSION['session_data']['registrationDetails'][$k] = $v;
		}
	}
	
	if($_POST['Submit'] == 'Continue to Payment Info'){
		if($_POST['firstname'] == ''){ $boolErr = true; $errString .= '&firstname=true'; }
		if($_POST['lastname'] == ''){ $boolErr = true; $errString .= '&lastname=true'; }
		if($_POST['email'] == ''){ $boolErr = true; $errString .= '&email=true'; }
		if($_POST['phone'] == ''){ $boolErr = true; $errString .= '&phone=true'; }
		if($_SESSION['session_data']['registrationDetails']['attendee_firstname'][0] == ''){ $boolErr = true; $errString .= '&attendee=true'; }
		if($boolErr){
			header('location:'.DOMAIN_ROOT.'calendar/registration?id='.$eid.'&step=1&error=true'.$errString);
		}
	}
	
	if($_POST['Submit'] == 'Continue to Verify Registration'){
		$_SESSION['session_data']['registrationDetails']['amount'] = 0;
		$_SESSION['session_data']['registrationDetails']['cardexp'] = $_POST['cardMonth']."/".$_POST['cardYear'];
		$aCount = count($_SESSION['session_data']['registrationDetails']['attendee_firstname']);
		for($a=0;$a<$aCount;$a++){
			if($_SESSION['session_data']['registrationDetails']['attendee_member'][$a] == 'yes'){
				$_SESSION['session_data']['registrationDetails']['amount'] += $evt[0]['cal_memberprice'];
			}else{
				$_SESSION['session_data']['registrationDetails']['amount'] += $evt[0]['cal_nonmemberprice'];
			}
		}
		if($_POST['cardname'] == ''){ $boolErr = true; $errString .= '&cardname=true'; }
		if($_POST['cardnumber'] == ''){ $boolErr = true; $errString .= '&cardnumber=true'; }
		if($_POST['cardccv'] == ''){ $boolErr = true; $errString .= '&cardccv=true'; }
		if($boolErr){
			header('location:'.DOMAIN_ROOT.'calendar/registration?id='.$eid.'&step=2&error=true'.$errString);
		}
	}
	
	if($_POST['Submit'] == 'Submit Registration'){
		$paySuccess = false;
		
		if($_SESSION['session_data']['registrationDetails']['amount'] > 0){
			
			//PAYPAL VARIABLES ////////////////////////////////////////////////////////
			$paymentType = urlencode('Authorization');				// or 'Sale'
			$firstName = urlencode($_SESSION['session_data']['registrationDetails']['firstname']);
			$lastName = urlencode($_SESSION['session_data']['registrationDetails']['lastname']);
			if(substr($_SESSION['session_data']['registrationDetails']['cardnumber'],0,1) == "3"){
				$creditCardType = urlencode('Amex');
			}
			
			if(substr($_SESSION['session_data']['registrationDetails']['cardnumber'],0,1) == "4"){
				$creditCardType = urlencode('Visa');
			}
			
			if(substr($_SESSION['session_data']['registrationDetails']['cardnumber'],0,1) == "5"){
				$creditCardType = urlencode('MasterCard');
			}
			$creditCardNumber = urlencode($_SESSION['session_data']['registrationDetails']['cardnumber']);
			$expDateMonth = $_SESSION['session_data']['registrationDetails']['cardMonth'];
			// Month must be padded with leading zero
			$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
			$expDateYear = urlencode($_SESSION['session_data']['registrationDetails']['cardYear']);
			$cvv2Number = urlencode($_SESSION['session_data']['registrationDetails']['cardccv']);
			$address1 = urlencode($_SESSION['session_data']['registrationDetails']['address']);
			$address2 = urlencode('');
			$city = urlencode($_SESSION['session_data']['registrationDetails']['city']);
			$state = urlencode($_SESSION['session_data']['registrationDetails']['state']);
			$zip = urlencode($_SESSION['session_data']['registrationDetails']['zip']);
			$country = urlencode('USA');				// US or other valid country code
			$amount = urlencode($_SESSION['session_data']['registrationDetails']['amount']);
			$currencyID = urlencode('USD');							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
			////////////////////////////////////////////////////////////////////////////
			
			include(FILE_ROOT.'payments/'.MERCHANT.'.php');
		}else{
			$paySuccess = true;
		}
		if(!$paySuccess){
			header('location:'.DOMAIN_ROOT.'calendar/registration?id='.$eid.'&step=3&error=true');
		}else{
			//INSERT REGISTRATION TO DATABASE AND SEND EMAILS
				$attArr = array();
				$attArr[0] = $_SESSION['session_data']['registrationDetails']['attendee_firstname'];
				$attArr[1] = $_SESSION['session_data']['registrationDetails']['attendee_lastname'];
				$attArr[2] = $_SESSION['session_data']['registrationDetails']['attendee_member'];
				
				$insData = array(
					'eid' => $eid,
					'reg_firstname' => $_SESSION['session_data']['registrationDetails']['firstname'],
					'reg_lastname' => $_SESSION['session_data']['registrationDetails']['lastname'],
					'reg_company' => $_SESSION['session_data']['registrationDetails']['company'],
					'reg_email' => $_SESSION['session_data']['registrationDetails']['email'],
					'reg_phone' => $_SESSION['session_data']['registrationDetails']['phone'],
					'reg_attendees' => serialize($attArr)
				);
				$mbrid = $app->db->insert('lmg_registrations',$insData);
				//USER EMAIL
					$msg = '<h1>Thank you for your registration</h1>';
					$msg .= '<p>Your registration details are as follows:<br>
							<h4>'.$evt[0]['cal_title'].'</h4>
							<b>'.writeDate($evt[0]['cal_startdate'],$evt[0]['cal_enddate']).'</b>
							</p>';
					$msg .= '<p>Thanks again for registering</p>';
					LMGmail($_SESSION['session_data']['registrationDetails']['email'], 'Thank you for your registration', $msg ,$msg, COMPANY_EMAIL);
				//COMPANY EMAIL
					$msg = '<h1>New Event Registration for'.$evt[0]['cal_title'].'</h1>';
					foreach($_SESSION['session_data']['registrationDetails'] as $k => $v){
						if($k == 'attendee_firstname'){
							$aCt = count($v);
							$msg .= '<br><b>Attendees</b><br>';
							for($h=0;$h<$aCt;$h++){
								$msg .= '<b>'.$_SESSION['session_data']['registrationDetails']['attendee_firstname'][$h].' '.$_SESSION['session_data']['registrationDetails']['attendee_lastname'][$h].'</b><br><br><br>';
							}
						}else{
							$msg .= '<b>'.$k.'</b>: '.$v.'<br>';
						}
					}
					$eTo = array(
						COMPANY_EMAIL,
						'vee@lmgnow.com',
						'webmaster@lmgnow.com'
					);
					LMGmail($eTo, 'New Event Registration From '.COMPANY, $msg ,$msg, COMPANY_EMAIL);
					
					unset($_SESSION['session_data']['registrationDetails']);
			//////////////////////////////////////////////////
		}
	}
}

?>
<form action="<?=DOMAIN_ROOT;?>calendar/registration?id=<?=$eid;?>&step=<?=$regStep+1;?>" method="post" name="Register">
<table class="table-condensed" style="width:100%">
	<tr>
		<td colspan="3" class="alert alert-info">
			<div class="pull-right" style="text-align:right;">
				Member Price: $<?=number_format($evt[0]['cal_memberprice'],2);?><br>
				Non-Member Price: $<?=number_format($evt[0]['cal_nonmemberprice'],2);?><br>
			</div>
			<h4><?=$evt[0]['cal_title'];?></h4>
			<b><?=writeDate($evt[0]['cal_startdate'],$evt[0]['cal_enddate']);?></b>
			<hr style="clear:both;" />
			<div class="btn-group">
				<a href="<?=DOMAIN_ROOT;?>calendar/registration?id=<?=$eid;?>&step=1" class="btn<? echo ($regStep > 0 ? ' btn-success' : ''); echo ($regStep < 1 ? ' disabled' : ''); ?>"><? echo ($regStep > 1 ? '<span class="icon-ok"></span> ' : ''); ?>Information</a>
				<a href="<?=DOMAIN_ROOT;?>calendar/registration?id=<?=$eid;?>&step=2" class="btn<? echo ($regStep > 1 ? ' btn-success' : ''); echo ($regStep < 2 ? ' disabled' : ''); ?>"><? echo ($regStep > 2 ? '<span class="icon-ok"></span> ' : ''); ?>Payment</a>
				<a href="<?=DOMAIN_ROOT;?>calendar/registration?id=<?=$eid;?>&step=3" class="btn<? echo ($regStep > 2 ? ' btn-success' : ''); echo ($regStep < 3 ? ' disabled' : ''); ?>"><? echo ($regStep > 3 ? '<span class="icon-ok"></span> ' : ''); ?>Review</a>
				<a href="" class="btn<? echo ($regStep == 4 ? ' btn-success' : ''); echo ($regStep < 4 ? ' disabled' : ''); ?>"><? echo ($regStep == 4 ? '<span class="icon-ok"></span> ' : ''); ?>Confirmation</a>
			</div>
		</td>
	</tr>
<?
switch ($regStep){
	
	case 1:
		if($_SESSION['session_data']['memberDetails']['mid'] == ""){
			?>
			<tr><td colspan="3">
			<div class="alert alert-warning"><span class="icon-arrow-left"></span> Login to the left to automatically fill in form.</div>
			<?
			if($_GET['error'] == 'true'){ echo '<div class="alert alert-error"><span class="icon-warning-sign"></span> ERROR - Please fill in required fields.</div>'; }
			?>
			</td></tr>
			<?
		}
		?>
			<tr><td colspan="3"><br>
			<h3>Registrant Information</h3>
			</td></tr>
			<tr>
				<td<? echo ($_GET['firstname'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>First Name: </b></label>
					<input type="text" name="firstname" value="<? echo ($_SESSION['session_data']['registrationDetails']['firstname'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['firstname']
							 : $_SESSION['session_data']['memberDetails']['firstname']
							); ?>" />
				</td>
				<td colspan="2"<? echo ($_GET['lastname'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Last Name: </b></label>
					<input type="text" name="lastname" value="<? echo ($_SESSION['session_data']['registrationDetails']['lastname'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['lastname']
							 : $_SESSION['session_data']['memberDetails']['lastname']
							); ?>" />
				</td>
			</tr>
			<tr>
				<td<? echo ($_GET['address'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Address: </b></label>
					<input type="text" name="address" value="<? echo ($_SESSION['session_data']['registrationDetails']['address'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['address']
							 : $_SESSION['session_data']['memberDetails']['address']
							); ?>" />
				</td>
				<td colspan="2"<? echo ($_GET['city'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>City: </b></label>
					<input type="text" name="city" value="<? echo ($_SESSION['session_data']['registrationDetails']['city'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['city']
							 : $_SESSION['session_data']['memberDetails']['city']
							); ?>" />
				</td>
			</tr>
			<tr>
				<td<? echo ($_GET['state'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>State: </b></label>
				<?=$app->getStateDropdown('state',$app->states_arr,$_SESSION['session_data']['application']['state']);?>
				</td>
				<td colspan="2"<? echo ($_GET['zip'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Zip: </b></label>
					<input type="text" name="zip" value="<? echo ($_SESSION['session_data']['registrationDetails']['zip'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['zip']
							 : $_SESSION['session_data']['memberDetails']['zip']
							); ?>" />
				</td>
			</tr>
			<tr>
				<td<? echo ($_GET['email'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Email Address: </b></label>
					<input type="email" name="email" value="<? echo ($_SESSION['session_data']['registrationDetails']['email'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['email']
							 : $_SESSION['session_data']['memberDetails']['email']
							); ?>" />
				</td>
				<td colspan="2"<? echo ($_GET['phone'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Phone Number: </b></label>
					<input type="text" name="phone" value="<? echo ($_SESSION['session_data']['registrationDetails']['phone'] != ''
							 ? formatPhone($_SESSION['session_data']['registrationDetails']['phone'])
							 : formatPhone($_SESSION['session_data']['memberDetails']['phone'])
							); ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Company: </b></label>
					<input type="text" name="company" value="<? echo ($_SESSION['session_data']['registrationDetails']['company'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['company']
							 : $_SESSION['session_data']['memberDetails']['company']
							); ?>" />
				</td>
				<td colspan="2">

				</td>
			</tr>
			<tr><td colspan="3"><br>
			<h3>Attendee Information</h3>
			</td></tr>
			<tr>
				<td<? echo ($_GET['attendee'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>First Name: </b></label>
					<input type="text" name="attendee_firstname[]" value="<? echo ($_SESSION['session_data']['registrationDetails']['attendee_firstname'][0] != ''
							 ? $_SESSION['session_data']['registrationDetails']['attendee_firstname'][0]
							 : $_SESSION['session_data']['memberDetails']['firstname']
							); ?>" />
				</td>
				<td<? echo ($_GET['attendee'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Last Name: </b></label>
					<input type="text" name="attendee_lastname[]" value="<? echo ($_SESSION['session_data']['registrationDetails']['attendee_lastname'][0] != ''
							 ? $_SESSION['session_data']['registrationDetails']['attendee_lastname'][0]
							 : $_SESSION['session_data']['memberDetails']['lastname']
							); ?>" />
				</td>
				<td>
					<label><b>Member</b></label>
					<select name="attendee_member[]" style="width:100px;">
						<option value="yes"<? echo ($_SESSION['session_data']['memberDetails']['attendee_member'][0] == 'yes' ? ' selected' : '');?>>Yes</option>
						<option value="no"<? echo ($_SESSION['session_data']['memberDetails']['attendee_member'][0] == 'no' ? ' selected' : '');?>>No</option>
					</select>
				</td>
			</tr>
			<?
			$attCount = count($_SESSION['session_data']['registrationDetails']['attendee_firstname']);
			for($ac=1;$ac<$attCount;$ac++){
				?>
					<tr>
						<td>
							<label><b>First Name: </b></label>
							<input type="text" name="attendee_firstname[]" value="<? echo ($_SESSION['session_data']['registrationDetails']['attendee_firstname'][$ac] != ''
									 ? $_SESSION['session_data']['registrationDetails']['attendee_firstname'][$ac]
									 : ''
									); ?>" />
						</td>
						<td>
							<label><b>Last Name: </b></label>
							<input type="text" name="attendee_lastname[]" value="<? echo ($_SESSION['session_data']['registrationDetails']['attendee_lastname'][$ac] != ''
									 ? $_SESSION['session_data']['registrationDetails']['attendee_lastname'][$ac]
									 : ''
									); ?>" />
						</td>
						<td>
							<label><b>Member</b></label>
							<select name="attendee_member[]" style="width:100px;">
								<option value="yes"<? echo ($_SESSION['session_data']['memberDetails']['attendee_member'][$ac] == 'yes' ? ' selected' : '');?>>Yes</option>
								<option value="no"<? echo ($_SESSION['session_data']['memberDetails']['attendee_member'][$ac] == 'no' ? ' selected' : '');?>>No</option>
							</select>
						</td>
					</tr>
				<?
			}
			?>
			<tr>
				<td colspan="3">
					<div id="extraAttendees"></div>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<a title="<? echo($attCount == 0 ? '1' : $attCount);?>" id="addAttendee" class="btn" name="addAttendee">Add Attendee</a>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<input class="btn btn-primary pull-right" type="submit" name="Submit" value="Continue to Payment Info" />
				</td>
			</tr>
			
		<?
		break;
		
	case 2:
		?>
			<tr><td colspan="3">
			<?
			if($_GET['error'] == 'true'){ echo '<div class="alert alert-error"><span class="icon-warning-sign"></span> ERROR - Please fill in required fields.</div>'; }
			?>
			</td></tr>
			<tr><td colspan="3"><br>
			<h3>Payment Information</h3>
			</td></tr>
			<tr>
				<td<? echo ($_GET['cardname'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Name on Card: </b></label>
					<input type="text" name="cardname" value="<? echo ($_SESSION['session_data']['registrationDetails']['cardname'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['cardname']
							 : ''
							); ?>" />
				</td>
				<td colspan="2"<? echo ($_GET['cardnumber'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Card Number: </b></label>
					<input type="text" name="cardnumber" value="<? echo ($_SESSION['session_data']['registrationDetails']['cardnumber'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['cardnumber']
							 : ''
							); ?>" />
				</td>
			</tr>
			<tr>
				<td<? echo ($_GET['cardexp'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Expiration: </b></label>
					<select name="cardMonth" style="width:65px;">
					<?
					for($m=1;$m<13;$m++){
						?>
						<option value="<?=$m;?>"<? echo ($_SESSION['session_data']['registrationDetails']['cardMonth'] == $m ? ' selected' : '');?>><?=date("M",mktime(0,0,0,$m,1,date("Y")));?></option>
						<?
					}
					?>
					</select>
					<select name="cardYear" style="width:75px;">
					<?
					for($y=date("Y");$y<date("Y")+6;$y++){
						?>
						<option value="<?=$y;?>"<? echo ($_SESSION['session_data']['registrationDetails']['cardYear'] == $y ? ' selected' : '');?>><?=date("Y",mktime(0,0,0,1,1,$y));?></option>
						<?
					}
					?>
					</select>
				</td>
				<td colspan="2"<? echo ($_GET['cardccv'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Security Code: </b></label>
					<input type="text" name="cardccv" value="<? echo ($_SESSION['session_data']['registrationDetails']['cardccv'] != ''
							 ? $_SESSION['session_data']['registrationDetails']['cardccv']
							 : ''
							); ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<p>&nbsp;</p>
					<input class="btn btn-primary pull-right" type="submit" name="Submit" value="Continue to Verify Registration" />
				</td>
			</tr>
		<?
		break;
		
	case 3:
		?>
			<tr><td colspan="3">
			<?
			if($_GET['error'] == 'true'){
				?>
				<div class="alert alert-error">
				<?
				echo('ERROR - ' . urldecode($_SESSION['payError']['L_LONGMESSAGE0']));
				//print_r($_SESSION['payError']);
				?>
				</div>
				<?
			}
			?>
			</td></tr>
			<tr>
				<td><br>
					<h3>Registration Info</h3>
					<b>Registrant: </b>
					<div style="margin:0 0 0 20px;">
						<?=$_SESSION['session_data']['registrationDetails']['firstname']." ".$_SESSION['session_data']['registrationDetails']['lastname'];?><br>
						<?=$_SESSION['session_data']['registrationDetails']['company'];?><br>
						<?=$_SESSION['session_data']['registrationDetails']['email'];?><br>
						<?=$_SESSION['session_data']['registrationDetails']['phone'];?><br>
					</div><br><br>
					<b>Attendees</b>
					<div style="margin:0 0 0 20px;">
					<?
						$attCount = count($_SESSION['session_data']['registrationDetails']['attendee_firstname']);
						for($ac=0;$ac<$attCount;$ac++){
							echo $_SESSION['session_data']['registrationDetails']['attendee_firstname'][$ac]." ".$_SESSION['session_data']['registrationDetails']['attendee_lastname'][$ac]."<br>";
						}
					?>
					</div>
				</td>
				<td colspan="2"><br>
					<h3>Payment Info</h3>
					<b>Card Info</b>:
					<div style="margin:0 0 0 20px;">
						<?=$_SESSION['session_data']['registrationDetails']['cardname'];?><br>
						<?
						$cLen = strlen($_POST['cardnumber']);
						?>
						<?='xxxxxxxxxxxx'.substr($_SESSION['session_data']['registrationDetails']['cardnumber'],$cLen-4);?><br>
						<?=$_SESSION['session_data']['registrationDetails']['cardexp'];?><br>
						<?=$_SESSION['session_data']['registrationDetails']['cardccv'];?><br>
					</div><br><br>
					<b>Charges</b>:
					<div style="margin:0 0 0 20px;">
						<?
						for($ac=0;$ac<$attCount;$ac++){
							echo ($_SESSION['session_data']['registrationDetails']['attendee_member'][$ac] == "yes" ? '(Member) $'.number_format($evt[0]['cal_memberprice'],2) : '(Non-Member) $'.number_format($evt[0]['cal_nonmemberprice'],2))."<br>";
						}
						echo '<br><b>TOTAL</b>: $'.$_SESSION['session_data']['registrationDetails']['amount'];
						?>
					</div>
					
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<p>&nbsp;</p>
					<input class="btn btn-primary pull-right" type="submit" name="Submit" value="Submit Registration" />
				</td>
			</tr>
		<?
		break;
		
	case 4:
		?>
			<tr>
				<td colspan="3">
				<div class="alert alert-success">
					<h3>Your registration has been processed.</h3>
				</div>
				
				</td>
			</tr>
		<?
		break;
				
}
?>
</table>
</form>