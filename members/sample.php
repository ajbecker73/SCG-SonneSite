<h1>Download a Sample Copy of the ASA Short-Form Addendum to Subcontract</h1>
<?
$regStep = $_GET['step'];
if($regStep == ''){ $regStep = 1; }

if($_POST['Submit'] != ''){
	
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$_SESSION['session_data']['prospect'][$k] = $v;
		}
	}
	
	if($_POST['Submit'] == 'Submit Info'){
			
		if($_POST['company'] == ''){ $boolErr = true; $errString .= '&company=true'; }
		if($_POST['firstname'] == ''){ $boolErr = true; $errString .= '&firstname=true'; }
		if($_POST['lastname'] == ''){ $boolErr = true; $errString .= '&lastname=true'; }
		if($_POST['email'] == ''){ $boolErr = true; $errString .= '&email=true'; }
		if($_POST['phone'] == ''){ $boolErr = true; $errString .= '&phone=true'; }
			
			if($boolErr){
				header('location:'.DOMAIN_ROOT.'members/sample?step=1&error=true'.$errString);
			}else{
				//INSERT prospect TO DATABASE AND SEND EMAILS
				$newPass = pass_gen(10);
				$insData = array(
					'mbr_active' => 1,
					'mbr_firstname' => $_SESSION['session_data']['prospect']['firstname'],
					'mbr_lastname' => $_SESSION['session_data']['prospect']['lastname'],
					'mbr_company' => $_SESSION['session_data']['prospect']['company'],
					'mbr_address' => $_SESSION['session_data']['prospect']['address'],
					'mbr_city' => $_SESSION['session_data']['prospect']['city'],
					'mbr_state' => $_SESSION['session_data']['prospect']['state'],
					'mbr_email' => $_SESSION['session_data']['prospect']['email'],
					'mbr_phone' => $_SESSION['session_data']['prospect']['phone']
				);
				$mbrid = $app->db->insert('lmg_prospects',$insData);
				//USER EMAIL
					//$msg = '<h1>Thank you for downloading ASA\'s Sample Document</h1>';
					//LMGmail($_SESSION['session_data']['prospect']['email'], 'Thank you for downloading ASA\'s Sample Document', $msg ,$msg, COMPANY_EMAIL);
				//COMPANY EMAIL
					$msg = '<h1>New Member Prospect</h1>';
					foreach($_SESSION['session_data']['prospect'] as $k => $v){
						$msg .= '<b>'.$k.'</b>: '.$v.'<br />';
					}
					LMGmail(COMPANY_EMAIL, 'New Sample Document Download '.COMPANY, $msg ,$msg, COMPANY_EMAIL);
				
					unset($_SESSION['session_data']['prospect']);
				//////////////////////////////////////////////////
			}
	}
}
?>
<form action="<?=DOMAIN_ROOT;?>members/sample?step=<?=$regStep+1;?>" method="post" name="Register">
<table class="table-condensed" style="width:100%">
<?
switch ($regStep){
	
	case 1:
		?>
		<tr>
			<td colspan="2">
				<?
				if($_GET['error'] == 'true'){ echo '<div class="alert alert-error"><span class="icon-warning-sign"></span> ERROR - Please fill in required fields.</div>'; }
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><h3>Company Info</h3></td>
		</tr>
		<tr>
			<td colspan="2"<? echo ($_GET['company'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>Company Name</b></label>
				<input style="width:82%;" type="text" name="company" value="<?=$_SESSION['session_data']['prospect']['company'];?>" />
			</td>
		</tr>
		<tr>
			<td<? echo ($_GET['firstname'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>First Name</b></label>
				<input type="text" name="firstname" value="<?=$_SESSION['session_data']['prospect']['firstname'];?>" />
			</td>
			<td<? echo ($_GET['lastname'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>Last Name</b></label>
				<input type="text" name="lastname" value="<?=$_SESSION['session_data']['prospect']['lastname'];?>" />
			</td>
		</tr>
		<tr>
			<td>
				<label><b>Address</b></label>
				<input type="text" name="address" value="<?=$_SESSION['session_data']['prospect']['address'];?>" />
			</td>
			<td>
				<label><b>City</b></label>
				<input type="text" name="city" value="<?=$_SESSION['session_data']['prospect']['city'];?>" />
			</td>
		</tr>
		<tr>
			<td>
				<label><b>State</b></label>
				<?=$app->getStateDropdown('state',$app->states_arr,$_SESSION['session_data']['prospect']['state']);?>
			</td>
			<td>
				<label><b>Zip</b></label>
				<input type="text" name="zip" value="<?=$_SESSION['session_data']['prospect']['zip'];?>" />
			</td>
		</tr>
		<tr>
			<td<? echo ($_GET['phone'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>Phone</b></label>
				<input type="text" name="phone" value="<?=$_SESSION['session_data']['prospect']['phone'];?>" />
			</td>
			<td>
				<label><b>Fax</b></label>
				<input type="text" name="fax" value="<?=$_SESSION['session_data']['prospect']['fax'];?>" />
			</td>
		</tr>
		<tr>
			<td<? echo ($_GET['email'] == 'true' ? ' class="control-group error"' : ''); ?>>
				<label><b>Email</b></label>
				<input type="email" name="email" value="<?=$_SESSION['session_data']['prospect']['email'];?>" />
			</td>
			<td>
				<label><b>Website</b></label>
				<input type="url" name="website" placeholder="http://" value="<?=$_SESSION['session_data']['prospect']['website'];?>" />
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<input class="btn btn-primary pull-right" type="submit" name="Submit" value="Submit Info" />
			</td>
		</tr>
		<?
		break;

	case 2:
		?>
		<tr>
			<td colspan="3">
				<div class="alert alert-success">
					<h3>Your Download has been processed.</h3>
					<p><a class="btn btn-primary" href="<?=DOMAIN_ROOT;?>/doc/ASA Short-Form Addendum to Subcontract 2011.pdf" target="_blank">Click Here</a> to download Sample Document.</p>
				</div>
			</td>
		</tr>
		<?
		break;
		
}
?>
</table>
</form>