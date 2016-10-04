<?
if($_POST['Submit'] == "Upload Image"){
	$dir = '../img/uploads/';
	 
	$_FILES['file']['type'] = strtolower($_FILES['file']['type']);
	 
	if ($_FILES['file']['type'] == 'image/png' 
	|| $_FILES['file']['type'] == 'image/jpg' 
	|| $_FILES['file']['type'] == 'image/gif' 
	|| $_FILES['file']['type'] == 'image/jpeg'
	|| $_FILES['file']['type'] == 'image/pjpeg')
	{	
	    // setting file's mysterious name
	    $file = md5(date('YmdHis')).'.jpg';
	 
	    // copying
	    move_uploaded_file($_FILES["file"]["tmp_name"],$dir.$file);
	    $insData = array(
	    	'file' => $file,
		'alt_text' => $_POST['alt_text']
	    );
	 	$app->db->insert('lmg_slideshow',$insData);
	}
}
?>

<h1>Slideshow Image</h1>
<p>&nbsp;</p>
		<div class="row">
			<div class="col-lg-4 col-sm-4">
				<h3>Current Images</h3>
				<div id="cSlides">
				<?
				$cur_slides = $app->db
					->orderBy('sid','DESC')
					->get('lmg_slideshow');
					$sCount = count($cur_slides);
					
				for($s=0;$s<$sCount;$s++){
					?>
					<div>
					<img src="<?=DOMAIN_ROOT.'img/uploads/'.$cur_slides[$s]['file'];?>" style="width:80%; margin:0;" /><br>
					<a title="Delete Image" class="icon-remove deleteSlide" href="javascript:void()" id="<?=$cur_slides[$s]['sid'];?>"></a>
					<a title="Delete Image" class="deleteSlide" href="javascript:void()" id="<?=$cur_slides[$s]['sid'];?>">Remove Image</a>
					<br><br>
					</div>
					<?
				}
				?>
				</div>
			</div>
			<div class="col-lg-8 col-sm-8">
			<h3>Upload Images</h3>
				<p>Photos should be at 705x300 px.</p>
				<form action="" method="post" name="UploadSlides" enctype="multipart/form-data">
					<label>Alt Tag Text</label>
					<input type="text" name="alt_text" value="" />
					<label>Image File</label>
					<input type="file" name="file" />
					<input type="submit" name="Submit" value="Upload Image" class="btn" />
				</form>
			</div>
		</div>
