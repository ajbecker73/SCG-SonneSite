<?
	session_start();
	ob_start(ob_gzhandler);
	
	include_once(FILE_ROOT.'cls/app.php');
	
	date_default_timezone_set(TIME_ZONE);
	$app = new App();
	$app->load();
	
	$meta = $app->meta;
	
	if($app->getPage() == 'gallery'){
		$gs = $app->db->where('gid',$_GET['gallery'])->get('lmg_galleries');
		$meta[0]['page_title'] .= ' | '.$gs[0]['gallery_name'];
		$meta[0]['page_description'] .= ' '.$gs[0]['gallery_name'];
		$meta[0]['page_keywords'] .= ', '.$gs[0]['gallery_name'];
	}

	$sslPages = array();
	if(($_SERVER['HTTPS']=='off' && in_array($app->getPage(),$sslPages)) || (strtolower(current(explode('.',$_SERVER['HTTP_HOST']))) != 'www') || (strtolower(current(explode('.php',basename($_SERVER['REQUEST_URI'])))) == 'index'))
	{
		$newUrl = (in_array($app->getPage(),$sslPages))?'https://':'http://';
		$newUrl .= (strtolower(current(explode('.',$_SERVER['HTTP_HOST']))) != 'www')?'www.'.strtolower($_SERVER['HTTP_HOST']):strtolower($_SERVER['HTTP_HOST']);
		$newUrl .= ($_SERVER['QUERY_STRING']!='')?'?'.$_SERVER['QUERY_STRING']:'';
		if(!strpos($_SERVER["SCRIPT_FILENAME"],'backoffice')){
			header('Location: '.$newUrl); exit();
		}
	}
?>
