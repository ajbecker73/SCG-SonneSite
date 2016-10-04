<?
$section=$_GET['section'];
if($section == ''){
	$section = 'categories';
}
switch ($section){
	
	case "categories":
		echo '<h1>Categories</h1>';
		$cats = $app->db
			->orderBy('category_name','ASC')
			->get('lmg_cart_categories');
			$cCount = count($cats);
			for($bx=0;$bx<$cCount;$bx++){
				?>
				<a class="btn" href="<?=DOMAIN_ROOT;?>cart/store?section=category&amp;category=<?=$cats[$bx]['id'];?>"><?=$cats[$bx]['category_name'];?></a>
				<?
			}
		break;
		
	case "category":
		$cat = $app->db->where('id',$_GET['category'])->get('lmg_cart_categories');
		echo '<h1>'.$cat[0]['category_name'].'</h1>';
		echo $cat[0]['category_description'];
		$prods = $app->db
			->orderBy('product_name','ASC')
			->where('cid',$_GET['category'])
			->get('lmg_cart_products');
			$pCount = count($prods);
			for($bx=0;$bx<$pCount;$bx++){
				?>
				<div class="prodBox clearfix">
					<?
					if($prods[$bx]['product_image'] != ''){
						?>
						<img src="<?=DOMAIN_ROOT;?>img/uploads/<?=$prods[$bx]['product_image'];?>" class="pull-left" style="margin:0 20px 0 0; max-height:40px; max-width:40px;" />
						<?
					}
					?>
					<a class="pull-right btn btn-mini" href="<?=DOMAIN_ROOT;?>cart/store?section=product&amp;product=<?=$prods[$bx]['id'];?>">View Product</a>
					<a href="<?=DOMAIN_ROOT;?>cart/store?section=product&amp;product=<?=$prods[$bx]['id'];?>"><?=$prods[$bx]['product_name'];?></a>
				</div>
				<?
			}
		break;
		
	case "product":
		$prod = $app->db->where('id',$_GET['product'])->get('lmg_cart_products');
		/*
		?>
		<form action="<?=DOMAIN_ROOT;?>cart/cart?action=addItem&amp;id=<?=$prod[0]['id'];?>&amp;price=<?=$prod[0]['product_price'];?>&amp;title=<?=$prod[0]['product_name'];?>&amp;taxable=<?=$prod[0]['taxable'];?>" method="post" name="AddProduct">
		<?
		*/
		echo '<div class="pull-right" style="font-size:24px;">$'.number_format($prod[0]['product_price'],2).'</div>';
		if($prod[0]['product_image'] != ''){
			?>
			<img src="<?=DOMAIN_ROOT;?>img/uploads/<?=$prod[0]['product_image'];?>" class="pull-left" style="margin:0 20px 500px 0; max-height:200px; max-width:200px;" />
			<?
		}
		echo '<h1>'.$prod[0]['product_name'].'</h1>';
		echo $prod[0]['product_description'].'<br /><br />';
			/*
		
		$options = $app->db
			->where('pid',$prod[0]['id'])
			->get('lmg_cart_product_options');
			$optNum = count($options);
			$optAfter = "";
			$sel = 0;
			for($oc=0;$oc<$optNum;$oc++){
				if($optAfter != $options[$oc]['option_name']){
					if($sel > 0){
						?>
						</select><br />
						<?
						$sel = 0;
					}
					?>
					<label><b><?=$options[$oc]['option_name'];?>: </b></label>
					<?
					if($options[$oc]['option_value'] == ""){
						?>
						<input required<? echo($_POST[$options[$oc]['option_name']] == "" ? " class='ui-state-error'" : "");?> type="text" name="<?=$options[$oc]['option_name'];?>" value="<?=$_POST[str_replace(" ","_",$options[$oc]['option_name'])];?>" />
						<?
					}else{
						?>
						<select required name="<?=$options[$oc]['option_name'];?>">
							<option value="">Select One</option>
						<?
						$sel ++;
					}
				}
				if($options[$oc]['option_value'] != ""){
					?>
					<option value="<?=$options[$oc]['option_value'];?><? echo($_POST[str_replace(" ","_",$options[$oc]['option_name'])] == $options[$oc]['option_name'] ? ' selected' : '');?>"><?=$options[$oc]['option_value'];?></option>
					<?
				}
				$optAfter = $options[$oc]['option_name'];

			}
			if($sel > 0){
				echo "</select>";
			}
			
			echo '<label><b>Qty: </b></label><input style="width:75px;" name="qty" type="number" min="1" step"1" value="1" />';
			echo '<p><br /><input required type="submit" value="Add To Cart" name="Submit" class="btn" /></p>';
			echo '</form>';
			*/
		break;
		
}
?>