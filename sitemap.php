<h1>Sitemap</h1>
<?php 
$pages = $app->db->orderBy('link','ASC')->get('lmg_navigation');
		for($p=0;$p<count($pages);$p++){
			?>
				<a href="<?=DOMAIN_ROOT.$pages[$p]['link'];?>">
					<b><?=$pages[$p]['name'];?></b>
					<?
					$titles = $app->db->where('page_name',$pages[$p]['link'])->get('lmg_pages');
					echo ' - '.$titles[0]['page_title'];
					?>
				</a><br />
			<?
		}

?>