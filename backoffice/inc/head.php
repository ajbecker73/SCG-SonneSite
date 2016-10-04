<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?= COMPANY." | ".$meta[0]['page_title']; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="description" content="<?= $meta[0]['page_description']; ?>" />
	<meta http-equiv="keywords" content="<?= $meta[0]['page_keywords']; ?>" />
	<meta name="author" content="Design By Lifestyles Media Group LLC, Owned and Operated by <?= COMPANY; ?>">
	<?= GOOGLE."\n"; ?>
	<?= YAHOO."\n"; ?>
	<?= BING."\n"; ?>
	<base href="<?= DOMAIN_ROOT; ?>" />
	<link href="<?=DOMAIN_ROOT;?>css/reset.css" rel="stylesheet" />
	<link href="<?=DOMAIN_ROOT;?>css/bootstrap-glyphicons.css" rel="stylesheet" />
	<link href="<?=DOMAIN_ROOT;?>css/docs.css" rel="stylesheet" />
	<link href="<?=DOMAIN_ROOT;?>css/smoothness/jquery-ui.css" rel="stylesheet" />
	<link href="<?=DOMAIN_ROOT;?>backoffice/css/style.css" rel="stylesheet" />
	<script src="<?=DOMAIN_ROOT;?>js/ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.editorConfig = function( config ) {
			// Define changes to default configuration here. For example:
			// config.language = 'fr';
			// config.uiColor = '#AADC6E';
			config.contentsCss = '../tpl/<?=THEME;?>/css/style.css';
		};
	</script>
	<!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body<?= ($_SESSION['session_data']['adminDetails']['aid'] == '' ? ' style="background-image:url('.DOMAIN_ROOT.'backoffice/img/stripe_c3c0e79f27c3831e121f73146d7302c3.png);"' : ''); ?>>
<?
if($_SESSION['session_data']['adminDetails']['aid'] != ""){
	?>
	<nav class="navbar navbar-inverse" role="navigation">
	  <!-- Brand and toggle get grouped for better mobile display -->
	  <div class="navbar-header">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
		 <span class="sr-only">Toggle navigation</span>
		 <span class="icon-bar"></span>
		 <span class="icon-bar"></span>
		 <span class="icon-bar"></span>
	    </button>
	    <a class="navbar-brand" href="#">Backoffice</a>
	  </div>
	
	  <!-- Collect the nav links, forms, and other content for toggling -->
	  <div class="collapse navbar-collapse navbar-ex1-collapse">
				<?
				include(FILE_ROOT.'backoffice/inc/admin-nav.php');
				?>
	  </div><!-- /.navbar-collapse -->
	</nav>
	<?
}
?>
<div class="page">

