<?
	include_once('../../inc/config.php');
	include_once(FILE_ROOT.'inc/app_start.php');
	
	$string = "[";
	
	$today = date("Y-m-d");
	$a=1;
	
	for($d=30;$d>-1;$d--){
		$day = date("Y-m-d",strtotime('-'.$d.' days'));
		$params = array($day);
		$results = $app->db->rawQuery('SELECT id FROM lmg_sessions WHERE started = ?',$params);
		$visitors = count($results);
		if($d == 30){
			$string .= '['.$a.', '.$visitors.']';
		}else{
			$string .= ',['.$a.', '.$visitors.']';
		}
		$a++;
	}
		
	$string .= "]";
	
	echo $string;
	
	include_once(FILE_ROOT.'inc/app_end.php');
?>