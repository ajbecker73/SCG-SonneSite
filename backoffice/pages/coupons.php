<?
$boolErr = false;
$bxID = '';

if($_GET['id'] != ''){
	$bxID = $_GET['id'];
}

if($_POST['Submit'] == "Add Coupon"){
	if($_POST['couponHeading'] == ''){
		$boolErr = true;
	}
	if(!$boolErr){
		$insertData = array(
			'couponHeading' => $_POST['couponHeading']
		);
		$bxID = $app->db->insert('lmg_coupons',$insertData);
	}
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$updateData = array(
				$k => $v
			);
			$app->db->where('bid',$bxID);
			$app->db->update('lmg_coupons',$updateData);
		}
	}
}

if($bxID != ''){
	$editBox = $app->db
		->where('bid',$bxID)
		->get('lmg_coupons');
}
?>
<h1>Coupons</h1>
<p>&nbsp;</p>
<?
$boxes = $app->db
	->orderBy('couponHeading','ASC')
	->get('lmg_coupons');
	$bCount = count($boxes);
?>
	<div class="row">
		<div class="col-lg-4 col-sm-4">
			<h3>Add a Coupon</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=coupons" method="post" name="AddCoupon">
				<label>Coupon Heading</label>
				<input autofocus type="text" name="couponHeading" class="small" />
				<p><input class="btn" type="submit" name="Submit" value="Add Coupon" /></p>
				</form>
				<hr />
			<h3>Current Coupons</h3>
			<div class="data-grid">
			<?
			for($bx=0;$bx<$bCount;$bx++){
				?>
				<div<? echo $boxes[$bx]['featured'] == 'true' ? ' class="green"': '';?>>
					<a title="Edit Coupon" class="glyphicon glyphicon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=coupons&id=<?=$boxes[$bx]['bid'];?>"></a>
					<a title="Delete Coupon" class="glyphicon glyphicon-remove deleteCoupon" href="javascript:void()" id="<?=$boxes[$bx]['bid'];?>"></a>
					<?=$boxes[$bx]['couponHeading'];?><? echo $boxes[$bx]['featured'] == 'true' ? ' <b>(Featured)</b>': '';?>
				</div>
				<?
			}
			?>
			</div>
		</div>
		<div class="col-lg-8 col-sm-4">
		<?
		if($bxID != ''){
			?>
			<h3>Edit Coupons (<?=$editBox[0]['couponHeading'];?>)</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=coupons&id=<?=$bxID;?>" method="post" name="EditPage">
				<label>Coupon Heading</label>
				<input autofocus class="form-control" style="width:90%;" type="text" name="couponHeading" value="<?=$editBox[0]['couponHeading'];?>" />
				<label>Coupon Content</label>
				<input class="form-control" type="text" name="couponText" value="<?=$editBox[0]['couponText']?>" />
				<label>Coupon Exp</label>
				<input class="form-control datepicker" type="text" name="couponExp" value="<?=$editBox[0]['couponExp']?>" />
				<label>Home Page Featured Coupon (Up to Four)</label>
				<p>
					<label>
						<input type="radio" name="featured" value="true"<? echo($editBox[0]['featured'] == 'true' ? ' checked' : ''); ?>>
						Featured</label>
					<br>
					<label>
						<input type="radio" name="featured" value="false"<? echo($editBox[0]['featured'] == 'false' ? ' checked' : ''); ?> >
						Not Featured</label>
					<br>
				</p>
				<input class="btn pull-right" type="submit" name="Submit" value="Save Changes" />
				</form>
			<?
		}
		?>
		</div>
	</div>
