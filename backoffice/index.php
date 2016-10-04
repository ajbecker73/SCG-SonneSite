<?
	//Sonne Site Template
	//   © copyright 2012 - Sonne Creative Group, LLC. all rights reserved.
	//   Licensed to Lifestyles Media Group, LLC.
	////////////////////////////////
	include_once('../inc/config.php');
	include_once(FILE_ROOT.'inc/app_start.php');
	include_once(FILE_ROOT.'inc/functions.php');
	include_once(FILE_ROOT.'backoffice/inc/head.php');
	
	?>
	<div class="page"<?= ($_SESSION['session_data']['adminDetails']['aid'] != '' ? ' style="margin-top:0px;"' : ''); ?>>
	<?
	if($_SESSION['session_data']['adminDetails']['aid'] != ""){
		if(!file_exists(FILE_ROOT.'backoffice/pages/'.$app->getPage().'.php'))
			{
			header( 'HTTP/1.1 404 Not Found' );
			include('pages/404.php');
		}else{
			include('pages/'.$app->getPage().'.php');
		}
	}else{
		if($_GET['error'] == 'true'){
			?>
			<div class="alert-error">ERROR</div>
			<?
		}
		?>
		<div class="row">
			<div class="col-lg-3 col-sm-3">
				&nbsp;
			</div>
			<div class="col-lg-6 col-sm-6 loginBox">
			<h1><?= COMPANY; ?> BackOffice</h1>
			<p>&nbsp;</p>
			<form action="?action=alogin" method="post" name="AdminLogin">
				<input autofocus="autofocus" class="form-control" placeholder="Username" type="text" name="username" />
				<br /><br />
				<input class="form-control" placeholder="Password" type="password" name="password" />
				<br><br>
				<input class="btn btn-primary" type="submit" name="Submit" value="LOGIN" />
			</form>
			</div>
			<div class="col-lg-3 col-sm-3">
				&nbsp;
			</div>
		</div>
		<br style="clear:both;" />
		<?
	}
	
	
	?>
	</div>
	<?
	include_once(FILE_ROOT.'backoffice/inc/foot.php');
	include_once(FILE_ROOT.'inc/app_end.php');
?>