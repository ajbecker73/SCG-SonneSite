<h1>Join <?= COMPANY ?></h1>
<?
$regStep = $_GET['step'];
if($regStep == ''){ $regStep = 1; }

if($_POST['Submit'] != ''){
	
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$_SESSION['session_data']['application'][$k] = $v;
		}
	}
	
	if($_POST['Submit'] == 'Continue to Payment Info'){
		if($_POST['company'] == ''){ $boolErr = true; $errString .= '&company=true'; }
		if($_POST['firstname'] == ''){ $boolErr = true; $errString .= '&firstname=true'; }
		if($_POST['lastname'] == ''){ $boolErr = true; $errString .= '&lastname=true'; }
		if($_POST['email'] == ''){ $boolErr = true; $errString .= '&email=true'; }
		if($_POST['phone'] == ''){ $boolErr = true; $errString .= '&phone=true'; }
		if($_POST['dues'] == ''){ $boolErr = true; $errString .= '&dues=true'; }
		
		$_SESSION['session_data']['application']['amount'] = $_POST['dues'];
		
		if($boolErr){
			header('location:'.DOMAIN_ROOT.'members/join?step=1&error=true'.$errString);
		}
	}
	
	if($_POST['Submit'] == 'Continue to Verify Application'){
		$_SESSION['session_data']['application']['cardexp'] = $_POST['cardMonth']."/".$_POST['cardYear'];
		if($_POST['cardname'] == ''){ $boolErr = true; $errString .= '&cardname=true'; }
		if($_POST['cardnumber'] == ''){ $boolErr = true; $errString .= '&cardnumber=true'; }
		if($_POST['cardccv'] == ''){ $boolErr = true; $errString .= '&cardccv=true'; }
		if($boolErr){
			header('location:'.DOMAIN_ROOT.'members/join?step=2&error=true'.$errString);
		}
	}
	
	if($_POST['Submit'] == 'Submit Application'){
		$paySuccess = false;
			
			//PAYPAL VARIABLES ////////////////////////////////////////////////////////
			$paymentType = urlencode('Authorization');				// or 'Sale'
			$firstName = urlencode($_SESSION['session_data']['application']['firstname']);
			$lastName = urlencode($_SESSION['session_data']['application']['lastname']);
			if(substr($_SESSION['session_data']['application']['cardnumber'],0,1) == "3"){
				$creditCardType = urlencode('Amex');
			}
			
			if(substr($_SESSION['session_data']['application']['cardnumber'],0,1) == "4"){
				$creditCardType = urlencode('Visa');
			}
			
			if(substr($_SESSION['session_data']['application']['cardnumber'],0,1) == "5"){
				$creditCardType = urlencode('MasterCard');
			}
			$creditCardNumber = urlencode($_SESSION['session_data']['application']['cardnumber']);
			$expDateMonth = $_SESSION['session_data']['application']['cardMonth'];
			// Month must be padded with leading zero
			$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
			$expDateYear = urlencode($_SESSION['session_data']['application']['cardYear']);
			$cvv2Number = urlencode($_SESSION['session_data']['application']['cardccv']);
			$address1 = urlencode($_SESSION['session_data']['application']['address']);
			$address2 = urlencode('');
			$city = urlencode($_SESSION['session_data']['application']['city']);
			$state = urlencode($_SESSION['session_data']['application']['state']);
			$zip = urlencode($_SESSION['session_data']['application']['zip']);
			$country = urlencode('USA');				// US or other valid country code
			$amount = urlencode($_SESSION['session_data']['application']['amount']);
			$currencyID = urlencode('USD');							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
			////////////////////////////////////////////////////////////////////////////
			
			if($_SESSION['session_data']['application']['amount'] > 0){
				include(FILE_ROOT.'payments/'.MERCHANT.'.php');
			}else{
				$paySuccess = true;
			}
			
			if(!$paySuccess){
				header('location:'.DOMAIN_ROOT.'members/join?step=3&error=true');
			}else{
				//INSERT APPLICATION TO DATABASE AND SEND EMAILS
				$newPass = pass_gen(10);
				$insData = array(
					'date_joined' => date('Y-m-d'),
					'mbr_active' => 1,
					'mbr_firstname' => $_SESSION['session_data']['application']['firstname'],
					'mbr_lastname' => $_SESSION['session_data']['application']['lastname'],
					'mbr_company' => $_SESSION['session_data']['application']['company'],
					'mbr_address' => $_SESSION['session_data']['application']['address'],
					'mbr_city' => $_SESSION['session_data']['application']['city'],
					'mbr_state' => $_SESSION['session_data']['application']['state'],
					'mbr_email' => $_SESSION['session_data']['application']['email'],
					'mbr_phone' => $_SESSION['session_data']['application']['phone'],
					'mbr_username' => $_SESSION['session_data']['application']['email'],
					'mbr_password' => $newPass
				);
				$mbrid = $app->db->insert('lmg_members',$insData);
				$mbrid = $app->db->insert('lmg_users',$insData);
				//USER EMAIL
					$msg = '<h1>Thank you for joining '.COMPANY.'</h1>';
					$msg .= '<p>Your login credentials are as follows:<br />
							Username: '.$_SESSION['session_data']['application']['email'].'<br />
							Password: '.$newPass.'
							</p>';
					$msg .= '<p>Thanks again for joining '.COMPANY.'</p>';
					LMGmail($_SESSION['session_data']['application']['email'], 'Thank you for joining '.COMPANY, $msg ,$msg, COMPANY_EMAIL);
				//COMPANY EMAIL
					$msg = '<h1>New Member Application</h1>';
					foreach($_SESSION['session_data']['application'] as $k => $v){
						$msg .= '<b>'.$k.'</b>: '.$v.'<br />';
					}
					LMGmail(COMPANY_EMAIL, 'New Member Application From '.COMPANY, $msg ,$msg, COMPANY_EMAIL);
				
					unset($_SESSION['session_data']['application']);
				//////////////////////////////////////////////////
			}
	}
}
?>
<form action="<?=DOMAIN_ROOT;?>members/join?step=<?=$regStep+1;?>" method="post" name="Register">
<table class="table-condensed" style="width:100%">
	<tr>
		<td colspan="2" class="alert alert-info">
			<hr style="clear:both;" />
			<div class="btn-group">
				<a href="<?=DOMAIN_ROOT;?>members/join?step=1" class="btn<? echo ($regStep > 0 ? ' btn-success' : ''); echo ($regStep < 1 ? ' disabled' : ''); ?>"><? echo ($regStep > 1 ? '<span class="icon-ok"></span> ' : ''); ?>Information</a>
				<a href="<?=DOMAIN_ROOT;?>members/join?step=2" class="btn<? echo ($regStep > 1 ? ' btn-success' : ''); echo ($regStep < 2 ? ' disabled' : ''); ?>"><? echo ($regStep > 2 ? '<span class="icon-ok"></span> ' : ''); ?>Payment</a>
				<a href="<?=DOMAIN_ROOT;?>members/join?step=3" class="btn<? echo ($regStep > 2 ? ' btn-success' : ''); echo ($regStep < 3 ? ' disabled' : ''); ?>"><? echo ($regStep > 3 ? '<span class="icon-ok"></span> ' : ''); ?>Review</a>
				<a href="" class="btn<? echo ($regStep == 4 ? ' btn-success' : ''); echo ($regStep < 4 ? ' disabled' : ''); ?>"><? echo ($regStep == 4 ? '<span class="icon-ok"></span> ' : ''); ?>Confirmation</a>
			</div>
		</td>
	</tr>
<?
switch ($regStep){
	
	case 1:
		?>
		<tr>
			<td colspan="2"><br />
				<?
				if($_GET['error'] == 'true'){ echo '<div class="alert alert-error"><span class="icon-warning-sign"></span> ERROR - Please fill in required fields.</div>'; }
				?>
				<h3>Select Dues</h3>
				<table class="table table-condensed">
					<tr<? echo ($_GET['dues'] == 'true' ? ' class="control-group error"' : ''); ?>>
						<td></td>
						<td><b>Annual Sales</b></td>
						<td><b>Dues</b></td>
					</tr>
					<tr class="selectRow<? echo ($_SESSION['session_data']['application']['dues'] == '900' ? ' rowSelected' : ''); ?>">
						<td><input type="radio" name="dues" value="900"<? echo ($_SESSION['session_data']['application']['dues'] == '900' ? ' checked' : ''); ?> /></td>
						<td>$0-2 Million</td>
						<td>$900 dues</td>
					</tr>
					<tr class="selectRow<? echo ($_SESSION['session_data']['application']['dues'] == '1100' ? ' rowSelected' : ''); ?>">
						<td><input type="radio" name="dues" value="1100"<? echo ($_SESSION['session_data']['application']['dues'] == '1100' ? ' checked' : ''); ?> /></td>
						<td>$2-6 Million</td>
						<td>$1100 dues</td>
					</tr>
					<tr class="selectRow<? echo ($_SESSION['session_data']['application']['dues'] == '1500' ? ' rowSelected' : ''); ?>">
						<td><input type="radio" name="dues" value="1500"<? echo ($_SESSION['session_data']['application']['dues'] == '1500' ? ' checked' : ''); ?> /></td>
						<td>$6-10 Million</td>
						<td>$1500 dues</td>
					</tr>
					<tr class="selectRow<? echo ($_SESSION['session_data']['application']['dues'] == '1950' ? ' rowSelected' : ''); ?>">
						<td><input type="radio" name="dues" value="1950"<? echo ($_SESSION['session_data']['application']['dues'] == '1950' ? ' checked' : ''); ?> /></td>
						<td>$10-20 Million</td>
						<td>$1950 dues</td>
					</tr>
					<tr class="selectRow<? echo ($_SESSION['session_data']['application']['dues'] == '2150' ? ' rowSelected' : ''); ?>">
						<td><input type="radio" name="dues" value="2150"<? echo ($_SESSION['session_data']['application']['dues'] == '2150' ? ' checked' : ''); ?> /></td>
						<td>$20+ Million</td>
						<td>$2150 dues</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2"><h3>Company Info</h3></td>
		</tr>
		<tr>
			<td colspan="2"<? echo ($_GET['company'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>Company Name</b></label>
				<input style="width:82%;" type="text" name="company" value="<?=$_SESSION['session_data']['application']['company'];?>" />
			</td>
		</tr>
		<tr>
			<td<? echo ($_GET['firstname'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>First Name</b></label>
				<input type="text" name="firstname" value="<?=$_SESSION['session_data']['application']['firstname'];?>" />
			</td>
			<td<? echo ($_GET['lastname'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>Last Name</b></label>
				<input type="text" name="lastname" value="<?=$_SESSION['session_data']['application']['lastname'];?>" />
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Address</b></label>
				<input type="text" name="address" value="<?=$_SESSION['session_data']['application']['address'];?>" />
			</td>
			<td>
				<label><b>City</b></label>
				<input type="text" name="city" value="<?=$_SESSION['session_data']['application']['city'];?>" />
			</td>
		</tr>
		<tr>
			<td>
				<label><b>State</b></label>
				<?=$app->getStateDropdown('state',$app->states_arr,$_SESSION['session_data']['application']['state']);?>
			</td>
			<td>
				<label><b>Zip</b></label>
				<input type="text" name="zip" value="<?=$_SESSION['session_data']['application']['zip'];?>" />
			</td>
		</tr>
		<tr>
			<td<? echo ($_GET['phone'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>Phone</b></label>
				<input type="text" name="phone" value="<?=$_SESSION['session_data']['application']['phone'];?>" />
			</td>
			<td>
				<label><b>Fax</b></label>
				<input type="text" name="fax" value="<?=$_SESSION['session_data']['application']['fax'];?>" />
			</td>
		</tr>
		<tr>
			<td<? echo ($_GET['email'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>Email</b></label>
				<input type="email" name="email" value="<?=$_SESSION['session_data']['application']['email'];?>" />
			</td>
			<td>
				<label><b>Website</b></label>
				<input type="url" name="website" placeholder="http://" value="<?=$_SESSION['session_data']['application']['website'];?>" />
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
			<tr><td colspan="3"><br />
			<h3>Payment Information</h3>
			</td></tr>
			<tr>
				<td<? echo ($_GET['cardname'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Name on Card: </b></label>
					<input type="text" name="cardname" value="<? echo ($_SESSION['session_data']['application']['cardname'] != ''
							 ? $_SESSION['session_data']['application']['cardname']
							 : ''
							); ?>" />
				</td>
				<td colspan="2"<? echo ($_GET['cardnumber'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Card Number: </b></label>
					<input type="text" name="cardnumber" value="<? echo ($_SESSION['session_data']['application']['cardnumber'] != ''
							 ? $_SESSION['session_data']['application']['cardnumber']
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
						<option value="<?=$m;?>"<? echo ($_SESSION['session_data']['application']['cardMonth'] == $m ? ' selected' : '');?>><?=date("M",mktime(0,0,0,$m,1,date("Y")));?></option>
						<?
					}
					?>
					</select>
					<select name="cardYear" style="width:75px;">
					<?
					for($y=date("Y");$y<date("Y")+6;$y++){
						?>
						<option value="<?=$y;?>"<? echo ($_SESSION['session_data']['application']['cardYear'] == $y ? ' selected' : '');?>><?=date("Y",mktime(0,0,0,1,1,$y));?></option>
						<?
					}
					?>
					</select>
				</td>
				<td colspan="2"<? echo ($_GET['cardccv'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Security Code: </b></label>
					<input type="text" name="cardccv" value="<? echo ($_SESSION['session_data']['application']['cardccv'] != ''
							 ? $_SESSION['session_data']['application']['cardccv']
							 : ''
							); ?>" />
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<p>&nbsp;</p>
					<input class="btn btn-primary pull-right" type="submit" name="Submit" value="Continue to Verify Application" />
				</td>
			</tr>
		<?
		break;

	case 3:
		?>
		<tr>
			<td colspan="2"><br />
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
				<table class="table table-condensed">
					<tr>
						<td><b>Annual Sales</b></td>
						<td><b>Dues</b></td>
					</tr>
					<tr<? echo ($_SESSION['session_data']['application']['dues'] == '900' ? ' class="rowSelected"' : ''); ?>>
						<td>$0-2 Million</td>
						<td>$900 dues</td>
					</tr>
					<tr<? echo ($_SESSION['session_data']['application']['dues'] == '1100' ? ' class="rowSelected"' : ''); ?>>
						<td>$2-6 Million</td>
						<td>$1100 dues</td>
					</tr>
					<tr<? echo ($_SESSION['session_data']['application']['dues'] == '1500' ? ' class="rowSelected"' : ''); ?>>
						<td>$6-10 Million</td>
						<td>$1500 dues</td>
					</tr>
					<tr<? echo ($_SESSION['session_data']['application']['dues'] == '1950' ? ' class="rowSelected"' : ''); ?>>
						<td>$10-20 Million</td>
						<td>$1950 dues</td>
					</tr>
					<tr<? echo ($_SESSION['session_data']['application']['dues'] == '2150' ? ' class="rowSelected"' : ''); ?>>
						<td>$20+ Million</td>
						<td>$2150 dues</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3">
			<div class="pull-left" style="margin:0 30px 0 0;">
				<?
				foreach($_SESSION['session_data']['application'] as $k => $v){
					echo '<b>'.$k.'</b>: '.$v."<br />";
					if($k == 'contact-title'){
						echo '</div><div>';
					}
				}
				?>
			</div>
				<input class="btn btn-primary pull-right" type="submit" name="Submit" value="Submit Application" />
			</td>
		</tr>
		<?
		break;
		
	case 4:
		?>
		<tr>
			<td colspan="3">
				<div class="alert alert-success">
					<h3>Your application has been processed.</h3>
				</div>
			</td>
		</tr>
		<?
		break;
		
}
?>
</table>
</form>