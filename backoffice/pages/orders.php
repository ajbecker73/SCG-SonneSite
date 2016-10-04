<?
$boolErr = false;
$bxID = '';

if($_GET['id'] != ''){
	$bxID = $_GET['id'];
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$updateData = array(
				$k => $v
			);
			$app->db->where('id',$bxID);
			$app->db->update('lmg_cart_orders',$updateData);
		}
		
	}
}

if($bxID != ''){
	$editBox = $app->db
		->where('id',$bxID)
		->get('lmg_cart_orders');
}
?>
<h1>Orders</h1>
<p>&nbsp;</p>
<?
$cats = $app->db
	->orderBy('id','DESC')
	->get('lmg_cart_orders');
	$cCount = count($cats);
?>
<div class="container">
	<div class="row">
		<div class="col-lg-4 col-md-4">
			<h3>Current Orders</h3>
			<div class="data-grid">
			<?
			for($bx=0;$bx<$cCount;$bx++){
				?>
				<div>
					<a title="Edit Order" class="icon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=orders&id=<?=$cats[$bx]['id'];?>"></a>
					<a title="Delete Order" class="icon-remove deleteOrder" href="javascript:void()" id="<?=$cats[$bx]['id'];?>"></a>
					<b>Order# <?=$cats[$bx]['id'];?></b><br />
					<?=$cats[$bx]['customer_name'];?><br />
					<?=$cats[$bx]['customer_company'];?>
				</div>
				<?
			}
			?>
			</div>
		</div>
		<div class="col-lg-8 col-md-8">
		<?
		if($bxID != ''){
			?>
			<h3>Edit Order# <?=$editBox[0]['id'];?></h3>
				<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'];?>?url=orders&id=<?=$bxID;?>" method="post" name="EditPage">
				<b>ORDER DATE: <?=$editBox[0]['order_date'];?></b><br /><br />
				<label>Customer Name</label>
				<input autofocus class="small" style="width:90%;" type="text" name="customer_name" value="<?=$editBox[0]['customer_name'];?>" />
				<label>Customer Company</label>
				<input class="small" style="width:90%;" type="text" name="customer_company" value="<?=$editBox[0]['customer_company'];?>" />
				<label>Customer Email</label>
				<input class="small" style="width:90%;" type="text" name="email" value="<?=$editBox[0]['email'];?>" />
				<label>Customer Phone</label>
				<input class="small" style="width:90%;" type="text" name="phone" value="<?=$editBox[0]['phone'];?>" />
				<label>Billing Address</label>
				<input class="small" style="width:90%;" type="text" name="billing_address" value="<?=$editBox[0]['billing_address'];?>" />
				<label>Shipping Address</label>
				<input class="small" style="width:90%;" type="text" name="shipping_address" value="<?=$editBox[0]['shipping_address'];?>" />
				<h4>Order Details</h4>
				<?
				$prods = unserialize($editBox[0]['products']);
				$getSubtotal = 0;
				$tTax = 0;
				if(count($prods) > 0){
					?>
					<table class="table table-striped table-condensed">
						<tr>
							<td style="width:50px;"><b>QTY</b></td>
							<td><b>Product</b></td>
							<td style="width:100px;"><b>Unit Price</b></td>
							<td style="width:75px;"><b>Total</b></td>
							<td style="width:75px;"><b>Tax</b></td>
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
									$tx = TAX_RATE*($v['price']*$v['qty']);
									echo '$'.number_format($tx,2);
									$tTax += $tx;
								}else{
									echo '$0.00';
								}
								$getSubtotal += $v['price'];
								?>
							</td>
						</tr>
						<?
					}
					?>
					<tr>
						<td colspan="6" style="text-align:right;">
							<b>Subtotal: </b><?=number_format($getSubtotal,2);?><br />
							<b>Tax: </b><?='$'.number_format($tTax,2);?><br />
							<b>Total: </b><?='$'.number_format($getSubtotal+$tTax,2);?><br />
						</td>
					</tr>
					</table>
					<?
				}else{
					echo 'There are no items in your cart.';
				}
				?>
				
				<input class="btn pull-right" type="submit" name="Submit" value="Save Changes" />
				</form>
			<?
		}
		?>
		</div>
	</div>
</div>
