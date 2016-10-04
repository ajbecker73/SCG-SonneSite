<?
	////////////////////////////////
	//LMG Site Template
	//   © copyright 2012 - Lifestyles Media Group, LLC. all rights reserved.
	////////////////////////////////
	
	//LOAD SITE
	include_once('inc/config.php');
	include_once("inc/app_start.php");
	if(OFFLINE == "true"){
		include_once("inc/maintenance.php");
	}else{
		include_once("tpl/".THEME."/head.php");
	}
	if(OFFLINE != "true"){
		//HOME PAGE SLIDESHOW (IF ACTIVATED)
		if($app->getPage() == "" && SLIDESHOW == "true"){
			$slides = $app->db
				->orderBy('sort','ASC')
				->get('lmg_slideshow');
				$sCount = count($slides);
				?>
			<div id="carousel-example-generic" class="carousel slide">
				<!-- Indicators -->
				<ol class="carousel-indicators">
					<?
					for($s=0;$s<$sCount;$s++){
					?>
						<li data-target="#carousel-example-generic" data-slide-to="0"<?= ($s == 0 ? ' class="active"' : ''); ?>></li>
					<?
					}
					?>
				</ol>
				
				<!-- Wrapper for slides -->
				<div class="carousel-inner">
					<?
					for($s=0;$s<$sCount;$s++){
					?>
						<div class="item<?= ($s == 0 ? ' active' : ''); ?>">
							<img src="<?=DOMAIN_ROOT;?>img/uploads/<?=$slides[$s]['file'];?>" alt="">
							<div class="carousel-caption">
								<h2>Slide <?=$s;?></h2>
							</div>
						</div>
					<?
					}
					?>
				</div>
				
				<!-- Controls -->
				<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
				<span class="icon-prev"></span>
				</a>
				<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
				<span class="icon-next"></span>
				</a>
			</div>
		<?
		}
						
		//PAGE CONTENT FROM FILE (IF EXISTS)
		if(file_exists(FILE_ROOT.$app->getPage().'.php') || $app->getPage() == ''){
			if($app->db
				->where('page_name',$app->getPage())
				->get('lmg_pages')
				){
				echo stripslashes($app->meta[0]['page_body']);
			}
			if($app->getPage() == ''){
				include(FILE_ROOT.'home.php');
			}else{
				include(FILE_ROOT.$app->getPage().'.php');
			}
		}else{
	
			//404 ERROR PAGE (IF NO RECORD FOUND IN DATABASE)
			if(!$app->db
				->where('page_name',$app->getPage())
				->get('lmg_pages'))
				{
				header( "HTTP/1.1 404 Not Found" );
				include('inc/404.php');
			}else{
				
				//PAGE CONTENT FROM DATABASE (IF EXISTS)
				?>
				<?= stripslashes($app->meta[0]['page_body']); ?>
				<?
			}
		}
	}
	//END SITE
	if(OFFLINE == "false"){
		include_once("tpl/".THEME."/foot.php");
	}
	include_once("inc/app_end.php");
?>