<?
$boolErr = false;
$bxID = '';

if($_GET['id'] != ''){
	$bxID = $_GET['id'];
}

if($_POST['Submit'] == "Add Box"){
	if($_POST['box_title'] == ''){
		$boolErr = true;
	}
	if(!$boolErr){
		$insertData = array(
			'box_title' => $_POST['box_title']
		);
		$bxID = $app->db->insert('lmg_boxes',$insertData);
	}
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			$updateData = array(
				$k => $v
			);
			$app->db->where('bid',$bxID);
			$app->db->update('lmg_boxes',$updateData);
		}
	}
}

if($bxID != ''){
	$editBox = $app->db
		->where('bid',$bxID)
		->get('lmg_boxes');
}
?>
<h1>Site Boxes</h1>
<p>&nbsp;</p>
<?
$boxes = $app->db
	->orderBy('box_title','ASC')
	->get('lmg_boxes');
	$bCount = count($boxes);
?>
	<div class="row">
		<div class="col-lg-4 col-sm-4">
			<h3>Add a Box</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=boxes" method="post" name="AddBox">
				<label>Box Title</label>
				<input autofocus="autofocus" type="text" name="box_title" class="small" />
				<p><input class="btn" type="submit" name="Submit" value="Add Box" /></p>
				</form>
				<hr />
			<h3>Current Boxes</h3>
			<div class="data-grid">
			<?
			for($bx=0;$bx<$bCount;$bx++){
				?>
				<div>
					<a title="Edit Box" class="glyphicon glyphicon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=boxes&id=<?=$boxes[$bx]['bid'];?>"></a>
					<a title="Delete Box" class="glyphicon glyphicon-remove deleteBox" href="javascript:void()" id="<?=$boxes[$bx]['bid'];?>"></a>
					<?=$boxes[$bx]['box_title'];?>
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
			<h3>Edit Box (<?=$editBox[0]['box_title'];?>)</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=boxes&id=<?=$bxID;?>" method="post" name="EditPage">
				<label>Box Title</label>
				<input autofocus="autofocus" class="small" style="width:90%;" type="text" name="box_title" value="<?=$editBox[0]['box_title'];?>" />
				<label>Box Content</label>
				<textarea name="box_contents" style="width:90%; height:250px;"><?=stripslashes($editBox[0]['box_contents']);?></textarea>
				<script>
					CKEDITOR.replace( 'box_contents',
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
