<h1>Shopping Cart</h1>
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
					$tx = TAX_RATE*($v['price']*$v['qty']);
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
		</td>
	</tr>
	</table>
	<?
}else{
	echo 'There are no items in your cart.';
}
?>
<div class="clearfix" style="margin-bottom:20px;"></div>
<a href="<?=DOMAIN_ROOT;?>cart/store" class="btn btn-primary pull-left">Continue Shopping</a>
<a href="<?=DOMAIN_ROOT;?>cart/checkout" class="btn btn-primary pull-right">Checkout</a>