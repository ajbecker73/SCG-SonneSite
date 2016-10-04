<?
$boolErr = false;
$bxID = '';

if($_GET['id'] != ''){
	$bxID = $_GET['id'];
}

if($_POST['Submit'] == "Add Service"){
	if($_POST['service_name'] == ''){
		$boolErr = true;
	}
	if(!$boolErr){
		$insertData = array(
			'service_name' => $_POST['service_name']
		);
		$bxID = $app->db->insert('lmg_scheduler_services',$insertData);
	}
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$updateData = array(
				$k => $v
			);
			$app->db->where('sid',$bxID);
			$app->db->update('lmg_scheduler_services',$updateData);
		}
	}
}

if($bxID != ''){
	$editBox = $app->db
		->where('sid',$bxID)
		->get('lmg_scheduler_services');
}
?>
<h1>Scheduler Services</h1>
<p>&nbsp;</p>
<?
$cats = $app->db
	->orderBy('service_name','ASC')
	->get('lmg_scheduler_services');
	$cCount = count($cats);
?>
	<div class="row">
		<div class="col-ls-4 col-md-4 col-sm-4">
			<h3>Add a Service</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=scheduler3" method="post" name="AddBox">
				<label>Service Name</label>
				<input autofocus="autofocus" type="text" name="service_name" class="small" />
				<p><input class="btn" type="submit" name="Submit" value="Add Service" /></p>
				</form>
				<hr />
			<h3>Current Services</h3>
			<div class="data-grid">
			<?
			for($bx=0;$bx<$cCount;$bx++){
				?>
				<div>
					<a title="Edit Service" class="glyphicon glyphicon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=scheduler3&id=<?=$cats[$bx]['sid'];?>"></a>
					<a title="Delete Service" class="glyphicon glyphicon-remove deleteService" href="javascript:void()" id="<?=$cats[$bx]['sid'];?>"></a>
					<?=$cats[$bx]['service_name'];?>
				</div>
				<?
			}
			?>
			</div>
		</div>
		<div class="col-ls-8 col-md-8 col-sm-8">
		<?
		if($bxID != ''){
			?>
			<h3>Edit Service (<?=$editBox[0]['service_name'];?>)</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=scheduler3&id=<?=$bxID;?>" method="post" name="EditPage">
				<label>Service Name</label>
				<input autofocus="autofocus" class="small" style="width:90%;" type="text" name="service_name" value="<?=$editBox[0]['service_name'];?>" />
				<label>Service Description</label>
				<textarea name="service_description" style="width:90%; height:250px;"><?=stripslashes($editBox[0]['service_description']);?></textarea>
				<script>
					CKEDITOR.replace( 'service_description',
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
