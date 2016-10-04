<?
$boolErr = false;
$bxID = '';

if($_GET['id'] != ''){
	$bxID = $_GET['id'];
}

if($_POST['Submit'] == "Add Gallery"){
	if($_POST['gallery_name'] == ''){
		$boolErr = true;
	}
	if(!$boolErr){
		$insertData = array(
			'gallery_name' => $_POST['gallery_name']
		);
		$bxID = $app->db->insert('lmg_galleries',$insertData);
	}
}

if($_POST['Submit'] == "Upload"){
		$dir = FILE_ROOT.'img/uploads/';
		 
		$_FILES['gallery_photo']['type'] = strtolower($_FILES['gallery_photo']['type']);
		 
		if ($_FILES['gallery_photo']['type'] == 'image/png' 
		|| $_FILES['gallery_photo']['type'] == 'image/jpg' 
		|| $_FILES['gallery_photo']['type'] == 'image/gif' 
		|| $_FILES['gallery_photo']['type'] == 'image/jpeg'
		|| $_FILES['gallery_photo']['type'] == 'image/pjpeg')
		{	
		    $file = md5(date('YmdHis')).'.jpg';
		    move_uploaded_file($_FILES["gallery_photo"]["tmp_name"],$dir.$file);
		}
		
		$insertData = array(
			'gid' => $bxID,
			'gallery_photo' => $file
		);
		
		$app->db->insert('lmg_gallery_photos',$insertData);
}

if($bxID != ''){
	$editBox = $app->db
		->where('gid',$bxID)
		->get('lmg_galleries');
}
?>
<h1>Site Galleries</h1>
<p>&nbsp;</p>
<?
$boxes = $app->db
	->orderBy('gallery_name','ASC')
	->get('lmg_galleries');
	$bCount = count($boxes);
?>
<div class="container">
	<div class="row">
		<div class="col-lg-4 col-md-4">
			<h3>Add a Gallery</h3>
				<form action="<?=$_SERVER['PHP_SELF'];?>?url=gallery" method="post" name="AddBox">
				<label>Gallery Name</label>
				<input autofocus type="text" name="gallery_name" class="small" />
				<p><input class="btn" type="submit" name="Submit" value="Add Gallery" /></p>
				</form>
				<hr />
			<h3>Current Galleries</h3>
			<div class="data-grid">
			<?
			for($bx=0;$bx<$bCount;$bx++){
				?>
				<div>
					<a title="Edit Gallery" class="icon-edit" href="<?=$_SERVER['PHP_SELF'];?>?url=gallery&id=<?=$boxes[$bx]['gid'];?>"></a>
					<a title="Delete Gallery" class="icon-remove deleteGallery" href="javascript:void()" id="<?=$boxes[$bx]['gid'];?>"></a>
					<?=$boxes[$bx]['gallery_name'];?>
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
			<h3>Edit Gallery (<?=$editBox[0]['gallery_name'];?>)</h3>
				<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF'];?>?url=gallery&id=<?=$bxID;?>" method="post" name="EditPage">
				<label>Add Photo</label>
				<input type="file" name="gallery_photo" style="width:50%;" />
				<input class="btn" type="submit" name="Submit" value="Upload" />
				<h3>Existing Photos</h3>
				<p>Clcik photo to delete</p>
				<?
				$pics = $app->db->where('gid',$bxID)->get('lmg_gallery_photos');
				for($p=0;$p<count($pics);$p++){
					?>
					<div style="display:inline-block; padding:5px;"><a title="Delete Image" class="deleteGalleryPic" href="javascript:void()" id="<?=$pics[$p]['pid'];?>"><img src="<?=DOMAIN_ROOT;?>img/uploads/<?=$pics[$p]['gallery_photo'];?>" class="galThumb" /></a></div>
					<?
				}
				?>
				</form>
			<?
		}
		?>
		</div>
	</div>
</div>
