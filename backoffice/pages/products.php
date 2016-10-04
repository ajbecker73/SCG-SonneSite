<?
$boolErr = false;
$bxID = '';

if($_GET['id'] != ''){
	$bxID = $_GET['id'];
}

if($_POST['Submit'] == "Save Product"){
	if($_POST['product_name'] == ''){
		$boolErr = true;
	}
	if(!$boolErr){
		$dir = FILE_ROOT.'img/uploads/';
		 
		$_FILES['product_image']['type'] = strtolower($_FILES['product_image']['type']);
		 
		if ($_FILES['product_image']['type'] == 'image/png' 
		|| $_FILES['product_image']['type'] == 'image/jpg' 
		|| $_FILES['product_image']['type'] == 'image/gif' 
		|| $_FILES['product_image']['type'] == 'image/jpeg'
		|| $_FILES['product_image']['type'] == 'image/pjpeg')
		{	
		    $file = md5(date('YmdHis')).'.jpg';
		    move_uploaded_file($_FILES["product_image"]["tmp_name"],$dir.$file);
		}
		
		$insertData = array(
			'cid' => $_POST['cid'],
			'product_name' => $_POST['product_name'],
			'product_price' => $_POST['product_price'],
			'stock' => $_POST['stock'],
			'taxable' => $_POST['taxable'],
			'product_image' => $file,
			'product_description' => $_POST['product_description']
		);
		
		$pOptNames = $_POST['pOptNames'];
		$pOptValues = $_POST['pOptValues'];
		$pOptPrices = $_POST['pOptPrices'];
		$optCount = count($pOptNames);
		
		if($bxID = $app->db->insert('lmg_cart_products',$insertData)){
			for($x=0; $x<$optCount; $x++){
				$insertData = array(
				    'pid' => $bxID,
				    'option_name' => stripslashes($pOptNames[$x]),
				    'option_value' => stripslashes($pOptValues[$x]),
				    'option_price' => stripslashes($pOptPrices[$x])
				);
				$app->db->insert('lmg_cart_product_options', $insertData);
			}
		}
	}
}

if($_POST['Submit'] == "Save Changes"){
		$file = $_POST['ExFile'];
		$dir = FILE_ROOT.'img/uploads/';
		 
		$_FILES['product_image']['type'] = strtolower($_FILES['product_image']['type']);
		 
		if ($_FILES['product_image']['type'] == 'image/png' 
		|| $_FILES['product_image']['type'] == 'image/jpg' 
		|| $_FILES['product_image']['type'] == 'image/gif' 
		|| $_FILES['product_image']['type'] == 'image/jpeg'
		|| $_FILES['product_image']['type'] == 'image/pjpeg')
		{	
		    $file = md5(date('YmdHis')).'.jpg';
		    move_uploaded_file($_FILES["product_image"]["tmp_name"],$dir.$file);
		}
		
	foreach($_POST as $k => $v){
		if($k != 'Submit' && $k != 'product_image' && $k != 'ExFile' && $k != 'pOptNames' && $k != 'pOptValues' && $k != 'pOptPrices'){
			$updateData = array(
				$k => $v
			);
			$app->db->where('id',$bxID);
			$app->db->update('lmg_cart_products',$updateData);
		}
		
	}
	$updateData = array(
		'product_image' => $file
	);
	$app->db->where('id',$bxID);
	$app->db->update('lmg_cart_products',$updateData);
	
	$pOptNames = $_POST['pOptNames'];
	$pOptValues = $_POST['pOptValues'];
	$pOptPrices = $_POST['pOptPrices'];
	$optCount = count($pOptNames);
	$app->db->where('pid',$bxID)->delete('lmg_cart_product_options');
		for($x=0; $x<$optCount; $x++){
			$insertData = array(
			    'pid' => $bxID,
			    'option_name' => stripslashes($pOptNames[$x]),
			    'option_value' => stripslashes($pOptValues[$x]),
			    'option_price' => stripslashes($pOptPrices[$x])
			);
			$app->db->insert('lmg_cart_product_options', $insertData);
		}
}

if($bxID != ''){
	$editBox = $app->db
		->where('id',$bxID)
		->get('lmg_cart_products');
}
?>
<h1>Products</h1>
<p>&nbsp;</p>
<?
$cats = $app->db
	->orderBy('product_name','ASC')
	->get('lmg_cart_products');
	$cCount = count($cats);
?>
<div class="container">
	<div class="row">
		<div class="col-lg-4 col-md-4">
			<h3>Current Products</h3>
			<div class="data-grid">
			<?
			for($bx=0;$bx<$cCount;$bx++){
				?>
				<div>
					<a title="Edit Product" class="icon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=products&id=<?=$cats[$bx]['id'];?>"></a>
					<a title="Delete Product" class="icon-remove deleteProduct" href="javascript:void()" id="<?=$cats[$bx]['id'];?>"></a>
					<?=$cats[$bx]['product_name'];?>
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
			<h3>Edit Product (<?=$editBox[0]['product_name'];?>)</h3>
				<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'];?>?url=products&id=<?=$bxID;?>" method="post" name="EditPage">
				<label>Product Name</label>
				<input autofocus class="small" style="width:90%;" type="text" name="product_name" value="<?=$editBox[0]['product_name'];?>" />
				<label>Category</label>
				<select style="width:auto;" type="text" name="cid">
					<option value="0">Select Category</option>
					<?
					$parents = $app->db->orderBy('category_name','ASC')->get('lmg_cart_categories');
					$pCt = count($parents);
					for($p=0;$p<$pCt;$p++){
					?>
						<option<? echo ($editBox[0]['cid'] == $parents[$p]['id'] ? ' selected' : ''); ?> value="<?=$parents[$p]['id'];?>"><?=$parents[$p]['category_name'];?></option>
					<?
					}
					?>
				</select>
				<label>Price</label>
				<input class="small" type="number" min="0" step=".01" name="product_price" value="<?=$editBox[0]['product_price'];?>" />
				<label>Stock Level</label>
				<input class="small" type="number" min="0" step="1" name="stock" value="<?=$editBox[0]['stock'];?>" />
				<label>Taxable</label>
				<input type="radio" name="taxable" value="yes" checked />Yes&nbsp;&nbsp;&nbsp;
				<input type="radio" name="taxable" value="no"<? echo ($editBox[0]['taxable'] == 'no' ? ' checked' : ''); ?> />No
				<p>&nbsp;</p>
				<label>Photo</label>
				<?
				if($editBox[0]['product_image'] != ''){
					?>
					<input type="hidden" name="ExFile" value="<?=$editBox[0]['product_image'];?>" />
					Existing Image: <img src="<?=DOMAIN_ROOT;?>img/uploads/<?=$editBox[0]['product_image'];?>" style="max-height:100px; max-width:100px;" /> <br />
					<?
				}
				?>
				<input type="file" name="product_image" />
				<p>&nbsp;</p>
				<label>Product Description</label>
				<textarea name="product_description" style="width:90%; height:250px;"><?=stripslashes($editBox[0]['product_description']);?></textarea>
				<script>
					CKEDITOR.replace( 'product_description',
						{
							filebrowserUploadUrl : '../upload-file.php?type=Files'
						});
				</script>
				<p>&nbsp;</p>
				<h3>Product Options</h3>
				<p>
					To create a text input: enter the option name, but leave the value blank.<br /><br />
					To create a dropdown box: enter the option name, and option value. make sure all values have the same name for a dropdown ...example: Color:Red, Color:Blue.
				</p>
				<p><input type="button" id="attbtn" class="ui-button" name="add-option" value="Add Option" /></p>
				<div id="atts">
					<?
					$app->db->where('pid',$bxID);
					$app->db->orderBy('option_name','ASC');
					$app->db->orderBy('option_value','ASC');
					$opts = $app->db->get('lmg_cart_product_options');
					$cOp = count($opts);
					for($x=0; $x<$cOp; $x++){
						?>
						<p>
							<b>Name:</b> <input style="width:130px; margin:0 20px 0 0;" type="text" name="pOptNames[]" value="<?=$opts[$x]['option_name'];?>" />
							<b>Value:</b> <input style="width:130px; margin:0 20px 0 0;" type="text" name="pOptValues[]" value="<?=$opts[$x]['option_value'];?>" />
							<b>Price +-:</b> <input style="width:50px; margin:0 20px 0 0;" type="number" min="-9999" step=".01" name="pOptPrices[]" value="<?=$opts[$x]['option_price'];?>" />
						</p>
					<?
					}
					?>
				</div>
				
				<input class="btn pull-right" type="submit" name="Submit" value="Save Changes" />
				</form>
			<?
		}else{
			?>
			<h3>Add Product</h3>
				<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'];?>?url=products" method="post" name="EditPage">
				<label>Product Name</label>
				<input autofocus class="small" style="width:90%;" type="text" name="product_name" value="" />
				<label>Category</label>
				<select style="width:auto;" type="text" name="cid">
					<option value="0">Select Category</option>
					<?
					$parents = $app->db->orderBy('category_name','ASC')->get('lmg_cart_categories');
					$pCt = count($parents);
					for($p=0;$p<$pCt;$p++){
					?>
						<option value="<?=$parents[$p]['id'];?>"><?=$parents[$p]['category_name'];?></option>
					<?
					}
					?>
				</select>
				<label>Price</label>
				<input class="small" type="number" min="0" step=".01" name="product_price" value="0.00" />
				<label>Stock Level</label>
				<input class="small" type="number" min="0" step="1" name="stock" value="0" />
				<label>Taxable</label>
				<input type="radio" name="taxable" value="yes" checked />Yes&nbsp;&nbsp;&nbsp;
				<input type="radio" name="taxable" value="no" />No
				<p>&nbsp;</p>
				<label>Photo</label>
				<input type="file" name="product_image" />
				<p>&nbsp;</p>
				<label>Product Description</label>
				<textarea name="product_description" style="width:90%; height:250px;"></textarea>
				<script>
					CKEDITOR.replace( 'product_description',
						{
							filebrowserUploadUrl : '../upload-file.php?type=Files'
						});
				</script>
				<p>&nbsp;</p>
				<h3>Product Options</h3>
				<p>
					To create a text input: enter the option name, but leave the value blank.<br /><br />
					To create a dropdown box: enter the option name, and option value. make sure all values have the same name for a dropdown ...example: Color:Red, Color:Blue.
				</p>
				<p><input type="button" id="attbtn" class="ui-button" name="add-option" value="Add Option" /></p>
				<div id="atts">
					<?
					for($x=0; $x<$optCount; $x++){
						?>
						<p>
							<b>Name:</b> <input style="width:130px; margin:0 20px 0 0;" type="text" name="pOptNames[]" value="<?=$pOptNames[$x];?>" />
							<b>Value:</b> <input style="width:130px; margin:0 20px 0 0;" type="text" name="pOptValues[]" value="<?=$pOptValues[$x];?>" />
							<b>Price +-:</b> <input style="width:50px; margin:0 20px 0 0;" type="number" min="-9999" step=".01" name="pOptPrices[]" value="<?=$pOptPrices[$x];?>" />
						</p>
					<?
					}
					?>
				</div>
				
				<input class="btn pull-right" type="submit" name="Submit" value="Save Product" />
				</form>
			<?
		}
		?>
		</div>
	</div>
</div>
