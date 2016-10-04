<h1>Checkout</h1>
<?
$regStep = $_GET['step'];
if($regStep == ''){ $regStep = 1; }

if($_POST['Submit'] != ''){
	
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$_SESSION['session_data']['orderDetails'][$k] = $v;
		}
	}
	
	if($_POST['Submit'] == 'Continue to Payment Info'){
		if($_POST['firstname'] == ''){ $boolErr = true; $errString .= '&firstname=true'; }
		if($_POST['lastname'] == ''){ $boolErr = true; $errString .= '&lastname=true'; }
		if($_POST['email'] == ''){ $boolErr = true; $errString .= '&email=true'; }
		if($_POST['phone'] == ''){ $boolErr = true; $errString .= '&phone=true'; }
		if($boolErr){
			header('location:'.DOMAIN_ROOT.'cart/checkout?step=1&error=true'.$errString);
		}
	}
	
	if($_POST['Submit'] == 'Continue to Verify Registration'){
		$_SESSION['session_data']['orderDetails']['cardexp'] = $_POST['cardMonth']."/".$_POST['cardYear'];
		if($_POST['cardname'] == ''){ $boolErr = true; $errString .= '&cardname=true'; }
		if($_POST['cardnumber'] == ''){ $boolErr = true; $errString .= '&cardnumber=true'; }
		if($_POST['cardccv'] == ''){ $boolErr = true; $errString .= '&cardccv=true'; }
		if($boolErr){
			header('location:'.DOMAIN_ROOT.'cart/checkout?step=2&error=true'.$errString);
		}
	}
	
	if($_POST['Submit'] == 'Submit Order'){
		$paySuccess = false;
		if($_SESSION['session_data']['orderDetails']['amount'] > 0){
			
			//PAYPAL VARIABLES ////////////////////////////////////////////////////////
			$paymentType = urlencode('Authorization');				// or 'Sale'
			$firstName = urlencode($_SESSION['session_data']['orderDetails']['firstname']);
			$lastName = urlencode($_SESSION['session_data']['orderDetails']['lastname']);
			if(substr($_SESSION['session_data']['orderDetails']['cardnumber'],0,1) == "3"){
				$creditCardType = urlencode('Amex');
			}
			
			if(substr($_SESSION['session_data']['orderDetails']['cardnumber'],0,1) == "4"){
				$creditCardType = urlencode('Visa');
			}
			
			if(substr($_SESSION['session_data']['orderDetails']['cardnumber'],0,1) == "5"){
				$creditCardType = urlencode('MasterCard');
			}
			$creditCardNumber = urlencode($_SESSION['session_data']['orderDetails']['cardnumber']);
			$expDateMonth = $_SESSION['session_data']['orderDetails']['cardMonth'];
			// Month must be padded with leading zero
			$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
			$expDateYear = urlencode($_SESSION['session_data']['orderDetails']['cardYear']);
			$cvv2Number = urlencode($_SESSION['session_data']['orderDetails']['cardccv']);
			$address1 = urlencode($_SESSION['session_data']['orderDetails']['address']);
			$address2 = urlencode('');
			$city = urlencode($_SESSION['session_data']['orderDetails']['city']);
			$state = urlencode($_SESSION['session_data']['orderDetails']['state']);
			$zip = urlencode($_SESSION['session_data']['orderDetails']['zip']);
			$country = urlencode('USA');				// US or other valid country code
			$amount = urlencode($_SESSION['session_data']['orderDetails']['amount']);
			$currencyID = urlencode('USD');							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
			////////////////////////////////////////////////////////////////////////////
			
			include(FILE_ROOT.'payments/'.MERCHANT.'.php');
		}else{
			$paySuccess = true;
		}
		if(!$paySuccess){
			header('location:'.DOMAIN_ROOT.'cart/checkout?step=3&error=true');
		}else{
			//INSERT ORDER TO DATABASE AND SEND EMAILS
				$insData = array(
					'oid' => $_SESSION['session_data']['sid'],
					'customer_name' => $_SESSION['session_data']['orderDetails']['firstname']
								.' '.$_SESSION['session_data']['orderDetails']['lastname'],
					'billing_address' => $_SESSION['session_data']['orderDetails']['address']
								.' '.$_SESSION['session_data']['orderDetails']['city']
								.' '.$_SESSION['session_data']['orderDetails']['state']
								.' '.$_SESSION['session_data']['orderDetails']['zip'],
					'shipping_address' => $_SESSION['session_data']['orderDetails']['address']
								.' '.$_SESSION['session_data']['orderDetails']['city']
								.' '.$_SESSION['session_data']['orderDetails']['state']
								.' '.$_SESSION['session_data']['orderDetails']['zip'],
					'customer_company' => $_SESSION['session_data']['orderDetails']['company'],
					'email' => $_SESSION['session_data']['orderDetails']['email'],
					'phone' => $_SESSION['session_data']['orderDetails']['phone'],
					'products' => serialize($_SESSION['session_data']['cartProducts']),
					'subtotal' => $app->getSubtotal(),
					'tax' => $app->getTax(),
					'total' => $app->getSubtotal()+$app->getTax(),
					'order_date' => date('Y-m-d'),
					'mid' => $_SESSION['session_data']['memberDetails']['mid']
				);
				$mbrid = $app->db->insert('lmg_cart_orders',$insData);
				//USER EMAIL
					$msg = '<h1>Thank you for your order</h1>';
					$msg .= '<p>Your order details are as follows.</p>';
					$tTax = 0;
					$prods = $app->getCart();
					if(count($prods) > 0){
						$msg .= '<table class="table table-striped table-condensed">
							<tr>
								<td style="width:50px;"><b>QTY</b></td>
								<td><b>Product</b></td>
								<td style="width:100px;"><b>Unit Price</b></td>
								<td style="width:75px;"><b>Total</b></td>
								<td style="width:75px;"><b>Tax</b></td>
								<td style="width:25px;"></td>
							</tr>';
						foreach($prods as $k => $v){
							$msg .= '<tr>
								<td>'.$v['qty'].'</td>
								<td>
									<b>'.$v['title'].'</b>';
									$options = $v['options'];
									if(count($options)>0){
										$msg .= '<div style="margin:0 0 0 20px;">';
										foreach($options as $a => $b){
											$msg .= $a.': '.$b.'<br />';
										}
										$msg .= '</div>';
									}
								$msg .= '</td>
								<td>$'.number_format($v['price'],2).'</td>
								<td>$'.number_format($v['price']*$v['qty'],2).'</td>
								<td>';
								if($v['taxable'] == 'yes'){
										$tx = TAX_RATE*($v['price']*$v['qty']);
										$msg .= '$'.number_format($tx,2);
										$tTax += $tx;
									}else{
										$msg .= '$0.00';
									}
								$msg .= '</td>
								<td><a href="'.DOMAIN_ROOT.'cart/cart?action=removeItem&amp;id='.$k.'"><b class="icon-remove"></b></td>
							</tr>';
						}
						$msg .= '<tr>
							<td colspan="6" style="text-align:right;">
								<b>Subtotal: </b>'.number_format($app->getSubtotal(),2).'<br />
								<b>Tax: </b>$'.number_format($tTax,2).'<br />
								<b>Total: </b>$'.number_format($app->getSubtotal()+$tTax,2).'<br />
							</td>
						</tr>
						</table>';
					}else{
						$msg .= 'There are no items in your cart.';
					}
					
					$msg .= '<p>Thanks again for ordering</p>';
					LMGmail($_SESSION['session_data']['orderDetails']['email'], 'Thank you for your order', $msg ,$msg, COMPANY_EMAIL);
				//COMPANY EMAIL
					$msg = '<h1>New Order from website</h1>';
					foreach($_SESSION['session_data']['orderDetails'] as $k => $v){
						$msg .= '<b>'.$k.'</b>: '.$v.'<br>';
					}
					$tTax = 0;
					$prods = $app->getCart();
					if(count($prods) > 0){
						$msg .= '<table class="table table-striped table-condensed">
							<tr>
								<td style="width:50px;"><b>QTY</b></td>
								<td><b>Product</b></td>
								<td style="width:100px;"><b>Unit Price</b></td>
								<td style="width:75px;"><b>Total</b></td>
								<td style="width:75px;"><b>Tax</b></td>
								<td style="width:25px;"></td>
							</tr>';
						foreach($prods as $k => $v){
							$msg .= '<tr>
								<td>'.$v['qty'].'</td>
								<td>
									<b>'.$v['title'].'</b>';
									$options = $v['options'];
									if(count($options)>0){
										$msg .= '<div style="margin:0 0 0 20px;">';
										foreach($options as $a => $b){
											$msg .= $a.': '.$b.'<br />';
										}
										$msg .= '</div>';
									}
								$msg .= '</td>
								<td>$'.number_format($v['price'],2).'</td>
								<td>$'.number_format($v['price']*$v['qty'],2).'</td>
								<td>';
								if($v['taxable'] == 'yes'){
										$tx = TAX_RATE*($v['price']*$v['qty']);
										$msg .= '$'.number_format($tx,2);
										$tTax += $tx;
									}else{
										$msg .= '$0.00';
									}
								$msg .= '</td>
								<td><a href="'.DOMAIN_ROOT.'cart/cart?action=removeItem&amp;id='.$k.'"><b class="icon-remove"></b></td>
							</tr>';
						}
						$msg .= '<tr>
							<td colspan="6" style="text-align:right;">
								<b>Subtotal: </b>'.number_format($app->getSubtotal(),2).'<br />
								<b>Tax: </b>$'.number_format($tTax,2).'<br />
								<b>Total: </b>$'.number_format($app->getSubtotal()+$tTax,2).'<br />
							</td>
						</tr>
						</table>';
					}else{
						$msg .= 'There are no items in your cart.';
					}
					$eTo = array(
						COMPANY_EMAIL,
						'vee@lmgnow.com'
					);
					LMGmail($eTo, 'New Order From '.COMPANY, $msg ,$msg, COMPANY_EMAIL);
					
					unset($_SESSION['session_data']['orderDetails']);
					unset($_SESSION['session_data']['cartProducts']);
			//////////////////////////////////////////////////
		}
	}
}

?>
<form action="<?=DOMAIN_ROOT;?>cart/checkout?step=<?=$regStep+1;?>" method="post" name="Register">
<table class="table-condensed" style="width:100%">
	<tr>
		<td colspan="3" class="alert alert-info">
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
			<h3>Customer Information</h3>
			</td></tr>
			<tr>
				<td<? echo ($_GET['firstname'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>First Name: </b></label>
					<input type="text" name="firstname" value="<? echo ($_SESSION['session_data']['orderDetails']['firstname'] != ''
							 ? $_SESSION['session_data']['orderDetails']['firstname']
							 : $_SESSION['session_data']['memberDetails']['firstname']
							); ?>" />
				</td>
				<td colspan="2"<? echo ($_GET['lastname'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Last Name: </b></label>
					<input type="text" name="lastname" value="<? echo ($_SESSION['session_data']['orderDetails']['lastname'] != ''
							 ? $_SESSION['session_data']['orderDetails']['lastname']
							 : $_SESSION['session_data']['memberDetails']['lastname']
							); ?>" />
				</td>
			</tr>
			<tr>
				<td<? echo ($_GET['address'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Address: </b></label>
					<input type="text" name="address" value="<? echo ($_SESSION['session_data']['orderDetails']['address'] != ''
							 ? $_SESSION['session_data']['orderDetails']['address']
							 : $_SESSION['session_data']['memberDetails']['address']
							); ?>" />
				</td>
				<td colspan="2"<? echo ($_GET['city'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>City: </b></label>
					<input type="text" name="city" value="<? echo ($_SESSION['session_data']['orderDetails']['city'] != ''
							 ? $_SESSION['session_data']['orderDetails']['city']
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
					<input type="text" name="zip" value="<? echo ($_SESSION['session_data']['orderDetails']['zip'] != ''
							 ? $_SESSION['session_data']['orderDetails']['zip']
							 : $_SESSION['session_data']['memberDetails']['zip']
							); ?>" />
				</td>
			</tr>
			<tr>
				<td<? echo ($_GET['email'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Email Address: </b></label>
					<input type="email" name="email" value="<? echo ($_SESSION['session_data']['orderDetails']['email'] != ''
							 ? $_SESSION['session_data']['orderDetails']['email']
							 : $_SESSION['session_data']['memberDetails']['email']
							); ?>" />
				</td>
				<td colspan="2"<? echo ($_GET['phone'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Phone Number: </b></label>
					<input type="text" name="phone" value="<? echo ($_SESSION['session_data']['orderDetails']['phone'] != ''
							 ? formatPhone($_SESSION['session_data']['orderDetails']['phone'])
							 : formatPhone($_SESSION['session_data']['memberDetails']['phone'])
							); ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label><b>Company: </b></label>
					<input type="text" name="company" value="<? echo ($_SESSION['session_data']['orderDetails']['company'] != ''
							 ? $_SESSION['session_data']['orderDetails']['company']
							 : $_SESSION['session_data']['memberDetails']['company']
							); ?>" />
				</td>
				<td colspan="2">

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
					<input type="text" name="cardname" value="<? echo ($_SESSION['session_data']['orderDetails']['cardname'] != ''
							 ? $_SESSION['session_data']['orderDetails']['cardname']
							 : ''
							); ?>" />
				</td>
				<td colspan="2"<? echo ($_GET['cardnumber'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Card Number: </b></label>
					<input type="text" name="cardnumber" value="<? echo ($_SESSION['session_data']['orderDetails']['cardnumber'] != ''
							 ? $_SESSION['session_data']['orderDetails']['cardnumber']
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
						<option value="<?=$m;?>"<? echo ($_SESSION['session_data']['orderDetails']['cardMonth'] == $m ? ' selected' : '');?>><?=date("M",mktime(0,0,0,$m,1,date("Y")));?></option>
						<?
					}
					?>
					</select>
					<select name="cardYear" style="width:75px;">
					<?
					for($y=date("Y");$y<date("Y")+6;$y++){
						?>
						<option value="<?=$y;?>"<? echo ($_SESSION['session_data']['orderDetails']['cardYear'] == $y ? ' selected' : '');?>><?=date("Y",mktime(0,0,0,1,1,$y));?></option>
						<?
					}
					?>
					</select>
				</td>
				<td colspan="2"<? echo ($_GET['cardccv'] == 'true' ? ' class="control-group error"' : ''); ?>>
					<label><b>Security Code: </b></label>
					<input type="text" name="cardccv" value="<? echo ($_SESSION['session_data']['orderDetails']['cardccv'] != ''
							 ? $_SESSION['session_data']['orderDetails']['cardccv']
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
					<h3>Customer Info</h3>
					<div style="margin:0 0 0 20px;">
						<?=$_SESSION['session_data']['orderDetails']['firstname']." ".$_SESSION['session_data']['orderDetails']['lastname'];?><br>
						<?=$_SESSION['session_data']['orderDetails']['company'];?><br>
						<?=$_SESSION['session_data']['orderDetails']['email'];?><br>
						<?=$_SESSION['session_data']['orderDetails']['phone'];?><br>
					</div><br><br>
				</td>
				<td colspan="2"><br>
					<h3>Payment Info</h3>
					<div style="margin:0 0 0 20px;">
						<?=$_SESSION['session_data']['orderDetails']['cardname'];?><br>
						<?
						$cLen = strlen($_POST['cardnumber']);
						?>
						<?='xxxxxxxxxxxx'.substr($_SESSION['session_data']['orderDetails']['cardnumber'],$cLen-4);?><br>
						<?=$_SESSION['session_data']['orderDetails']['cardexp'];?><br>
						<?=$_SESSION['session_data']['orderDetails']['cardccv'];?><br>
					</div><br><br>
					
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<b>Order</b>
					<?
					$tTax = 0;
					$prods = $app->getCart();
					if(count($prods) > 0){
						?>
						<table class="table table-striped table-condensed">
							<tr>
								<td style="width:50px;"><b>QTY</b></td>
								<td><b>Product</b></td>
								<td style="width:100px;"><b>Unit Price</b></td>
								<td style="width:75px;"><b>Total</b></td>
								<td style="width:75px;"><b>Tax</b></td>
								<td style="width:25px;"></td>
							</tr>
						<?
						foreach($prods as $k => $v){
							?>
							<tr>
								<td><?=$v['qty'];?></td>
								<td>
									<b><?=$v['title'];?></b>
									<?
									$options = $v['options'];
									if(count($options)>0){
										echo '<div style="margin:0 0 0 20px;">';
										foreach($options as $a => $b){
											echo $a.': '.$b.'<br />';
										}
										echo '</div>';
									}
									?>
								</td>
								<td>$<?=number_format($v['price'],2);?></td>
								<td>$<?=number_format($v['price']*$v['qty'],2);?></td>
								<td>
									<?
									if($v['taxable'] == 'yes'){
										$tx = .082*($v['price']*$v['qty']);
										echo '$'.number_format($tx,2);
										$tTax += $tx;
									}else{
										echo '$0.00';
									}
									?>
								</td>
								<td><a href="<?=DOMAIN_ROOT;?>cart/cart?action=removeItem&amp;id=<?=$k;?>"><b class="icon-remove"></b></td>
							</tr>
							<?
						}
						?>
						<tr>
							<td colspan="6" style="text-align:right;">
								<b>Subtotal: </b><?=number_format($app->getSubtotal(),2);?><br />
								<b>Tax: </b><?='$'.number_format($tTax,2);?><br />
								<b>Total: </b><?='$'.number_format($app->getSubtotal()+$tTax,2);?><br />
								<? $_SESSION['session_data']['orderDetails']['amount'] = $app->getSubtotal()+$tTax;?>
							</td>
						</tr>
						</table>
						<?
					}else{
						echo 'There are no items in your cart.';
					}
					?>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<p>&nbsp;</p>
					<input class="btn btn-primary pull-right" type="submit" name="Submit" value="Submit Order" />
				</td>
			</tr>
		<?
		break;
		
	case 4:
		?>
			<tr>
				<td colspan="3">
				<div class="alert alert-success">
					<h3>Your order has been processed.</h3>
				</div>
				
				</td>
			</tr>
		<?
		break;
				
}
?>
</table>
</form>