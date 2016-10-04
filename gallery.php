<?
$gals = $app->db->get('lmg_galleries');
for($g=0;$g<count($gals);$g++){
	?>
	<a href="gallery?gallery=<?=$gals[$g]['gid'];?>" class="btn btn-default btn-xs"><?=$gals[$g]['gallery_name'];?></a>
	<?
}
?>
<hr />
<?
$gallery = $_GET['gallery'];

if($gallery != ''){
	$pics = $app->db->where('gid',$gallery)->get('lmg_gallery_photos');
	if(count($pics) == 0){
		?>
		<h4>There are no images in this gallery</h4>
		<?
	}else{
		for($p=0;$p<count($pics);$p++){
			$n = $p + 1;
			?>
			<a class="fancybox" rel="gallery" href="<?=DOMAIN_ROOT;?>img/uploads/<?=$pics[$p]['gallery_photo'];?>">
				<img class="galThumb" alt="<?= COMPANY;?> Image # <?= $n;?>" title="<?= COMPANY;?> Image # <?= $n;?>" src="<?=DOMAIN_ROOT;?>img/uploads/<?=$pics[$p]['gallery_photo'];?>" />
			</a>
			<?
		}
	}
}else{
	//Change value number to gallery ID to be set as default ex: 'gid', 8
$pics = $app->db->where('gid',8)->get('lmg_gallery_photos');
if(count($pics) == 0){
		?>
		<h4>There are no images in this gallery</h4>
		<?
	}else{
		for($p=0;$p<count($pics);$p++){
			$n = $p + 1;
			?>
			<a class="fancybox" rel="gallery" href="<?=DOMAIN_ROOT;?>img/uploads/<?=$pics[$p]['gallery_photo'];?>">
				<img class="galThumb" alt="<?= COMPANY;?> Image # <?= $n;?>" title="<?= COMPANY;?> Image # <?= $n;?>" src="<?=DOMAIN_ROOT;?>img/uploads/<?=$pics[$p]['gallery_photo'];?>" />
			</a>
			<?
		}
	}
}
?>