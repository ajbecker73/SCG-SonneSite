<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/LocalBusiness">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?=$meta[0]['page_title']; ?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= $meta[0]['page_description']; ?>" />
<meta name="keywords" content="<?= $meta[0]['page_keywords']; ?>" />
<meta itemprop="name" content="<?= $meta[0]['page_title']; ?>" />
<meta itemprop="description" content="<?= $meta[0]['page_description']; ?>" />
<meta name="author" content="Design By Lifestyles Media Group LLC, Owned and Operated by <?= COMPANY; ?>">
<meta name="robots" CONTENT="noindex, nofollow">
<meta name="format-detection" content="telephone=no">
<meta property="og:title" content="<?= $meta[0]['page_title']; ?>" />
<meta property="og:type" content="business.business" />
<meta property="og:url" content="<?= DOMAIN_ROOT; ?><?= $meta[0]['page_name']?>" />
<meta property="og:image" content="<?= DOMAIN_ROOT; ?>img/facebook-post.jpg" />
<meta property="business:contact_data:street_address" content="Sample Contact data: Street Address" /> 
<meta property="business:contact_data:locality"       content="Sample Contact data: Locality" /> 
<meta property="business:contact_data:postal_code"    content="Sample Contact data: Postal Code" /> 
<meta property="business:contact_data:country_name"   content="Sample Contact data: Country Name" /> 
<?= stripslashes(GOOGLE)."\n"; ?>
<?= stripslashes(YAHOO)."\n"; ?>
<?= stripslashes(BING)."\n"; ?>
<base href="<?= DOMAIN_ROOT; ?>" />
<link href="<?=DOMAIN_ROOT;?>css/smoothness/jquery-ui.css" rel="stylesheet" />
<link rel="canonical" href="<?=DOMAIN_ROOT;?><?=$app->getPage(); ?>">

<!--[if lt IE 9]>
		<link href="<?=DOMAIN_ROOT;?>tpl/<?= THEME; ?>/css/ie8.css" rel="stylesheet" />
	<![endif]-->
<!--[if !lt IE 9]><!-->
<link href="<?=DOMAIN_ROOT;?>tpl/<?= THEME; ?>/css/style.css" rel="stylesheet" />
<!--<![endif]-->
<link href="<?=DOMAIN_ROOT;?>css/themes/default/default.css" rel="stylesheet" />
<link href="<?=DOMAIN_ROOT;?>css/jquery.fancybox.css?v=2.1.2" rel="stylesheet" />
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
<!--[if lt IE 9]>
	<div class="alert-danger" style="padding:10px; text-align:center; font-size:18px;">This site requires Internet Explorer 9 or above to display properly. You are currently running 8 or below. Please <a href="http://windows.microsoft.com/en-US/internet-explorer/download-ie" target="_blank">upgrade</a> to IE9 or better.</div>
<![endif]-->

<?
if($_SESSION['session_data']['adminDetails']['aid'] != ""){
	?>
<nav class="navbar navbar-inverse hidden-xs hidden-sm" role="navigation"> 
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
		<a class="navbar-brand" href="#">Backoffice</a> </div>
	
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<?
				include(FILE_ROOT.'backoffice/inc/admin-nav.php');
				?>
	</div>
	<!-- /.navbar-collapse --> 
</nav>
<?
}
?>
<div class="page"<?= ($_SESSION['session_data']['adminDetails']['aid'] != '' ? ' style="margin-top:40px;"' : ''); ?>>
<header>
	<div id="header">
		<div class="row">
			<div class="col-lg-5 col-sm-6 logo" style="text-align:center;"><img class="img-responsive" src="<?=DOMAIN_ROOT;?>img/company-logo.png" /></div>
			<div class="col-lg-7 col-sm-6">
				<div class="row">
					<div class="col-lg-6 col-sm-6"> </div>
					<div class="col-lg-6 col-sm-6">
						<div class="social"> 
							<a href="" target="_blank"><img src="<?=DOMAIN_ROOT;?>img/social/facebook.png" alt="Follow Us On Facebook" title="Follow Us On Facebook" /></a> 
							<a href="" target="_blank"><img src="<?=DOMAIN_ROOT;?>img/social/twitter.png" alt="Follow Us On Twitter" title="Follow Us On Twitter" /></a> 
							<a href="" target="_blank"><img src="<?=DOMAIN_ROOT;?>img/social/google.png" alt="Follow Us On Google Plus" title="Follow Us On Google Plus" /></a> 
							<a href="" target="_blank"><img src="<?=DOMAIN_ROOT;?>img/social/wordpress.png" alt="Follow Us On Wordpress" title="Follow Us On Wordpress" /></a> 
							<a href="" target="_blank"><img src="<?=DOMAIN_ROOT;?>img/social/pinterest.png" alt="Follow Us On Pinterest" title="Follow Us On Pinterest" /></a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<nav class="navbar navbar-default" role="navigation" id="nav"> 
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
	</div>
	
	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<?
				echo $app->getNav();
				?>
	</div>
	<!-- /.navbar-collapse --> 
</nav>
<?
if(substr($_SERVER['SCRIPT_NAME'],0,5) == '/blog'){
	?>
<div class="row">
<div class="col-lg-8 col-md-8 col-sm-8">
<?
}
?>
