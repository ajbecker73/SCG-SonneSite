<h1>Member Directory</h1>
<?
$recLimit = 20;
$recPage = $_GET['page'];
if($recPage == ''){
	$recPage = '1';
}
if($recPage == '1'){
	$recOffset = 0;
}else{
	$recOffset = ($recLimit*$recPage)-$recLimit;
}

if($_GET['letter'] != ''){
	$params = array($_GET['letter'].'%',$recLimit,$recOffset);
	$directory = $app->db->rawQuery('SELECT * FROM lmg_members WHERE mbr_company LIKE ? ORDER BY mbr_company ASC LIMIT ? OFFSET ?',$params);
	$params = array($_GET['letter'].'%');
	$directoryPages = $app->db->rawQuery('SELECT * FROM lmg_members WHERE mbr_company LIKE ? ORDER BY mbr_company ASC',$params);
}else{
	$params = array($recLimit,$recOffset);
	$directory = $app->db->rawQuery('SELECT * FROM lmg_members ORDER BY mbr_company ASC LIMIT ? OFFSET ?',$params);
	$directoryPages = $app->db->rawQuery('SELECT * FROM lmg_members ORDER BY mbr_company ASC');
}
	$dirCount = count($directory);
	$dirCountPages = count($directoryPages);
?>
<div class="pagination">
	<ul>
		<?
		$alphas = range('A', 'Z');
		foreach($alphas as $k => $v){
			?>
			<li<? echo ($_GET['letter'] == $v ? ' class="active"' : ''); ?>>
			<a style="font-size:10px; padding:9px; line-height:12px;" 
					href="<?=DOMAIN_ROOT;?>members/member-directory?letter=<?=$v;?>"><?=$v;?></a></li>
			<?
		}
		?>
	</ul>
</div>
<?

if($dirCount == 0){
	echo 'There are no active members in the database';
}else{
	?>
	<div class="pagination">
		<ul>
			<?
			$pg = 1;
			for($p=0;$p<$dirCountPages;$p+=$recLimit){
				?>
				<li<? echo ($_GET['page'] == $pg ? ' class="active"' : ''); ?>>
				<a href="<?=DOMAIN_ROOT;?>members/member-directory?page=<?=$pg;?>&letter=<?=$_GET['letter'];?>"><?=$pg;?></a></li>
				<?
				$pg++;
			}
			?>
		</ul>
	</div>
	<div class="data-grid">
	<?
	for($d=0;$d<$dirCount;$d++){
		?>
		<div class="row-fluid">
			<span class="span8">
				<h4><?=$directory[$d]['mbr_company'];?></h4>
				<?
				if($directory[$d]['mbr_firstname'] != ''){
					?>
					<b><?=$directory[$d]['mbr_firstname'];?> <?=$directory[$d]['mbr_lastname'];?></b><br />
					<?
				}
				?>
				<a href="mailto:<?=$directory[$d]['mbr_email'];?>"><?=$directory[$d]['mbr_email'];?></a><br />
			</span>
			<span class="span4">
				<?=$directory[$d]['mbr_address'];?><br />
				<?=$directory[$d]['mbr_city'];?>,&nbsp;<?=$directory[$d]['mbr_state'];?>&nbsp;<?=$directory[$d]['mbr_zip'];?><br />
				<?=$directory[$d]['mbr_phone'];?>
			</span>
		</div>
		<?
	}
	?>
	</div>
	<div class="pagination">
		<ul>
			<?
			$pg = 1;
			for($p=0;$p<$dirCountPages;$p+=$recLimit){
				?>
				<li<? echo ($_GET['page'] == $pg ? ' class="active"' : ''); ?>>
				<a href="<?=DOMAIN_ROOT;?>members/member-directory?page=<?=$pg;?>&letter=<?=$_GET['letter'];?>"><?=$pg;?></a></li>
				<?
				$pg++;
			}
			?>
		</ul>
	</div>
	<?
}
?>