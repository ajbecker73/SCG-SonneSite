<?
$boolErr = false;
$pgID = '';

if($_GET['id'] != ''){
	$pgID = $_GET['id'];
}

if($_POST['Submit'] == "Add Page"){
	if($_POST['page_name'] == ''){
		$boolErr = true;
	}
	$pgName = str_replace(" ","-",$_POST['page_name']);
	if(!$boolErr){
		$insertData = array(
			'page_name' => $pgName,
			'page_secured' => $_POST['page_secured'],
			'page_body' => '<p>Coming Soon</p>'
		);
		$pgID = $app->db->insert('lmg_pages',$insertData);
	}
}

if($_POST['Submit'] == "Save Changes"){
	foreach($_POST as $k => $v){
		if($k != 'Submit'){
			if($k == 'page_name'){ $v = str_replace(" ","-",$v); }
			$updateData = array(
				$k => $v
			);
			$app->db->where('id',$pgID);
			$app->db->update('lmg_pages',$updateData);
		}
	}
}

if($pgID != ''){
	$editPage = $app->db
		->where('id',$pgID)
		->get('lmg_pages');
}
?>
<h1>Site Pages</h1>
<p>&nbsp;</p>
<?
$pages = $app->db
	->orderBy('page_name','ASC')
	->get('lmg_pages');
	$pCount = count($pages);
?>
	<div class="row">
		<div class="col-lg-4 col-sm-4">
			<h3>Add a Page</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=pages" method="post" name="AddPage">
				<label>Page Name</label>
				<input autofocus="autofocus" type="text" name="page_name" class="small" />
				<br />
				<?
				if(MEMBERS_PORTAL == "true"){
					?>
					<label>Secure Page</label>
					<input type="radio" name="page_secured" value="0" checked />No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="page_secured" value="1" />Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
					<?
				}
				?>
				<br />
				
				<input class="btn" type="submit" name="Submit" value="Add Page" />
				</form>
				<hr />
			<h3>Current Pages</h3>
			<div class="data-grid">
			<?
			for($pg=0;$pg<$pCount;$pg++){
				?>
				<div>
					<a title="Edit Page" class="glyphicon glyphicon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=pages&id=<?=$pages[$pg]['id'];?>"></a>
					&nbsp;
					<a title="Delete Page" class="glyphicon glyphicon-remove deletePage" href="javascript:void()" id="<?=$pages[$pg]['id'];?>"></a>
					&nbsp;&nbsp;
					<b><?=$pages[$pg]['page_name'];?></b>
				</div>
				<?
			}
			?>
			</div>
		</div>
		<div class="col-lg-8 col-sm-8">
		<?
		if($pgID != ''){
			?>
			<h3>Edit page (<?=$editPage[0]['page_name'];?>)</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=pages&id=<?=$pgID;?>" method="post" name="EditPage">
				<label><b>Page Name</b></label>
				<input autofocus="autofocus" class="small" style="width:90%;" type="text" name="page_name" value="<?=$editPage[0]['page_name'];?>" />
				<?
				if(MEMBERS_PORTAL == "true"){
					?>
					<label>Secure Page</label>
					<input type="radio" name="page_secured" value="0" checked />No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name="page_secured" value="1" />Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
					<?
				}
				?>
				<p>&nbsp;</p>
				<div class="well">
					<h3>Meta Info</h3>
					<label>Page Title</label>
					<input class="small" style="width:90%;" type="text" name="page_title" value="<?=$editPage[0]['page_title'];?>" />
					<label>Page Description</label>
					<textarea name="page_description" style="width:90%; height:50px;"><?=$editPage[0]['page_description'];?></textarea>
					<label>Page Keywords</label>
					<textarea name="page_keywords" style="width:90%; height:50px;"><?=$editPage[0]['page_keywords'];?></textarea>
				</div>
				<label><b>Page Content</b></label>
				<textarea name="page_body" style="width:90%; height:250px;"><?=stripslashes($editPage[0]['page_body']);?></textarea>
				<script>
					CKEDITOR.replace( 'page_body',
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