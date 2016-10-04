<h1>Search Results</h1>
<?
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
if($_GET['search'] == ''){
	echo '<p>Please Enter a Search Term</p>';
}else{
	if($_GET['search'] != ''){
		
		$stmt = $mysqli->query("SELECT * FROM lmg_pages WHERE page_name LIKE '%".$_GET['search']."%' OR  page_body LIKE '%".$_GET['search']."%' ORDER BY id ASC");
		$pages = 0;
		while($directory = $stmt->fetch_assoc())
		{
			$pageArr[$pages] = array('page',$directory['id'],$directory['page_name'],$directory['page_body']);
			$pages ++;
		}

		$stmt2 = $mysqli->query("SELECT * FROM wp_posts WHERE (post_title LIKE '%".$_GET['search']."%' OR  post_content LIKE '%".$_GET['search']."%') AND post_type = 'post' ORDER BY ID ASC");
		$posts = 0;
		while($directory2 = $stmt2->fetch_assoc())
		{
			$postArr[$posts] = array('post',$directory2['ID'],$directory2['post_title'],$directory2['post_content']);
			$posts ++;
		}
		if(is_array($pageArr) && is_array($postArr)){
			$results = array_merge($pageArr,$postArr);
		}else{
			if(is_array($pageArr)){
				$results = $pageArr;
			}
			if(is_array($postArr)){
				$results = $postArr;
			}
		}
		$dirCount = $pages + $posts;
	}else{
		$dirCount = 0;
	}
	
	if($dirCount == 0){
		echo 'Your search found no results';
	}else{
		?>
<div class="data-grid">
	<?
			foreach($results as $k => $v){
				?>
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="well"> <a href="<?=($v[0] == 'page' ? 'http://www.ifs411.com/'.$v[2] : 'http://www.ifs411.com/blog/?p='.$v[1]);?>"><i style="color:#999;">[
				<?=$v[0];?>
				]</i> -
				<?=$v[2];?>
				</a> </div>
		</div>
	</div>
	<?
			}
			?>
</div>
<?
	}
}
?>
</div>