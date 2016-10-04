<?
$boolErr = false;
$bxID = '';

if($_GET['id'] != ''){
	$bxID = $_GET['id'];
}

if($_POST['Submit'] == "Add Category"){
	if($_POST['category_name'] == ''){
		$boolErr = true;
	}
	if(!$boolErr){
		$insertData = array(
			'category_name' => $_POST['category_name']
		);
		$bxID = $app->db->insert('lmg_cart_categories',$insertData);
	}
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$updateData = array(
				$k => $v
			);
			$app->db->where('id',$bxID);
			$app->db->update('lmg_cart_categories',$updateData);
		}
	}
}

if($bxID != ''){
	$editBox = $app->db
		->where('id',$bxID)
		->get('lmg_cart_categories');
}
?>
<h1>Product Categories</h1>
<p>&nbsp;</p>
<?
$cats = $app->db
	->orderBy('category_name','ASC')
	->get('lmg_cart_categories');
	$cCount = count($cats);
?>
<div class="container">
	<div class="row">
		<div class="col-lg-4 col-md-4">
			<h3>Add a Category</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=categories" method="post" name="AddBox">
				<label>Category Name</label>
				<input autofocus type="text" name="category_name" class="small" />
				<p><input class="btn" type="submit" name="Submit" value="Add Category" /></p>
				</form>
				<hr />
			<h3>Current Categories</h3>
			<div class="data-grid">
			<?
			for($bx=0;$bx<$cCount;$bx++){
				?>
				<div>
					<a title="Edit Category" class="icon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=categories&id=<?=$cats[$bx]['id'];?>"></a>
					<a title="Delete Category" class="icon-remove deleteCategory" href="javascript:void()" id="<?=$cats[$bx]['id'];?>"></a>
					<?=$cats[$bx]['category_name'];?>
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
			<h3>Edit Category (<?=$editBox[0]['category_name'];?>)</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=categories&id=<?=$bxID;?>" method="post" name="EditPage">
				<label>Category Name</label>
				<input autofocus class="small" style="width:90%;" type="text" name="category_name" value="<?=$editBox[0]['category_name'];?>" />
				<label>Category Description</label>
				<textarea name="category_description" style="width:90%; height:250px;"><?=stripslashes($editBox[0]['category_description']);?></textarea>
				<script>
					CKEDITOR.replace( 'category_description',
						{
							filebrowserUploadUrl : '../upload-file.php?type=Files'
						});
				</script>
				<p>&nbsp;</p>
				<input class="btn pull-right" type="submit" name="Submit" value="Save Changes" />
				</form>
			<?
		}
		?>
		</div>
	</div>
</div>
